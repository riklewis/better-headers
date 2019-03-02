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

  //Miscellaneous
  if(($settings['better-headers-xcto'] ?: "")!=="") {
    header('X-Content-Type-Options: nosniff');
  }
  if(($settings['better-headers-rp'] ?: "")!=="") {
    header('Referrer-Policy: ' . $settings['better-headers-rp']);
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

  add_settings_section('better-headers-section-misc', __('Miscellaneous', 'better-head-text'), 'better_head_section_misc', 'better-headers');
	add_settings_field('better-headers-xcto', __('Content Type Options', 'better-head-text'), 'better_head_xcto', 'better-headers', 'better-headers-section-misc');
	add_settings_field('better-headers-rp', __('Referrer Policy', 'better-head-text'), 'better_head_rp', 'better-headers', 'better-headers-section-misc');
}

//allow the settings to be stored
add_filter('whitelist_options', function($whitelist_options) {
  $whitelist_options['better-headers'][] = 'better-headers-xcto';
  $whitelist_options['better-headers'][] = 'better-headers-rp';
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
  echo '  <h2>Saved Headers</h2>';
  echo '  <hr>';
  echo '  <table class="form-table">';
  echo '    <tbody>';

  $settings = get_option('better-headers-settings');
  $boo = false;

  if(($settings['better-headers-xcto'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Content-Type-Options</th>';
    echo '        <td>nosniff</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(($settings['better-headers-rp'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">Referrer-Policy</th>';
    echo '        <td>' . $settings['better-headers-rp'] . '</td>';
    echo '      </tr>';
    $boo = true;
  }

  if(!$boo) {
    echo '      <tr>';
    echo '        <th><em>None yet - configure some above!</em></th>';
    echo '      </tr>';
  }
  echo '    </tbody>';
  echo '  </table>';
  echo '</div>';
}

//define output for settings section
function better_head_section_rp() {
  echo '<hr>';
}

//defined output for settings
function better_head_rp() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-rp'] ?: "");
  echo better_head_rp_option('',$value,'-- Not set -- ');
  echo better_head_rp_option('no-referrer',$value,'No referrer information should be sent along with requests');
  echo better_head_rp_option('no-referrer-when-downgrade',$value,'The full URL should be sent as the referrer when the protocol security level stays the same (HTTP→HTTP, HTTPS→HTTPS), but not sent to a less secure destination (HTTPS→HTTP)');
  echo better_head_rp_option('origin',$value,'The origin of the document should be sent as the referrer in all cases (eg. the domain only)');
  echo better_head_rp_option('origin-when-cross-origin',$value,'The full URL should be sent when performing a same-origin request, but only send the origin of the document for cross-site requests');
  echo better_head_rp_option('same-origin',$value,'The full URL should be sent when performing a same-origin request, but no referrer information for cross-site requests');
  echo better_head_rp_option('strict-origin',$value,'The origin of the document should be sent as the referrer when the protocol security level stays the same (HTTPS→HTTPS), but not sent to a less secure destination (HTTPS→HTTP)');
  echo better_head_rp_option('strict-origin-when-cross-origin',$value,'The full URL should be sent when performing a same-origin request, send the origin only for cross-site requests when the protocol security level stays the same (HTTPS→HTTPS), and send no referrer information to a less secure destination (HTTPS→HTTP)');
}

function better_head_rp_option($opt,$val,$txt) {
  return '<label><input type="radio" id="better-headers-rp' . $opt . '" name="better-headers-settings[better-headers-rp]" value="' . $opt . '"' . ($opt===$val ? ' checked' : '') . '>&nbsp; ' . $txt . '</label><br><br>';
}

//define output for settings section
function better_head_section_misc() {
  echo '<hr>';
}

//defined output for settings
function better_head_xcto() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-xcto']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-xcto" name="better-headers-settings[better-headers-xcto]" type="checkbox" value="YES"' . $checked . '> Protect against Content Sniffing attacks by setting <b>X-Content-Type-Options</b>';
}

//add actions
add_action('admin_menu','better_head_menus');
add_action('admin_init','better_head_settings');

/*
----------------------------- The End ------------------------------
*/
