from odoo import models, fields, api
import pika
import json

class ProductTemplate(models.Model):
    _inherit = 'product.template'

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
                'price': self.list_price,
                'description': self.description,
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
        product = super(ProductTemplate, self).create(vals)
        product.send_to_wordpress('create')
        return product

    def write(self, vals):
        result = super(ProductTemplate, self).write(vals)
        for product in self:
            product.send_to_wordpress('update')
        return result

    def unlink(self):
        for product in self:
            product.send_to_wordpress('delete')
        return super(ProductTemplate, self).unlink()

    @api.model
    def receive_from_wordpress(self, data):
        action = data.get('action')
        product_data = data.get('data')

        if action == 'create':
            self.create(product_data)
        elif action == 'update':
            product = self.search([('wordpress_id', '=', product_data['wordpress_id'])])
            if product:
                product.write(product_data)
        elif action == 'delete':
            product = self.search([('wordpress_id', '=', product_data['wordpress_id'])])
            if product:
                product.unlink()