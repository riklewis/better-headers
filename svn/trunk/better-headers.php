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

  //Referrer Policy
  if(($settings['better-headers-rp-down'] ?: "")!=="" || ($settings['better-headers-rp-cros'] ?: "")!=="" || ($settings['better-headers-rp-same'] ?: "")!=="") {
    $value = better_head_calc_rp($settings);
    if($value!=="") {
      header('Referrer-Policy: ' . $value);
    }
  }

  //Miscellaneous
  if(($settings['better-headers-xcto'] ?: "")!=="") {
    header('X-Content-Type-Options: nosniff');
  }
}

//calculate Referrer Policy value
function better_head_calc_rp($settings) {
  $value = ''; //don't send header


  return $value;
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

	add_settings_section('better-headers-section-rp', __('Referrer Policy', 'better-head-text'), 'better_head_section_rp', 'better-headers');
	add_settings_field('better-headers-rp-down', __('Referrer when downgrading (HTTPS -> HTTP)', 'better-head-text'), 'better_head_rp_down', 'better-headers', 'better-headers-section-rp');
	add_settings_field('better-headers-rp-cros', __('Referrer when changing domain/site', 'better-head-text'), 'better_head_rp_cros', 'better-headers', 'better-headers-section-rp');
	add_settings_field('better-headers-rp-same', __('Referrer for same domain/site', 'better-head-text'), 'better_head_rp_same', 'better-headers', 'better-headers-section-rp');

  add_settings_section('better-headers-section-misc', __('Miscellaneous', 'better-head-text'), 'better_head_section_misc', 'better-headers');
	add_settings_field('better-headers-xcto', __('Content Type Options', 'better-head-text'), 'better_head_xcto', 'better-headers', 'better-headers-section-misc');
}

//allow the settings to be stored
add_filter('whitelist_options', function($whitelist_options) {
  $whitelist_options['better-headers'][] = 'better-headers-xcto';
  $whitelist_options['better-headers'][] = 'better-headers-rp-down';
  $whitelist_options['better-headers'][] = 'better-headers-rp-cros';
  $whitelist_options['better-headers'][] = 'better-headers-rp-same';
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

  if(($settings['better-headers-rp-down'] ?: "")!=="" || ($settings['better-headers-rp-cros'] ?: "")!=="" || ($settings['better-headers-rp-same'] ?: "")!=="") {
    $value = better_head_calc_rp($settings);
    if($value!=="") {
      echo '      <tr>';
      echo '        <th scope="row">Referrer-Policy</th>';
      echo '        <td>' . $value . '</td>';
      echo '      </tr>';
      $boo = true;
    }
  }
  if(($settings['better-headers-xcto'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Content-Type-Options</th>';
    echo '        <td>nosniff</td>';
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
function better_head_rp_down() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-passwords-rp-down'] ?: "");
  echo '<select id="better-headers-rp-down" name="better-headers-settings[better-headers-rp-down]">';
  echo better_head_rp_option('',$value,'-- Not set --');
  echo better_head_rp_option('N',$value,'Nothing (no referrer)');
  echo better_head_rp_option('O',$value,'Origin (domain only)');
  echo better_head_rp_option('F',$value,'Full (whole URL)');
  echo '</select>';
}

function better_head_rp_cros() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-passwords-rp-cros'] ?: "");
  echo '<select id="better-headers-rp-cros" name="better-headers-settings[better-headers-rp-cros]">';
  echo better_head_rp_option('',$value,'-- Not set --');
  echo better_head_rp_option('N',$value,'Nothing (no referrer)');
  echo better_head_rp_option('O',$value,'Origin (domain only)');
  echo better_head_rp_option('F',$value,'Full (whole URL)');
  echo '</select>';
}

function better_head_rp_same() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-passwords-rp-same'] ?: "");
  echo '<select id="better-headers-rp-same" name="better-headers-settings[better-headers-rp-same]">';
  echo better_head_rp_option('',$value,'-- Not set --');
  echo better_head_rp_option('N',$value,'Nothing (no referrer)');
  echo better_head_rp_option('O',$value,'Origin (domain only)');
  echo better_head_rp_option('F',$value,'Full (whole URL)');
  echo '</select>';
}

function better_head_rp_option($opt,$val,$txt) {
  return '  <option value="' . $opt . '"' . ($opt===$val ? ' selected' : '') . '>' . $txt . '</option>';
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
