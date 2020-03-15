=== Plugin Name ===
Contributors: devworks, isoftware, ikappas, akkis
Tags: forms, gravity, gravityforms, html5, validation
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: 2.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds native HTML5 validation support to Gravity Forms' fields.

== Description ==

Adds native HTML5 validation support to the Gravity Forms plugin.

It supports both standard and paginated forms.

= Supported Standard Fields =
* Single Line Text
* Paragraph Text
* Drop Down
* Multi Select
* Checkboxes
* Number
* Radio Buttons

= Supported Advanced Fields =
* Name
* Date
* Time
* Phone
* Address
* Website
* Username
* Password
* Email
* File Upload

= Supported Post Fields =
* Title
* Body
* Excerpt
* Tags
* Category
* Post Image
* Custom Field

= Supported Pricing Fields =
* Product
* Quantity
* Credit Card

= Supported Hooks =
* gform_name_prefix_required
* gform_name_middle_required
* gform_name_suffix_required
* gform_address_street2_required

These hooks can be used to force html5 validation on these subfields.

Example usage:

The following would apply your function to all forms.
add_filter( 'gform_name_prefix_required', 'your_function_name', 10, 2 );

To target a specific form append the form id to the hook name. (format: gform_password_FORMID)
add_filter( 'gform_name_prefix_required_6', 'your_function_name', 10, 2 );

The return value is expected to be a boolean value; As such you can also use them with "__return_true".
add_filter( 'gform_address_street2_required', '__return_true' );

= Requirements =
This plugin requires prior installation and activation of [Gravity Forms](http://www.gravityforms.com/) plugin by [Rocketgenius](http://www.rocketgenius.com/) ver. 1.9 and above.

= Tested =
Up to Gravity Forms plugin ver. 1.9.17.6

== Installation ==

1. Download the gravityforms-html5-validation.zip file to your local machine.
2. Either use the automatic plugin installer (Plugins - Add New) or Unzip the file and upload the isw-blocks folder to your /wp-content/plugins/ directory.
3. Activate the plugin through the Plugins menu
4. Visit the Gravity Forms general settings page ( Forms -> Settings ) and make sure that Output HTML5 option is set to yes.
5. All fields configured as "required" will now use native html5 validation.

== Changelog ==

= 2.4.2 =
* Fix DOM class names and methods.

= 2.4.1 =
* Fix script debug flag.

= 2.4 =
* Add support for various fields.
* Add support for paginated forms.

= 2.3 =
* Initial WordPress.org Release
