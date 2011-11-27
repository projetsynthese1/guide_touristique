<?php
/**
 * Functions used for the attributes section in WordPress Admin
 * 
 * The attributes section lets users add custom attributes to assign to products - they can also be used in the layered nav widgets.
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
 * Shows the created attributes and lets you add new ones.
 * The added attributes are stored in the database and can be used for layered navigation.
 *
 * @since 		1.0
 * @usedby 		jigoshop_admin_menu2()
 */
function jigoshop_attributes() {
	
	global $wpdb;
	
	if (isset($_POST['add_new_attribute']) && $_POST['add_new_attribute']) :
		
		$attribute_name = (string) $_POST['attribute_name'];
		$attribute_type = (string) $_POST['attribute_type'];
		if (isset($_POST['show-on-product-page']) && $_POST['show-on-product-page']) $product_page = 1; else $product_page = 0;
		
		if ($attribute_name && $attribute_type && !taxonomy_exists('pa_'.strtolower(sanitize_title($attribute_name)))) :
		
			$wpdb->insert( $wpdb->prefix . "jigoshop_attribute_taxonomies", array( 'attribute_name' => $attribute_name, 'attribute_type' => $attribute_type ), array( '%s', '%s' ) );
			
			update_option('jigowatt_update_rewrite_rules', '1');
			
			wp_safe_redirect( get_admin_url() . 'admin.php?page=attributes' );
			exit;
			
		else :
			print_r('<div id="message" class="error"><p>'.__('That attribute already exists, no additions were made.', 'jigoshop' ).'</p></div>');
		endif;
		
	elseif (isset($_POST['save_attribute']) && $_POST['save_attribute'] && isset($_GET['edit'])) :
		
		$edit = absint($_GET['edit']);
		
		if ($edit>0) :
		
			$attribute_type = $_POST['attribute_type'];
		
			$wpdb->update( $wpdb->prefix . "jigoshop_attribute_taxonomies", array( 'attribute_type' => $attribute_type ), array( 'attribute_id' => $_GET['edit'] ), array( '%s' ) );
		
		endif;
		
		wp_safe_redirect( get_admin_url() . 'admin.php?page=attributes' );
		exit;
			
	elseif (isset($_GET['delete'])) :
	
		$delete = absint($_GET['delete']);
		
		if ($delete>0) :
		
			$att_name = $wpdb->get_var("SELECT attribute_name FROM " . $wpdb->prefix . "jigoshop_attribute_taxonomies WHERE attribute_id = '$delete'");
			
			if ($att_name && $wpdb->query("DELETE FROM " . $wpdb->prefix . "jigoshop_attribute_taxonomies WHERE attribute_id = '$delete'")) :
				
				$taxonomy = 'pa_'.strtolower(sanitize_title($att_name));
				
				// Old taxonomy prefix left in for backwards compatibility
				if (taxonomy_exists($taxonomy)) :
				
					$terms = get_terms($taxonomy, 'orderby=name&hide_empty=0'); 
					foreach ($terms as $term) {
						wp_delete_term( $term->term_id, $taxonomy );
					}
				
				endif;
				
				wp_safe_redirect( get_admin_url() . 'admin.php?page=attributes' );
				exit;
										
			endif;
			
		endif;
		
	endif;
	
	if (isset($_GET['edit']) && $_GET['edit'] > 0) :
		jigoshop_edit_attribute();
	else :	
		jigoshop_add_attribute();
	endif;
	
}

/**
 * Edit Attribute admin panel
 * 
 * Shows the interface for changing an attributes type between select, multiselect and text
 *
 * @since 		1.0
 * @usedby 		jigoshop_attributes()
 */
function jigoshop_edit_attribute() {
	
	global $wpdb;
	
	$edit = absint($_GET['edit']);
		
	$att_type = $wpdb->get_var("SELECT attribute_type FROM " . $wpdb->prefix . "jigoshop_attribute_taxonomies WHERE attribute_id = '$edit'");		
	?>
	<div class="wrap jigoshop">
		<div class="icon32 icon32-attributes" id="icon-jigoshop"><br/></div>
	    <h2><?php _e('Attributes','jigoshop') ?></h2>
	    <br class="clear" />
	    <div id="col-container">
	    	<div id="col-left">
	    		<div class="col-wrap">
	    			<div class="form-wrap">
	    				<h3><?php _e('Edit Attribute','jigoshop') ?></h3>
	    				<p><?php _e('Attribute taxonomy names cannot be changed; you may only change an attributes type.','jigoshop') ?></p>
	    				<form action="admin.php?page=attributes&amp;edit=<?php echo $edit; ?>" method="post">
							
							<div class="form-field">
								<label for="attribute_type"><?php _e('Attribute type', 'jigoshop'); ?></label>
								<select name="attribute_type" id="attribute_type" style="width: 100%;">
									<option value="select" <?php if ($att_type=='select') echo 'selected="selected"'; ?>><?php _e('Select','jigoshop') ?></option>
									<option value="multiselect" <?php if ($att_type=='multiselect') echo 'selected="selected"'; ?>><?php _e('Multiselect','jigoshop') ?></option>
									<option value="text" <?php if ($att_type=='text') echo 'selected="selected"'; ?>><?php _e('Text','jigoshop') ?></option>										
								</select>
							</div>
							
							<p class="submit"><input type="submit" name="save_attribute" id="submit" class="button" value="<?php _e('Save Attribute', 'jigoshop'); ?>"></p>
	    				</form>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>
	<?php
	
}

/**
 * Add Attribute admin panel
 * 
 * Shows the interface for adding new attributes
 *
 * @since 		1.0
 * @usedby 		jigoshop_attributes()
 */
function jigoshop_add_attribute() {
	?>
	<div class="wrap jigoshop">
		<div class="icon32 icon32-attributes" id="icon-jigoshop"><br/></div>
	    <h2><?php _e('Attributes','jigoshop') ?></h2>
	    <br class="clear" />
	    <div id="col-container">
	    	<div id="col-right">
	    		<div class="col-wrap">
		    		<table class="widefat fixed" style="width:100%">
				        <thead>
				            <tr>
				                <th scope="col"><?php _e('Name','jigoshop') ?></th>
				                <th scope="col"><?php _e('Type','jigoshop') ?></th>
				                <th scope="col"><?php _e('Terms','jigoshop') ?></th>
				            </tr>
				        </thead>
				        <tbody>
				        	<?php
				        		$attribute_taxonomies = jigoshop_product::getAttributeTaxonomies();
				        		if ( $attribute_taxonomies ) :
				        			foreach ($attribute_taxonomies as $tax) :
				        				?><tr>

				        					<td><a href="edit-tags.php?taxonomy=pa_<?php echo strtolower(sanitize_title($tax->attribute_name)); ?>&amp;post_type=product"><?php echo $tax->attribute_name; ?></a>
				        					
				        					<div class="row-actions"><span class="edit"><a href="<?php echo add_query_arg('edit', $tax->attribute_id, 'admin.php?page=attributes') ?>"><?php _e('Edit', 'jigoshop'); ?></a> | </span><span class="delete"><a class="delete" href="<?php echo add_query_arg('delete', $tax->attribute_id, 'admin.php?page=attributes') ?>"><?php _e('Delete', 'jigoshop'); ?></a></span></div>				        					
				        					</td>
				        					<td><?php echo ucwords($tax->attribute_type); ?></td>
				        					<td><?php 
				        						if (taxonomy_exists('pa_'.strtolower(sanitize_title($tax->attribute_name)))) :
					        						$terms_array = array();
					        						$terms = get_terms( 'pa_'.strtolower(sanitize_title($tax->attribute_name)), 'orderby=name&hide_empty=0' );
					        						if ($terms) :
						        						foreach ($terms as $term) :
															$terms_array[] = $term->name;
														endforeach;
														echo implode(', ', $terms_array);
													else :
														echo '<span class="na">&ndash;</span>';
													endif;
												else :
													echo '<span class="na">&ndash;</span>';
												endif;
				        					?></td>
				        				</tr><?php
				        			endforeach;
				        		else :
				        			?><tr><td colspan="5"><?php _e('No attributes currently exist.','jigoshop') ?></td></tr><?php
				        		endif;
				        	?>
				        </tbody>
				    </table>
	    		</div>
	    	</div>
	    	<div id="col-left">
	    		<div class="col-wrap">
	    			<div class="form-wrap">
	    				<h3><?php _e('Add New Attribute','jigoshop') ?></h3>
	    				<p><?php _e('Attributes let you define extra product data, such as size or colour. You can use these attributes in the shop sidebar using the "layered nav" widgets. Please note: you cannot rename an attribute later on.','jigoshop') ?></p>
	    				<form action="admin.php?page=attributes" method="post">
							<div style="width:47%; float:left; margin:0 1% 0 0;">
								<div class="form-field">
									<label for="attribute_name"><?php _e('Attribute Name', 'jigoshop'); ?></label>
									<input name="attribute_name" id="attribute_name" type="text" value="" />
								</div>
							</div>
							<div style="width:47%; float:left; margin:0 1% 0 0;">
								<div class="form-field">
									<label for="attribute_type"><?php _e('Attribute type', 'jigoshop'); ?></label>
									<select name="attribute_type" id="attribute_type" style="width: 100%;">
										<option value="select"><?php _e('Select','jigoshop') ?></option>
										<option value="multiselect"><?php _e('Multiselect','jigoshop') ?></option>
										<option value="text"><?php _e('Text','jigoshop') ?></option>										
									</select>
								</div>
							</div>
							<div class="clear"></div>
							
							<p class="submit"><input type="submit" name="add_new_attribute" id="submit" class="button" value="<?php _e('Add Attribute', 'jigoshop'); ?>"></p>
	    				</form>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	    <script type="text/javascript">
		/* <![CDATA[ */
		
			jQuery('a.delete').click(function(){
	    		var answer = confirm ("<?php _e('Are you sure you want to delete this?', 'jigoshop'); ?>");
				if (answer) return true;
				return false;
	    	});
		    	
		/* ]]> */
		</script>
	</div>
	<?php
}