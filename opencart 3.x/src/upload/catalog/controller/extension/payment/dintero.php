<?php
class ControllerExtensionPaymentDintero extends Controller {
	
    public $_url_token = 'https://api.dintero.com/v1/';
    public $_url_checkout = 'https://checkout.dintero.com/v1/';   
    
    public function index() {
        $this->load->language('extension/payment/dintero');
		$data['text_testmode'] = $this->language->get('text_testmode');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['testmode'] = $this->config->get('payment_dintero_test');
        return $this->load->view('extension/payment/dintero', $data);
	}
    
    public function send() {
        $this->load->language('extension/payment/dintero');
        if( $this->config->get('payment_dintero_test') ){
            $_payment_dintero_payment_profile_id = $this->config->get('payment_dintero_payment_profile_id_test');
        } else {
            $_payment_dintero_payment_profile_id = $this->config->get('payment_dintero_payment_profile_id');
        }
        
        $access_token = $this->get_access_token();      
          
        if( isset($access_token['error']) ){
            $json['error'] = 'Error: '.$access_token['error_text']."\r\n".'Contact to store owner!';
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        if( isset($access_token['success']) ){
            $access_token = $access_token['access_token'];
        } else {
            $json['error'] = 'Unknown error!'."\r\n".'Contact to store owner!';
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;            
        }
        
        $this->load->model('checkout/order');
        $this->load->model('catalog/product');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_products = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
        $order_totals = $this->model_checkout_order->getOrderTotals($this->session->data['order_id']);
        
        $tax_all = 0;
        foreach($order_totals AS $order_total){
            if( $order_total['code'] == 'tax' ){
                $tax_all = number_format($order_total['value']*100,0,'','');    
            }
        }
        
        /** forming dara for request START */
        $order_total_sum = number_format($order_info['total']*100,0,'',''); 
        
        $_CURLOPT_POSTFIELDS = '{
              "url": {
                "return_url": "'.$this->url->link('extension/payment/dintero/callback', '', true).'"
              },
              "customer": {
                "email": "'.$order_info['email'].'",
                "phone_number": "'.$order_info['telephone'].'"
              },
              "order": {
                "amount": '.(int)$order_total_sum.',
                "vat_amount": '.(int)$tax_all.',
                "currency": "'.$order_info['currency_code'].'",
                "merchant_reference": "'.$order_info['order_id'].'",
                "shipping_address": {
                  "first_name": "'.$order_info['shipping_firstname'].'",
                  "last_name": "'.$order_info['shipping_lastname'].'",
                  "address_line": "'.$order_info['shipping_address_1'].' '.$order_info['shipping_address_2'].'",
                  "postal_code": "'.$order_info['shipping_postcode'].'",
                  "postal_place": "'.$order_info['shipping_zone'].'",
                  "country": "'.$order_info['shipping_iso_code_2'].'"
                },
                "billing_address": {
                  "first_name": "'.$order_info['payment_firstname'].'",
                  "last_name": "'.$order_info['payment_lastname'].'",
                  "address_line": "'.$order_info['payment_address_1'].' '.$order_info['payment_address_2'].'",
                  "postal_code": "'.$order_info['payment_postcode'].'",
                  "postal_place": "'.$order_info['payment_zone'].'",
                  "country": "'.$order_info['payment_iso_code_2'].'"
                },
                "items": [';
        $_porducts = '';
        
        $total_product = 0;
        $total_product_vat = 0;
        
        $pr_c = 1;
        foreach($order_products AS $p=>$order_product){
            $products_info = $this->model_catalog_product->getProduct($order_product['product_id']);
             
            $tax_rate_id = $this->db->query("SELECT * FROM `".DB_PREFIX."tax_rule` WHERE 
            tax_class_id = '".(int)$products_info['tax_class_id']."' ");
            $tax_rate_id = $tax_rate_id->row['tax_rate_id'];
            $tax_rate_info = $this->db->query(" SELECT * FROM `".DB_PREFIX."tax_rate` WHERE 
            tax_rate_id = '".(int)$tax_rate_id."'");
            
            $tax_class_info = $this->model_catalog_product->getProduct($products_info['tax_class_id']);
            $pr_price = (($order_product['price']+$order_product['tax'])*$order_product['quantity'])*100;
            $pr_price = number_format($pr_price,0,'','');

            $_porducts.= ' {
                        "id": "'.(int)$pr_c.'",
                        "line_id": "'.$order_product['order_product_id'].'",
                        "description": "'.$order_product['name'].'",
                        "quantity": '.(int)$order_product['quantity'].',
                        "amount": '.(int)$pr_price.',
                        "vat_amount": '. number_format($order_product['tax']*100,0,'','').',
                        "vat": '.(int)$tax_rate_info->row['rate'].'
                      },'; 
                                    
            $total_product+= $pr_price;
            $total_product_vat+= number_format($order_product['tax']*100,0,'','');      
            $pr_c++;                 
        }
        $total = $order_total_sum - $total_product;
        $total_vat = $tax_all - $total_product_vat;
        
        if( $total>0 ){
            $_porducts.= ' {
                    "id": "'.(int)$pr_c.'",
                    "line_id": "shipping",
                    "description": "'.$this->language->get('text_total').'",
                    "quantity": 1,
                    "amount": '.(int)$total.',
                    "vat_amount": '.(int)$total_vat.',
                    "vat": '.(int)$tax_rate_info->row['rate'].'
                  },';
        }                        
        
        $_CURLOPT_POSTFIELDS.= substr($_porducts, 0, -1);   
        $_CURLOPT_POSTFIELDS.= ']
              },
              "profile_id": "'.$_payment_dintero_payment_profile_id.'"
            }';                   
        /** forming dara for request END */
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->_url_checkout."sessions-profile",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $_CURLOPT_POSTFIELDS,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$access_token,
            "Content-Type: application/json",
            "cache-control: no-cache"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        $err_m = array();
        $err_m_t = '';
        $json = array();
        if ($err) {
            $json['error'] = 'Error: '.$err."\r\n".'Contact to store owner!';
        } else {
            $response = json_decode( $response, true );
            
            if( isset( $response['error'] ) ){
                if( isset($response['error']['errors']) ){
                    foreach($response['error']['errors'] AS $_error ){
                        $err_m[] = $_error['message'];
                    }
                    if( $err_m ) $err_m_t = ' <strong>['.implode(', ',$err_m).']</strong>'; 
                }
                $json['error'] = 'Error: '.$response['error']['message'].$err_m_t."\r\n".'Contact to store owner!';
            }
        }              
        
        if (!$json) {
            $dintero_id = $response['id'];
            $this->add_dintero_id_to_order($response['id'],$order_info['order_id']);
            $json['success'] = $response['url'];
        }
         
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return;
    }
    
    public function update_status() {
        if( !isset( $this->session->data['user_token'] ) ){
            exit;
        }
        
        $order_id = $_POST['order_id'];
        
        $this->load->model('checkout/order');
        $json = array();
        $access_token = $this->get_access_token();  
        if( isset($access_token['error']) ){
            $json['error'] = 'Error: '.$access_token['error_text']."\r\n".'Contact to store owner!';
        }
        if( isset($access_token['success']) ){
            $access_token = $access_token['access_token'];
        } else {
            $json['error'] = 'Unknown error!'."\r\n".'Contact to store owner!'; 
        }
        
        if( $json ){
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $order_info = $this->db->query("SELECT * FROM `".DB_PREFIX."order`  
        WHERE order_id = '".(int)$order_id."'")->row;
        
        if( isset($transactions_status['error']) ) {
            $json['error'] = $transactions_status['error'];
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));  
            return;
        }
        
        $transaction_info = $this->TransactionInfo($access_token, $order_info['dintero_transaction_id']); 
        $this->SaveTransactionStatus($order_id,$transaction_info['status']);
              
        $json['success'] = $transaction_info['status'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));           
                
    }
    
    public function change_status() {
        if( !isset( $this->session->data['user_token'] ) ){
            exit;
        }
        
        $_status   = $_POST['dintero_status'];
        $order_id = $_POST['order_id'];
        
        $this->load->model('checkout/order');
        $json = array();
        $access_token = $this->get_access_token();  
        if( isset($access_token['error']) ){
            $json['error'] = 'Error: '.$access_token['error_text']."\r\n".'Contact to store owner!';
        }
        if( isset($access_token['success']) ){
            $access_token = $access_token['access_token'];
        } else {
            $json['error'] = 'Unknown error!'."\r\n".'Contact to store owner!'; 
        }
        
        if( $json ){
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $order_info = $this->db->query("SELECT * FROM `".DB_PREFIX."order`  
        WHERE order_id = '".(int)$order_id."'")->row;
        
        $transactions_status = $this->TransactionsOperation($_status,$access_token,$order_info['dintero_transaction_id'],$order_id);
        
        if( isset($transactions_status['error']) ) {
            $json['error'] = $transactions_status['error'];
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));  
            return;
        }
        
        $transaction_info = $this->TransactionInfo($access_token, $order_info['dintero_transaction_id']);   
        
        $this->SaveTransactionStatus($order_id,$transaction_info['status']);
        
        $this->SaveDinteroStatus($order_id,$transaction_info['status']);                
                      
        $json['success'] = $transaction_info['status'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));              
    }
    
    public function SaveTransactionStatus($order_id, $transaction_info_status) {
        $this->load->model('checkout/order');
        $order_status_id = $this->config->get('config_order_status_id');
        $this->SaveDinteroStatus($order_id,$transaction_info_status);
        switch($transaction_info_status) {
            case 'AUTHORIZED':
				$order_status_id = $this->config->get('payment_dintero_authorized_status_id');
				break;
            case 'REFUNDED':
				$order_status_id = $this->config->get('payment_dintero_refunded_status_id');
				break;                      
            case 'CAPTURED':
				$order_status_id = $this->config->get('payment_dintero_captured_status_id');
				break;                    
            case 'DECLINED':
				$order_status_id = $this->config->get('payment_dintero_declined_status_id');
				break;
            case 'FAILED':
				$order_status_id = $this->config->get('payment_dintero_failed_status_id');
				break;
            case 'UNKNOWN':
				$order_status_id = $this->config->get('payment_dintero_unknown_status_id');
				break;
            case 'AUTHORIZATION_VOIDED':
				$order_status_id = $this->config->get('payment_dintero_authorization_voided_status_id');
				break;
            case 'PARTIALLY_CAPTURED':
				$order_status_id = $this->config->get('payment_dintero_partially_captured_status_id');
				break;
            case 'PARTIALLY_CAPTURED_REFUNDED':
				$order_status_id = $this->config->get('payment_dintero_partially_captured_refunded_status_id');
				break;
            case 'PARTIALLY_REFUNDED':
				$order_status_id = $this->config->get('payment_dintero_partially_refunded_status_id');
				break;                           
        }  
        $order_info = $this->model_checkout_order->getOrder($order_id);
        if( $order_info['email'] ){ 
            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true);
        } else {
            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', false);
        }       
    }
    
	public function callback() {
        $this->load->model('checkout/order');
        if( isset($_GET['merchant_reference']) ){
            $order_id = $_GET['merchant_reference'];    
        } else {
            $order_id = (int)$this->session->data['order_id'];                
        }

        if( isset( $_GET['error'] ) ) {
            $this->session->data['error'] = 'Something went wrong with the payment flow.';
            if( $_GET['error'] == 'cancelled' ){
                $this->SaveTransactionStatus($order_id, 'ERROR_'.strtoupper( $_GET['error']) );
                //$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_dintero_customer_cancelled_status_id'), '', true);
            }
            if( $_GET['error'] == 'authorization' ){
                $this->SaveTransactionStatus($order_id, 'ERROR_'.strtoupper( $_GET['error']) );
                //$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_dintero_customer_failed_status_id'), '', true);
            }          
            if( $_GET['error'] == 'failed' ){
                $this->SaveTransactionStatus($order_id, 'ERROR_'.strtoupper( $_GET['error']) );
                //$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_dintero_rejected_by_dintero_status_id'), '', true);
            }                     
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }

        $json = array();
        $access_token = $this->get_access_token();  
        if( isset($access_token['error']) ){
            $json['error'] = 'Error: '.$access_token['error_text']."\r\n".'Contact to store owner!';
        }
        if( isset($access_token['success']) ){
            $access_token = $access_token['access_token'];
        } else {
            $json['error'] = 'Unknown error!'."\r\n".'Contact to store owner!';
        }
        
        if( $json ){
            $this->session->data['error'] = $json['error'];
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
            return;
        }
        
        $transaction_info = $this->TransactionInfo($access_token,$_GET['transaction_id']);     
        
        if( $this->config->get('payment_dintero_transaction') ){
            $capture_status = $this->TransactionsOperation('capture',$access_token,$_GET['transaction_id'], $transaction_info['merchant_reference']);
            $transaction_info = $this->TransactionInfo($access_token,$_GET['transaction_id']);
        } 
        
        $order_id = $transaction_info['merchant_reference'];
        $this->SaveTransactionID($order_id, $transaction_info['id']);
        $order_info = $this->model_checkout_order->getOrder($_GET['merchant_reference']);
                
        if( $order_info ){ 
            $this->SaveTransactionStatus($order_id, $transaction_info['status']);
        }       
        
        $this->response->redirect($this->url->link('checkout/success', '', true));        
	}
    
    public function TransactionsOperation($dintero_status ,$access_token,$transaction_id,$order_id){
        $this->load->model('checkout/order');
        $this->load->model('catalog/product');
		$order_info = $this->model_checkout_order->getOrder($order_id);
        $order_products = $this->model_checkout_order->getOrderProducts($order_id);
        $order_totals = $this->model_checkout_order->getOrderTotals($order_id);        
        
        $order_total_sum = number_format($order_info['total']*100,0,'',''); 
        
        $_CURLOPT_POSTFIELDS = '       
            {
              "amount": '.(int)$order_total_sum.',
              "reason": "shipped",
              "items": [';
        
        $_porducts = '';  
        $total_product = 0;
        foreach($order_products AS $p=>$order_product){  
            $pr_price = (($order_product['price']+$order_product['tax'])*$order_product['quantity'])*100;
            $pr_price = number_format($pr_price,0,'','');            
            $_porducts.= '{"line_id": "'.$order_product['order_product_id'].'","amount": '.(int)$pr_price.'},';
            $total_product+= $pr_price;
            break;
        }   
        $total = $order_total_sum - $total_product;
        
        $_porducts.= '{"line_id": "shipping", "amount": '.(int)$total.'},';
        $_CURLOPT_POSTFIELDS.= substr($_porducts, 0, -1);              
        $_CURLOPT_POSTFIELDS.= ']}';        
           
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->_url_checkout."transactions/".$transaction_id."/".$dintero_status,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $_CURLOPT_POSTFIELDS,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$access_token,
            "Content-Type: application/json",
            "cache-control: no-cache"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);         
        $json = array();
        if ($err) {
            $json['error'] = 'Error: '.$err."\r\n".'Contact to store owner!';
        } else {
            $response = json_decode( $response, true );
            
            if( isset( $response['error'] ) ){
                if( isset($response['error']['errors']) ){
                    $err_m = array();
                    foreach($response['error']['errors'] AS $_error ){
                        if( isset($_error['message']) )
                            $err_m[] = $_error['message'];
                        if( isset($_error['code']) )
                            $err_m[] = $_error['code'];                            
                    }
                    if( $err_m ) $err_m_t = ' <strong>['.implode(', ',$err_m).']</strong>'; 
                }
                $json['error'] = 'Error: '.$response['error']['message'].$err_m_t."\r\n".'Contact to store owner!';
            }
        }
        
        if( $json ) return $json;                      
        return true;
              
    }
    
    public function TransactionInfo($access_token,$transaction_id) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->_url_checkout."transactions/".$transaction_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$access_token,
            "Content-Type: application/json",
            "cache-control: no-cache"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
            
        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = json_decode( $response, true );
        }      
        
        return $response;   
    }
    
    public function SaveTransactionID($order_id, $transaction_id) {
        /** check if column exsis */
        $check = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".DB_PREFIX."order' 
        AND column_name = 'dintero_transaction_id'");
        if( $check->num_rows == 0 ){
            $this->db->query("ALTER TABLE `".DB_PREFIX."order` ADD `dintero_transaction_id` VARCHAR(100) NOT NULL AFTER `order_status_id`;");
        }        
        $this->db->query("UPDATE `".DB_PREFIX."order` SET  
        dintero_transaction_id = '".$this->db->escape($transaction_id)."' WHERE 
        order_id = '".(int)$order_id."'");    
        
    }

    public function SaveDinteroStatus($order_id, $dintero_status) {
        /** check if column exsis */
        $check = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".DB_PREFIX."order' 
        AND column_name = 'dintero_status'");
        if( $check->num_rows == 0 ){
            $this->db->query("ALTER TABLE `".DB_PREFIX."order` ADD `dintero_status` VARCHAR(30) NOT NULL AFTER `order_status_id`;");
        }        
        $this->db->query("UPDATE `".DB_PREFIX."order` SET 
        dintero_status = '".$this->db->escape($dintero_status)."' WHERE 
        order_id = '".(int)$order_id."'");

    }
    
    public function add_dintero_id_to_order($dintero_id, $order_id) {
        /** check if column exsis */
        $check = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".DB_PREFIX."order' 
        AND column_name = 'dintero_session_id'");
        if( $check->num_rows == 0 ){
            $this->db->query("ALTER TABLE `".DB_PREFIX."order` ADD `dintero_session_id` VARCHAR(150) NOT NULL AFTER `ip`");
        }
        $this->db->query("UPDATE `".DB_PREFIX."order` SET 
        dintero_session_id = '".$this->db->escape($dintero_id)."' WHERE 
        order_id = '".(int)$order_id."'");
        
        return true;        
    }
    
    public function get_access_token() {
        if( $this->config->get('payment_dintero_test') ){
            $_payment_dintero_client_id = $this->config->get('payment_dintero_client_id_test');
            $_payment_dintero_client_secret = $this->config->get('payment_dintero_client_secret_test');
            $_account_id = 'T'.$this->config->get('payment_dintero_account_id');
        } else {
            $_payment_dintero_client_id = $this->config->get('payment_dintero_client_id');
            $_payment_dintero_client_secret = $this->config->get('payment_dintero_client_secret');
            $_account_id = 'P'.$this->config->get('payment_dintero_account_id');            
        }
        
        $_url = $this->_url_token.'accounts/'.$_account_id;
        
        $curl = curl_init();
        $req = array(
            "grant_type" => "client_credentials",
            "audience"   => $_url,
            "type"       => "any",
            "scope"      => array("regular", "receipts:write")
        );
        curl_setopt_array($curl, array(
          CURLOPT_URL => $_url.'/auth/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($req),
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic ".base64_encode($_payment_dintero_client_id.":".$_payment_dintero_client_secret),
            "Content-Type: application/json",
            "cache-control: no-cache"
          ),
        ));
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            return array(
                'error'      => true,
                'error_text' => "cURL Error #:" . $err
            );          
        } else {
            $response = json_decode( $response, true );
            if( isset($response['error']) ){
                return array(
                    'error'      => true,
                    'error_text' => $response['error']['message']
                );
            } else {
                return array(
                    'success'      => true,
                    'access_token' => $response['access_token']
                );                
            } 
        }     
        
    }    
    
}