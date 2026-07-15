/**
 * Category filter buttons for the Blog archive grid (static show/hide over
 * the server-rendered cards, same pattern as project-filter.js).
 */
( function () {
	'use strict';

	function initFilters() {
		var buttons = document.querySelectorAll( '.blog__filter-btn' );
		var cards = document.querySelectorAll( '.blog-post-card' );

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

	document.addEventListener( 'DOMContentLoaded', initFilters );
} )();
