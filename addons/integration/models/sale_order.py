from odoo import models, fields, api
import pika
import json

class SaleOrder(models.Model):
    _inherit = 'sale.order'

    wordpress_id = fields.Integer(string='WordPress ID')

    def send_to_wordpress(self, action):
        connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
        channel = connection.channel()
        channel.queue_declare(queue='wp_odoo_queue', durable=True)

        message = {
            'action': action,
            'data': {
                'id': self.id,
                'wordpress_id': self.wordpress_id,
                'name': self.name,
                'partner_id': self.partner_id.id,
                'amount_total': self.amount_total,
                'state': self.state,
            }
        }

        channel.basic_publish(
            exchange='',
            routing_key='wp_odoo_queue',
            body=json.dumps(message),
            properties=pika.BasicProperties(delivery_mode=2)
        )

        connection.close()

    @api.model
    def create(self, vals):
        order = super(SaleOrder, self).create(vals)
        order.send_to_wordpress('create')
        return order

    def write(self, vals):
        result = super(SaleOrder, self).write(vals)
        for order in self:
            order.send_to_wordpress('update')
        return result

    @api.model
    def receive_from_wordpress(self, data):
        action = data.get('action')
        order_data = data.get('data')

        if action == 'create':
            self.create(order_data)
        elif action == 'update':
            order = self.search([('wordpress_id', '=', order_data['wordpress_id'])])
            if order:
                order.write(order_data)