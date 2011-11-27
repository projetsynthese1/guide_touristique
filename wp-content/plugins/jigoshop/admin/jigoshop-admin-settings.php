<?php
/**
 * Functions for the settings page in admin.
 *
 * The settings page contains options for the Jigoshop plugin - this file contains functions to display
 * and save the list of options.
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
 * Update options
 *
 * Updates the options on the jigoshop settings page.
 *
 * @since 		1.0
 * @usedby 		jigoshop_settings()
 *
 * @param 		array $options List of options to go through and save
 */
function jigoshop_update_options($options) {
	
    if(isset($_POST['submitted']) && $_POST['submitted'] == 'yes') {
		
		$update_image_meta = false;

        foreach ($options as $value) {
        	if (isset($value['id']) && $value['id']=='jigoshop_tax_rates') :

        		$tax_classes = array();
        		$tax_countries = array();
        		$tax_rate = array();
        		$tax_rates = array();
        		$tax_shipping = array();

				if (isset($_POST['tax_class'])) $tax_classes = $_POST['tax_class'];
				if (isset($_POST['tax_country'])) $tax_countries = $_POST['tax_country'];
				if (isset($_POST['tax_rate'])) $tax_rate = $_POST['tax_rate'];
				if (isset($_POST['tax_shipping'])) $tax_shipping = $_POST['tax_shipping'];

				for ($i=0; $i<sizeof($tax_classes); $i++) :

					if (isset($tax_classes[$i]) && isset($tax_countries[$i]) && isset($tax_rate[$i]) && $tax_rate[$i] && is_numeric($tax_rate[$i])) :

						$country = jigowatt_clean($tax_countries[$i]);
						$state = '*';
						$rate = number_format(jigowatt_clean($tax_rate[$i]), 4);
						$class = jigowatt_clean($tax_classes[$i]);

						if (isset($tax_shipping[$i]) && $tax_shipping[$i]) $shipping = 'yes'; else $shipping = 'no';

						// Get state from country input if defined
						if (strstr($country, ':')) :
							$cr = explode(':', $country);
							$country = current($cr);
							$state = end($cr);
						endif;

						$tax_rates[] = array(
							'country' => $country,
							'state' => $state,
							'rate' => $rate,
							'shipping' => $shipping,
							'class' => $class
						);

					endif;

				endfor;

				update_option($value['id'], $tax_rates);

			elseif (isset($value['id']) && $value['id']=='jigoshop_coupons') :

				$coupon_code = array();
        		$coupon_type = array();
        		$coupon_amount = array();
        		$product_ids = array();
        		$date_from = array();
        		$date_to = array();
        		$coupons = array();
				$individual = array();

				if (isset($_POST['coupon_code'])) $coupon_code = $_POST['coupon_code'];
				if (isset($_POST['coupon_type'])) $coupon_type = $_POST['coupon_type'];
				if (isset($_POST['coupon_amount'])) $coupon_amount = $_POST['coupon_amount'];
				if (isset($_POST['product_ids'])) $product_ids = $_POST['product_ids'];
				if (isset($_POST['coupon_date_from'])) $date_from = $_POST['coupon_date_from'];
				if (isset($_POST['coupon_date_to'])) $date_to = $_POST['coupon_date_to'];
				if (isset($_POST['individual'])) $individual = $_POST['individual'];

				for ($i=0; $i<sizeof($coupon_code); $i++) :

					if ( isset($coupon_code[$i]) && isset($coupon_type[$i]) && isset($coupon_amount[$i]) ) :

						$code = jigowatt_clean($coupon_code[$i]);
						$type = jigowatt_clean($coupon_type[$i]);
						$amount = jigowatt_clean($coupon_amount[$i]);

						if (isset($product_ids[$i]) && $product_ids[$i]) $products = array_map('trim', explode(',', $product_ids[$i])); else $products = array();

 						if (isset($date_from[$i]) && $date_from[$i]) $from_date = strtotime($date_from[$i]); else $from_date = 0;
 						if (isset($date_to[$i]) && $date_to[$i]) $to_date = strtotime($date_to[$i]) + (60*60*24-1); else $to_date = 0;

						if (isset($individual[$i]) && $individual[$i]) $individual_use = 'yes'; else $individual_use = 'no';

						if ($code && $type && $amount) :
							$coupons[$code] = array(
								'code' => $code,
								'amount' => $amount,
								'type' => $type,
								'products' => $products,
								'date_from' => $from_date,
								'date_to' => $to_date,
								'individual_use' => $individual_use
							);
						endif;
					
					endif;

				endfor;

				update_option($value['id'], $coupons);

			elseif (isset($value['type']) && $value['type']=='multi_select_countries') :

				// Get countries array
				if (isset($_POST[$value['id']])) $selected_countries = $_POST[$value['id']]; else $selected_countries = array();
				update_option($value['id'], $selected_countries);

			/* price separators get a special treatment as they should allow a spaces (don't trim) */
			elseif ( isset($value['id']) && ( $value['id'] == 'jigoshop_price_thousand_sep' || $value['id'] == 'jigoshop_price_decimal_sep' ) ):

				if( isset( $_POST[ $value['id'] ] )  ) {
					update_option($value['id'], $_POST[$value['id']] );
				} else {
	                @delete_option($value['id']);
	            }

			/* image values can not be empty, if empty fallback to default values */
			elseif ( isset($value['id']) && preg_match('/^jigoshop_shop_(tiny|thumbnail|small|large)_(h|w)$/', $value['id']) ):

				$old_val = get_option($value['id'], false);
				$size = intval( jigowatt_clean($_POST[$value['id']]) );

				if (!$size && $old_val !== false || $size == $old_val){
					continue;
				} else if (!$size) {
					$var_name = str_replace('jigoshop_', '', $value['id']);
					$size = jigoshop::get_var($var_name);
					$update_image_meta = true;
				} else {
					$update_image_meta = true;
				}

				update_option($value['id'], $size);

        	else:

        		if(isset($value['id']) && isset($_POST[$value['id']])) {
	            	update_option($value['id'], jigowatt_clean($_POST[$value['id']]));
	            } else {
	                @delete_option($value['id']);
	            }

	        endif;

        }

		if ($update_image_meta){

			// reset the image sizes to generate the new metadata
			jigoshop_set_image_sizes();

			$posts = get_posts('post_type=product&post_status=publish&posts_per_page=-1');

			foreach ( (array) $posts as $post) {
				$images =& get_children("post_parent={$post->ID}&post_type=attachment&post_mime_type=image");

				foreach ( (array) $images as $image) {
					$fullsizepath = get_attached_file($image->ID);

					if (false !== $fullsizepath || file_exists($fullsizepath)) {
						$metadata = wp_generate_attachment_metadata($image->ID, $fullsizepath);

						if (!is_wp_error($metadata) && !empty($metadata)) {
							wp_update_attachment_metadata($image->ID, $metadata);
						}
					}
				}
			}
		}

        do_action('jigoshop_update_options');

        echo '<div id="message" class="updated fade"><p><strong>'.__('Your settings have been saved.','jigoshop').'</strong></p></div>';
    }
}

/**
 * Admin fields
 *
 * Loops though the jigoshop options array and outputs each field.
 *
 * @since 		1.0
 * @usedby 		jigoshop_settings()
 *
 * @param 		array $options List of options to go through and save
 */
function jigoshop_admin_fields($options) {
	?>
	<div id="tabs-wrap">
		<p class="submit"><input name="save" type="submit" value="<?php _e('Save changes','jigoshop') ?>" /></p>
		<?php
		    $counter = 1;
		    echo '<ul class="tabs">';
		    foreach ($options as $value) {
				if ( 'tab' == $value['type'] ) :
		            echo '<li><a href="#'.$value['type'].$counter.'">'.$value['tabname'].'</a></li>'. "\n";
		            $counter = $counter + 1;
				endif;
		    }
		    echo '</ul>';
		    $counter = 1;
		    foreach ($options as $value) :
		        switch($value['type']) :
					case 'string':
						?>
						<tr>
							<td class="titledesc"><?php echo $value['name']; ?></td>
							<td class="forminp"><?php echo $value['desc']; ?></td>
						</tr>
						<?php
					break;
		            case 'tab':
		                echo '<div id="'.$value['type'].$counter.'" class="panel">';
		                echo '<table class="widefat fixed">'. "\n\n";
		            break;
		            case 'title':
		            	?><thead><tr><th scope="col" width="200px"><?php echo $value['name'] ?></th><th scope="col" class="desc"><?php if (isset($value['desc'])) echo $value['desc'] ?>&nbsp;</th></tr></thead><?php
		            break;
		            case 'text':
		            	?><tr>
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>

		                    <td class="forminp"><input name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" type="<?php echo $value['type'] ?>" style="<?php echo $value['css'] ?>"
								value="<?php if ( get_option( $value['id']) !== false && get_option( $value['id']) !== null ) echo get_option( $value['id'] ); else echo $value['std'] ?>" /><br /><small><?php echo $value['desc'] ?></small></td>
		                </tr><?php
		            break;
		            case 'select':
		            	?><tr>
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>
		                    <td class="forminp"><select name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" style="<?php echo $value['css'] ?>">
		                        <?php
		                        foreach ($value['options'] as $key => $val) {
		                        ?>
		                            <option value="<?php echo $key ?>" <?php if (get_option($value['id']) == $key) { ?> selected="selected" <?php } ?>><?php echo ucfirst($val) ?></option>
		                        <?php
		                        }
		                        ?>
		                       </select><br /><small><?php echo $value['desc'] ?></small>
		                    </td>
		                </tr><?php
		            break;
		            case 'textarea':
		            	?><tr>
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>
		                    <td class="forminp">
		                        <textarea <?php if ( isset($value['args']) ) echo $value['args'] . ' '; ?>name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" style="<?php echo $value['css'] ?>"><?php if (get_option($value['id'])) echo stripslashes(get_option($value['id'])); else echo $value['std']; ?></textarea>
		                        <br /><small><?php echo $value['desc'] ?></small>
		                    </td>
		                </tr><?php
		            break;
		            case 'tabend':
						echo '</table></div>';
		                $counter = $counter + 1;
		            break;
		            case 'single_select_page' :
		            	$page_setting = (int) get_option($value['id']);

		            	$args = array( 'name'	=> $value['id'],
		            				   'id'		=> $value['id']. '" style="width: 200px;',
		            				   'sort_column' 	=> 'menu_order',
		            				   'sort_order'		=> 'ASC',
		            				   'selected'		=> $page_setting);

		            	if( isset($value['args']) ) $args = wp_parse_args($value['args'], $args);

		            	?><tr class="single_select_page">
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>
		                    <td class="forminp">
					        	<?php wp_dropdown_pages($args); ?>
					        	<br /><small><?php echo $value['desc'] ?></small>
					        </td>
		               	</tr><?php
		            break;
		            case 'single_select_country' :
		            	$countries = jigoshop_countries::$countries;
		            	$country_setting = (string) get_option($value['id']);
		            	if (strstr($country_setting, ':')) :
		            		$country = current(explode(':', $country_setting));
		            		$state = end(explode(':', $country_setting));
		            	else :
		            		$country = $country_setting;
		            		$state = '*';
		            	endif;
		            	?><tr class="multi_select_countries">
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>
		                    <td class="forminp"><select name="<?php echo $value['id'] ?>" title="Country" style="width: 150px;">
					        	<?php echo jigoshop_countries::country_dropdown_options($country, $state); ?>
					        </select>
		               		</td>
		               	</tr><?php
		            break;
		            case 'multi_select_countries' :
		            	$countries = jigoshop_countries::$countries;
		            	asort($countries);
		            	$selections = (array) get_option($value['id']);
		            	?><tr class="multi_select_countries">
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>
		                    <td class="forminp">
		                    	<div class="multi_select_countries"><ul><?php
			            			if ($countries) foreach ($countries as $key=>$val) :

			            				echo '<li><label><input type="checkbox" name="'. $value['id'] .'[]" value="'. $key .'" ';
			            				if (in_array($key, $selections)) echo 'checked="checked"';
			            				echo ' />'. $val .'</label></li>';

		                    		endforeach;
		               			?></ul></div>
		               		</td>
		               	</tr><?php
		            break;
		            case 'coupons' :
		            	$coupons = new jigoshop_coupons();
		            	$coupon_codes = $coupons->get_coupons();
		            	?><tr>
		                    <td class="titledesc"><?php echo $value['name'] ?>:</td>
		                    <td class="forminp" id="coupon_codes">
		                    	<table class="coupon_rows" cellspacing="0">
			                    	<thead>
			                    		<tr>
			                    			<th><?php _e('', 'jigoshop'); ?></th>
			                    			<th><?php _e('Code', 'jigoshop'); ?></th>
			                    			<th><?php _e('Type', 'jigoshop'); ?></th>
			                    			<th><?php _e('Amount', 'jigoshop'); ?></th>
			                    			<th><?php _e("ID's", 'jigoshop'); ?></th>
			                    			<th><?php _e('From', 'jigoshop'); ?></th>
			                    			<th><?php _e('To', 'jigoshop'); ?></th>
			                    			<th><?php _e('Alone', 'jigoshop'); ?></th>
			                    		</tr>
			                    	</thead>
			                    	<tbody>
			                    	<?php
			                    	$i = -1;
			                    	if ($coupon_codes && is_array($coupon_codes) && sizeof($coupon_codes)>0) foreach( $coupon_codes as $coupon ) : $i++;
			                    		echo '<tr class="coupon_row">';
			                    		echo '<td><a href="#" class="remove button" title="'.__('Delete this Coupon','jigoshop').'">&times;</a></td>';
			                    		echo '<td><input type="text" value="'.$coupon['code'].'" name="coupon_code['.$i.']" title="'.__('Coupon Code', 'jigoshop').'" placeholder="'.__('Coupon Code', 'jigoshop').'" class="text" /></td><td><select name="coupon_type['.$i.']" title="Coupon Type">';

			                    		$discount_types = array(
			                    			'fixed_cart' 	=> __('Cart Discount', 'jigoshop'),
			                    			'percent' 		=> __('Cart % Discount', 'jigoshop'),
			                    			'fixed_product'	=> __('Product Discount', 'jigoshop'),
			                    			'percent_product'	=> __('Product % Discount', 'jigoshop')
			                    		);

			                    		foreach ($discount_types as $type => $label) :
			                    			$selected = ($coupon['type']==$type) ? 'selected="selected"' : '';
			                    			echo '<option value="'.$type.'" '.$selected.'>'.$label.'</option>';
			                    		endforeach;
			                    		echo '</select></td>';
			                    		echo '<td><input type="text" value="'.$coupon['amount'].'" name="coupon_amount['.$i.']" title="'.__('Coupon Amount', 'jigoshop').'" placeholder="'.__('Amount', 'jigoshop').'" class="text" /></td>
			                    			<td><input type="text" value="'.implode(', ', $coupon['products']).'" name="product_ids['.$i.']" placeholder="'.__('1, 2, 3,', 'jigoshop').'" class="text" /></td>';
			                    		
			                    		$date_from = $coupon['date_from'];
										echo '<td><label for="coupon_date_from['.$i.']"></label><input type="text" class="text date-pick" name="coupon_date_from['.$i.']" id="coupon_date_from['.$i.']" value="';
										if ($date_from) echo date('Y-m-d', $date_from);
										echo '" placeholder="'. __('yyyy-mm-dd', 'jigoshop').'" /></td>';

			                    		$date_to = $coupon['date_to'];
										echo '<td><label for="coupon_date_to['.$i.']"></label><input type="text" class="text date-pick" name="coupon_date_to['.$i.']" id="coupon_date_to['.$i.']" value="';
										if ($date_to) echo date('Y-m-d', $date_to);
										echo '" placeholder="'. __('yyyy-mm-dd', 'jigoshop').'" /></td>';

			                    		echo '<td><input type="checkbox" name="individual['.$i.']" ';
					                    if (isset($coupon['individual_use']) && $coupon['individual_use']=='yes')
					                    	echo 'checked="checked"';
					                    echo ' /></td>';
					                    echo '</tr>';

										?>
										<script type="text/javascript">
										/* <![CDATA[ */
											jQuery(function() {
												// DATE PICKER FIELDS
												Date.firstDayOfWeek = 1;
												Date.format = 'yyyy-mm-dd';
												jQuery('.date-pick').datePicker();
												jQuery('#coupon_date_from[<?php echo $i; ?>]').bind(
													'dpClosed',
													function(e, selectedDates)
													{
														var d = selectedDates[0];
														if (d) {
															d = new Date(d);
															jQuery('#coupon_date_to[<?php echo $i; ?>]').dpSetStartDate(d.addDays(1).asString());
														}
													}
												);
												jQuery('#coupon_date_to[<?php echo $i; ?>]').bind(
													'dpClosed',
													function(e, selectedDates)
													{
														var d = selectedDates[0];
														if (d) {
															d = new Date(d);
															jQuery('#coupon_date_from[<?php echo $i; ?>]').dpSetEndDate(d.addDays(-1).asString());
														}
													}
												);
											});
											/* ]]> */
										</script>
										<?php

			                    	endforeach;
			                    	?>
			                    	</tbody>
		                        </table>
		                        <p><a href="#" class="add button"><?php _e('+ Add Coupon', 'jigoshop'); ?></a></p>
		                    </td>
		                </tr>
		                <script type="text/javascript">
						/* <![CDATA[ */
							jQuery(function() {
								jQuery('#coupon_codes a.add').live('click', function(){
									var size = jQuery('#coupon_codes table.coupon_rows tbody .coupon_row').size();
									// Make sure tbody exists
									var tbody_size = jQuery('#coupon_codes table.coupon_rows tbody').size();
									if (tbody_size==0) jQuery('#coupon_codes table.coupon_rows').append('<tbody></tbody>');

									// Add the row
									jQuery('<tr class="coupon_row">\
										<td><a href="#" class="remove button" title="<?php __('Delete this Coupon','jigoshop'); ?>">&times;</a></td>\
										<td><input type="text" value="" name="coupon_code[' + size + ']" title="<?php _e('Coupon Code', 'jigoshop'); ?>" placeholder="<?php _e('Coupon Code', 'jigoshop'); ?>" class="text" /></td>\
										<td><select name="coupon_type[' + size + ']" title="Coupon Type">\
			                    			<option value="fixed_cart"><?php _e('Cart Discount', 'jigoshop'); ?></option>\
			                    			<option value="percent"><?php _e('Cart % Discount', 'jigoshop'); ?></option>\
			                    			<option value="fixed_product"><?php _e('Product Discount', 'jigoshop');?></option>\
			                    			<option value="percent_product"><?php _e('Product % Discount', 'jigoshop');?></option>\
			                    		</select></td>\
			                    		<td><input type="text" value="" name="coupon_amount[' + size + ']" title="<?php _e('Coupon Amount', 'jigoshop'); ?>" placeholder="<?php _e('Amount', 'jigoshop'); ?>" class="text" /></td>\
			                    		<td><input type="text" value="" name="product_ids[' + size + ']" \
			                    			placeholder="<?php _e('1, 2, 3,', 'jigoshop'); ?>" class="text" /></td>\
			                    		<td><label for="coupon_date_from[' + size + ']"></label>\
			                    			<input type="text" class="text date-pick" name="coupon_date_from[' + size + ']" \
			                    			id="coupon_date_from[' + size + ']" value="" \
			                    			placeholder="<?php _e('yyyy-mm-dd', 'jigoshop'); ?>" /></td>\
			                    		<td><label for="coupon_date_to[' + size + ']"></label>\
			                    			<input type="text" class="text date-pick" name="coupon_date_to[' + size + ']" \
			                    			id="coupon_date_to[' + size + ']" value="" \
			                    			placeholder="<?php _e('yyyy-mm-dd', 'jigoshop'); ?>" /></td>\
			                    		<td><input type="checkbox" name="individual[' + size + ']" /></td>').appendTo('#coupon_codes table.coupon_rows tbody');

									jQuery(function() {
										// DATE PICKER FIELDS
										Date.firstDayOfWeek = 1;
										Date.format = 'yyyy-mm-dd';
										jQuery('.date-pick').datePicker();
										jQuery('#coupon_date_from[' + size + ']').bind(
											'dpClosed',
											function(e, selectedDates)
											{
												var d = selectedDates[0];
												if (d) {
													d = new Date(d);
													jQuery('#coupon_date_to[' + size + ']').dpSetStartDate(d.addDays(1).asString());
												}
											}
										);
										jQuery('#coupon_date_to[' + size + ']').bind(
											'dpClosed',
											function(e, selectedDates)
											{
												var d = selectedDates[0];
												if (d) {
													d = new Date(d);
													jQuery('#coupon_date_from[' + size + ']').dpSetEndDate(d.addDays(-1).asString());
												}
											}
										);
									});

									return false;
								});
								jQuery('#coupon_codes a.remove').live('click', function(){
									var answer = confirm("<?php _e('Delete this coupon?', 'jigoshop'); ?>")
									if (answer) {
										jQuery('input', jQuery(this).parent().parent()).val('');
										jQuery(this).parent().parent().hide();
									}
									return false;
								});
							});
						/* ]]> */
						</script>
		                <?php
		            break;
		            case 'tax_rates' :
		            	$_tax = new jigoshop_tax();
		            	$tax_classes = $_tax->get_tax_classes();
		            	$tax_rates = get_option('jigoshop_tax_rates');
		            	?><tr>
		                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" class="tips" tabindex="99"></a><?php } ?><?php echo $value['name'] ?>:</td>
		                    <td class="forminp" id="tax_rates">
		                    	<div class="taxrows">
			                    	<?php
			                    	$i = -1;
			                    	if ($tax_rates && is_array($tax_rates) && sizeof($tax_rates)>0) foreach( $tax_rates as $rate ) : $i++;
			                    		echo '<p class="taxrow"><select name="tax_class['.$i.']" title="Tax Class"><option value="">'.__('Standard Rate', 'jigoshop').'</option>';

			                    		if ($tax_classes) foreach ($tax_classes as $class) :
					                        echo '<option value="'.sanitize_title($class).'"';

					                        if ($rate['class']==sanitize_title($class)) echo 'selected="selected"';

					                        echo '>'.$class.'</option>';
					                    endforeach;

					                    echo '</select><select name="tax_country['.$i.']" title="Country">';

					                    jigoshop_countries::country_dropdown_options($rate['country'], $rate['state']);

					                    echo '</select><input type="text" class="text" value="'.$rate['rate'].'" name="tax_rate['.$i.']" title="'.__('Rate', 'jigoshop').'" placeholder="'.__('Rate', 'jigoshop').'" maxlength="8" />% <label><input type="checkbox" name="tax_shipping['.$i.']" ';

					                    if (isset($rate['shipping']) && $rate['shipping']=='yes') echo 'checked="checked"';

					                    echo ' /> '.__('Apply to shipping', 'jigoshop').'</label><a href="#" class="remove button">&times;</a></p>';
			                    	endforeach;
			                    	?>
		                        </div>
		                        <p><a href="#" class="add button"><?php _e('+ Add Tax Rule', 'jigoshop'); ?></a></p>
		                    </td>
		                </tr>
		                <script type="text/javascript">
						/* <![CDATA[ */
							jQuery(function() {
								jQuery('#tax_rates a.add').live('click', function(){
									var size = jQuery('.taxrows .taxrow').size();

									// Add the row
									jQuery('<p class="taxrow"> \
				                        <select name="tax_class[' + size + ']" title="Tax Class"> \
				                        	<option value=""><?php _e('Standard Rate', 'jigoshop'); ?></option><?php
				                        		$tax_classes = $_tax->get_tax_classes();
				                        		if ($tax_classes) foreach ($tax_classes as $class) :
				                        			echo '<option value="'.sanitize_title($class).'">'.$class.'</option>';
				                        		endforeach;
				                        	?></select><select name="tax_country[' + size + ']" title="Country"><?php
				                        		jigoshop_countries::country_dropdown_options('','',true);
				                        	?></select><input type="text" class="text" name="tax_rate[' + size + ']" title="<?php _e('Rate', 'jigoshop'); ?>" placeholder="<?php _e('Rate', 'jigoshop'); ?>" maxlength="8" />%\
				                        	<label><input type="checkbox" name="tax_shipping[' + size + ']" /> <?php _e('Apply to shipping', 'jigoshop'); ?></label>\
				                        	<a href="#" class="remove button">&times;</a>\
			                        </p>').appendTo('#tax_rates div.taxrows');
									return false;
								});
								jQuery('#tax_rates a.remove').live('click', function(){
									var answer = confirm("<?php _e('Delete this rule?', 'jigoshop'); ?>");
									if (answer) {
										jQuery('input', jQuery(this).parent()).val('');
										jQuery(this).parent().hide();
									}
									return false;
								});
							});
						/* ]]> */
						</script>
		                <?php
		            break;
		            case "shipping_options" :

		            	foreach (jigoshop_shipping::get_all_methods() as $method) :

		            		$method->admin_options();

		            	endforeach;

		            break;
		            case "gateway_options" :

		            	foreach (jigoshop_payment_gateways::payment_gateways() as $gateway) :

		            		$gateway->admin_options();

		            	endforeach;

		            break;
		        endswitch;
		    endforeach;
		?>
		<p class="submit"><input name="save" type="submit" value="<?php _e('Save changes','jigoshop') ?>" /></p>
	</div>
	<script type="text/javascript">
	jQuery(function($) {
	    // Tabs
		jQuery('ul.tabs').show();
		jQuery('ul.tabs li:first').addClass('active');
		jQuery('div.panel:not(div.panel:first)').hide();
		jQuery('ul.tabs a').click(function(){
			jQuery('ul.tabs li').removeClass('active');
			jQuery(this).parent().addClass('active');
			jQuery('div.panel').hide();
			jQuery( jQuery(this).attr('href') ).show();

			jQuery.cookie('jigoshop_settings_tab_index', jQuery(this).parent().index('ul.tabs li'))

			return false;
		});

		<?php if (isset($_COOKIE['jigoshop_settings_tab_index']) && $_COOKIE['jigoshop_settings_tab_index'] > 0) : ?>

			jQuery('ul.tabs li:eq(<?php echo $_COOKIE['jigoshop_settings_tab_index']; ?>) a').click();

		<?php endif; ?>

		// Countries
		jQuery('select#jigoshop_allowed_countries').change(function(){
			if (jQuery(this).val()=="specific") {
				jQuery(this).parent().parent().next('tr.multi_select_countries').show();
			} else {
				jQuery(this).parent().parent().next('tr.multi_select_countries').hide();
			}
		}).change();

		// permalink double save hack
		$.get('<?php echo admin_url('options-permalink.php') ?>');

	});
	</script>
	<?php
	flush_rewrite_rules();
}


/**
 * Settings page
 *
 * Handles the display of the settings page in admin.
 *
 * @since 		1.0
 * @usedby 		jigoshop_admin_menu2()
 */
function jigoshop_settings() {
    global $options_settings;
    jigoshop_update_options($options_settings);
	?>
	<script type="text/javascript" src="<?php echo jigoshop::plugin_url(); ?>/assets/js/easyTooltip.js"></script>
	<div class="wrap jigoshop">
        <div class="icon32 icon32-jigoshop-settings" id="icon-jigoshop"><br/></div>
		<h2><?php _e('General Settings','jigoshop'); ?></h2>
		<form method="post" id="mainform" action="">
	        <?php jigoshop_admin_fields($options_settings); ?>
	        <input name="submitted" type="hidden" value="yes" />
		</form>
	</div>
	<?php
}
