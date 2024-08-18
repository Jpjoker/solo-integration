{
    'name': 'integration',
    'version': '1.0',
    'category': 'Extra Tools',
    'summary': 'Integrate Odoo with WordPress via RabbitMQ',
    'author': 'jurgen',
    'depends': ['base', 'product','sale'],
    'external_dependencies': {'python': ['pika']},
    'data': [
        'security/ir.model.access.csv',
        'data/rabbitmq_cron.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}