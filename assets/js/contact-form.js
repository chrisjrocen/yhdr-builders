/**
 * Contact form: basic client-side required-field feedback, plus builds the
 * WhatsApp CTA's deep link from whatever the visitor has typed so far.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var form = document.getElementById( 'yhdr-contact-form' );
		var whatsappLink = document.querySelector( '.contact__whatsapp-cta' );

		if ( ! form ) {
			return;
		}

		form.addEventListener( 'submit', function ( event ) {
			var required = form.querySelectorAll( '[required]' );
			var firstInvalid = null;

			required.forEach( function ( field ) {
				if ( ! field.value.trim() && ! firstInvalid ) {
					firstInvalid = field;
				}
			} );

			if ( firstInvalid ) {
				event.preventDefault();
				firstInvalid.focus();
			}
		} );

		if ( ! whatsappLink ) {
			return;
		}

		var baseHref = whatsappLink.getAttribute( 'href' );

		function updateWhatsappLink() {
			var name = ( form.querySelector( '#yhdr_name' ) || {} ).value || '';
			var message = ( form.querySelector( '#yhdr_message' ) || {} ).value || '';

			var text = 'Hi YHDR Builders,';
			if ( name ) {
				text += ' my name is ' + name + '.';
			}
			if ( message ) {
				text += ' ' + message;
			}

			var separator = baseHref.indexOf( '?' ) === -1 ? '?' : '&';
			whatsappLink.setAttribute( 'href', baseHref.split( '?' )[ 0 ] + separator + 'text=' + encodeURIComponent( text ) );
		}

		form.addEventListener( 'input', updateWhatsappLink );
	} );
} )();
