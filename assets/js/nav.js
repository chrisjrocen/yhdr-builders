/**
 * Supplemental site-wide behaviour that Blocksy's own header/footer builder
 * JS does not already provide: a floating WhatsApp button, and a passive
 * `.is-scrolled` class toggle on the header for a shrink/shadow effect on
 * scroll. Does NOT reimplement the mobile hamburger nav -- Blocksy's
 * ct-scripts/ct-events already handle that for .ct-header.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var header = document.querySelector( '.ct-header' );

		if ( header ) {
			var toggleScrolled = function () {
				header.classList.toggle( 'is-scrolled', window.scrollY > 40 );
			};

			toggleScrolled();
			window.addEventListener( 'scroll', toggleScrolled, { passive: true } );
		}

		if ( document.querySelector( '.yhdr-whatsapp-float' ) ) {
			return;
		}

		var number = window.yhdrContact && window.yhdrContact.whatsappNumber
			? window.yhdrContact.whatsappNumber
			: '';

		if ( ! number ) {
			return;
		}

		var link = document.createElement( 'a' );
		link.href = 'https://wa.me/' + number;
		link.className = 'yhdr-whatsapp-float';
		link.target = '_blank';
		link.rel = 'noopener noreferrer';
		link.setAttribute( 'aria-label', 'Chat with us on WhatsApp' );
		link.innerHTML = '<svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9 3 3.3 8.7 3.3 15.7c0 2.5.7 4.8 1.9 6.8L3 29l6.7-2.1c1.9 1 4.1 1.6 6.3 1.6 7 0 12.7-5.7 12.7-12.7S23 3 16 3zm7.4 18.1c-.3.9-1.8 1.7-2.5 1.8-.6.1-1.5.1-2.4-.2-.6-.2-1.3-.4-2.2-.8-3.9-1.7-6.4-5.6-6.6-5.9-.2-.3-1.6-2.1-1.6-4s1-2.8 1.3-3.2c.3-.3.7-.4 1-.4h.7c.2 0 .5 0 .8.6.3.7 1 2.4 1.1 2.6.1.2.2.4 0 .7-.1.3-.2.4-.4.6-.2.2-.4.5-.6.6-.2.2-.4.4-.2.8.2.4 1 1.7 2.2 2.7 1.5 1.3 2.7 1.7 3.1 1.9.4.2.6.1.8-.1.2-.3.9-1 1.2-1.4.3-.4.5-.3.9-.2.4.2 2.4 1.1 2.8 1.3.4.2.7.3.8.5.1.2.1 1-.2 1.9z"></path></svg>';

		document.body.appendChild( link );
	} );
} )();
