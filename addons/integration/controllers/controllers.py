from odoo import http
from odoo.http import request
import json

class WordPressIntegration(http.Controller):

    @http.route('/api/wordpress/customer', type='json', auth='public', methods=['POST'])
    def receive_customer_data(self, **post):
        data = json.loads(request.httprequest.data)
        request.env['res.partner'].sudo().receive_from_wordpress(data)
        return {'status': 'success'}

    @http.route('/api/wordpress/customers', type='json', auth='public', methods=['GET'])
    def get_customers(self, **kwargs):
        partners = request.env['res.partner'].sudo().search([])
        customers = [{
            'id': partner.id,
            'name': partner.name,
            'email': partner.email,
            'phone': partner.phone,
            'wordpress_id': partner.wordpress_id,
        } for partner in partners]
        return customers
