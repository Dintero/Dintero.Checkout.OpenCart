<?php
class ControllerExtensionPaymentDintero extends Controller {
	private $error = array();

    public function get_status() {
        $this->load->language('extension/payment/dintero');
        
        $order_info = $this->db->query("SELECT * FROM `".DB_PREFIX."order`  
        WHERE order_id = '".(int)$_GET['order_id']."'")->row;
        
        if( $order_info['payment_code'] == 'dintero' ){} else {
            return;
        } 
                                
        if( $_SERVER['HTTPS'] ){
            $_link = HTTPS_CATALOG;//.'index.php?route=extension/payment/dintero/capture&order_id='.(int)$_GET['order_id'];
        } else {
            $_link = HTTP_CATALOG;//.'index.php?route=extension/payment/dintero/capture&order_id='.(int)$_GET['order_id'];
        }
        $data['_href'] = $_link;
        $data['order_id'] = (int)$_GET['order_id'];
        
        $data['dintero_status'] = $order_info['dintero_status'];
        
        
        $_color = '#17a2b8';
        if( $order_info['dintero_status'] == 'AUTHORIZED' ) $_color = '#ffc107';
        if( $order_info['dintero_status'] == 'CAPTURED' ) $_color = '#28a745';
        if( $order_info['dintero_status'] == 'DECLINED' ) $_color = '#dc3545';
        if( $order_info['dintero_status'] == 'FAILED' || $order_info['dintero_status'] == 'REFUNDED' ) $_color = '#dc3545';
        if( $order_info['dintero_status'] == 'UNKNOWN' ) $_color = '#6c757d';
        
        $data['_color'] = $_color;
        
        $this->response->setOutput($this->load->view('extension/payment/dintero_order', $data));
        
    }

	public function index() {
		$this->load->language('extension/payment/dintero');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
          
			$this->model_setting_setting->editSetting('payment_dintero', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['client_id'])) {
			$data['error_client_id'] = $this->error['client_id'];
		} else {
			$data['error_client_id'] = '';
		}
        
		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}        
        
		if (isset($this->error['client_secret'])) {
			$data['error_client_secret'] = $this->error['client_secret'];
		} else {
			$data['error_client_secret'] = '';
		}  
        
		if (isset($this->error['payment_profile_id'])) {
			$data['error_payment_profile_id'] = $this->error['payment_profile_id'];
		} else {
			$data['error_payment_profile_id'] = '';
		}                    

		if (isset($this->error['payment_profile_id_test'])) {
			$data['error_payment_profile_id_test'] = $this->error['payment_profile_id_test'];
		} else {
			$data['error_payment_profile_id_test'] = '';
		}  

		if (isset($this->error['account_id'])) {
			$data['error_account_id'] = $this->error['account_id'];
		} else {
			$data['error_account_id'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/dintero', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/dintero', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_dintero_client_id'])) {
			$data['payment_dintero_client_id'] = $this->request->post['payment_dintero_client_id'];
		} else {
			$data['payment_dintero_client_id'] = $this->config->get('payment_dintero_client_id');
		}

		if (isset($this->request->post['payment_dintero_client_id_test'])) {
			$data['payment_dintero_client_id_test'] = $this->request->post['payment_dintero_client_id_test'];
		} else {
			$data['payment_dintero_client_id_test'] = $this->config->get('payment_dintero_client_id_test');
		}
        
		if (isset($this->error['payment_client_secret_test'])) {
			$data['error_payment_client_secret_test'] = $this->error['payment_client_secret_test'];
		} else {
			$data['error_payment_client_secret_test'] = '';
		}          

		if (isset($this->request->post['payment_dintero_title'])) {
			$data['payment_dintero_title'] = $this->request->post['payment_dintero_title'];
		} elseif( $this->config->get('payment_dintero_title') ) {
			$data['payment_dintero_title'] = $this->config->get('payment_dintero_title');
		} else {
            $data['payment_dintero_title'] = 'Dintero';
		}

		if (isset($this->error['payment_client_id_test'])) {
			$data['error_payment_client_id_test'] = $this->error['payment_client_id_test'];
		} else {
			$data['error_payment_client_id_test'] = '';
		}  

		if (isset($this->request->post['payment_dintero_client_secret'])) {
			$data['payment_dintero_client_secret'] = $this->request->post['payment_dintero_client_secret'];
		} else {
			$data['payment_dintero_client_secret'] = $this->config->get('payment_dintero_client_secret');
		}
        
		if (isset($this->request->post['payment_dintero_client_secret_test'])) {
			$data['payment_dintero_client_secret_test'] = $this->request->post['payment_dintero_client_secret_test'];
		} else {
			$data['payment_dintero_client_secret_test'] = $this->config->get('payment_dintero_client_secret_test');
		}
        
		if (isset($this->request->post['payment_dintero_payment_profile_id'])) {
			$data['payment_dintero_payment_profile_id'] = $this->request->post['payment_dintero_payment_profile_id'];
		} else {
			$data['payment_dintero_payment_profile_id'] = $this->config->get('payment_dintero_payment_profile_id');
		}        

		if (isset($this->request->post['payment_dintero_payment_profile_id_test'])) {
			$data['payment_dintero_payment_profile_id_test'] = $this->request->post['payment_dintero_payment_profile_id_test'];
		} else {
			$data['payment_dintero_payment_profile_id_test'] = $this->config->get('payment_dintero_payment_profile_id_test');
		}        

		if (isset($this->request->post['payment_dintero_account_id'])) {
			$data['payment_dintero_account_id'] = $this->request->post['payment_dintero_account_id'];
		} else {
			$data['payment_dintero_account_id'] = $this->config->get('payment_dintero_account_id');
		}  

		if (isset($this->request->post['payment_dintero_test'])) {
			$data['payment_dintero_test'] = $this->request->post['payment_dintero_test'];
		} else {
			$data['payment_dintero_test'] = $this->config->get('payment_dintero_test');
		}

		if (isset($this->request->post['payment_dintero_transaction'])) {
			$data['payment_dintero_transaction'] = $this->request->post['payment_dintero_transaction'];
		} else {
			$data['payment_dintero_transaction'] = $this->config->get('payment_dintero_transaction');
		}

		if (isset($this->request->post['payment_dintero_total'])) {
			$data['payment_dintero_total'] = $this->request->post['payment_dintero_total'];
		} else {
			$data['payment_dintero_total'] = $this->config->get('payment_dintero_total');
		}

		if (isset($this->request->post['payment_dintero_authorized_status_id'])) {
			$data['payment_dintero_authorized_status_id'] = $this->request->post['payment_dintero_authorized_status_id'];
		} else {
			$data['payment_dintero_authorized_status_id'] = $this->config->get('payment_dintero_authorized_status_id');
		}

		if (isset($this->request->post['payment_dintero_captured_status_id'])) {
			$data['payment_dintero_captured_status_id'] = $this->request->post['payment_dintero_captured_status_id'];
		} else {
			$data['payment_dintero_captured_status_id'] = $this->config->get('payment_dintero_captured_status_id');
		}

		if (isset($this->request->post['payment_dintero_declined_status_id'])) {
			$data['payment_dintero_declined_status_id'] = $this->request->post['payment_dintero_declined_status_id'];
		} else {
			$data['payment_dintero_declined_status_id'] = $this->config->get('payment_dintero_declined_status_id');
		}

		if (isset($this->request->post['payment_dintero_failed_status_id'])) {
			$data['payment_dintero_failed_status_id'] = $this->request->post['payment_dintero_failed_status_id'];
		} else {
			$data['payment_dintero_failed_status_id'] = $this->config->get('payment_dintero_failed_status_id');
		}
        
		if (isset($this->request->post['payment_dintero_refunded_status_id'])) {
			$data['payment_dintero_refunded_status_id'] = $this->request->post['payment_dintero_refunded_status_id'];
		} else {
			$data['payment_dintero_refunded_status_id'] = $this->config->get('payment_dintero_refunded_status_id');
		}        

		if (isset($this->request->post['payment_dintero_unknown_status_id'])) {
			$data['payment_dintero_unknown_status_id'] = $this->request->post['payment_dintero_unknown_status_id'];
		} else {
			$data['payment_dintero_unknown_status_id'] = $this->config->get('payment_dintero_unknown_status_id');
		}


		if (isset($this->request->post['payment_dintero_authorization_voided_status_id'])) {
			$data['payment_dintero_authorization_voided_status_id'] = $this->request->post['payment_dintero_authorization_voided_status_id'];
		} else {
			$data['payment_dintero_authorization_voided_status_id'] = $this->config->get('payment_dintero_authorization_voided_status_id');
		}


		if (isset($this->request->post['payment_dintero_partially_captured_status_id'])) {
			$data['payment_dintero_partially_captured_status_id'] = $this->request->post['payment_dintero_partially_captured_status_id'];
		} else {
			$data['payment_dintero_partially_captured_status_id'] = $this->config->get('payment_dintero_partially_captured_status_id');
		}
        
		if (isset($this->request->post['payment_dintero_partially_captured_refunded_status_id'])) {
			$data['payment_dintero_partially_captured_refunded_status_id'] = $this->request->post['payment_dintero_partially_captured_refunded_status_id'];
		} else {
			$data['payment_dintero_partially_captured_refunded_status_id'] = $this->config->get('payment_dintero_partially_captured_refunded_status_id');
		}
		if (isset($this->request->post['payment_dintero_partially_refunded_status_id'])) {
			$data['payment_dintero_partially_refunded_status_id'] = $this->request->post['payment_dintero_partially_refunded_status_id'];
		} else {
			$data['payment_dintero_partially_refunded_status_id'] = $this->config->get('payment_dintero_partially_refunded_status_id');
		}
        
		if (isset($this->request->post['payment_dintero_customer_cancelled_status_id'])) {
			$data['payment_dintero_customer_cancelled_status_id'] = $this->request->post['payment_dintero_customer_cancelled_status_id'];
		} else {
			$data['payment_dintero_customer_cancelled_status_id'] = $this->config->get('payment_dintero_customer_cancelled_status_id');
		}        

		if (isset($this->request->post['payment_dintero_customer_failed_status_id'])) {
			$data['payment_dintero_customer_failed_status_id'] = $this->request->post['payment_dintero_customer_failed_status_id'];
		} else {
			$data['payment_dintero_customer_failed_status_id'] = $this->config->get('payment_dintero_customer_failed_status_id');
		}   
        
		if (isset($this->request->post['payment_dintero_rejected_by_dintero_status_id'])) {
			$data['payment_dintero_rejected_by_dintero_status_id'] = $this->request->post['payment_dintero_rejected_by_dintero_status_id'];
		} else {
			$data['payment_dintero_rejected_by_dintero_status_id'] = $this->config->get('payment_dintero_rejected_by_dintero_status_id');
		}           

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_dintero_geo_zone_id'])) {
			$data['payment_dintero_geo_zone_id'] = $this->request->post['payment_dintero_geo_zone_id'];
		} else {
			$data['payment_dintero_geo_zone_id'] = $this->config->get('payment_dintero_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_dintero_status'])) {
			$data['payment_dintero_status'] = $this->request->post['payment_dintero_status'];
		} else {
			$data['payment_dintero_status'] = $this->config->get('payment_dintero_status');
		}

		if (isset($this->request->post['payment_dintero_sort_order'])) {
			$data['payment_dintero_sort_order'] = $this->request->post['payment_dintero_sort_order'];
		} else {
			$data['payment_dintero_sort_order'] = $this->config->get('payment_dintero_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/dintero', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/dintero')) {
			//$this->error['warning'] = $this->language->get('error_permission');
		}

        if( (int)$this->request->post['payment_dintero_test'] == 0 ){
    		if (!$this->request->post['payment_dintero_client_id']) {
    			$this->error['client_id'] = $this->language->get('error_client_id');
    		}
    		if (!$this->request->post['payment_dintero_client_secret']) {
    			$this->error['client_secret'] = $this->language->get('error_client_secret');
    		}
    		if (!$this->request->post['payment_dintero_payment_profile_id']) {
    			$this->error['payment_profile_id'] = $this->language->get('error_payment_profile_id');
    		}  
        }
        
        if( (int)$this->request->post['payment_dintero_test'] == 1 ){
    		if (!$this->request->post['payment_dintero_client_id_test']) {
    			$this->error['payment_client_id_test'] = $this->language->get('error_client_id_test');
    		}
    		if (!$this->request->post['payment_dintero_client_secret_test']) {
    			$this->error['payment_client_secret_test'] = $this->language->get('error_payment_client_secret_test');
    		}                              
    		if (!$this->request->post['payment_dintero_payment_profile_id_test']) {
    			$this->error['payment_profile_id_test'] = $this->language->get('error_payment_profile_id_test');
    		}               
        }   
        
		if (!$this->request->post['payment_dintero_payment_profile_id_test']) {
			//$this->error['payment_profile_id_test'] = $this->language->get('error_payment_profile_id_test');
		}         
              
		if (!$this->request->post['payment_dintero_account_id']) {
			$this->error['account_id'] = $this->language->get('error_account_id');
		}
        
		if (!$this->request->post['payment_dintero_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}        
           
		return !$this->error;
	}
}