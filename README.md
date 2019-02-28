# Better Headers
This is a Wordpress plugin that makes it easy to set HTTP response headers that will improve the security of your website.

This plugin does not make any changes to your server configuration, such as the .htaccess file, but instead sends the headers as part of the Wordpress page response.  The reason for this is that many of them are not valid for assets such as stylesheets and images, but are sent anyway if the server configuration method is used.

Headers that can be set include...
* Feature-Policy
* Referrer-Policy
* Strict-Transport-Security
* X-Frame-Options
* X-Content-Type-Options
* X-XSS-Protection   
