<?php
/*
Plugin Name:  Better Headers
Description:  Improve the security of your website by easily setting HTTP response headers to enable browser protection
Version:      1.3
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
  if(isset($settings['better-headers-rp']) && $settings['better-headers-rp']!=="") {
    @header('Referrer-Policy: ' . $settings['better-headers-rp']);
  }

  //Feature Policy
  $fp = better_head_calc_fp($settings);
  if($fp!=="") {
    @header('Feature-Policy: ' . $fp);
  }

  //Strict Transport Security
  $hsts = better_head_calc_hsts($settings);
  if($hsts!=="") {
    @header('Strict-Transport-Security: ' . $hsts);
  }

  //Expect Certificate Transparency
  $ect = better_head_calc_ect($settings);
  if($ect!=="") {
    @header('Expect-CT: ' . $ect);
  }

  //Miscellaneous
  if(isset($settings['better-headers-xcto']) && $settings['better-headers-xcto']!=="") {
    @header('X-Content-Type-Options: nosniff');
  }
  if(isset($settings['better-headers-xfo']) && $settings['better-headers-xfo']!=="") {
    @header('X-Frame-Options: sameorigin');
  }
  if(isset($settings['better-headers-xxp']) && $settings['better-headers-xxp']!=="") {
    @header('X-XSS-Protection: 1; mode=block');
  }
  if(isset($settings['better-headers-xpcdp']) && $settings['better-headers-xpcdp']!=="") {
    @header('X-Permitted-Cross-Domain-Policies: none');
  }
}

function better_head_calc_fp($settings) {
  $fp = "";
  if(isset($settings['better-headers-fp-ac']) && $settings['better-headers-fp-ac']!=="") $fp .= "; accelerometer " . better_head_fp_value($settings['better-headers-fp-ac']);
  if(isset($settings['better-headers-fp-als']) && $settings['better-headers-fp-als']!=="") $fp .= "; ambient-light-sensor " . better_head_fp_value($settings['better-headers-fp-als']);
  if(isset($settings['better-headers-fp-ap']) && $settings['better-headers-fp-ap']!=="") $fp .= "; autoplay " . better_head_fp_value($settings['better-headers-fp-ap']);
  if(isset($settings['better-headers-fp-cam']) && $settings['better-headers-fp-cam']!=="") $fp .= "; camera " . better_head_fp_value($settings['better-headers-fp-cam']);
  if(isset($settings['better-headers-fp-dd']) && $settings['better-headers-fp-dd']!=="") $fp .= "; document-domain " . better_head_fp_value($settings['better-headers-fp-dd']);
  if(isset($settings['better-headers-fp-em']) && $settings['better-headers-fp-em']!=="") $fp .= "; encrypted-media " . better_head_fp_value($settings['better-headers-fp-em']);
  if(isset($settings['better-headers-fp-fs']) && $settings['better-headers-fp-fs']!=="") $fp .= "; fullscreen " . better_head_fp_value($settings['better-headers-fp-fs']);
  if(isset($settings['better-headers-fp-geo']) && $settings['better-headers-fp-geo']!=="") $fp .= "; geolocation " . better_head_fp_value($settings['better-headers-fp-geo']);
  if(isset($settings['better-headers-fp-gy']) && $settings['better-headers-gp-gy']!=="") $fp .= "; gyroscope " . better_head_fp_value($settings['better-headers-fp-gy']);
  if(isset($settings['better-headers-fp-lif']) && $settings['better-headers-gp-lif']!=="") $fp .= "; legacy-image-formats " . better_head_fp_value($settings['better-headers-fp-lif']);
  if(isset($settings['better-headers-fp-ma']) && $settings['better-headers-fp-ma']!=="") $fp .= "; magnetometer " . better_head_fp_value($settings['better-headers-fp-ma']);
  if(isset($settings['better-headers-fp-mic']) && $settings['better-headers-fp-mic']!=="") $fp .= "; microphone " . better_head_fp_value($settings['better-headers-fp-mic']);
  if(isset($settings['better-headers-fp-mid']) && $settings['better-headers-fp-mid']!=="") $fp .= "; midi " . better_head_fp_value($settings['better-headers-fp-mid']);
  if(isset($settings['better-headers-fp-oi']) && $settings['better-headers-fp-oi']!=="") $fp .= "; oversized-images " . better_head_fp_value($settings['better-headers-fp-oi']);
  if(isset($settings['better-headers-fp-pay']) && $settings['better-headers-fp-pay']!=="") $fp .= "; payment " . better_head_fp_value($settings['better-headers-fp-pay']);
  if(isset($settings['better-headers-fp-sp']) && $settings['better-headers-fp-sp']!=="") $fp .= "; speaker " . better_head_fp_value($settings['better-headers-fp-sp']);
  if(isset($settings['better-headers-fp-sx']) && $settings['better-headers-fp-sx']!=="") $fp .= "; sync-xhr " . better_head_fp_value($settings['better-headers-fp-sx']);
  if(isset($settings['better-headers-fp-ui']) && $settings['better-headers-fp-ui']!=="") $fp .= "; unoptimized-images " . better_head_fp_value($settings['better-headers-fp-ui']);
  if(isset($settings['better-headers-fp-um']) && $settings['better-headers-fp-um']!=="") $fp .= "; unsized-media " . better_head_fp_value($settings['better-headers-fp-um']);
  if(isset($settings['better-headers-fp-usb']) && $settings['better-headers-fp-usb']!=="") $fp .= "; usb " . better_head_fp_value($settings['better-headers-fp-usb']);
  if(isset($settings['better-headers-fp-vib']) && $settings['better-headers-fp-vib']!=="") $fp .= "; vibrate " . better_head_fp_value($settings['better-headers-fp-vib']);
  if(isset($settings['better-headers-fp-vr']) && $settings['better-headers-fp-vr']!=="") $fp .= "; vr " . better_head_fp_value($settings['better-headers-fp-vr']);
  return (substr($fp,2) ?: "");
}
function better_head_fp_value($value) {
  if($value==="all") {
    return "*";
  }
  return "'" . $value . "'";
}
function better_head_calc_hsts($settings) {
  $hsts = "";
  if(isset($settings['better-headers-hsts']) && $settings['better-headers-hsts']!=="") {
    $maxage = 0;
    if(isset($settings['better-headers-hsts-age']) && $settings['better-headers-hsts-age']!=="") {
      $maxage = $settings['better-headers-hsts-age'];
    }
    $hsts = "max-age=" . ($maxage*60*60*24*30);
    if(isset($settings['better-headers-hsts-sub']) && $settings['better-headers-hsts-sub']!=="") {
      $hsts .= "; includeSubDomains";
      if(isset($settings['better-headers-hsts-pre']) && $settings['better-headers-hsts-pre']!=="") {
        $hsts .= "; preload";
      }
    }
  }
  return $hsts;
}

function better_head_calc_ect($settings) {
  $ect = "";
  if(isset($settings['better-headers-ect']) && $settings['better-headers-ect']!=="") {
    $maxage = 0;
    if(isset($settings['better-headers-ect-age']) && $settings['better-headers-ect-age']!=="") {
      $maxage = $settings['better-headers-ext-age'];
    }
    $ect = "max-age=" . ($maxage*60*60*24*30);
    if(isset($settings['better-headers-ect-enf']) && $settings['better-headers-ect-enf']!=="") {
      $ect .= "; enforce";
    }
  }
  return $ect;
}

//add actions
if(is_admin()) {
  add_action('admin_init','better_head_send_headers');
}
else {
  add_action('send_headers','better_head_send_headers');
}

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

  add_settings_section('better-headers-section-hsts', __('Strict Transport Security', 'better-head-text'), 'better_head_section_hsts', 'better-headers');
  add_settings_field('better-headers-hsts', __('Enable', 'better-head-text'), 'better_head_hsts', 'better-headers', 'better-headers-section-hsts');
  add_settings_field('better-headers-hsts-age', __('Maximum Age', 'better-head-text'), 'better_head_hsts_age', 'better-headers', 'better-headers-section-hsts');
  add_settings_field('better-headers-hsts-sub', __('Include Subdomains', 'better-head-text'), 'better_head_hsts_sub', 'better-headers', 'better-headers-section-hsts');
  add_settings_field('better-headers-hsts-pre', __('Allow Preload', 'better-head-text'), 'better_head_hsts_pre', 'better-headers', 'better-headers-section-hsts');

  add_settings_section('better-headers-section-ect', __('Expect Certificate Transparency', 'better-head-text'), 'better_head_section_ect', 'better-headers');
  add_settings_field('better-headers-ect', __('Enable', 'better-head-text'), 'better_head_ect', 'better-headers', 'better-headers-section-ect');
  add_settings_field('better-headers-ect-age', __('Maximum Age', 'better-head-text'), 'better_head_ect_age', 'better-headers', 'better-headers-section-ect');
  add_settings_field('better-headers-ect-enf', __('Enforce', 'better-head-text'), 'better_head_ect_enf', 'better-headers', 'better-headers-section-ect');

  add_settings_section('better-headers-section-misc', __('Miscellaneous', 'better-head-text'), 'better_head_section_misc', 'better-headers');
  add_settings_field('better-headers-xcto', __('Content Type Options', 'better-head-text'), 'better_head_xcto', 'better-headers', 'better-headers-section-misc');
	add_settings_field('better-headers-xfo', __('Frame Options', 'better-head-text'), 'better_head_xfo', 'better-headers', 'better-headers-section-misc');
	add_settings_field('better-headers-xxp', __('Cross Site Scripting Protection', 'better-head-text'), 'better_head_xxp', 'better-headers', 'better-headers-section-misc');
	add_settings_field('better-headers-xpcdp', __('Permitted Cross Domain Policies', 'better-head-text'), 'better_head_xpcdp', 'better-headers', 'better-headers-section-misc');
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
  $whitelist_options['better-headers'][] = 'better-headers-hsts';
  $whitelist_options['better-headers'][] = 'better-headers-hsts-age';
  $whitelist_options['better-headers'][] = 'better-headers-hsts-sub';
  $whitelist_options['better-headers'][] = 'better-headers-hsts-pre';
  $whitelist_options['better-headers'][] = 'better-headers-ect';
  $whitelist_options['better-headers'][] = 'better-headers-ect-age';
  $whitelist_options['better-headers'][] = 'better-headers-ect-enf';
  $whitelist_options['better-headers'][] = 'better-headers-xcto';
  $whitelist_options['better-headers'][] = 'better-headers-xfo';
  $whitelist_options['better-headers'][] = 'better-headers-xxp';
  $whitelist_options['better-headers'][] = 'better-headers-xpcdp';
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
  echo '  <div style="margin:0 0 24px 0;">';
  echo '    <a href="https://www.php.net/supported-versions.php" target="_blank"><img src="' . better_head_badge_php() . '"></a>';
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

  if(isset($settings['better-headers-rp']) && $settings['better-headers-rp']!=="") {
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
  $hsts = better_head_calc_hsts($settings);
  if($hsts!=="") {
    echo '      <tr>';
    echo '        <th scope="row">Strict-Transport-Security</th>';
    echo '        <td>' . $hsts . '</td>';
    echo '      </tr>';
    $boo = true;
  }
  $ect = better_head_calc_ect($settings);
  if($ect!=="") {
    echo '      <tr>';
    echo '        <th scope="row">Expect-CT</th>';
    echo '        <td>' . $ect . '</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(isset($settings['better-headers-xcto']) && $settings['better-headers-xcto']!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Content-Type-Options</th>';
    echo '        <td>nosniff</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(isset($settings['better-headers-xfo']) && $settings['better-headers-xfo']!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Frame-Options</th>';
    echo '        <td>sameorigin</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(isset($settings['better-headers-xxp']) && $settings['better-headers-xxp']!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-XSS-Protection</th>';
    echo '        <td>1; mode=block</td>';
    echo '      </tr>';
    $boo = true;
  }
  if(isset($settings['better-headers-xpcdp']) && $settings['better-headers-xpdcp']!=="") {
    echo '      <tr>';
    echo '        <th scope="row">X-Permitted-Cross-Domain-Policies</th>';
    echo '        <td>none</td>';
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

function better_head_badge_php() {
  $ver = phpversion();
  $col = "critical";
  if(version_compare($ver,'7.1','>=')) {
    $col = "important";
  }
  if(version_compare($ver,'7.2','>=')) {
    $col = "success";
  }
  return 'https://img.shields.io/badge/PHP-' . $ver . '-' . $col . '.svg?logo=php&style=for-the-badge';
}

//define output for settings section
function better_head_section_rp() {
  echo '<hr>';
  echo '<p>Protect against information leakage by setting the <strong>Referrer-Policy</strong> header:</p>';
}

//defined output for settings
function better_head_rp() {
	$settings = get_option('better-headers-settings');
	$value = "";
  if(isset($settings['better-headers-rp']) && $settings['better-headers-rp']!=="") {
    $value = $settings['better-headers-rp'];
  }
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
  $value = "";
  if(isset($settings['better-headers-fp-ac']) && $settings['better-headers-fp-ac']!=="") {
    $value = $settings['better-headers-fp-ac'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-ac" name="better-headers-settings[better-headers-fp-ac]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_als() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-als']) && $settings['better-headers-fp-als']!=="") {
    $value = $settings['better-headers-fp-als'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-als" name="better-headers-settings[better-headers-fp-als]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_ap() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-ap']) && $settings['better-headers-fp-ap']!=="") {
    $value = $settings['better-headers-fp-ap'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-ap" name="better-headers-settings[better-headers-fp-ap]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_cam() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-cam']) && $settings['better-headers-fp-cam']!=="") {
    $value = $settings['better-headers-fp-cam'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-cam" name="better-headers-settings[better-headers-fp-cam]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_dd() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-dd']) && $settings['better-headers-fp-dd']!=="") {
    $value = $settings['better-headers-fp-dd'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-dd" name="better-headers-settings[better-headers-fp-dd]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_em() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-em']) && $settings['better-headers-fp-em']!=="") {
    $value = $settings['better-headers-fp-em'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-em" name="better-headers-settings[better-headers-fp-em]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_fs() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-em']) && $settings['better-headers-fp-em']!=="") {
    $value = $settings['better-headers-fp-em'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-fs" name="better-headers-settings[better-headers-fp-fs]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_geo() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-geo']) && $settings['better-headers-fp-geo']!=="") {
    $value = $settings['better-headers-fp-geo'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-geo" name="better-headers-settings[better-headers-fp-geo]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_gy() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-gy']) && $settings['better-headers-fp-gy']!=="") {
    $value = $settings['better-headers-fp-gy'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-gy" name="better-headers-settings[better-headers-fp-gy]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_lif() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-lif']) && $settings['better-headers-fp-lif']!=="") {
    $value = $settings['better-headers-fp-lif'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-lif" name="better-headers-settings[better-headers-fp-lif]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
}
function better_head_fp_ma() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-ma']) && $settings['better-headers-fp-ma']!=="") {
    $value = $settings['better-headers-fp-ma'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-ma" name="better-headers-settings[better-headers-fp-ma]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
}
function better_head_fp_mic() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-mic']) && $settings['better-headers-fp-mic']!=="") {
    $value = $settings['better-headers-fp-mic'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-mic" name="better-headers-settings[better-headers-fp-mic]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
}
function better_head_fp_mid() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-mid']) && $settings['better-headers-fp-mid']!=="") {
    $value = $settings['better-headers-fp-mid'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-mid" name="better-headers-settings[better-headers-fp-mid]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_oi() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-oi']) && $settings['better-headers-fp-oi']!=="") {
    $value = $settings['better-headers-fp-oi'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-oi" name="better-headers-settings[better-headers-fp-oi]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_pay() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-pay']) && $settings['better-headers-fp-pay']!=="") {
    $value = $settings['better-headers-fp-pay'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-pay" name="better-headers-settings[better-headers-fp-pay]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_sp() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-sp']) && $settings['better-headers-fp-sp']!=="") {
    $value = $settings['better-headers-fp-sp'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-sp" name="better-headers-settings[better-headers-fp-sp]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_sx() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-sx']) && $settings['better-headers-fp-sx']!=="") {
    $value = $settings['better-headers-fp-sx'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-sx" name="better-headers-settings[better-headers-fp-sx]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_ui() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-ui']) && $settings['better-headers-fp-ui']!=="") {
    $value = $settings['better-headers-fp-ui'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-ui" name="better-headers-settings[better-headers-fp-ui]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_um() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-um']) && $settings['better-headers-fp-um']!=="") {
    $value = $settings['better-headers-fp-um'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-um" name="better-headers-settings[better-headers-fp-um]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_usb() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-usb']) && $settings['better-headers-fp-usb']!=="") {
    $value = $settings['better-headers-fp-usb'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-usb" name="better-headers-settings[better-headers-fp-usb]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_vib() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-vib']) && $settings['better-headers-fp-vib']!=="") {
    $value = $settings['better-headers-fp-vib'];
  }
  echo '<select class="better-headers-fp" id="better-headers-fp-vib" name="better-headers-settings[better-headers-fp-vib]">';
  echo better_head_fp_option('',$value,'-- Not set -- ');
  echo better_head_fp_option('none',$value,'Disabled');
  echo better_head_fp_option('self',$value,'Enabled (this domain only)');
  echo better_head_fp_option('all',$value,'Enabled (all domains)');
  echo '</select>';
}
function better_head_fp_vr() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-fp-vr']) && $settings['better-headers-fp-vr']!=="") {
    $value = $settings['better-headers-fp-vr'];
  }
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
function better_head_section_hsts() {
  echo '<hr>';
}

//defined output for settings
function better_head_hsts() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-hsts']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-hsts" name="better-headers-settings[better-headers-hsts]" type="checkbox" value="YES"' . $checked . '> Protect against downgrade attacks by setting the <strong>Strict-Transport-Security</strong> header';
}

//defined output for settings
function better_head_hsts_age() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-hsts-age']) && $settings['better-headers-hsts-age']!=="") {
    $value = $settings['better-headers-hsts-age'];
  }
  echo '<select class="better-headers-hsts" id="better-headers-hsts-age" name="better-headers-settings[better-headers-hsts-age]">';
  echo better_head_hsts_option('',$value,'Immediate');
  echo better_head_hsts_option('1',$value,'1 month');
  echo better_head_hsts_option('2',$value,'2 months');
  echo better_head_hsts_option('3',$value,'3 months');
  echo better_head_hsts_option('4',$value,'4 months');
  echo better_head_hsts_option('5',$value,'5 months');
  echo better_head_hsts_option('6',$value,'6 months');
  echo better_head_hsts_option('12',$value,'12 months');
  echo '</select>';
}
function better_head_hsts_option($opt,$val,$txt) {
  return '  <option value="' . $opt . '"' . ($opt===$val ? ' selected' : '') . '>' . $txt . '</option>';
}

//defined output for settings
function better_head_hsts_sub() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-hsts-sub']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-hsts-sub" name="better-headers-settings[better-headers-hsts-sub]" type="checkbox" value="YES"' . $checked . '> Every domain below this will inherit the same Strict Transport Security header';
}

//defined output for settings
function better_head_hsts_pre() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-hsts-pre']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-hsts-pre" name="better-headers-settings[better-headers-hsts-pre]" type="checkbox" value="YES"' . $checked . '> Permit browsers to preload Strict Transport Security configuration automatically';
}

//define output for settings section
function better_head_section_ect() {
  echo '<hr>';
}

//defined output for settings
function better_head_ect() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-ect']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-ect" name="better-headers-settings[better-headers-ect]" type="checkbox" value="YES"' . $checked . '> Protect against fraudulent certificates by setting the <strong>Expect-CT</strong> header';
}

//defined output for settings
function better_head_ect_age() {
	$settings = get_option('better-headers-settings');
  $value = "";
  if(isset($settings['better-headers-ect-age']) && $settings['better-headers-ect-age']!=="") {
    $value = $settings['better-headers-ect-age'];
  }
  echo '<select class="better-headers-ect" id="better-headers-ect-age" name="better-headers-settings[better-headers-ect-age]">';
  echo better_head_ect_option('',$value,'Immediate');
  echo better_head_ect_option('1',$value,'1 month');
  echo better_head_ect_option('2',$value,'2 months');
  echo better_head_ect_option('3',$value,'3 months');
  echo better_head_ect_option('4',$value,'4 months');
  echo better_head_ect_option('5',$value,'5 months');
  echo better_head_ect_option('6',$value,'6 months');
  echo better_head_ect_option('12',$value,'12 months');
  echo '</select>';
}
function better_head_ect_option($opt,$val,$txt) {
  return '  <option value="' . $opt . '"' . ($opt===$val ? ' selected' : '') . '>' . $txt . '</option>';
}

//defined output for settings
function better_head_ect_enf() {
	$settings = get_option('better-headers-settings');
	$checked = ($settings['better-headers-ect-enf']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-ect-enf" name="better-headers-settings[better-headers-ect-enf]" type="checkbox" value="YES"' . $checked . '> Enforce this policy (show an error instead of a warning)';
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

//defined output for settings
function better_head_xpcdp() {
  $settings = get_option('better-headers-settings');
  $checked = ($settings['better-headers-xpcdp']==="YES" ? " checked" : "");
  echo '<label><input id="better-headers-xpcdp" name="better-headers-settings[better-headers-xpcdp]" type="checkbox" value="YES"' . $checked . '> Protect against cross site Flash attacks by setting the <strong>X-Permitted-Cross-Domain-Policies</strong> header';
}

//add actions
if(is_admin()) {
  add_action('admin_menu','better_head_menus');
  add_action('admin_init','better_head_settings');
}

/*
--------------------- Add links to plugins page ---------------------
*/

//show settings link
function better_head_links($links) {
	$links[] = sprintf('<a href="%s">%s</a>',admin_url('options-general.php?page=better-headers-settings'),'Settings');
	return $links;
}

//add actions
if(is_admin()) {
  add_filter('plugin_action_links_'.plugin_basename(__FILE__),'better_head_links');
}

/*
----------------------------- The End ------------------------------
*/
