=== BrowserID ===
Contributors: Marcel Bokhorst
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AJSBB7DGNA3MJ&lc=US&item_name=BrowserID%20WordPress%20plugin&item_number=Marcel%20Bokhorst&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: security, admin, authentication, access, widget, login
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 0.1

Implementation of Mozilla BrowserID for WordPress

== Description ==

[BrowserID](https://browserid.org/ "BrowserID") is an open source experiment from the [Identity Team](http://identity.mozilla.com/ "Identity Team") at [Mozilla Labs](https://mozillalabs.com/ "Mozilla Labs").
This plugin adds a BrowserID login button to your login page.
There is also a widget with a login button, which you can add to for example your side bar.
It is possible to customize the login and logout button/link.

**BrowserID and this plugin are experimental!**

Please report any issue you have with this plugin on the [forum page](http://wordpress.org/tags/browserid?forum_id=10 "Forum"), so I can at least try to fix it.
If you rate this plugin low, please [let me know why](http://blog.bokhorst.biz/contact/ "Marcel's weblog").

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

*Setup*

Drag the BrowserID widget to for example your side bar. The widget contains the login button.

== Frequently Asked Questions ==

= Which server does verify the assertion? =

The assertion is verified by the server at https://browserid.org/verify.

= Where can I ask questions, report bugs and request features? =

You can ask for support on the [forum page](http://wordpress.org/tags/browserid?forum_id=10 "Forum").

== Screenshots ==

1. BrowserID logo
1. BrowserID login button

== Changelog ==

= 0.1 =
* Initial version

= 0.0 =
* Development version

== Upgrade Notice ==

= 0.1 =
First public release

== Acknowledgments ==

This plugin uses:

* The client side [BrowserID script](https://browserid.org/include.js "BrowserID script").
