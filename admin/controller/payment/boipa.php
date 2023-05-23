<?php
namespace Opencart\Admin\Controller\Extension\Boipa\Payment;
class Boipa extends \Opencart\System\Engine\Controller {


    //Define the $integration_modes,specifies whether integration mode should be shown or not, 1 means to show, 0 means not
    private $integration_show_iframe = "1";
    private $integration_show_redirect = "1";
    private $integration_show_hostedpay = "1";
    /*
     * The index of the mode:
     * hostedpay:0
     * iframe:1
     * redirect: 2
     */
    //specifies the default index of integration mode
    private $default_integration_mode = "1";

    public function index() {
        $this->load->language('extension/boipa/payment/boipa');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        $data = array();
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/boipa/payment/boipa', 'user_token=' . $this->session->data['user_token'])
        );

        $data['save'] = $this->url->link('extension/boipa/payment/boipa|save', 'user_token=' . $this->session->data['user_token']);

        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

       

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');


        $data['entry_clientid'] = $this->language->get('entry_clientid');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_testmode'] = $this->language->get('entry_testmode');
        $data['entry_status'] = $this->language->get('entry_status');


        


        if (isset($this->request->post['payment_boipa_clientid'])) {
            $data['payment_boipa_clientid'] = $this->request->post['payment_boipa_clientid'];
        } else {
            $data['payment_boipa_clientid'] = $this->config->get('payment_boipa_clientid');
        }
        if (isset($this->request->post['payment_boipa_password'])) {
            $data['payment_boipa_password'] = $this->request->post['payment_boipa_password'];
        } else {
            $data['payment_boipa_password'] = $this->config->get('payment_boipa_password');
        }
        if (isset($this->request->post['payment_boipa_brandid'])) {
            $data['payment_boipa_brandid'] = $this->request->post['payment_boipa_brandid'];
        } else {
            $data['payment_boipa_brandid'] = $this->config->get('payment_boipa_brandid');
        }
        if (isset($this->request->post['payment_boipa_status'])) {
            $data['payment_boipa_status'] = $this->request->post['payment_boipa_status'];
        } else {
            $data['payment_boipa_status'] = $this->config->get('payment_boipa_status');
        }



        if (isset($this->request->post['payment_boipa_testmode'])) {
            $data['payment_boipa_testmode'] = $this->request->post['payment_boipa_testmode'];
        } else {
            $data['payment_boipa_testmode'] = $this->config->get('payment_boipa_testmode');
        }
        
        if(empty($this->integration_show_iframe) && empty($this->integration_show_redirect) && empty($this->integration_show_hostedpay)){
            $data['payment_boipa_pay_type'] = $this->default_integration_mode;
            $data['showPayTypeField'] = 0;
        }else{
            $data['showPayTypeField'] = 1;
            if (isset($this->request->post['payment_boipa_pay_type'])) {
                $data['payment_boipa_pay_type'] = $this->request->post['payment_boipa_pay_type'];
            } else {
                $data['payment_boipa_pay_type'] = $this->config->get('payment_boipa_pay_type');
            }
        }


        if (isset($this->request->post['payment_boipa_pay_action'])) {
            $data['payment_boipa_pay_action'] = $this->request->post['payment_boipa_pay_action'];
        } else {
            $data['payment_boipa_pay_action'] = $this->config->get('payment_boipa_pay_action');
        }

        //set up order status
        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        if (isset($this->request->post['payment_boipa_success_status_id'])) {
            $data['payment_boipa_success_status_id'] = $this->request->post['payment_boipa_success_status_id'];
        } else {
            $data['payment_boipa_success_status_id'] = $this->config->get('payment_boipa_success_status_id');
        }
        if (isset($this->request->post['payment_boipa_failed_status_id'])) {
            $data['payment_boipa_failed_status_id'] = $this->request->post['payment_boipa_failed_status_id'];
        } else {
            $data['payment_boipa_failed_status_id'] = $this->config->get('payment_boipa_failed_status_id');
        }
        if (isset($this->request->post['payment_boipa_refunded_status_id'])) {
            $data['payment_boipa_refunded_status_id'] = $this->request->post['payment_boipa_refunded_status_id'];
        } else {
            $data['payment_boipa_refunded_status_id'] = $this->config->get('payment_boipa_refunded_status_id');
        }
        if (isset($this->request->post['payment_boipa_auth_status_id'])) {
            $data['payment_boipa_auth_status_id'] = $this->request->post['payment_boipa_auth_status_id'];
        } else {
            $data['payment_boipa_auth_status_id'] = $this->config->get('payment_boipa_auth_status_id');
        }
        if (isset($this->request->post['payment_boipa_voided_status_id'])) {
            $data['payment_boipa_voided_status_id'] = $this->request->post['payment_boipa_voided_status_id'];
        } else {
            $data['payment_boipa_voided_status_id'] = $this->config->get('payment_boipa_voided_status_id');
        }
        if (isset($this->request->post['payment_boipa_canceled_status_id'])) {
            $data['payment_boipa_canceled_status_id'] = $this->request->post['payment_boipa_canceled_status_id'];
        } else {
            $data['payment_boipa_canceled_status_id'] = $this->config->get('payment_boipa_canceled_status_id');
        }

        if (isset($this->request->post['payment_boipa_sort_order'])) {
            $data['payment_boipa_sort_order'] = $this->request->post['payment_boipa_sort_order'];
        } else {
            $data['payment_boipa_sort_order'] = $this->config->get('payment_boipa_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/boipa/payment/boipa', $data));
    }
    public function save() {
		$this->load->language('extension/boipa/payment/boipa');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/boipa/payment/boipa')) {
			$json['error'] = $this->language->get('error_permission');
		}
        if (!$this->request->post['payment_boipa_brandid']) {
			$json['error'] = $this->language->get('error_brandid');
		}
        if (!$this->request->post['payment_boipa_password']) {
			$json['error'] = $this->language->get('error_password');
		}
		if (!$this->request->post['payment_boipa_clientid']) {
			$json['error'] = $this->language->get('error_clientid');
		}
		if (!$json) {
            $post_data = $this->request->post;
            $post_data['payment_boipa_test_token_url'] = "https://apiuat.test.boipapaymentgateway.com/token";
            $post_data['payment_boipa_test_payments_url'] = "https://apiuat.test.boipapaymentgateway.com/payments";
            $post_data['payment_boipa_test_javascript_url'] = "https://cashierui-apiuat.test.boipapaymentgateway.com/js/api.js";
            $post_data['payment_boipa_test_cashier_url'] = "https://cashierui-apiuat.test.boipapaymentgateway.com/ui/cashier";
            $post_data['payment_boipa_token_url'] = "https://api.boipapaymentgateway.com/token";
            $post_data['payment_boipa_payments_url'] = "https://api.boipapaymentgateway.com/payments";
            $post_data['payment_boipa_javascript_url'] = "https://cashierui-api.boipapaymentgateway.com/js/api.js";
            $post_data['payment_boipa_cashier_url'] = "https://cashierui-api.boipapaymentgateway.com/ui/cashier";
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_boipa', $post_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function install() {
        //by default, the OpenCart4 system sets the session samesite attribute to be Strict, 
        //it causes the cookie can not be carried when the customers are redirected back from Gateway even through a GET request
        //so have to change the samesite attribute to be Lax 
        $this->load->model('setting/setting');
		$this->model_setting_setting->editValue('config', 'config_session_samesite', 'Lax');
        $this->load->model('extension/boipa/payment/boipa');
        $this->model_extension_boipa_payment_boipa->install();
    }

    public function uninstall() {
        $this->load->model('extension/boipa/payment/boipa');
        $this->model_extension_boipa_payment_boipa->uninstall();
    }

    //show the order tab in the back office
    public function order() {
        if ($this->config->get('payment_boipa_status')) {
            $this->load->model('extension/boipa/payment/boipa');

            $order_info = $this->model_extension_boipa_payment_boipa->getOrder($this->request->get['order_id']);
            if (!empty($order_info)) {
                $this->load->language('extension/boipa/payment/boipa');

                $order_info['total_formatted'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1, true);

                $order_info['total_captured'] = $this->model_extension_boipa_payment_boipa->getTotalCaptured($order_info['boipa_order_id']);
                $order_info['total_captured_formatted'] = $this->currency->format($order_info['total_captured'], $order_info['currency_code'], 1, true);

                $order_info['total_refunded'] = $this->model_extension_boipa_payment_boipa->getTotalRefunded($order_info['boipa_order_id']);
                $order_info['total_refunded_formatted'] = $this->currency->format($order_info['total_refunded'], $order_info['currency_code'], 1, true);

                $order_info['unrefunded'] = number_format($order_info['total_captured'] - $order_info['total_refunded'],2,'.','');

                $data = array();
                $data['user_token'] = $this->session->data['user_token'];
                $data['order_info'] = $order_info;
                $data['order_id'] = $this->request->get['order_id'];

                return $this->load->view('extension/boipa/payment/boipa_order', $data);
            }
        }
    }
    public function capture() {
        $this->load->language('extension/boipa/payment/boipa');
        $json = array();

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
            $this->load->model('extension/boipa/payment/boipa');

            $order = $this->model_extension_boipa_payment_boipa->getOrder($this->request->post['order_id']);
            if(empty($order)){
                $json['error'] = true;
                $json['msg'] = 'Order does not exsit';
            }else{
                //does not support partial capture yet, so capture only one time
                $capture_amount = $order['total'];

                try {
                    $capture_response = $this->model_extension_boipa_payment_boipa->capture($this->request->post['order_id'], $capture_amount);
                } catch (\Exception $e) {
                    $json['error'] = true;
                    $json['msg'] = $this->language->get('text_error_connect_gateway');
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($json));
                    return;
                }

                if ($capture_response) {
                    $this->model_extension_boipa_payment_boipa->addTransaction($order['boipa_order_id'], 'payment', $capture_amount);
                    $json['msg'] = $this->language->get('text_capture_ok');
                    $this->model_extension_boipa_payment_boipa->updateCaptureStatus($order['boipa_order_id'], 2);

                    $this->model_extension_boipa_payment_boipa->updateOrderHistory($this->request->post['order_id'],$this->config->get('payment_boipa_success_status_id'),'Captured');

                    $json['data'] = array();
                    $json['data']['created'] = $this->model_extension_boipa_payment_boipa->getMysqlNowTime();
                    $json['data']['amount'] = $capture_amount;
                    $json['data']['total_captured_formatted'] = $this->currency->format($capture_amount, $order['currency_code'], 1, true);
                    $json['data']['capture_status'] = 1;
                    $json['data']['total'] = $capture_amount;
                    $json['error'] = false;
                } else {
                    $json['error'] = true;
                    $json['msg'] = 'Capture error';
                }
            }
        } else {
            $json['error'] = true;
            $json['msg'] = 'Missing data';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function void() {
        $this->load->language('extension/boipa/payment/boipa');
        $json = array();

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
            $this->load->model('extension/boipa/payment/boipa');

            $order = $this->model_extension_boipa_payment_boipa->getOrder($this->request->post['order_id']);

            try {
                $void_response = $this->model_extension_boipa_payment_boipa->void($this->request->post['order_id']);
            } catch (\Exception $e) {
                $json['error'] = true;
                $json['msg'] = $this->language->get('text_error_connect_gateway');
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }


            if ($void_response) {
                $this->model_extension_boipa_payment_boipa->addTransaction($order['boipa_order_id'], 'void', 0.00);
                $this->model_extension_boipa_payment_boipa->updateVoidStatus($order['boipa_order_id'], 1);

                $this->model_extension_boipa_payment_boipa->updateOrderHistory($this->request->post['order_id'],$this->config->get('payment_boipa_voided_status_id'),'Voided');

                $json['msg'] = $this->language->get('text_void_ok');
                $json['data'] = array();
                $json['data']['created'] = $this->model_extension_boipa_payment_boipa->getMysqlNowTime();
                $json['error'] = false;
            } else {
                $json['error'] = true;
                $json['msg'] = 'Void error';
            }
        } else {
            $json['error'] = true;
            $json['msg'] = 'Missing data';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function refund() {
        $this->load->language('extension/boipa/payment/boipa');
        $json = array();

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
            $refund_amount = (double)$this->request->post['amount'];

            $this->load->model('extension/boipa/payment/boipa');

            $order = $this->model_extension_boipa_payment_boipa->getOrder($this->request->post['order_id']);

            try {
                $refund_response = $this->model_extension_boipa_payment_boipa->refund($this->request->post['order_id'],$refund_amount);
            } catch (\Exception $e) {
                $json['error'] = true;
                $json['msg'] = $this->language->get('text_error_connect_gateway');
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }


            if ($refund_response) {
                $this->model_extension_boipa_payment_boipa->addTransaction($order['boipa_order_id'], 'refund', $refund_amount);
                $total_captured = $this->model_extension_boipa_payment_boipa->getTotalCaptured($order['boipa_order_id']);
                $total_refunded = $this->model_extension_boipa_payment_boipa->getTotalRefunded($order['boipa_order_id']);
                $refund_status = 0;
                if ($total_captured == $total_refunded) {
                    $refund_status = 1;
                    $this->model_extension_boipa_payment_boipa->updateRefundStatus($order['boipa_order_id'], $refund_status);
                    $this->model_extension_boipa_payment_boipa->updateOrderHistory($this->request->post['order_id'],$this->config->get('payment_boipa_refunded_status_id'),'Refunded');
                }
                $remaining = number_format($total_captured - $total_refunded, 2, '.', '');


                $json['data'] = array();
                $json['data']['amount'] = number_format($refund_amount, 2, '.', '');
                $json['data']['total_refunded_formatted'] = $this->currency->format($total_refunded, $order['currency_code'], 1, true);
                $json['data']['refund_status'] = $refund_status;
                $json['data']['remaining'] = $remaining;
                $json['msg'] = $this->language->get('text_refund_ok');
                $json['data']['created'] = $this->model_extension_boipa_payment_boipa->getMysqlNowTime();
                $json['error'] = false;
            } else {
                $json['error'] = true;
                $json['msg'] = 'Refund error';
            }
        } else {
            $json['error'] = true;
            $json['msg'] = 'Missing data';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
