=== LH Cookieless Domain ===
Contributors: shawfactor
Donate link: http://lhero.org/portfolio/lh-cookieless-domain/
Tags: cookies, cdn, subdomain, scripts, css
Requires at least: 3.0.
Tested up to: 5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Filters the css and script source attribute and moves their domain to one of your choosing

== Description ==

I wrote this plugin as I wanted a cleaner more flexible way of serving my css and script files from a cookieles domain.

== Frequently Asked Questions ==

= How does this plugin behave when network activated? =
When network activated the admin is moved under network admin and is stored as a site_optiuon

== Installation ==

1. Upload the entire `lh-cookieless-domain` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. If local site activated navigate to to Settings->Cookieless Domain, if network activated navigate to Network Admin->Settings->Cookieless Domain
1. Add the url of the cookieless domain or subdomain in th settinsg field


== Changelog ==

**1.00 September 20, 2018**  
Initial release.

**1.01 October 09, 2018**  
Singleton Pattern.

**1.02 January 15, 2019**  
Filter wp_get_attachment_url on front end.