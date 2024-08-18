from odoo import models, fields, api
import pika
import json
import logging

_logger = logging.getLogger(__name__)

class ProductTemplate(models.Model):
    _inherit = 'product.template'

    wordpress_id = fields.Integer(string="WordPress ID")

    @api.model
    def create(self, vals):
        product = super(ProductTemplate, self).create(vals)
        self.send_to_wordpress(product, 'create')
        return product

    def write(self, vals):
        result = super(ProductTemplate, self).write(vals)
        for product in self:
            self.send_to_wordpress(product, 'update')
        return result

    def unlink(self):
        for product in self:
            self.send_to_wordpress(product, 'delete')
        return super(ProductTemplate, self).unlink()

    def send_to_wordpress(self, product, action):
        try:
            connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.56.103'))
            channel = connection.channel()

            product_data = {
                'action': action,
                'id': product.id,
                'name': product.name,
                'list_price': product.list_price,
                'description': product.description,
            }

            channel.basic_publish(
                exchange='',
                routing_key='product_to_wordpress',
                body=json.dumps(product_data)
            )

            connection.close()
        except pika.exceptions.AMQPError as error:
            _logger.error(f"Failed to send product to WordPress: {error}")
