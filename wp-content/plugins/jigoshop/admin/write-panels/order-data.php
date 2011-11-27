<?php
/**
 * Order Data
 *
 * Functions for displaying the order data meta box
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package    Jigoshop
 * @category   Admin
 * @author     Jigowatt
 * @copyright  Copyright (c) 2011 Jigowatt Ltd.
 * @license    http://jigoshop.com/license/commercial-edition
 */

/**
 * Order data meta box
 *
 * Displays the meta box
 *
 * @since 		1.0
 */
function jigoshop_order_data_meta_box($post) {

	global $post, $wpdb, $thepostid;
	add_action('admin_footer', 'jigoshop_meta_scripts');
	
	wp_nonce_field('jigoshop_save_data', 'jigoshop_meta_nonce');

    $data = (array) maybe_unserialize( get_post_meta($post->ID, 'order_data', true) );
	
	$data['customer_user'] = (int) get_post_meta($post->ID, 'customer_user', true);

	$order_status = wp_get_post_terms($post->ID, 'shop_order_status');
	if ($order_status) :
		$order_status = current($order_status);
		$data['order_status'] = $order_status->slug;
	else :
		$data['order_status'] = 'pending';
	endif;

	if (!isset($post->post_title) || empty($post->post_title)) :
		$order_title = 'Order';
	else :
		$order_title = $post->post_title;
	endif;

	?>
	<style type="text/css">
		#titlediv, #major-publishing-actions, #minor-publishing-actions { display:none }
	</style>
	<div class="panel-wrap jigoshop">
		<input name="post_title" type="hidden" value="<?php echo $order_title; ?>" />
		<input name="post_status" type="hidden" value="publish" />

		<ul class="product_data_tabs tabs" style="display:none;">

			<li class="active"><a href="#order_data"><?php _e('Order', 'jigoshop'); ?></a></li>

			<li><a href="#order_customer_billing_data"><?php _e('Customer Billing Address', 'jigoshop'); ?></a></li>

			<li><a href="#order_customer_shipping_data"><?php _e('Customer Shipping Address', 'jigoshop'); ?></a></li>

		</ul>

		<div id="order_data" class="panel jigoshop_options_panel">

			<p class="form-field"><label for="order_status"><?php _e('Order status:', 'jigoshop') ?></label>
			<select id="order_status" name="order_status">
				<?php
					$statuses = (array) get_terms('shop_order_status', array('hide_empty' => 0, 'orderby' => 'id'));
					foreach ($statuses as $status) :
						echo '<option value="'.$status->slug.'" ';
						if ($status->slug==$data['order_status']) echo 'selected="selected"';
						echo '>'.$status->name.'</option>';
					endforeach;
				?>
			</select></p>

			<p class="form-field"><label for="customer_user"><?php _e('Customer:', 'jigoshop') ?></label>
			<select id="customer_user" name="customer_user">
				<option value=""><?php _e('Guest', 'jigoshop') ?></option>
				<?php
					$users = $wpdb->get_col( $wpdb->prepare("SELECT $wpdb->users.ID FROM $wpdb->users ORDER BY %s ASC", 'display_name' ));

					foreach ( $users as $user_id ) :

						$user = get_userdata( $user_id );
						echo '<option value="'.$user->ID.'" ';
						if ($user->ID==$data['customer_user']) echo 'selected="selected"';
						echo '>' . $user->display_name . ' ('.$user->user_email.')</option>';

					endforeach;
				?>
			</select></p>

			<p class="form-field"><label for="excerpt"><?php _e('Customer Note:', 'jigoshop') ?></label>
			<textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt" placeholder="<?php _e('Customer\'s notes about the order', 'jigoshop'); ?>"><?php echo $post->post_excerpt; ?></textarea></p>
		</div>
		
		<div id="order_customer_billing_data" class="panel jigoshop_options_panel">
            <?php
            //display billing fieds and values
            
                $billing_fields = array(
                    'first_name' => __('First Name', 'jigoshop'),
                    'last_name' => __('Last Name', 'jigoshop'),
                    'company' => __('Company', 'jigoshop'),
                    'address_1' => __('Address 1', 'jigoshop'),
                    'address_2' => __('Address 2', 'jigoshop'),
                    'city' => __('City', 'jigoshop'),
                    'postcode' => __('Postcode', 'jigoshop'),
                    'country' => __('Country', 'jigoshop'),
                    'state' => __('State/County', 'jigoshop'),
                    'email' => __('Email Address', 'jigoshop'),
                    'phone' => __('Tel', 'jigoshop'),
                );
    
                foreach($billing_fields as $field_id => $field_desc) {
                    $field_id = 'billing_' . $field_id;
                    $field_value = '';
                    
                    if(isset($data[$field_id])) {
                        $field_value = $data[$field_id];
                    }
                    
                    echo '<p class="form-field"><label for="'.$field_id.'">'.$field_desc.':</label>
				<input type="text" name="'.$field_id.'" id="'.$field_id.'" value="'.$field_value.'" /></p>';
                }
				
			?>
		</div>

		<div id="order_customer_shipping_data" class="panel jigoshop_options_panel">

			<p class="form-field"><button class="button billing-same-as-shipping"><?php _e('Copy billing address to shipping address', 'jigoshop'); ?></button></p>
			<?php
            //display shipping fieds and values
            
                $shipping_fields = array(
                    'first_name' => __('First Name', 'jigoshop'),
                    'last_name' => __('Last Name', 'jigoshop'),
                    'company' => __('Company', 'jigoshop'),
                    'address_1' => __('Address 1', 'jigoshop'),
                    'address_2' => __('Address 2', 'jigoshop'),
                    'city' => __('City', 'jigoshop'),
                    'postcode' => __('Postcode', 'jigoshop'),
                    'country' => __('Country', 'jigoshop'),
                    'state' => __('State/County', 'jigoshop')
                );
    
                foreach($shipping_fields as $field_id => $field_desc) {
                    $field_id = 'shipping_' . $field_id;
                    $field_value = '';
                    
                    if(isset($data[$field_id])) {
                        $field_value = $data[$field_id];
                    }
                    
                    echo '<p class="form-field"><label for="'.$field_id.'">'.$field_desc.':</label>
				<input type="text" name="'.$field_id.'" id="'.$field_id.'" value="'.$field_value.'" /></p>';
                }
			?>
		</div>
	</div>
	<?php

}

/**
 * Order items meta box
 *
 * Displays the order items meta box - for showing individual items in the order
 *
 * @since 		1.0
 */
function jigoshop_order_items_meta_box($post) {

	$order_items = (array) maybe_unserialize( get_post_meta($post->ID, 'order_items', true) );

	?>
	<div class="jigoshop_order_items_wrapper">
		<table cellpadding="0" cellspacing="0" class="jigoshop_order_items">
			<thead>
				<tr>
					<th class="product-id"><?php _e('ID', 'jigoshop'); ?></th>
					<th class="variation-id"><?php _e('Variation ID', 'jigoshop'); ?></th>
					<th class="product-sku"><?php _e('SKU', 'jigoshop'); ?></th>
					<th class="name"><?php _e('Name', 'jigoshop'); ?></th>
					<th class="variation"><?php _e('Variation', 'jigoshop'); ?></th>
					<th class="meta"><?php _e('Order Item Meta', 'jigoshop'); ?></th>
					<?php do_action('jigoshop_admin_order_item_headers'); ?>
					<th class="quantity"><?php _e('Quantity', 'jigoshop'); ?></th>
					<th class="cost"><?php _e('Cost', 'jigoshop'); ?></th>
					<th class="tax"><?php _e('Tax Rate', 'jigoshop'); ?></th>
					<th class="center" width="1%"><?php _e('Remove', 'jigoshop'); ?></th>
				</tr>
			</thead>
			<tbody id="order_items_list">	
				
				<?php if (sizeof($order_items)>0 && isset($order_items[0]['id'])) foreach ($order_items as $item) : 
					
					if (isset($item['variation_id']) && $item['variation_id'] > 0) {
						$_product = &new jigoshop_product_variation( $item['variation_id'] );
                        if(is_array($item['variation'])) {
                            $_product->set_variation_attributes($item['variation']);
                        }
                    } else {
						$_product = &new jigoshop_product( $item['id'] );
                    }

					?>
					<tr class="item">
						<td class="product-id"><?php echo $item['id']; ?></td>
						<td class="variation-id"><?php if ( isset($item['variation_id']) ) echo $item['variation_id']; else echo '-'; ?></td>
						<td class="product-sku"><?php if ($_product->sku) echo $_product->sku; ?></td>
						<td class="name"><a href="<?php echo admin_url('post.php?post='. $_product->id .'&action=edit'); ?>"><?php echo $item['name']; ?></a></td>
						<td class="variation"><?php
							if (isset($_product->variation_data)) :
								echo jigoshop_get_formatted_variation( $_product->variation_data, true );
							else :
								echo '-';
							endif;
						?></td>
						<td>
							<table class="meta" cellspacing="0">
								<tfoot>
									<tr>
										<td colspan="3"><button class="add_meta button"><?php _e('Add meta', 'jigoshop'); ?></button></td>
									</tr>
								</tfoot>
								<tbody></tbody>
							</table>
						</td>
						<?php do_action('jigoshop_admin_order_item_values', $_product, $item); ?>
						<td class="quantity">
                            <input type="text" name="item_quantity[]" placeholder="<?php _e('Quantity e.g. 2', 'jigoshop'); ?>" value="<?php echo $item['qty']; ?>" />
                        </td>
						<td class="cost">
                            <input type="text" name="item_cost[]" placeholder="<?php _e('Cost per unit ex. tax e.g. 2.99', 'jigoshop'); ?>" value="<?php echo $item['cost']; ?>" />
                        </td>
						<td class="tax">
                            <input type="text" name="item_tax_rate[]" placeholder="<?php _e('Tax Rate e.g. 20.0000', 'jigoshop'); ?>" value="<?php echo $item['taxrate']; ?>" />
                        </td>
						<td class="center">
							<input type="hidden" name="item_id[]" value="<?php echo $item['id']; ?>" />
							<input type="hidden" name="item_name[]" value="<?php echo $item['name']; ?>" />
                            <input type="hidden" name="item_variation_id[]" value="<?php if ($item['variation_id']) echo $item['variation_id']; else echo ''; ?>" />
							<button type="button" class="remove_row button">&times;</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<p class="buttons">
		<select class="item_id">
			<?php
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> -1,
					'post_status'		=> 'publish',
					'post_parent'		=> 0,
					'order'				=> 'ASC',
					'orderby'			=> 'title'
				);
				$products = get_posts( $args );

				if ($products) foreach ($products as $product) :

					$sku = get_post_meta($product->ID, 'SKU', true);

					if ($sku) $sku = ' SKU: '.$sku;

					echo '<option value="'.$product->ID.'">'.$product->post_title.$sku.' (#'.$product->ID.''.$sku.')</option>';

					$args_get_children = array(
						'post_type' => array( 'product_variation', 'product' ),
						'posts_per_page' 	=> -1,
						'order'				=> 'ASC',
						'orderby'			=> 'title',
						'post_parent'		=> $product->ID
					);

					if ( $children_products =& get_children( $args_get_children ) ) :

						foreach ($children_products as $child) :

							echo '<option value="'.$child->ID.'">&nbsp;&nbsp;&mdash;&nbsp;'.$child->post_title.'</option>';

						endforeach;

					endif;

				endforeach;
			?>
		</select>

		<button type="button" class="button button-primary add_shop_order_item"><?php _e('Add item', 'jigoshop'); ?></button>
	</p>
	<p class="buttons buttons-alt">
		<button type="button" class="button button calc_totals"><?php _e('Calculate totals', 'jigoshop'); ?></button>
	</p>

	<div class="clear"></div>
	<?php

}

/**
 * Order actions meta box
 *
 * Displays the order actions meta box - buttons for managing order stock and sending the customer an invoice.
 *
 * @since 		1.0
 */
function jigoshop_order_actions_meta_box($post) {
	?>
	<ul class="order_actions">
		<li><input type="submit" class="button button-primary" name="save" value="<?php _e('Save Order', 'jigoshop'); ?>" /> <?php _e('- Save/update the order.', 'jigoshop'); ?></li>

		<li><input type="submit" class="button" name="reduce_stock" value="<?php _e('Reduce stock', 'jigoshop'); ?>" /> <?php _e('- Reduces stock for each item in the order; useful after manually creating an order or manually marking an order as complete/processing after payment.', 'jigoshop'); ?></li>
		<li><input type="submit" class="button" name="restore_stock" value="<?php _e('Restore stock', 'jigoshop'); ?>" /> <?php _e('- Restores stock for each item in the order; useful after refunding or canceling the entire order.', 'jigoshop'); ?></li>

		<li><input type="submit" class="button" name="invoice" value="<?php _e('Email invoice', 'jigoshop'); ?>" /> <?php _e('- Emails the customer order details and a payment link.', 'jigoshop'); ?></li>

		<li>
		<?php
		if ( current_user_can( "delete_post", $post->ID ) ) {
			if ( !EMPTY_TRASH_DAYS )
				$delete_text = __('Delete Permanently');
			else
				$delete_text = __('Move to Trash');
			?>
		<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
		} ?>
		</li>
	</ul>
	<?php
}

/**
 * Order totals meta box
 *
 * Displays the order totals meta box
 *
 * @since 		1.0
 */
function jigoshop_order_totals_meta_box($post) {

	$data = maybe_unserialize( get_post_meta($post->ID, 'order_data', true) );

	if (!isset($data['shipping_method'])) $data['shipping_method'] = '';
	if (!isset($data['payment_method'])) $data['payment_method'] = '';
	if (!isset($data['order_subtotal'])) $data['order_subtotal'] = '';
	if (!isset($data['order_shipping'])) $data['order_shipping'] = '';
	if (!isset($data['order_discount'])) $data['order_discount'] = '';
	if (!isset($data['order_tax'])) $data['order_tax'] = '';
	if (!isset($data['order_total'])) $data['order_total'] = '';
	if (!isset($data['order_shipping_tax'])) $data['order_shipping_tax'] = '';
	?>
	<dl class="totals">
		<dt><?php _e('Subtotal:', 'jigoshop'); ?></dt>
		<dd><input type="text" id="order_subtotal" name="order_subtotal" placeholder="0.00 <?php _e('(ex. tax)', 'jigoshop'); ?>" value="<?php echo $data['order_subtotal']; ?>" class="first" /></dd>

		<dt><?php _e('Shipping &amp; Handling:', 'jigoshop'); ?></dt>
		<dd><input type="text" id="order_shipping" name="order_shipping" placeholder="0.00 <?php _e('(ex. tax)', 'jigoshop'); ?>" value="<?php echo $data['order_shipping']; ?>" class="first" /> <input type="text" name="shipping_method" id="shipping_method" value="<?php echo $data['shipping_method']; ?>" class="last" placeholder="<?php _e('Shipping method...', 'jigoshop'); ?>" /></dd>

		<dt><?php _e('Order shipping tax:', 'jigoshop'); ?></dt>
		<dd><input type="text" id="order_shipping_tax" name="order_shipping_tax" placeholder="0.00" value="<?php echo $data['order_shipping_tax']; ?>" class="first" /></dd>

		<dt><?php _e('Tax:', 'jigoshop'); ?></dt>
		<dd><input type="text" id="order_tax" name="order_tax" placeholder="0.00" value="<?php echo $data['order_tax']; ?>" class="first" /></dd>

		<?php
			$coupons = array();
			$applied_coupons = $data['order_discount_coupons'];
			foreach ( $applied_coupons as $coupon ) {
				$coupons[] = $coupon['code'];
			}
		?>
		<dt><?php _e('Discount: ', 'jigoshop'); ?><span class="applied-coupons-values"><?php echo implode( ',', $coupons ); ?></span></dt>
		<dd><input type="text" id="order_discount" name="order_discount" placeholder="0.00" value="<?php echo $data['order_discount']; ?>" /></dd>

		<dt><?php _e('Total:', 'jigoshop'); ?></dt>
		<dd><input type="text" id="order_total" name="order_total" placeholder="0.00" value="<?php echo $data['order_total']; ?>" class="first" /> <input type="text" name="payment_method" id="payment_method" value="<?php echo $data['payment_method']; ?>" class="last" placeholder="<?php _e('Payment method...', 'jigoshop'); ?>" /></dd>

	</dl>
	<div class="clear"></div>
	<?php
}