<?php
/**
 * Plugin Name: Gravity Forms HTML5 Validation
 * Plugin URI: https://gitlab.devworks.gr/plugins/gravityforms-html5-validation
 * Description: Adds native HTML5 validation support to Gravity Forms' fields. Javascript & jQuery are required.
 * Version: 2.4.2
 * Author: DevWorks
 * Author URI: http://www.devworks.gr
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: gf-html5-validation
 * Domain Path: /languages/
 *
 * @author  DevWorks
 * @package GFHtml5Validation
 */

namespace DevWorks;

use \DOMDocument;
use \DOMXpath;
use \RGFormsModel;
use \GFCommon;

/**
 * Gravity Forms Html5 Validation class.
 */
class GFHtml5Validation {

	/**
	 * Holds the plugin version.
	 * @var string
	 */
	protected $version = '2.4.2';

	/**
	 * Holds the plugin title.
	 * @var string
	 */
	protected $title = 'Gravity Forms HTML5 Validation';

	/**
	 * Holds the plugin short title.
	 * @var string
	 */
	protected $short_title = 'HTML5 Validation';

	/**
	 * Holds the plugin text domain.
	 * @var string
	 */
	protected $textdomain = 'gf-html5-validation';

	/**
	 * Holds the minimum WordPress version.
	 * @var string
	 */
	protected $min_wordpress_version    = '3.5';

	/**
	 * Holds the minimum Gravity Forms version.
	 * @var string
	 */
	protected $min_gravityforms_version = '1.9';

	/**
	 * Holds a boolean flag indicating whether debugin is enabled.
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * Holds the plugin localized strings.
	 * @var object
	 */
	protected $l10n;

	/**
	 * Holds a list of plugin warnings.
	 * @var array
	 */
	protected $warnings = array();

	/**
	 * Class constructor which hooks the instance into the WordPress init action
	 */
	public function __construct( $debug = false ) {
		$this->debug = $debug;
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Localize the plugin.
	 *
	 * @return void
	 */
	public function localize() {

		// Load plugin text domain.
		load_plugin_textdomain( $this->textdomain, false, '/languages' );

		// Setup our localized strings.
		$this->l10n = (object) array(

			'warnings' => array(

				'wp_version' => sprintf(
					esc_html__( 'Gravity Forms HTML5 Validation requires WordPress %s or greater. You must upgrade WordPress in order to use Gravity Forms HTML5 Validation', $this->textdomain ),
					esc_html( $this->min_wordpress_version )
				),

				'gf_missing' => sprintf(
					esc_html__( 'Gravity Forms HTML5 Validation is enabled but not effective. It requires %s in order to work.', $this->textdomain ),
					sprintf( '<a target="_blank" href="%s">%s</a>', 'http://www.gravityforms.com/' , 'Gravity Forms' )
				),

				'gf_version'  => sprintf(
					esc_html__( 'Gravity Forms HTML5 Validation is enabled but not effective. It requires %s version <strong>%s</strong> and above in order to work.', $this->textdomain ),
					sprintf( '<a target="_blank" href="%s">%s</a>', 'http://www.gravityforms.com/', 'Gravity Forms' ),
					$this->min_gravityforms_version
				),

				'gf_html5_output' => sprintf(
					esc_html__( 'Gravity Forms HTML5 Validation is enabled but not effective. It requires %s setting to be enabled in %s page.', $this->textdomain ),
					sprintf( '<strong>%s</strong>', esc_html__( 'Output HTML5', 'gravityforms' ) ),
					sprintf( '<a href="%s">%s</a>', esc_attr( admin_url( 'admin.php?page=gf_settings' ) ), esc_html__( 'General Settings', 'gravityforms' ) )
				),
			),

		);

	}

	#region Initialization Methods

	/**
	 * Plugin starting point. Handles hooks and loading of language files.
	 *
	 * @return bool True if the plugin initialized; Otherwise false.
	 */
	public function init() {

		// Localize plugin.
		$this->localize();

		// We use this hook to render our warnings.
		add_action( 'admin_notices', array( $this, 'render_warnings' ) );

		// Determine whether Wordpress version is supported
		if ( false === $this->is_wordpress_supported() ) {
			if ( current_user_can( 'install_plugins' ) ) {
				$this->add_warning( $this->l10n->warnings['wp_version'], 'plugins' );
			}
			return false;
		}

		// Determine whether Gravity Forms is installed.
		if ( false === $this->is_gravityforms_installed() ) {
			if ( current_user_can( 'install_plugins' ) ) {
				$this->add_warning( $this->l10n->warnings['gf_missing'], 'plugins' );
			}
			return false;
		}

		// Determine whether Gravity Forms is supported.
		if ( false === $this->is_gravityforms_supported() ) {
			if ( current_user_can( 'install_plugins' ) ) {
				$this->add_warning( $this->l10n->warnings['gf_version'], 'plugins' );
			}
			return false;
		}

		// We are good to go.
		if ( defined( 'RG_CURRENT_PAGE' ) && RG_CURRENT_PAGE === 'admin-ajax.php' ) {
			$this->init_ajax();
		} else if ( is_admin() ) {
			$this->init_admin();
		} else {
			$this->init_frontend();
		}

		return true;
	}

	/**
	 * Add tasks or filters here that you want to perform both in the backend and frontend and for ajax requests.
	 *
	 * @return void
	 */
	protected function init_ajax() {
		// NOOP: tbd
	}

	/**
	 * Add tasks or filters here that you want to perform only in admin.
	 *
	 * @return void
	 */
	protected function init_admin() {

		// Check if we are currently on the form editor page.
		if ( $this->is_form_editor() ) {

			// We use this to generate form editor warnings.
			if ( false === $this->is_gravityforms_html5_enabled() ) {
				$this->add_warning( $this->l10n->warnings['gf_html5_output'] );
			}
		}
	}

	/**
	 * Add tasks or filters here that you want to perform only in the front end.
	 *
	 * @return void
	 */
	protected function init_frontend() {

		// Enqueue frontend scripts.
		add_action( 'gform_enqueue_scripts', array( $this, 'gform_enqueue_scripts' ), 10, 2 );

		// We use this filter to manipulate the required field attributes.
		add_filter( 'gform_field_content', array( $this, 'gform_field_content' ), 10, 3 );

		// We use this filter to manipulate the next button on paginated forms.
		add_filter( 'gform_next_button', array( $this, 'gform_next_button' ), 10, 2 );

		// We use this filter to manipulate the submit button on paginated forms.
		add_filter( 'gform_submit_button', array( $this, 'gform_submit_button' ), 10, 2 );
	}

	#endregion Initialization Methods

	#region Action/Filter Target Methods

	/**
	 * Target of gform_field_content both on form editor & frontend.
	 *
	 * @param string $field_content        The current field content.
	 * @param array  $field                The current field.
	 * @param bool   $force_frontend_label A boolean value indicating whether to force the front end label.
	 *
	 * @return string
	 */
	public function gform_field_content( $field_content, $field, $force_frontend_label ) {

		if ( ! isset( $field_content ) || ! $this->is_gravityforms_html5_enabled() || ! isset( $field ) || ! array_key_exists( 'formId', $field ) ) {
			return $field_content;
		}

		if ( false === rgar( $field, 'isRequired', false ) ) {
			return $field_content;
		}

		// Current Field Attributes.
		$form_id = $field['formId'];
		$field_id = $field['id'];
		$field_type = rgar( $field, 'inputType', $field['type'] );

		$field_uid = "input_{$form_id}_{$field_id}";

		// Handle Field Content Encoding.
		$encoding = mb_detect_encoding( $field_content );
		if ( 'UTF-8' !== $encoding ) {
			$field_content = mb_convert_encoding( $field_content, 'UTF-8' ); }
		$field_content_wrapped = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body>$field_content</body></html>";

		// Disable libxml error output while we are processing html.
		$use_errors = libxml_use_internal_errors( true );

		// Prepare Dom Document and XPath.
		$doc = new DOMDocument();
		$doc->preserveWhiteSpace = false; // needs to be before loading, to have any effect.
		$doc->formatOutput = false;
		// @codingStandardsIgnoreStart
		@$doc->loadHTML( $field_content_wrapped );
		// @codingStandardsIgnoreEnd
		$xpath = new DOMXpath( $doc );

		switch ( $field_type ) {

			case 'text':
			case 'textarea':
			case 'phone':
			case 'website':
			case 'number':
			case 'select':
			case 'multiselect':
			case 'price':
			case 'username':
			case 'file':
			case 'fileupload':
			case 'post_title':
			case 'post_content':
			case 'post_excerpt':
			case 'post_tags':
			case 'post_image':
			case 'post_custom':

				$field_type_map = array(
					'select' => 'select',
					'multiselect' => 'select',
					'textarea' => 'textarea',
					'post_content' => 'textarea',
					'post_excerpt' => 'textarea',
				);

				$lookup_type = array_key_exists( $field_type, $field_type_map ) ? $field_type_map[ $field_type ] : 'input' ;

				if ( $element = (( $result = $xpath->query( "//{$lookup_type}[@id='{$field_uid}']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				break; // End text, textarea, phone, website, number, select, multiselect, price, username, file, fileupload, post_title, post_content, post_excerpt, post_tags, post_image, post_custom field.

			case 'checkbox':

				$value = rgar( $field, 'value' );
				if ( empty( $value ) ) {
					$inputs = rgar( $field, 'inputs', array() );
					foreach ( $inputs as $input ) {
						$input_id = rgar( $input, 'id', false );
						if ( is_string( $input_id ) ) {
							if ( $element = ( ( $result = $xpath->query( "//input[@name='input_{$input_id}']" ) ) ? $result->item( 0 ) : null ) ) {
								$element->setAttribute( 'required', 'required' );
							}
						}
					}
				}

				break; // End checkbox field;

			case 'radio':

				$choices = rgar( $field, 'choices', array() );
				for ( $i = 1; $i <= count( $choices ); $i++ ) {
					if ( $element = ( ( $result = $xpath->query( "//input[@id='choice_{$form_id}_{$field_id}_{$i}']" ) ) ? $result->item( 0 ) : null ) ) {
						$element->setAttribute( 'required', 'required' );
					}
				}

				break; // End checkbox field;

			case 'email':

				// Process email.
				if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process email confirmation.
				$email_confirm_enabled = rgar( $field, 'emailConfirmEnabled', false );
				if ( $email_confirm_enabled ) {
					if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_2']" )) ? $result->item( 0 ) : null ) ) {
						$element->setAttribute( 'required', 'required' );
					}
				}

				break; // End email field.

			case 'password':

				// Process password.
				if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process password confirmation.
				if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_2']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				break; // End password field.

			case 'name':

				$name_format = rgar( $field, 'nameFormat', 'normal' );
				switch ( $name_format ) {

					case 'simple':

						if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}']" )) ? $result->item( 0 ) : null ) ) {
							$element->setAttribute( 'required', 'required' );
						}

						break; // End simple format.

					case 'normal':
					case 'extended':

						// Process name prefix.
						$name_prefix_required = apply_filters( "gform_name_prefix_required_{$form_id}", apply_filters( 'gform_name_prefix_required', false, $form_id ), $form_id );
						if ( 'extended' === $name_format && $name_prefix_required ) {
							if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_2']" )) ? $result->item( 0 ) : null ) ) {
								$element->setAttribute( 'required', 'required' );
							}
						}

						// Process name first.
						if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_3']" )) ? $result->item( 0 ) : null ) ) {
							$element->setAttribute( 'required', 'required' );
						}

						// Process name last.
						if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_6']" )) ? $result->item( 0 ) : null ) ) {
							$element->setAttribute( 'required', 'required' );
						}

						// Process name suffix.
						$name_suffix_required = apply_filters( "gform_name_suffix_required_{$form_id}", apply_filters( 'gform_name_suffix_required', false, $form_id ), $form_id );
						if ( 'extended' === $name_format && $name_suffix_required ) {
							if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_8']" )) ? $result->item( 0 ) : null ) ) {
								$element->setAttribute( 'required', 'required' );
							}
						}

						break; // End normal and extended format.

					case 'advanced':

						$filters = array(
							"{$field_id}.2" => 'gform_name_prefix_required',
							"{$field_id}.4" => 'gform_name_middle_required',
							"{$field_id}.8" => 'gform_name_suffix_required',
						);

						$inputs = rgar( $field, 'inputs', array() );
						foreach ( $inputs as $input ) {
							$is_hidden = rgar( $input, 'isHidden', false );
							if ( false === $is_hidden ) {
								$input_id = rgar( $input, 'id', false );
								if ( is_string( $input_id ) ) {
									$element_required = array_key_exists( $input_id, $filters ) ? apply_filters( $filters[ $input_id ] . '_' . $form_id, apply_filters( $filters[ $input_id ], false, $form_id ), $form_id ) : true;
									if ( $element_required ) {
										if ( $element = ( ( $result = $xpath->query( "//input[@name='input_{$input_id}']" ) ) ? $result->item( 0 ) : null ) ) {
											$element->setAttribute( 'required', 'required' );
										}
									}
								}
							}
						}
						break; // End advanced format.

				} // End switch.

				break; // End name field.

			case 'address':

				// Process address line 1.
				if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_1']" )) ? $result->item( 0 ) : null) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process address line 2.
				$street2_hidden = rgar( $field, 'hideAddress2', false );
				$street2_required = apply_filters( "gform_address_street2_required_{$form_id}", apply_filters( 'gform_address_street2_required', false, $form_id ), $form_id );
				if ( false === $street2_hidden && true === $street2_required ) {
					if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_2']" )) ? $result->item( 0 ) : null) ) {
						$element->setAttribute( 'required', 'required' );
					}
				}

				// Process address city.
				if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_3']" )) ? $result->item( 0 ) : null) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process address state / province.
				$state_hidden = rgar( $field, 'hideState', false );
				if ( false === $state_hidden ) {
					if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_4']" )) ? $result->item( 0 ) : null) ) {
						$element->setAttribute( 'required', 'required' );
					}
				}

				// Process address zip / postal.
				if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_5']" )) ? $result->item( 0 ) : null) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process address country.
				$country_hidden = rgar( $field, 'hideCountry', false );
				if ( false === $country_hidden ) {
					if ( $element = (($result = $xpath->query( "//select[@id='{$field_uid}_6']" )) ? $result->item( 0 ) : null) ) {
						$element->setAttribute( 'required', 'required' );
					}
				}

				break; // End address field.

			case 'date':

				$date_type = rgar( $field, 'dateType', 'datepicker' );
				switch ( $date_type ) {

					case 'datepicker':

						// Process date picker.
						if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}']" )) ? $result->item( 0 ) : null) ) {
							$element->setAttribute( 'required', 'required' );
						}

						break; // End datepicker type.

					case 'datefield':

						// Process date month.
						if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_1']" )) ? $result->item( 0 ) : null) ) {
							$element->setAttribute( 'required', 'required' );
						}

						// Process date day.
						if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_2']" )) ? $result->item( 0 ) : null) ) {
							$element->setAttribute( 'required', 'required' );
						}

						// Process date year.
						if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_3']" )) ? $result->item( 0 ) : null) ) {
							$element->setAttribute( 'required', 'required' );
						}

						break; // End datefield type.

				} // End switch.

				break; // End date field.

			case 'time':

				// Process time hour.
				if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_1']" )) ? $result->item( 0 ) : null) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process time minute.
				if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}_2']" )) ? $result->item( 0 ) : null) ) {
					$element->setAttribute( 'required', 'required' );
				}

				break; // End time field.

			case 'product':
			case 'singleproduct':
			case 'calculation':

				$product_disable_quantity = rgar( $field, 'disableQuantity', false );
				if ( ! $product_disable_quantity ) {
					if ( $element = (($result = $xpath->query( "//input[@name='input_{$field_id}.3']" )) ? $result->item( 0 ) : null) ) {
						$element->setAttribute( 'required', 'required' );
					}
				}

				break; // End product, singleproduct & caclulation field.

			case 'quantity':

				$quantity_type = rgar( $field, 'inputType', 'number' );
				switch ( $quantity_type ) {

					case 'number':

						// Process Product Amount.
						if ( $element = (($result = $xpath->query( "//input[@id='{$field_uid}']" )) ? $result->item( 0 ) : null) ) {
							$element->setAttribute( 'required', 'required' );
						}

						break; // End number type.
				}

				break; // End quantity field.

			case 'creditcard':

				// Process card number.
				if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_1']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process expiration month.
				if ( $element = (( $result = $xpath->query( "//select[@id='{$field_uid}_2_month']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process expiration year.
				if ( $element = (( $result = $xpath->query( "//select[@id='{$field_uid}_2_year']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process security code.
				if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_3']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}

				// Process card holder name.
				if ( $element = (( $result = $xpath->query( "//input[@id='{$field_uid}_5']" )) ? $result->item( 0 ) : null ) ) {
					$element->setAttribute( 'required', 'required' );
				}
				break;
		}

		$field_content = $doc->saveHTML();

		// Remove our html wrapper from processed field.
		$field_content = str_replace(
			array( '<html>', '</html>', '<head>', '</head>', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">', '<body>', '</body>' ),
			array( '', '', '', '', '', '', '' ),
			$field_content
		);

		$field_content = trim( preg_replace( '/^<!DOCTYPE.+?>/', '', $field_content ) );

		// Check if we are currently on ajax and fix double quotes to single.
		if ( defined( 'RG_CURRENT_PAGE' ) && RG_CURRENT_PAGE === 'admin-ajax.php' ) {

			// Replace non escaped double quotes with single quotes for ajax support.
			$matches = array();
			preg_match_all( '/"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"/s', $field_content, $matches );
			if ( count( $matches[0] ) > 0 ) {
				foreach ( $matches[0] as $match ) {
					$replace = "'" . substr( $match, 1 , strlen( $match ) - 2 ) . "'";
					$field_content = str_replace( $match, $replace, $field_content );
				}
			}
		}

		// Restore libxml error handling.
		libxml_use_internal_errors( $use_errors );

		return $field_content;
	}

	/**
	 * Target of wp_enqueue_scripts hooks.
	 *
	 * @param mixed $form The current form object.
	 * @param bool  $ajax A boolean value indicating whether this is an ajax call.
	 *
	 * @return void
	 */
	public function gform_enqueue_scripts( $form, $ajax = false ) {
		if ( $this->is_gravityforms_html5_enabled() ) {

			// Determine whether to use minified script versions.
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Register our scripts and styles
			wp_enqueue_script(
				'gravityforms-html5-validation',
				$this->get_base_url( "/js/gravityforms-html5-validation{$min}.js" ),
				array( 'jquery' ),
				$this->version,
				false
			);

		}
	}

	/**
	 * Target of gform_next_button.
	 *
	 * Manipulate next buttons on paginated forms.
	 *
	 * @param string $button The next button content.
	 * @param array  $form   The current form object.
	 *
	 * @return string
	 */
	public function gform_next_button( $button, $form ) {
		if ( $this->is_gravityforms_html5_enabled() && rgar( $form, 'pagination', false ) ) {
			$button = preg_replace_callback( "/(.*onclick=')(.*)('.*)/", function( $m ) use ( $form ) {

				$p = array();
				preg_match( '/jQuery\("#gform_target_page_number_'.$form['id'].'"\).val\("(.*)"\);/', $m[2], $p );

				return $m[1].$p[0].' Html5ValidatePage('.$form['id'].', jQuery(this).closest(".gform_page"));'.$m[3];

			}, $button );
		}
		return $button;
	}

	/**
	 * Target of gform_submit_button.
	 *
	 * Manipulate next buttons on paginated forms.
	 *
	 * @param string $button The next button content.
	 * @param array  $form   The current form object.
	 *
	 * @return string
	 */
	public function gform_submit_button( $button, $form ) {
		if ( $this->is_gravityforms_html5_enabled() && rgar( $form, 'pagination', false ) ) {
			$button = preg_replace_callback( "/(.*onclick=')(.*)('.*)/", function( $m ) use ( $form ) {

				return $m[1].'if(window["gf_submitting_'.$form['id'].'"]){return false;} if(Html5ValidatePage('.$form['id'].', jQuery(this).closest(".gform_page")) ){window["gf_submitting_'.$form['id'].'"]=true;}'.$m[3];

			}, $button );
		}
		return $button;
	}

	/**
	 * Target of admin_notices action.
	 *
	 * @return void
	 */
	public function render_warnings() {

		$screen = get_current_screen();
		$messages = array();

		if ( array_key_exists( 'all', $this->warnings ) ) {
			$messages = array_merge( $messages, $this->warnings['all'] );
		}

		if ( array_key_exists( $screen->id, $this->warnings ) ) {
			$messages = array_merge( $messages, $this->warnings[ $screen->id ] );
		}

		foreach ( $messages as $message ) {
			echo '<div class="message error"><p>'.$message.'</p></div>';
		}
	}

	/**
	 * Target of admin_enqueue_scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts_admin() {
		// NOOP: To be used later.
	}

	#endregion Action/Filter Target Methods

	#region Helper Methods

	/**
	 * Add a warning message.
	 *
	 * @param string $message The message to show.
	 * @param string $page    Optional. The target page. Defaults to all.
	 *
	 * @return void
	 */
	protected function add_warning( $message, $page = 'all' ) {
		if ( is_string( $message ) && ! empty( $message ) ) {

			if ( empty( $page ) ) {
				$page = 'all';
			}

			if ( ! isset( $this->warnings[ $page ] ) ) {
				$this->warnings[ $page ] = array();
			}

			$this->warnings[ $page ][] = $message;
		}
	}

	/**
	 * Returns the url of the root folder of the current Add-On.
	 *
	 * @param string $extra_path Optional. Extra path appended to the base path. Default is empty string.
	 *
	 * @return string
	 */
	protected function get_base_url( $extra_path = '' ) {

		if ( ! is_string( $extra_path ) ) {
			$extra_path = '';
		}

		$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );

		if ( ! empty( $extra_path ) ) {
			$extra_path = trim( $extra_path, DIRECTORY_SEPARATOR );
			return join( DIRECTORY_SEPARATOR, array( $plugin_url, $extra_path ) );
		}

		return $plugin_url;

	}

	/**
	 * Returns the path of the root folder of the current Add-On.
	 *
	 * @param string $extra_path Optional. Extra path appended to the base path. Default is empty string.
	 *
	 * @return string
	 */
	protected function get_base_path( $extra_path = '' ) {

		if ( ! is_string( $extra_path ) ) {
			$extra_path = '';
		}

		$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

		if ( ! empty( $extra_path ) ) {
			$extra_path = trim( $extra_path, DIRECTORY_SEPARATOR );
			return join( DIRECTORY_SEPARATOR, array( $plugin_path, $extra_path ) );
		}

		return $plugin_path;
	}

	/**
	 * Returns an html formatted form tooltip.
	 *
	 * @param string $title The tooltip title.
	 * @param string $description The tooltip title.
	 *
	 * @return string
	 */
	protected function generate_tooltip( $title, $description ) {
		$tooltip = '';
		if ( is_string( $title ) ) {
			$tooltip .= "<h6>{$title}</h6>";
		}
		if ( is_string( $description ) ) {
			$tooltip .= $description;
		}
		return $tooltip;
	}

	/**
	 * Get the contents of a template file.
	 *
	 * @param string $filename  The template part filename.
	 * @param array  $data      The template part data.
	 *
	 * @return string The rendered template part.
	 */
	protected function get_template_part( $filename, array $data = array() ) {

		extract( $data );

		ob_start();
		include( $this->get_base_path( 'templates' . DIRECTORY_SEPARATOR . $filename ) );
		$template = ob_get_contents();
		ob_end_clean();

		return $template;
	}

	/**
	 * Determine whether we are currently in the form editor page.
	 *
	 * @return bool True if the current page is the form editor page; Otherwise, false.
	 */
	public function is_form_editor() {
		if ( class_exists( '\GFCommon' ) ) {
			return \GFCommon::is_form_editor();
		}
		return false;
	}

	/**
	 * Determine whether the Gravity Forms is installed.
	 *
	 * @return bool True if Gravity Forms is installed; Otherwise false.
	 */
	public function is_gravityforms_installed() {
		return class_exists( '\GFForms' );
	}

	/**
	 * Determine whether the Gravity Forms HTML5 output is enabled.
	 *
	 * @return bool True if Gravity Forms HTML5 output is enabled; Otherwise false.
	 */
	public function is_gravityforms_html5_enabled() {
		if ( class_exists( '\RGFormsModel' ) ) {
			return (bool) \RGFormsModel::is_html5_enabled();
		}
		return false;
	}

	/**
	 * Determine whether the current version of Gravity Forms is supported.
	 *
	 * @param string $min_version Optional. The minimum version to check. Defaults to the plugin minimum Gravity Forms version if set.
	 *
	 * @return bool True if the current version of Gravity Forms is supported; Otherwise false.
	 */
	public function is_gravityforms_supported( $min_version = '' ) {

		if ( isset( $this->min_gravityforms_version ) && empty( $min_version ) ) {
			$min_version = $this->min_gravityforms_version;
		}

		if ( empty( $min_version ) ) {
			return true;
		}

		if ( class_exists( '\GFCommon' ) ) {
			return version_compare( \GFCommon::$version, $min_version, '>=' );
		}

		return false;
	}

	/**
	 * Determine whether the current version of WordPress is supported.
	 *
	 * @param string $min_version Optional; The minimum version to check. Defaults to the plugin minimum WordPress version if set.
	 *
	 * @return bool True if the current version of WordPress is supported. Otherwise false.
	 */
	public function is_wordpress_supported( $min_version = '' ) {

		if ( isset( $this->min_wordpress_version ) && empty( $min_version ) ) {
			$min_version = $this->min_wordpress_version;
		}

		if ( empty( $min_version ) ) {
			return true;
		}

		if ( function_exists( 'get_bloginfo' ) ) {
			return version_compare( get_bloginfo( 'version' ), $min_version, '>=' );
		}

		return false;
	}

	/**
	 * Log a message.
	 *
	 * @param string $message The message to log.
	 * @param mixed  $attachment Optional. An attachment to the message. Defaults to null.
	 *
	 * @return void
	 */
	protected function log( $message, $attachment = null ) {

		if ( ! $this->debug ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) ) {
			$call_mode = 'WP_AJAX';
		} elseif ( defined( 'RG_CURRENT_PAGE' ) && RG_CURRENT_PAGE === 'admin-ajax.php' ) {
			$call_mode = 'GF_AJAX';
		} else {
			$call_mode = 'NORMAL';
		}

		$user_mode = defined( 'IS_ADMIN' ) ? 'ADMIN' 	: 'NORMAL';

		$timestamp = date( 'Y-m-d H:i:s' );
		$log  = "[ {$timestamp} ][ $call_mode ][ $user_mode ][ $message ]\r\n\n";

		if ( isset( $attachment ) ) {

			$type = gettype( $attachment );
			switch ( $type ) {
				case 'array':
				case 'object':
					$log .= print_r( $attachment, true );
					break;

				default:
					$log .= (string) $attachment;
					break;
			}
			$log .= "\r\n\n";
		}

		$logfile = $this->get_base_path( 'debug.log' );
		file_put_contents( $logfile , $log , FILE_APPEND );
	}

	#endregion Helper Methods
}

new GFHtml5Validation();
