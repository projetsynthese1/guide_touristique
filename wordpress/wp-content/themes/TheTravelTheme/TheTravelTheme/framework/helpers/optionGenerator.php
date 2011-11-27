<?php
/**
 * The `optionGenerator` class help generate the html code for theme options page.
 */
class optionGenerator {
	var $name;
	var $options;
	var $saved_options;
	/**
	 * Constructor
	 * 
	 * @param string $name
	 * @param array $options
	 */
	function optionGenerator($name, $options) {
		$this->name = $name;
		$this->options = $options;
		$this->save_options();
		$this->render();
	}
	function save_options() {
		$options = get_option(THEME_SLUG . '_' . $this->name);
		if (isset($_POST['save_theme_options'])) {
			foreach($this->options as $value) {
				if (isset($value['id']) && ! empty($value['id'])) {
					if (isset($_POST[$value['id']])) {
						if ($value['type'] == 'multidropdown') {
							if(empty($_POST[$value['id']])){
								$options[$value['id']] = array();
							}else{
								$options[$value['id']] = array_unique(explode(',', $_POST[$value['id']]));
							}
						} else {
							$options[$value['id']] = $_POST[$value['id']];
						}
					} else {
						$options[$value['id']] = false;
					}
				}
			}
			if ($options != $this->options) {
				update_option(THEME_SLUG . '_' . $this->name, $options);
			}
			echo '<div id="message" class="updated fade"><p><strong>Updated Successfully</strong></p></div>';
		}
		$this->saved_options = $options;
	}
	function render() {
		echo '<div class="wrap theme-options-page">';
		echo '<form method="post" action="">';
		foreach($this->options as $option) {
			if (method_exists($this, $option['type'])) {
				$this->$option['type']($option);
			}
		}
		echo '</form>';
		echo '</div>';
	}
	/**
	 * prints the options page title
	 */
	function title($value) {
		echo '<h2>' . $value['name'] . '</h2>';
		if (isset($value['desc'])) {
			echo '<p>' . $value['desc'] . '</p>';
		}
	}
	/**
	 * begins the group section
	 */
	function start($value) {
		echo '<div class="theme-options-group">';
		echo '<table cellspacing="0" class="widefat theme-options-table">';
		echo '<thead><tr>';
		echo '<th scope="row" colspan="2">' . $value['name'] . '</th>';
		echo '</tr></thead><tbody>';
	}
	function desc($value) {
		echo '<tr><td scope="row" colspan="2">' . $value['desc'] . '</td></tr>';
	}
	/**
	 * closes the group section
	 */
	function end($value) {
		echo '</tbody></table></div><p class="submit"><input type="submit" name="save_theme_options" class="button-primary autowidth" value="Save Changes" /></p>';
	}
	/**
	 * displays a text input
	 */
	function text($value) {
		$size = isset($value['size']) ? $value['size'] : '10';
		echo '<tr><th scope="row"><h4><label for="'.$value['id'].'">' . $value['name'] . '</label></h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" size="' . $size . '" value="';
		if (isset($this->saved_options[$value['id']])) {
			echo stripslashes($this->saved_options[$value['id']]);
		} else {
			echo $value['default'];
		}
		echo '" /><br />';
		echo '</td></tr>';
	}
	/**
	 * displays a textarea
	 */
	function textarea($value) {
		$rows = isset($value['rows']) ? $value['rows'] : '5';
		echo '<tr><th scope="row"><h4><label for="'.$value['id'].'">' . $value['name'] . '</label></h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<textarea id="'.$value['id'].'" rows="' . $rows . '" name="' . $value['id'] . '" type="' . $value['type'] . '" class="code">';
		if (isset($this->saved_options[$value['id']])) {
			echo stripslashes($this->saved_options[$value['id']]);
		} else {
			echo $value['default'];
		}
		echo '</textarea><br />';
		echo '</td></tr>';
	}
	/**
	 * displays a select
	 */
	function select($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->get_select_target_options($value['target']);
			}
		}
		echo '<tr><th scope="row"><h4><label for="'.$value['id'].'">' . $value['name'] . '</label></h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '">';
		if(isset($value['prompt'])){
			echo '<option value="">'.$value['prompt'].'</option>';
		}
		foreach($value['options'] as $key => $option) {
			echo "<option value='" . $key . "'";
			if (isset($this->saved_options[$value['id']])) {
				if (stripslashes($this->saved_options[$value['id']]) == $key) {
					echo ' selected="selected"';
				}
			} else if ($key == $value['default']) {
				echo ' selected="selected"';
			}
			echo '>' . $option . '</option>';
		}
		echo '</select><br />';
		echo '</td></tr>';
	}
	/**
	 * displays a multiselect
	 */
	function multiselect($value) {
		$size = isset($value['size']) ? $value['size'] : '5';
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->get_select_target_options($value['target']);
			}
		}
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<select name="' . $value['id'] . '[]" id="' . $value['id'] . '" multiple="multiple" size="' . $size . '" style="height:auto">';
		if(!empty($value['options']) && is_array($value['options'])){
			foreach($value['options'] as $key => $option) {
				echo '<option value="' . $key . '"';
				if (isset($this->saved_options[$value['id']])) {
					if (is_array($this->saved_options[$value['id']])) {
						if (in_array($key, $this->saved_options[$value['id']])) {
							echo ' selected="selected"';
						}
					}
				} else if (in_array($key, $value['default'])) {
					echo ' selected="selected"';
				}
				echo '>' . $option . '</option>';
			}
		}
		echo '</select><br />';
		echo '</td></tr>';
	}
	/**
	 * displays a multidropdown
	 */
	function multidropdown($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->get_select_target_options($value['target']);
			}
		}
		if (isset($this->saved_options[$value['id']])) {
			$selected_keys = $this->saved_options[$value['id']];
		} else {
			$selected_keys = $value['default'];
		}
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<input type="hidden" id="' . $value['id'] . '" name="' . $value['id'] . '" value="' . implode(',', $selected_keys) . '"/>';
		echo '<div class="multidropdown-wrap">';
		$i = 0;
		foreach($selected_keys as $selected) {
			echo '<select name="' . $value['id'] . '_' . $i . '" id="' . $value['id'] . '_' . $i . '">';
			if(isset($value['prompt'])){
				echo '<option value="">'.$value['prompt'].'</option>';
			}
			foreach($value['options'] as $key => $option) {
				echo '<option value="' . $key . '"';
				if ($selected == $key) {
					echo ' selected="selected"';
				}
				echo '>' . $option . '</option>';
			}
			$i++;
			echo '</select>';
		}
		echo '<select name="' . $value['id'] . '_' . $i . '" id="' . $value['id'] . '_' . $i . '">';
		if(isset($value['prompt'])){
			echo '<option value="">'.$value['prompt'].'</option>';
		}
		foreach($value['options'] as $key => $option) {
			echo '<option value="' . $key . '">' . $option . '</option>';
		}
		echo '</select></div>';
		echo '</td></tr>';
	}
	/**
	 * displays a checkbox
	 */
	function checkbox($value) {
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		$i = 0;
		foreach($value['options'] as $key => $option) {
			$i++;
			$checked = '';
			if (isset($this->saved_options[$value['id']])) {
				if (is_array($this->saved_options[$value['id']])) {
					if (in_array($key, $this->saved_options[$value['id']])) {
						$checked = ' checked="checked"';
					}
				}
			} else if (in_array($key, $value['default'])) {
				$checked = ' checked="checked"';
			}
			echo '<input type="checkbox" id="' . $value['id'] . '_' . $i . '" name="' . $value['id'] . '[]" value="' . $key . '" ' . $checked . ' />';
			echo '<label for="' . $value['id'] . '_' . $i . '">' . $option . '</label><br />';
		}
		echo '</td></tr>';
	}
	/**
	 * displays a radio
	 */
	function radio($value) {
		if (isset($this->saved_options[$value['id']])) {
			$checked_key = $this->saved_options[$value['id']];
		} else {
			$checked_key = $value['default'];
		}
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		$i = 0;
		foreach($value['options'] as $key => $option) {
			$i++;
			$checked = '';
			if ($key == $checked_key) {
				$checked = ' checked="checked"';
			}
			echo '<input type="radio" id="' . $value['id'] . '_' . $i . '" name="' . $value['id'] . '" value="' . $key . '" ' . $checked . ' />';
			echo '<label for="' . $value['id'] . '_' . $i . '">' . $option . '</label><br />';
		}
		echo '</td></tr>';
	}
	/**
	 * displays a upload field
	 */
	function upload($value) {
		$size = isset($value['size']) ? $value['size'] : '50';
		$button = isset($value['button']) ? $value['button'] : 'Insert Image';
		if (isset($this->saved_options[$value['id']])) {
			$value['default'] = stripslashes($this->saved_options[$value['id']]);
		}
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		echo '<div id="' . $value['id'] . '_preview" class="theme-option-image-preview">';
		if (! empty($value['default'])) {
			echo '<a class="thickbox" href="' . $value['default'] . '" target="_blank"><img src="' . $value['default'] . '"/></a>';
		}
		echo '</div>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<input type="text" id="' . $value['id'] . '" name="' . $value['id'] . '" size="' . $size . '"  value="';
		echo $value['default'];
		echo '" /><div class="theme-upload-buttons"><a class="thickbox button theme-upload-button" id="' . $value['id'] . '" href="media-upload.php?&post_id=-98765&target=' . $value['id'] . '&option_image_upload=1&type=image&TB_iframe=1&width=640&height=644">'.$button.'</a></div>';
		echo '</td></tr>';
	}
	/**
	 * displays a range input
	 */
	function range($value) {
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<div class="range-input-wrap"><input name="' . $value['id'] . '" id="' . $value['id'] . '" type="range" value="';
		if (isset($this->saved_options[$value['id']])) {
			echo stripslashes($this->saved_options[$value['id']]);
		} else {
			echo $value['default'];
		}
		if (isset($value['min'])) {
			echo '" min="' . $value['min'];
		}
		if (isset($value['max'])) {
			echo '" max="' . $value['max'];
		}
		if (isset($value['step'])) {
			echo '" step="' . $value['step'];
		}
		echo '" />';
		if (isset($value['unit'])) {
			echo '<span>' . $value['unit'] . '</span>';
		}
		echo '</div></td></tr>';
	}
	/**
	 * displays a color input
	 */
	function color($value) {
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<div class="color-input-wrap"><input name="' . $value['id'] . '" id="' . $value['id'] . '" type="color" data-hex="true" value="';
		if (isset($this->saved_options[$value['id']])) {
			echo stripslashes($this->saved_options[$value['id']]);
		} else {
			echo $value['default'];
		}
		echo '" />';
		echo '</div></td></tr>';
	}
	/**
	 * displays a toggle checkbox
	 */
	function toggle($value) {
		$checked = '';
		if (isset($this->saved_options[$value['id']])) {
			if ($this->saved_options[$value['id']] == true) {
				$checked = 'checked="checked"';
			}
		} elseif ($value['default'] == true) {
			$checked = 'checked="checked"';
		}
		echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<input type="checkbox" class="toggle-button" name="' . $value['id'] . '" id="' . $value['id'] . '" value="true" ' . $checked . ' />';
		echo '</td></tr>';
	}
	/**
	 * displays a validator input
	 */
	function validator($value) {
		echo '<tr><th scope="row"><h4><label for="'.$value['id'].'">' . $value['name'] . '</label></h4></th><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<div class="validator-wrap"><input name="' . $value['id'] . '" id="' . $value['id'] . '" type="'. $value['format'].'" value="';
		if (isset($this->saved_options[$value['id']])) {
			echo stripslashes($this->saved_options[$value['id']]);
		} else {
			echo $value['default'];
		}
		if (isset($value['max'])) {
			echo '" max="' . $value['max'];
		}
		if (isset($value['min'])) {
			echo '" min="' . $value['min'];
		}
		if (isset($value['pattern'])) {
			echo '" pattern="' . $value['pattern'];
		}
		if (isset($value['required'])) {
			echo '" required="required"';
		}
		if (isset($value['maxlength'])) {
			echo '" maxlength="' . $value['maxlength'];
		}
		if (isset($value['minlength'])) {
			echo '" minlength="' . $value['minlength'];
		}
		if (isset($value['error'])) {
			echo '" data-message="' . $value['error'];
		}
		echo '" /><span class="validator-error"></span></div>';
		echo '</td></tr>';
	}
	/**
	 * displays a editor
	 */
	function editor($value) {
		$rows = isset($value['rows']) ? $value['rows'] : '7';
		if (isset($this->saved_options[$value['id']])) {
			$value['default'] = stripslashes($this->saved_options[$value['id']]);
		}
		echo '<tr><td>';
		if(isset($value['desc'])){
			echo '<p class="description">' . $value['desc'] . '</p>';
		}
		echo '<div id="poststuff"><div id="post-body"><div id="post-body-content"><div class="postarea" id="postdivrich">';
		the_editor($value['default'],$value['id']);
		echo '<table id="post-status-info" cellspacing="0">';
		echo '<tbody><tr>';
		echo '<td>&nbsp;</td>';
		echo '<td>&nbsp;</td>';
		echo '</tr></tbody>';
		echo '</table>';
		echo '</div></div></div></div>';
		echo '</td></tr>';
	}
	/**
	 * displays a custom field
	 */
	function custom($value) {
		if (isset($this->saved_options[$value['id']])) {
			$default = $this->saved_options[$value['id']];
		} else {
			$default = $value['default'];
		}
		if(isset($value['layout']) && $value['layout']==false){
			if (isset($value['function']) && function_exists($value['function'])) {
				$value['function']($value, $default);
			} else {
				echo $value['html'];
			}
		}else{
			echo '<tr><th scope="row"><h4>' . $value['name'] . '</h4></th><td>';
			if(isset($value['desc'])){
				echo '<p class="description">' . $value['desc'] . '</p>';
			}
			if (isset($value['function']) && function_exists($value['function'])) {
				$value['function']($value, $default);
			} else {
				echo $value['html'];
			}
			echo '</td></tr>';
		}
	}
	function get_select_target_options($type) {
        $options = array();
        switch($type){
			case 'page':
				$entries = get_pages('title_li=&orderby=name');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'cat':
				$entries = get_categories('orderby=name&hide_empty=0');
				foreach($entries as $key => $entry) {
					$options[$entry->term_id] = $entry->name;
				}
				break;
			case 'post':
				$entries = get_posts('orderby=title&numberposts=-1&order=ASC');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'portfolio':
				$entries = get_posts('post_type=portfolio&orderby=title&numberposts=-1&order=ASC');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'portfolio_category':
				$entries = get_terms('portfolio_category','orderby=name&hide_empty=0');
				foreach($entries as $key => $entry) {
					$options[$entry->slug] = $entry->name;
				}
				break;
		}
		return $options;
	}
}
