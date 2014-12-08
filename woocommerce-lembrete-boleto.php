<?php
/**
 * Plugin Name: Woocommerce Lembrete Boleto
 * Depends: Woocommerce, Woocommerce Boleto
 * Plugin URI: http://www.agenciamagma.com.br
 * Description: Send email to on-hold boleto orders with few days left to pay.
 * Version: 1.0.0
 * Author: Carlos Cardoso Dias
 * Author URI: http://agenciamagma.com.br
 * License: GPL2
 */

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('AG_Magma_Lembrete_Boleto')):

class AG_Magma_Lembrete_Boleto {

	const VERSION = '1.0.0';
	const DAYS_TO_EXPIRE = 2;

	private static $instance = null;

	public static function get_instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action('ag-magma-lembrete-boleto-verify-orders', array($this, 'verify_orders_and_send_mail'));
	}

	public static function activation() {
		wp_schedule_event(strtotime('2014-12-03 00:00:00'), 'daily', 'ag-magma-lembrete-boleto-verify-orders');
	}

	public static function deactivation() {
		wp_clear_scheduled_hook('ag-magma-lembrete-boleto-verify-orders');
	}

	public function verify_orders_and_send_mail() {
		// recuperar o número de dias padrão para expiração do boleto
		$boleto_settings = get_option('woocommerce_boleto_settings', '5');
		$expiration_time = intval($boleto_settings['boleto_time']);

		// calcular as datas para pesquisa de ordens
		$start_date = date('Y-m-d', strtotime(date('Y-m-d') . ' - ' . $expiration_time . ' days'));
		$end_date = date('Y-m-d', strtotime(date('Y-m-d') . ' - ' . ($expiration_time - self::DAYS_TO_EXPIRE) . ' days'));

		// obter ordens prestes à expirar
		$orders_id = $this->get_all_onhold_boleto_orders_id_between($start_date, $end_date);

		// formatar e enviar os emails
		foreach ($orders_id as $order_id) {
			$order = new WC_Order($order_id);
			
			$days_left = $expiration_time - intval(date('d', time() - strtotime($order->order_date)));

			if ($days_left < 0) {
				continue;
			}

			$msg = 'Olá, ' . $order->get_user()->first_name . '.<br />';

			switch($days_left) {
				case 0:
					$msg .= "Seu boleto expira hoje.<br />";
				break;
				case 1:
					$msg .= "Falta " . $days_left . " dia para o seu boleto expirar.<br />";
				break;
				default:
					$msg .= "Faltam " . $days_left . " dias para o seu boleto expirar.<br />";
			}

			$msg .= 'Acesse seu boleto <a href="' . WC_Boleto::get_boleto_url($order->order_key) . '?utm_source=recupera-boleto&utm_medium=boleto&utm_campaign=recupera-boleto-' . date('d-m-Y') . '">aqui</a>.';
			
			wc_mail($order->get_user()->user_email, get_bloginfo('name'), $msg);
		}
	}

	private function get_all_onhold_boleto_orders_id_between($start_date, $end_date) {
		$start_date = explode('-', $start_date);
		$end_date = explode('-', $end_date);

		$start_year = $start_date[0];
		$start_month = $start_date[1];
		$start_day = $start_date[2];

		$end_year = $end_date[0];
		$end_month = $end_date[1];
		$end_day = $end_date[2];
		
		$orders = array();

    	$args = array(
    		'numberposts'        => -1,
    	    'post_type'          => 'shop_order',
    	    'post_status'        => 'wc-on-hold',
    	    'meta_key'           => '_payment_method',
    	    'meta_value'         => 'boleto',
    	    'date_query'         => array(
    	    	array(
    	    		'after'      => array(
    	    			'year'   => $start_year,
    	    			'month'  => $start_month,
    	    			'day'    => $start_day
    	    		),
    	    		'before'     => array(
    	    			'year'   => $end_year,
    	    			'month'  => $end_month,
    	    			'day'    => $end_day,
    	    		),
    	    		'inclusive'  => true
    	    	)
    	    )
    	);
    	
    	$posts = get_posts($args);
    	
    	$orders = wp_list_pluck($posts, 'ID');
    	
    	return $orders;
	}
}

/**
 * Plugin activation and deactivation methods.
 */
register_activation_hook(__FILE__, array( 'AG_Magma_Lembrete_Boleto', 'activate'));
register_deactivation_hook(__FILE__, array( 'AG_Magma_Lembrete_Boleto', 'deactivate'));

/**
 * Initialize the plugin.
 */
//add_action('plugins_loaded', array('AG_Magma_Lembrete_Boleto', 'get_instance'), 0);
add_action('woocommerce_init', array('AG_Magma_Lembrete_Boleto', 'get_instance'), 0);

endif;
