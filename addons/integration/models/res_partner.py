from odoo import models, fields, api
import pika
import json
import logging

_logger = logging.getLogger(__name__)

class ResPartner(models.Model):
    _inherit = 'res.partner'

    wordpress_id = fields.Integer(string='WordPress ID')

    def send_to_wordpress(self, action):
        try:
            connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
            channel = connection.channel()
            channel.queue_declare(queue='wp_odoo_queue', durable=True)

            message = {
                'action': action,
                'data': {
                    'id': self.id,
                    'wordpress_id': self.wordpress_id,
                    'name': self.name,
                    'email': self.email,
                }
            }

            channel.basic_publish(
                exchange='',
                routing_key='wp_odoo_queue',
                body=json.dumps(message),
                properties=pika.BasicProperties(delivery_mode=2)
            )

            connection.close()
        except pika.exceptions.AMQPError as error:
            _logger.error(f"Failed to send partner data to WordPress: {error}")

    @api.model
    def create(self, vals):
        partner = super(ResPartner, self).create(vals)
        partner.send_to_wordpress('create')
        return partner

    def write(self, vals):
        result = super(ResPartner, self).write(vals)
        for partner in self:
            partner.send_to_wordpress('update')
        return result

    def unlink(self):
        for partner in self:
            partner.send_to_wordpress('delete')
        return super(ResPartner, self).unlink()

    @api.model
    def receive_from_wordpress(self, data):
        action = data.get('action')
        customer_data = data.get('data')

        if action == 'create':
            self.create(customer_data)
        elif action == 'update':
            partner = self.search([('wordpress_id', '=', customer_data['wordpress_id'])])
            if partner:
                partner.write(customer_data)
        elif action == 'delete':
            partner = self.search([('wordpress_id', '=', customer_data['wordpress_id'])])
            if partner:
                partner.unlink()

    @api.model
    def process_rabbitmq_messages(self):
        try:
            connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
            channel = connection.channel()
            channel.queue_declare(queue='odoo_wp_queue', durable=True)

            def callback(ch, method, properties, body):
                data = json.loads(body)
                self.receive_from_wordpress(data)
                ch.basic_ack(delivery_tag=method.delivery_tag)

            channel.basic_consume(queue='odoo_wp_queue', on_message_callback=callback)
            channel.start_consuming()
        except pika.exceptions.AMQPError as error:
            _logger.error(f"Failed to process RabbitMQ messages: {error}")
