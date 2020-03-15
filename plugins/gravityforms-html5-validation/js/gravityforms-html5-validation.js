/* global jQuery */
(function ($) {
    "use strict";

    window.GFHtml5Validation = {};

    $.extend( $.expr[ ":" ], {
        data: $.expr.createPseudo ?
            $.expr.createPseudo( function( dataName ){
                return function( elem ) {
                    return !!$.data( elem, dataName );
                };
            }) :
            // support: $ <1.8
            function( elem, i, match ) {
                return !!$.data( elem, match[ 3 ] );
            }
    });

    var debug = window.GFHtml5Validation.Debug = false;
    var pageValidating = false;
    var pageValid = false;

    var trace = function( message, target ) {
        if ( debug ) {
            if ( target === undefined || debugLevel === 'info' ) {
                console.info( message );
            } else {
                console.info( message, target );
            }
        }
    };

	window.Html5ValidatePage = function( formId, $currentPage ){

        if ( pageValidating ) {
            return pageValid;
        }

        // Validate current page inputs
        $("[name^='input_']:not([type='hidden'])", $currentPage).each( function() {
            pageValid = this.checkValidity === undefined || this.checkValidity();
            return pageValid;
        });

        if (!pageValid) {

            if (debug) { console.info( "form validation on %s failed!", $currentPage[0] ); }

            if (debug) { console.group( "Pre Submit Phase" ); }
            if (debug) { console.groupCollapsed( "Process Current Page %s", $currentPage[0] ); }

            // Temporarily remove required attribute from visually hidden inputs on current page.
            if (debug) { console.groupCollapsed( "Remove required attributes from visually hidden field elements" ); }
            $("[name^='input_'].gform_hidden", $currentPage).each( function() {
                var element = $(this);
                if ( element.is(":required") ) {
                    if (debug) { console.log( "remove from %s", this.name ); }
                    $(this)
                        .data("tmp-required", true)
                        .removeAttr("required");
                }
            });
            if (debug) { console.groupEnd(); }

            if (debug) { console.groupEnd(); }

            // Iterate through other form pages.
            $currentPage.siblings().each( function() {
                if (debug) { console.groupCollapsed( "Process Sibling Page %s", this ); }

                // Temporarily remove required attribute from inputs on this sibling page.
                $("[name^='input_']:not([type='hidden'])", this).each( function() {
                    var $element = $(this);
                    if ( $element.is(":required") ) {
                        if (debug) { console.log( "remove required attribute from %s", this.name ); }
                        $element
                            .data("tmp-required", true)
                            .removeAttr("required");
                    }
                });

                if (debug) { console.groupEnd(); } // Process Sibling Page
            });

            if (debug) { console.groupEnd(); } // Pre Submit Phase

            if (debug) { console.group( "Form Submit Phase" ); }

            // Now submit the form to show errors on current page.
            pageValidating = true;
            if (debug) { console.info( "performing form submission on %s", $currentPage[0] ); }
            $("#gform_submit_button_" + formId).click();
            pageValidating = false;

            if (debug) { console.groupEnd(); }

            if (debug) { console.group( "Post Submit Phase" ); }
            if (debug) { console.groupCollapsed( "Process Current Page %s", $currentPage[0] ); }

            if (debug) { console.groupCollapsed( "Restore required attribute to visually hidden field elements" ); }
            $(":data(tmp-required)", $currentPage).each( function() {
                if (debug) { console.log( "restore to %s", this ); }
                $(this)
                    .attr("required", "required")
                    .removeData("tmp-required");
            });
            if (debug) { console.groupEnd(); }

            if (debug) { console.groupEnd(); }

            // Iterate through other form pages.
            $currentPage.siblings().each( function(){
                if (debug) { console.groupCollapsed( "Process Sibling Page %s", this ); }

                // Restore name attribute to inputs on this sibling page.
                if (debug) { console.groupCollapsed( "Restore name attributes to field elements" ); }
                $(":data(tmp-required)", this).each( function(){
                    var element = $(this);
                    if ( $element.data("tmp-required") ) {
                        if (debug) { console.log( "restore required to %s", this.name ); }
                        $element
                            .attr("required", "required")
                            .removeData("tmp-required");
                    }
                });
                if (debug) { console.groupEnd(); }

                if (debug) { console.groupEnd(); } // Process Sibling Page
            });

            if (debug) { console.groupEnd(); } // Post Submit Phase

        } else {

            if (debug) { console.info( "form validation on %s succeded!", $currentPage[0] ); }

            $("#gform_" + formId)
                .addClass('gf_submitting')
                .trigger("submit",[true]);

        }

        if (debug) { console.info( "form validation on %s completed!", $currentPage[0] ); }

        return pageValid;
    };

	$( document ).ready( function() {

        $(':checkbox[required]')
            .change( function() {

                var $group = $(this).closest(".gfield_checkbox");
                var $required = $(":checkbox", $group );
                if ( $required.is(':checked') ) {
                    $required
                        .data("gf-html5-required", true)
                        .removeAttr("required");
                } else {
                    $required
                        .attr("required", "required")
                        .removeData("gf-html5-required");
                }

            }).change();


        $(':file[required]')
            .change( function() {

                var $required = $(this);
                if ( $required.hasClass(".gform_hidden") ) {
                    $required
                        .data("gf-html5-required", true)
                        .removeAttr("required");
                } else {
                    $required
                        .attr("required", "required")
                        .removeData("gf-html5-required");
                }

            }).change();

        if (typeof window.gformDeleteUploadedFile === 'function' ) {

            var gformDeleteUploadedFileOriginal = window.gformDeleteUploadedFile;
            window.gformDeleteUploadedFile = function gformDeleteUploadedFile(formId, fieldId, deleteButton){

                gformDeleteUploadedFileOriginal( formId, fieldId, deleteButton );

                // Gravity Forms Tigger Change On File Inputs Fix.
                var parent = jQuery("#field_" + formId + "_" + fieldId);
                parent.find("input[type=\"file\"]").change();
            };
        }

		if (typeof window.gf_do_action === 'function') {

			var gfDoActionOriginal = window.gf_do_action;
			window.gf_do_action = function( action, targetId, useAnimation, defaultValues, isInit, callback ){

				// Gravity Forms Conditional Visibility Fix
				// ========================================
				// In cases where conditional visibility is used, required fields may be hidden which
				// produces a javascript error when trying to submit a hidden html5 required field. We
				// use a data attribute to store whether the input is html5 required and toggle the
				// actual html5 required attribute according to conditional field visibility.
				if ( action === "show" ) {

					$(":data(gf-html5-required)", targetId).each( function() {
                        $(this)
                            .attr("required", "required")
                            .removeData("gf-html5-required");
					});

				} else {

					$("[required]", targetId).each( function() {
						$(this)
                            .data("gf-html5-required", true)
                            .removeAttr("required");
					});
				}

				gfDoActionOriginal( action, targetId, useAnimation, defaultValues, isInit, callback );
			};

		}

	});

})(jQuery);
