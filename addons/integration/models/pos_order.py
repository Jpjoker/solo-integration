from odoo import models, fields, api
import pika
import json


class PosOrder(models.Model):
    _inherit = 'pos.order'

    wordpress_id = fields.Integer(string="WordPress ID")

    @api.model
    def create_from_ui(self, orders, draft=False):
        order_ids = super(PosOrder, self).create_from_ui(orders, draft)
        for order in self.browse(order_ids):
            self.send_to_wordpress(order)
        return order_ids

    def send_to_wordpress(self, order):
        connection = pika.BlockingConnection(pika.ConnectionParameters('rabbitmq'))
        channel = connection.channel()

        order_data = {
            'id': order.id,
            'name': order.name,
            'amount_total': order.amount_total,
            'date_order': order.date_order.isoformat(),
            'partner_id': order.partner_id.id if order.partner_id else None,
            'lines': [{
                'product_id': line.product_id.id,
                'qty': line.qty,
                'price_unit': line.price_unit,
            } for line in order.lines],
        }

        channel.basic_publish(
            exchange='',
            routing_key='pos_to_wordpress',
            body=json.dumps(order_data)
        )

        connection.close()