/**
 * Category filter buttons + a lightweight vanilla lightbox for the Projects
 * archive grid (no external lightbox library).
 */
( function () {
	'use strict';

	function initFilters() {
		var buttons = document.querySelectorAll( '.projects__filter-btn' );
		var cards = document.querySelectorAll( '.project-card' );

		if ( buttons.length === 0 || cards.length === 0 ) {
			return;
		}

		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var filter = button.getAttribute( 'data-filter' );

				buttons.forEach( function ( b ) {
					b.classList.toggle( 'is-active', b === button );
				} );

				cards.forEach( function ( card ) {
					var category = card.getAttribute( 'data-category' );
					var show = filter === 'all' || category === filter;
					card.classList.toggle( 'is-hidden', ! show );
				} );
			} );
		} );
	}

	function initLightbox() {
		var images = document.querySelectorAll( '.project-card__media img' );

		if ( images.length === 0 ) {
			return;
		}

		var overlay = document.createElement( 'div' );
		overlay.className = 'yhdr-lightbox';
		overlay.setAttribute( 'role', 'dialog' );
		overlay.setAttribute( 'aria-modal', 'true' );
		overlay.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(22,32,46,.85);z-index:1000;align-items:center;justify-content:center;padding:24px;';

		var img = document.createElement( 'img' );
		img.style.cssText = 'max-width:90vw;max-height:85vh;border-radius:12px;';
		overlay.appendChild( img );

		var closeBtn = document.createElement( 'button' );
		closeBtn.type = 'button';
		closeBtn.setAttribute( 'aria-label', 'Close' );
		closeBtn.textContent = '×';
		closeBtn.style.cssText = 'position:absolute;top:24px;right:32px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;line-height:1;';
		overlay.appendChild( closeBtn );

		document.body.appendChild( overlay );

		function open( src, alt ) {
			img.src = src;
			img.alt = alt || '';
			overlay.style.display = 'flex';
		}

		function close() {
			overlay.style.display = 'none';
			img.src = '';
		}

		images.forEach( function ( image ) {
			image.style.cursor = 'zoom-in';
			image.addEventListener( 'click', function () {
				open( image.currentSrc || image.src, image.alt );
			} );
		} );

		closeBtn.addEventListener( 'click', close );
		overlay.addEventListener( 'click', function ( event ) {
			if ( event.target === overlay ) {
				close();
			}
		} );
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				close();
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initFilters();
		initLightbox();
	} );
} )();
