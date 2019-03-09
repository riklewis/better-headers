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
  if(($settings['better-headers-rp'] ?: "")!=="") {
    header('Referrer-Policy: ' . $settings['better-headers-rp']);
  }

  //Feature Policy
  $fp = better_head_calc_fp($settings);
  if($fp!=="") {
    header('Feature-Policy: ' . $fp);
  }

  //Miscellaneous
  if(($settings['better-headers-xcto'] ?: "")!=="") {
    header('X-Content-Type-Options: nosniff');
  }
  if(($settings['better-headers-xfo'] ?: "")!=="") {
    header('X-Frame-Options: sameorigin');
  }
  if(($settings['better-headers-xxp'] ?: "")!=="") {
    header('X-XSS-Protection: 1; mode=block');
  }
}

function better_head_calc_fp($settings) {
  $fp = "";
  if(($settings['better-headers-fp-ac'] ?: "")!=="") $fp .= "; accelerometer " . better_head_fp_value($settings['better-headers-fp-ac']);
  if(($settings['better-headers-fp-als'] ?: "")!=="") $fp .= "; ambient-light-sensor " . better_head_fp_value($settings['better-headers-fp-als']);
  if(($settings['better-headers-fp-ap'] ?: "")!=="") $fp .= "; autoplay " . better_head_fp_value($settings['better-headers-fp-ap']);
  if(($settings['better-headers-fp-cam'] ?: "")!=="") $fp .= "; camera " . better_head_fp_value($settings['better-headers-fp-cam']);
  if(($settings['better-headers-fp-dd'] ?: "")!=="") $fp .= "; document-domain " . better_head_fp_value($settings['better-headers-fp-dd']);
  if(($settings['better-headers-fp-em'] ?: "")!=="") $fp .= "; encrypted-media " . better_head_fp_value($settings['better-headers-fp-em']);
  if(($settings['better-headers-fp-fs'] ?: "")!=="") $fp .= "; fullscreen " . better_head_fp_value($settings['better-headers-fp-fs']);
  if(($settings['better-headers-fp-geo'] ?: "")!=="") $fp .= "; geolocation " . better_head_fp_value($settings['better-headers-fp-geo']);
  if(($settings['better-headers-fp-gy'] ?: "")!=="") $fp .= "; gyroscope " . better_head_fp_value($settings['better-headers-fp-gy']);
  if(($settings['better-headers-fp-lif'] ?: "")!=="") $fp .= "; legacy-image-formats " . better_head_fp_value($settings['better-headers-fp-lif']);
  if(($settings['better-headers-fp-ma'] ?: "")!=="") $fp .= "; magnetometer " . better_head_fp_value($settings['better-headers-fp-ma']);
  if(($settings['better-headers-fp-mic'] ?: "")!=="") $fp .= "; microphone " . better_head_fp_value($settings['better-headers-fp-mic']);
  if(($settings['better-headers-fp-mid'] ?: "")!=="") $fp .= "; midi " . better_head_fp_value($settings['better-headers-fp-mid']);
  if(($settings['better-headers-fp-oi'] ?: "")!=="") $fp .= "; oversized-images " . better_head_fp_value($settings['better-headers-fp-oi']);
  if(($settings['better-headers-fp-pay'] ?: "")!=="") $fp .= "; payment " . better_head_fp_value($settings['better-headers-fp-pay']);
  if(($settings['better-headers-fp-sp'] ?: "")!=="") $fp .= "; speaker " . better_head_fp_value($settings['better-headers-fp-sp']);
  if(($settings['better-headers-fp-sx'] ?: "")!=="") $fp .= "; sync-xhr " . better_head_fp_value($settings['better-headers-fp-sx']);
  if(($settings['better-headers-fp-ui'] ?: "")!=="") $fp .= "; unoptimized-images " . better_head_fp_value($settings['better-headers-fp-ui']);
  if(($settings['better-headers-fp-um'] ?: "")!=="") $fp .= "; unsized-media " . better_head_fp_value($settings['better-headers-fp-um']);
  if(($settings['better-headers-fp-usb'] ?: "")!=="") $fp .= "; usb " . better_head_fp_value($settings['better-headers-fp-usb']);
  if(($settings['better-headers-fp-vib'] ?: "")!=="") $fp .= "; vibrate " . better_head_fp_value($settings['better-headers-fp-vib']);
  if(($settings['better-headers-fp-vr'] ?: "")!=="") $fp .= "; vr " . better_head_fp_value($settings['better-headers-fp-vr']);
  return (substr($fp,2) ?: "");
}
function better_head_fp_value($value) {
  if($value==="all") {
    return "*";
  }
  return "'" . $value . "'";
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
  add_settings_field('better-headers-rp', __('Referrer Policy', 'better-head-text'), 'better_head_rp', 'better-headers', 'better-headers-section-rp');

  add_settings_section('better-headers-section-fp', __('Feature Policy', 'better-head-text'), 'better_head_section_fp', 'better-headers');
  add_settings_field('better-headers-fp-ac', __('Accelerometer', 'better-head-text'), 'better_head_fp_ac', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-als', __('Ambient Light Sensor', 'better-head-text'), 'better_head_fp_als', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-ap', __('Autoplay', 'better-head-text'), 'better_head_fp_ap', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-cam', __('Camera', 'better-head-text'), 'better_head_fp_cam', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-dd', __('Document Domain', 'better-head-text'), 'better_head_fp_dd', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-em', __('Encrypted Media', 'better-head-text'), 'better_head_fp_em', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-fs', __('Fullscreen', 'better-head-text'), 'better_head_fp_fs', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-geo', __('Geolocation', 'better-head-text'), 'better_head_fp_geo', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-gy', __('Gyroscope', 'better-head-text'), 'better_head_fp_gy', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-lif', __('Legacy Image Formats', 'better-head-text'), 'better_head_fp_lif', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-ma', __('Magnetometer', 'better-head-text'), 'better_head_fp_ma', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-mic', __('Microphone', 'better-head-text'), 'better_head_fp_mic', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-mid', __('Midi', 'better-head-text'), 'better_head_fp_mid', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-oi', __('Oversized Images', 'better-head-text'), 'better_head_fp_oi', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-pay', __('Payment Request', 'better-head-text'), 'better_head_fp_pay', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-sp', __('Speaker', 'better-head-text'), 'better_head_fp_sp', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-sx', __('Synchronous XHR', 'better-head-text'), 'better_head_fp_sx', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-ui', __('Unoptimized Images', 'better-head-text'), 'better_head_fp_ui', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-um', __('Unsized Media', 'better-head-text'), 'better_head_fp_um', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-usb', __('USB', 'better-head-text'), 'better_head_fp_usb', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-vib', __('Vibrate', 'better-head-text'), 'better_head_fp_vib', 'better-headers', 'better-headers-section-fp');
  add_settings_field('better-headers-fp-vr', __('Virtual Reality', 'better-head-text'), 'better_head_fp_vr', 'better-headers', 'better-headers-section-fp');

  add_settings_section('better-headers-section-misc', __('Miscellaneous', 'better-head-text'), 'better_head_section_misc', 'better-headers');
  add_settings_field('better-headers-xcto', __('Content Type Options', 'better-head-text'), 'better_head_xcto', 'better-headers', 'better-headers-section-misc');
	add_settings_field('better-headers-xfo', __('Frame Options', 'better-head-text'), 'better_head_xfo', 'better-headers', 'better-headers-section-misc');
	add_settings_field('better-headers-xxp', __('Cross Site Scripting Protection', 'better-head-text'), 'better_head_xxp', 'better-headers', 'better-headers-section-misc');
}

//allow the settings to be stored
add_filter('whitelist_options', function($whitelist_options) {
  $whitelist_options['better-headers'][] = 'better-headers-rp';
  $whitelist_options['better-headers'][] = 'better-headers-fp-ac';
  $whitelist_options['better-headers'][] = 'better-headers-fp-als';
  $whitelist_options['better-headers'][] = 'better-headers-fp-ap';
  $whitelist_options['better-headers'][] = 'better-headers-fp-cam';
  $whitelist_options['better-headers'][] = 'better-headers-fp-dd';
  $whitelist_options['better-headers'][] = 'better-headers-fp-em';
  $whitelist_options['better-headers'][] = 'better-headers-fp-fs';
  $whitelist_options['better-headers'][] = 'better-headers-fp-geo';
  $whitelist_options['better-headers'][] = 'better-headers-fp-gy';
  $whitelist_options['better-headers'][] = 'better-headers-fp-lif';
  $whitelist_options['better-headers'][] = 'better-headers-fp-ma';
  $whitelist_options['better-headers'][] = 'better-headers-fp-mic';
  $whitelist_options['better-headers'][] = 'better-headers-fp-mid';
  $whitelist_options['better-headers'][] = 'better-headers-fp-oi';
  $whitelist_options['better-headers'][] = 'better-headers-fp-pay';
  $whitelist_options['better-headers'][] = 'better-headers-fp-sp';
  $whitelist_options['better-headers'][] = 'better-headers-fp-sx';
  $whitelist_options['better-headers'][] = 'better-headers-fp-ui';
  $whitelist_options['better-headers'][] = 'better-headers-fp-um';
  $whitelist_options['better-headers'][] = 'better-headers-fp-usb';
  $whitelist_options['better-headers'][] = 'better-headers-fp-vib';
  $whitelist_options['better-headers'][] = 'better-headers-fp-vr';
  $whitelist_options['better-headers'][] = 'better-headers-xcto';
  $whitelist_options['better-headers'][] = 'better-headers-xfo';
  $whitelist_options['better-headers'][] = 'better-headers-xxp';
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

  if(($settings['better-headers-rp'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">Referrer-Policy</th>';
    echo '        <td>' . $settings['better-headers-rp'] . '</td>';
    echo '      </tr>';
    $boo = true;
  }
  $fp = better_head_calc_fp($settings);
  if($fp!=="") {
    echo '      <tr>';
    echo '        <th scope="row">Feature-Policy</th>';
    echo '        <td>' . $fp . '</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(($settings['better-headers-xcto'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Content-Type-Options</th>';
    echo '        <td>nosniff</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(($settings['better-headers-xfo'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Frame-Options</th>';
    echo '        <td>sameorigin</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(($settings['better-headers-xxp'] ?: "")!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-XSS-Protection</th>';
    echo '        <td>1; mode=block</td>';
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
  echo '<p>Protect against information leakage by setting the <strong>Referrer-Policy</strong> header:</p>';  
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
function better_head_section_fp() {
  echo '<hr>';
  echo '<p>Protect against feature misuse by setting the <strong>Feature-Policy</strong> header. If this site isn\'t using the following features then it is best to disable them:</p>';
  echo '<p><a href="javascript:jQuery(\'select.better-headers-fp\').val(\'\');">Unset all</a> - <a href="javascript:jQuery(\'select.better-headers-fp\').val(\'none\');">Disable all</a> - <a href="javascript:jQuery(\'select.better-headers-fp\').val(\'self\');">Enable all (this domain only)</a> - <a href="javascript:jQuery(\'select.better-headers-fp\').val(\'all\');">Enable all (all domains)</a></p>';
}

//defined output for settings
function better_head_fp_ac() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-ac'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-ac" name="better-headers-settings[better-headers-fp-ac]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_als() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-als'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-als" name="better-headers-settings[better-headers-fp-als]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_ap() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-ap'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-ap" name="better-headers-settings[better-headers-fp-ap]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_cam() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-cam'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-cam" name="better-headers-settings[better-headers-fp-cam]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_dd() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-dd'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-dd" name="better-headers-settings[better-headers-fp-dd]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_em() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-em'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-em" name="better-headers-settings[better-headers-fp-em]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_fs() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-fs'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-fs" name="better-headers-settings[better-headers-fp-fs]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_geo() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-geo'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-geo" name="better-headers-settings[better-headers-fp-geo]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_gy() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-gy'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-gy" name="better-headers-settings[better-headers-fp-gy]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_lif() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-lif'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-lif" name="better-headers-settings[better-headers-fp-lif]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
}
function better_head_fp_ma() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-ma'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-ma" name="better-headers-settings[better-headers-fp-ma]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
}
function better_head_fp_mic() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-mic'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-mic" name="better-headers-settings[better-headers-fp-mic]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
}
function better_head_fp_mid() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-mid'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-mid" name="better-headers-settings[better-headers-fp-mid]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_oi() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-oi'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-oi" name="better-headers-settings[better-headers-fp-oi]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_pay() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-pay'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-pay" name="better-headers-settings[better-headers-fp-pay]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_sp() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-sp'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-sp" name="better-headers-settings[better-headers-fp-sp]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_sx() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-sx'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-sx" name="better-headers-settings[better-headers-fp-sx]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_ui() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-ui'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-ui" name="better-headers-settings[better-headers-fp-ui]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_um() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-um'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-um" name="better-headers-settings[better-headers-fp-um]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_usb() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-usb'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-usb" name="better-headers-settings[better-headers-fp-usb]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_vib() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-vib'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-vib" name="better-headers-settings[better-headers-fp-vib]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_vr() {
	$settings = get_option('better-headers-settings');
	$value = ($settings['better-headers-fp-vr'] ?: "");
  echo '<select class="better-headers-fp" id="better-headers-fp-vr" name="better-headers-settings[better-headers-fp-vr]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_option($opt,$val,$txt) {
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
  echo '<label><input id="better-headers-xcto" name="better-headers-settings[better-headers-xcto]" type="checkbox" value="YES"' . $checked . '> Protect against content sniffing attacks by setting the <strong>X-Content-Type-Options</strong> header';
}

//defined output for settings
function better_head_xfo() {
  $settings = get_option('better-headers-settings');
  $checked = ($settings['better-headers-xfo']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-xfo" name="better-headers-settings[better-headers-xfo]" type="checkbox" value="YES"' . $checked . '> Protect against clickjacking attacks by setting the <strong>X-Frame-Options</strong> header';
}

//defined output for settings
function better_head_xxp() {
  $settings = get_option('better-headers-settings');
  $checked = ($settings['better-headers-xxp']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-xxp" name="better-headers-settings[better-headers-xxp]" type="checkbox" value="YES"' . $checked . '> Protect against cross site scripting attacks by setting the <strong>X-XSS-Protection</strong> header';
}

//add actions
add_action('admin_menu','better_head_menus');
add_action('admin_init','better_head_settings');

/*
----------------------------- The End ------------------------------
*/
