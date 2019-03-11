# Better Headers
Improve the security of your website by easily setting HTTP response headers to enable browser protection

This plugin does not make any changes to your server configuration, such as the .htaccess file, but instead sends the headers as part of the Wordpress page response.  The reason for this is that many of them are not valid for assets such as stylesheets and images, but are sent anyway if the server configuration method is used.  

Unlike many security plugins, these headers are also sent for your admin panel, where security is arguably the most important.

Headers that can be set include...
* Feature-Policy
* Referrer-Policy
* Strict-Transport-Security
* X-Frame-Options
* X-Content-Type-Options
* X-XSS-Protection   
* X-Permitted-Cross-Domain-Policies
* Expect-CT
