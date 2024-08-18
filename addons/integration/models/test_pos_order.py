import pika
import json

def send_test_message():
    # Gebruik de standaard AMQP-poort 5672 in plaats van 15672
    connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.56.103'))
    channel = connection.channel()

    # Queue declaration (zorgt ervoor dat de queue bestaat)
    channel.queue_declare(queue='pos_to_wordpress', durable=True)

    # Testorder gegevens
    test_order = {
        'id': 1,
        'name': 'Test Order',
        'amount_total': 100.0,
        'date_order': '2023-08-01T10:00:00',
        'partner_id': 1,
        'lines': [{
            'product_id': 1,
            'qty': 2,
            'price_unit': 50.0,
        }]
    }

    # Bericht publiceren naar de RabbitMQ queue
    channel.basic_publish(
        exchange='',
        routing_key='pos_to_wordpress',
        body=json.dumps(test_order)
    )

    print(" [x] Sent 'Test Order'")
    connection.close()

send_test_message()
