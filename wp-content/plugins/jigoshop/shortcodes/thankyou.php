<?php
/**
 * Thank you shortcode
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package    Jigoshop
 * @category   Checkout
 * @author     Jigowatt
 * @copyright  Copyright (c) 2011 Jigowatt Ltd.
 * @license    http://jigoshop.com/license/commercial-edition
 */

function get_jigoshop_thankyou( $atts ) {
	return jigoshop::shortcode_wrapper('jigoshop_thankyou', $atts);
}

/**
 * Outputs the thankyou page
 **/
function jigoshop_thankyou() {

	_e('<p>Thank you. Your order has been processed successfully.</p>', 'jigoshop');

	// Pay for order after checkout step
	if (isset($_GET['order'])) $order_id = $_GET['order']; else $order_id = 0;
	if (isset($_GET['key'])) $order_key = $_GET['key']; else $order_key = '';

	if ($order_id > 0) :

		$order = &new jigoshop_order( $order_id );

		if ($order->order_key == $order_key) :

			?>
			<ul class="order_details">
				<li class="order">
					<?php _e('Order:', 'jigoshop'); ?>
					<strong># <?php echo $order->id; ?></strong>
				</li>
				<li class="date">
					<?php _e('Date:', 'jigoshop'); ?>
					<strong><?php echo date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime($order->order_date)); ?></strong>
				</li>
				<li class="total">
					<?php _e('Total:', 'jigoshop'); ?>
					<strong><?php echo jigoshop_price($order->order_total); ?></strong>
				</li>
				<li class="method">
					<?php _e('Payment method:', 'jigoshop'); ?>
					<strong><?php
						$gateways = jigoshop_payment_gateways::payment_gateways();
						if (isset($gateways[$order->payment_method])) echo $gateways[$order->payment_method]->title;
						else echo $order->payment_method;
					?></strong>
				</li>
			</ul>
			<div class="clear"></div>
			<?php

			do_action( 'thankyou_' . $order->payment_method, $order_id );

		endif;

	endif;
	
	echo '<p><a class="button" href="'.get_permalink(get_option('jigoshop_shop_page_id')).'">'.__('&larr; Continue Shopping', 'jigoshop').'</a></p>';

}