from odoo.tests.common import TransactionCase
from unittest.mock import patch

class TestPosOrder(TransactionCase):

    def setUp(self):
        super(TestPosOrder, self).setUp()
        self.pos_order_model = self.env['pos.order']

    @patch('odoo.addons.your_module_name.models.pos_order.pika.BlockingConnection')
    def test_create_from_ui(self, mock_connection):
        mock_channel = mock_connection.return_value.channel.return_value

        order_data = {
            'data': {
                'name': 'Test Order',
                'amount_total': 100,
                'lines': [],
            }
        }

        order_id = self.pos_order_model.create_from_ui([order_data])

        self.assertTrue(order_id)
        mock_channel.basic_publish.assert_called_once()

    # Add more tests...