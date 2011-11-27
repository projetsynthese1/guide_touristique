<?php
/*
 Plugin Name: WP E-Commerce Pick-up Shipping Module
 Plugin URI: http://www.whereyoursolutionis.com/?page_id=638
 Description: Adds local pick-up uption to wp-commerce
 Version: 1.0
 Author: Innovative Solutions
 Author URI: http://www.whereyoursolutionis.com
*/

class local_pickup {

	var $internal_name;
	var $name;
	var $is_external;

	function local_pickup () {

		// An internal reference to the method - must be unique!
		$this->internal_name = "local_pickup";
		
		// $this->name is how the method will appear to end users
		$this->name = "Local Pick-up";

		// Set to FALSE - doesn't really do anything :)
		$this->is_external = FALSE;

		return true;
	}
	
	/* You must always supply this */
	function getName() {
		return $this->name;
	}
	
	/* You must always supply this */
	function getInternalName() {
		return $this->internal_name;
	}
	
	
	

	function getForm() {

		$shipping = get_option('local_pickup_options');
		
		$output .= '<tr>';
		$output .= '	<td>';
		$output .= '		Pick-up Charge:<br/>';
		$output .= '		<input type="text" name="shipping[charge]" value="'.htmlentities($shipping['charge']).'"><br/>';
		$output .= '	</td>';
		$output .= '</tr>';

		return $output;
	}
	


	function submit_form() {

		if($_POST['shipping'] != null) {

			$shipping = (array)get_option('local_pickup_options');
			$submitted_shipping = (array)$_POST['shipping'];

			update_option('local_pickup_options',array_merge($shipping, $submitted_shipping));

		}

		return true;

	}


	function get_item_shipping(&$cart_item) {

		global $wpdb;

		// If we're calculating a price based on a product, and that the store has shipping enabled

		$product_id = $cart_item->product_id;
		$quantity = $cart_item->quantity;
		$weight = $cart_item->weight;
		$unit_price = $cart_item->unit_price;

    		if (is_numeric($product_id) && (get_option('do_not_use_shipping') != 1)) {

			$country_code = $_SESSION['wpsc_delivery_country'];

			// Get product information
      			$product_list = $wpdb->get_row("SELECT *
			                                  FROM `".WPSC_TABLE_PRODUCT_LIST."`
				                         WHERE `id`='{$product_id}'
			                                 LIMIT 1",ARRAY_A);

       			// If the item has shipping enabled
      			if($product_list['no_shipping'] == 0) {

        			if($country_code == get_option('base_country')) {

					// Pick up the price from "Local Shipping Fee" on the product form
          				$additional_shipping = $product_list['pnp'];

				} else {

					// Pick up the price from "International Shipping Fee" on the product form
          				$additional_shipping = $product_list['international_pnp'];

				}          

        			$shipping = $quantity * $additional_shipping;

			} else {

        			//if the item does not have shipping
        			$shipping = 0;

			}

		} else {

      			//if the item is invalid or store is set not to use shipping
			$shipping = 0;

		}

    		return $shipping;	
	}
	


	

	function getQuote() {

		global $wpdb, $wpsc_cart;

		// This code is let here to show how you can access delivery address info
		// We don't use it for this skeleton shipping method

		if (isset($_POST['country'])) {

			$country = $_POST['country'];
			$_SESSION['wpsc_delivery_country'] = $country;

		} else {

			$country = $_SESSION['wpsc_delivery_country'];

		}
		
		// Retrieve the options set by submit_form() above
		$local_pickup_rates = get_option('local_pickup_options');
			  
		// Return an array of options for the user to choose
		// The first option is the default
		return array ("Local Pick-up" => (float) $local_pickup_rates['charge'],
		              "Local Pick-up" => (float) 0);

	}
	
	
} 

function local_pickup_add($wpsc_shipping_modules) {

	global $local_pickup;
	$local_pickup = new local_pickup();

	$wpsc_shipping_modules[$local_pickup->getInternalName()] = $local_pickup;

	return $wpsc_shipping_modules;
}
	
add_filter('wpsc_shipping_modules', 'local_pickup_add');
?>
