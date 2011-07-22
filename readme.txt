=== BrowserID ===
Contributors: Marcel Bokhorst, M66B
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AJSBB7DGNA3MJ&lc=US&item_name=BrowserID%20WordPress%20plugin&item_number=Marcel%20Bokhorst&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: security, admin, authentication, access, widget, login, shortcode
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 0.15

Implementation of Mozilla BrowserID for WordPress

== Description ==

"As a user of BrowserID, you confirm your email addresses once. Then, you can sign into any web site that supports BrowserID with just two clicks."

This plugin adds a BrowserID login button as an additional way to login to your login page.
There is also a widget, shortcode and template tag. It is possible to customize the login and logout button/link.

**The BrowserID protocol has been changed, upgrade to at least version 0.15 of the plugin !**

[BrowserID](https://browserid.org/ "BrowserID") is an open source experiment from the [Identity Team](http://identity.mozilla.com/ "Identity Team") at [Mozilla Labs](https://mozillalabs.com/ "Mozilla Labs").

**BrowserID and this plugin are experimental !**

Please report any issue you have with this plugin on the [support page](http://blog.bokhorst.biz/5379/computers-en-internet/wordpress-plugin-browserid/ "Marcel's weblog"), so I can at least try to fix it.
If you rate this plugin low, please [let me know why](http://blog.bokhorst.biz/5379/computers-en-internet/wordpress-plugin-browserid/#respond "Marcel's weblog").

See my [other plugins](http://wordpress.org/extend/plugins/profile/m66b "Marcel Bokhorst").

== Installation ==

*Using the WordPress dashboard*

1. Login to your weblog
1. Go to Plugins
1. Select Add New
1. Search for BrowserID
1. Select Install
1. Select Install Now
1. Select Activate Plugin

*Manual*

1. Download and unzip the plugin
1. Upload the entire *browserid/* directory to the */wp-content/plugins/* directory
1. Activate the plugin through the Plugins menu in WordPress

== Frequently Asked Questions ==

= What is 'Custom login HTML for?' =

Try putting the following into this option:

`<img src="https://browserid.org/i/browserid_logo_sm.png" />`

Now you will see the BrowserID logo instead of the login button.

= Which server does verify the assertion? =

The assertion is verified by the server at https://browserid.org/verify.

= I get 'SSL certificate problem, verify that the CA cert is OK' =

Your hosting provider should take a look at the SSL certificates.
You can check the option *Do not verify SSL certificate*, but please realize this isn't entirely safe.

= I get 'Bad Gateway' =

The browserid.org service is still in testing phase.
Trying again may help.

= I get 'Login failed' =

Only users that registered before can login.
The e-mail address used for BrowserID should match the e-mail address registered with.

= I get 'Verification failed' =

Are you cheating?
If there isn't an error message, turn on debug mode to see the complete response.

= I get 'Verification void' =

Something went terribly wrong.
If there isn't an error message, turn on debug mode to see the complete response.

= I get 'Verification invalid' =

Maybe the time of your hosting server is incorrect.
You could check the option *Do not check valid until time* to solve this.

= Where can I ask questions, report bugs and request features? =

You can write comments on the [support page](http://blog.bokhorst.biz/5379/computers-en-internet/wordpress-plugin-browserid/ "Marcel's weblog").

== Screenshots ==

1. BrowserID logo
1. BrowserID login button
1. WordPress login dialog

== Changelog ==

= Next release =
* Development version is [here](http://wordpress.org/extend/plugins/browserid/download/ "Development version")

= 0.15 =
* **Protocol change**: verification with POST instead of GET
* Improvement: no logout link on login page

= 0.14 =
* New feature: option to redirect to set URL after login

= 0.13 =
* Bug fix: correctly handling WordPress errors

= 0.12 =
* Improvement: check issuer
* Improvement: more debug info

= 0.11 =
* Fixed IDN

= 0.9 =
* New feature: shortcode for login/out button/link: *[browserid_loginout]*
* New feature: template tag for login/out button/link: *browserid_loginout*
* Updated Dutch and Flemish translations
* Updated Norwegian (nb\_NO) translation by [Stein Ivar Johnsen](http://www.idyrøy.no/ "Stein Ivar Johnsen")

= 0.8 =
* New feature: option to set verification server
* Improvement: checking assertion valid until time (can be switch off with an option)
* Improvement: using [idn_to_utf8](http://php.net/manual/en/function.idn-to-utf8.php "idn_to_utf8") when available
* Updated FAQ
* Updated Dutch and Flemish translations

= 0.7 =
* New feature: support for *Remember Me* check box
* Updated Norwegian (nb\_NO) translation by [Stein Ivar Johnsen](http://www.idyrøy.no/ "Stein Ivar Johnsen")

= 0.6 =
* New feature: option *Do not verify SSL certificate*
* Updated Dutch and Flemish translations

= 0.5 =
* Improvement: more debug info
* Tested with WordPress 3.1

= 0.4 =
* Bug fix: using site URL in stead of home URL
* Updated FAQ

= 0.3 =
* Improvement: better error messages
* Improvement: more debug info
* Improvement: support for [internationalized domain names](http://en.wikipedia.org/wiki/Internationalized_domain_name "IDN")
* Updated FAQ
* Added Norwegian (nb\_NO) translation by [Stein Ivar Johnsen](http://www.idyrøy.no/ "Stein Ivar Johnsen"), thanks!

= 0.2 =
* Bugfix: custom HTML for login page
* Added Flemish translation
* Updated Dutch translation

= 0.1 =
* Initial version

= 0.0 =
* Development version

== Upgrade Notice ==

= 0.15 =
Protocol change! Verification with POST instead of GET

= 0.14 =
One new feature

= 0.13 =
One bugfix

= 0.12 =
Two improvements

= 0.11 =
Fixed IDN

= 0.9 =
Two new features, translation update

= 0.8 =
One new feature, two improvements

= 0.7 =
One new feature

= 0.6 =
One new feature

= 0.5 =
One improvement

= 0.4 =
Bugfix

= 0.3 =
Three improvements

= 0.2 =
One bugfix

= 0.1 =
First public release

== Acknowledgments ==

This plugin uses:

* The client side [BrowserID script](https://browserid.org/include.js "BrowserID script")
