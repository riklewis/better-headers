=== Better Headers ===
Contributors: bettersecurity, riklewis
Tags: better, security, headers, policy, options, http, response
Requires at least: 3.5
Tested up to: 5.1
Stable tag: 1.1
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Improve the security of your website by easily setting HTTP response headers to enable browser protection

== Description ==

This plugin does not make any changes to your server configuration, such as the .htaccess file, but instead sends the headers as part of the Wordpress page response.  The reason for this is that many of them are not valid for assets such as stylesheets and images, but are sent anyway if the server configuration method is used.

Unlike many security plugins, these headers are also sent for your admin panel, where security is arguably the most important.

Headers that can be set include...
* Feature-Policy
* Referrer-Policy
* Strict-Transport-Security
* X-Frame-Options
* X-Content-Type-Options
* X-XSS-Protection
