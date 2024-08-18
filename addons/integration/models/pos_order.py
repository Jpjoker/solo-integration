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
        try:
            order_ids = super(PosOrder, self).create_from_ui(orders, draft)
            for order in self.browse(order_ids):
                self.send_to_wordpress(order)
            return order_ids
        except Exception as e:
            _logger.error(f"Error in create_from_ui: {e}")
            raise

    def send_to_wordpress(self, order):
        try:
            # Ensure you're working with a single order
            if not order or len(order) != 1:
                _logger.error(f"Invalid order in send_to_wordpress: {order}")
                return

            connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.56.103'))
            channel = connection.channel()

            order_data = {
                'id': order.id,
                'name': order.name,
                'amount_total': order.amount_total,
                'date_order': order.date_order.isoformat() if order.date_order else None,
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
        except Exception as e:
            _logger.error(f"Failed to send POS order to WordPress: {e}")
