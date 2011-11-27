<?php
define('TRAVEL_DIR', get_template_directory(). '/travel');
/*-----------------------------------------------------------------------------------*/
/* Add Author Twitter Widget
/*-----------------------------------------------------------------------------------*/
require_once (TRAVEL_DIR . '/author_twitter.php');
register_widget('Theme_Widget_Author_Twitter');
require_once (TRAVEL_DIR . '/all_author_twitter.php');
register_widget('Theme_Widget_All_Author_Twitter');
require_once (TRAVEL_DIR . '/authors.php');
register_widget('Theme_Widget_Authors');
require_once (TRAVEL_DIR . '/geomap.php');
register_widget('Theme_Widget_Geo_Map');
/*-----------------------------------------------------------------------------------*/
/* Extend user profile fields
/*-----------------------------------------------------------------------------------*/
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
function my_show_extra_profile_fields( $user ) { ?>
	<h3>Extra profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="twitter">Twitter</label></th>
			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Twitter username.</span>
			</td>
		</tr>
	</table>
<?php }
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
}
/*-----------------------------------------------------------------------------------*/
/* Geo location category filter
/*-----------------------------------------------------------------------------------*/
function geo_mashup_locations_exclude_the_categorys($json_object,$object) {
	$exclude = theme_get_option('blog','exclude_categorys');
	foreach($json_object['categories'] as $key => $id){
		if(!in_array($id,$exclude)){
			unset($json_object['categories'][$key]);
		}
	}
	return $json_object;
}
add_filter('geo_mashup_locations_json_object','geo_mashup_locations_exclude_the_categorys',10,2);
