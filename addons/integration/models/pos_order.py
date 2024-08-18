from odoo import models, fields, api
import pika
import json
import logging

_logger = logging.getLogger(__name__)

class PosOrder(models.Model):
    _inherit = 'pos.order'

    wordpress_id = fields.Integer(string="WordPress ID")

    @api.model
    def create_from_ui(self, orders, draft=False):
        # Hier wordt er door de orders gelopen om elke bestelling individueel te verwerken
        order_ids = super(PosOrder, self).create_from_ui(orders, draft)
        for order in self.browse(order_ids):
            self.send_to_wordpress(order)
        return order_ids

    def send_to_wordpress(self, order):
        # Zorg ervoor dat er slechts één order wordt verwerkt
        self.ensure_one()
        try:
            connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.56.103'))
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
        except pika.exceptions.AMQPError as error:
            _logger.error(f"Failed to send POS order to WordPress: {error}")
