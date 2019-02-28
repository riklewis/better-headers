<?php
/*
Plugin Name:  Better Headers
Description:  This is a Wordpress plugin that makes it easy to set HTTP response headers that will improve the security of your website
Version:      1.0
Author:       Better Security
Author URI:   https://bettersecurity.co
License:      GPL3
License URI:  https://www.gnu.org/licenses/gpl-3.0.en.html
Text Domain:  better-head-text
Domain Path:  /languages
*/

//prevent direct access
defined('ABSPATH') or die('Forbidden');

/*
------------------------- Send headers --------------------------
*/

//send headers
function better_head_send_headers() {

  $settings = get_option('better-headers-settings');

  if($settings['better-headers-xcto']==="YES") {
    header('X-Content-Type-Options: nosniff');
  }
}

//add actions
add_action('send_headers', 'better_head_send_headers');

/*
----------------------------- Settings ------------------------------
*/

//add settings page
function better_head_menus() {
	add_options_page(__('Better Headers','better-head-text'), __('Better Headers','better-head-text'), 'manage_options', 'better-headers-settings', 'better_head_show_settings');
}

//add the settings
function better_head_settings() {
	register_setting('better-headers','better-headers-settings');
	add_settings_section('better-headers-section', __('Content Type Options', 'better-head-text'), 'better_head_section', 'better-headers');
	add_settings_field('better-headers-xcto', __('Content Type Options', 'better-head-text'), 'better_head_xcto', 'better-headers', 'better-headers-section');
}

//allow the settings to be stored
add_filter('whitelist_options', function($whitelist_options) {
  $whitelist_options['better-headers'][] = 'better-headers-xcto';
  return $whitelist_options;
});

//define output for settings page
function better_head_show_settings() {
  echo '<div class="wrap">';
  echo '  <div style="padding:12px;background-color:white;margin:24px 0;">';
  echo '    <a href="https://bettersecurity.co" target="_blank" style="display:inline-block;width:100%;">';
  echo '      <img src="' . WP_PLUGIN_URL . '/better-headers/header.png" style="height:64px;">';
  echo '    </a>';
  echo '  </div>';
  echo '  <h1>' . __('Better Headers', 'better-head-text') . '</h1>';
  echo '  <form action="options.php" method="post">';
	settings_fields('better-headers');
  do_settings_sections('better-headers');
	submit_button();
  echo '  </form>';
  echo '</div>';
}

//define output for settings section
function better_head_section() {
  // No output required for section
}

//defined output for settings
function better_head_xcto() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-xcto']==="YES" ? " checked" : "");
  echo '<input id="better-headers-xcto" name="better-headers-settings[better-headers-xcto]" type="checkbox" value="YES"' . $checked . '>';
}

//add actions
add_action('admin_menu','better_head_menus');
add_action('admin_init','better_head_settings');

/*
----------------------------- The End ------------------------------
*/
