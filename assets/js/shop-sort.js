/**
 * Auto-submits the Shop archive's sort <select> so choosing an option
 * re-requests the page with the new `orderby` query var -- filtering/sorting
 * on the Shop page is server-side (real query args), not client-side.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var select = document.querySelector( '.js-shop-sort-select' );

		if ( ! select ) {
			return;
		}

		select.addEventListener( 'change', function () {
			select.form.submit();
		} );
	} );
} )();
