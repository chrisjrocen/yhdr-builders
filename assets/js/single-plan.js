/**
 * Single-plan page: re-skins WooCommerce's native variation <select> elements
 * (File Type, Drawing Sets) as radio buttons / checkboxes matching the
 * mockup, without touching WooCommerce's own variation JS -- every radio
 * or checkbox just sets the real underlying <select>'s value and triggers a
 * jQuery 'change', so WooCommerce's core add-to-cart-variation.js still owns
 * price swapping, availability and validation. Also keeps the "Buy This
 * Plan on WhatsApp" link's message in sync with whichever variation is
 * currently selected.
 */
( function ( $ ) {
	'use strict';

	if ( ! $ ) {
		return;
	}

	function optionsFromSelect( $select ) {
		return $select.find( 'option' ).filter( function () {
			return $( this ).val() !== '';
		} );
	}

	function buildRadioGroup( $select ) {
		var $wrapper = $( '<div class="yhdr-option-group"></div>' );

		optionsFromSelect( $select ).each( function () {
			var $option = $( this );
			var id = $select.attr( 'name' ) + '-' + $option.val();

			var $input = $( '<input type="radio">' ).attr( {
				name: $select.attr( 'name' ) + '_yhdr',
				id: id,
				value: $option.val(),
			} ).prop( 'checked', $option.is( ':selected' ) );

			$input.on( 'change', function () {
				$select.val( $option.val() ).trigger( 'change' );
			} );

			var $label = $( '<label class="yhdr-option"></label>' ).attr( 'for', id )
				.append( $input )
				.append( document.createTextNode( ' ' + $option.text() ) );

			$wrapper.append( $label );
		} );

		$select.addClass( 'yhdr-visually-hidden' ).after( $wrapper );
	}

	function buildCheckboxGroup( $select ) {
		var $options = optionsFromSelect( $select );
		var $bothOption = $options.filter( function () { return $( this ).val() === 'both'; } );
		var $singleOptions = $options.not( $bothOption );

		if ( $bothOption.length !== 1 || $singleOptions.length !== 2 ) {
			// Doesn't match the expected "two individual choices + one
			// combined 'both' choice" shape -- leave the native select as-is
			// rather than guessing at a layout.
			return;
		}

		var $wrapper = $( '<div class="yhdr-option-group"></div>' );
		var $checkboxes = $();

		$singleOptions.each( function () {
			var $option = $( this );
			var id = $select.attr( 'name' ) + '-' + $option.val();

			var $input = $( '<input type="checkbox">' ).attr( {
				id: id,
				value: $option.val(),
			} ).prop( 'checked', $select.val() === $option.val() || $select.val() === $bothOption.val() );

			var $label = $( '<label class="yhdr-option"></label>' ).attr( 'for', id )
				.append( $input )
				.append( document.createTextNode( ' ' + $option.text() ) );

			$wrapper.append( $label );
			$checkboxes = $checkboxes.add( $input );
		} );

		$checkboxes.on( 'change', function () {
			var $checked = $checkboxes.filter( ':checked' );

			if ( $checked.length === 2 ) {
				$select.val( $bothOption.val() );
			} else if ( $checked.length === 1 ) {
				$select.val( $checked.val() );
			} else {
				$select.val( '' );
			}

			$select.trigger( 'change' );
		} );

		$select.addClass( 'yhdr-visually-hidden' ).after( $wrapper );
	}

	function humanize( slug ) {
		return ( slug || '' ).replace( /-/g, ' ' ).replace( /\b\w/g, function ( c ) { return c.toUpperCase(); } );
	}

	function stripTags( html ) {
		return $( '<div>' ).html( html ).text().trim();
	}

	function buildWaHref( message ) {
		var phone = window.yhdrShopData && window.yhdrShopData.whatsappNumber ? window.yhdrShopData.whatsappNumber : '';
		return 'https://wa.me/' + phone + '?text=' + encodeURIComponent( message );
	}

	function initWhatsappSync( $form ) {
		var $link = $( '#yhdr-whatsapp-buy-link' );

		if ( ! $link.length ) {
			return;
		}

		var baseMessage = $link.data( 'base-message' ) || '';

		$form.on( 'found_variation', function ( event, variation ) {
			var attrs = variation.attributes || {};
			var fileType = attrs.attribute_pa_file_type;
			var drawingSets = attrs.attribute_pa_drawing_sets;
			var price = variation.price_html ? stripTags( variation.price_html ) : '';

			var extra = [];
			if ( fileType ) { extra.push( 'File type: ' + humanize( fileType ) ); }
			if ( drawingSets ) { extra.push( 'Drawing sets: ' + humanize( drawingSets ) ); }
			if ( price ) { extra.push( 'Price: ' + price ); }

			var message = baseMessage + ( extra.length ? ' ' + extra.join( '. ' ) + '.' : '' );
			$link.attr( 'href', buildWaHref( message ) );
		} );

		$form.on( 'reset_data', function () {
			$link.attr( 'href', buildWaHref( baseMessage ) );
		} );
	}

	$( function () {
		var $form = $( '.variations_form' ).first();

		if ( ! $form.length ) {
			return;
		}

		var $fileType = $form.find( 'select[name="attribute_pa_file_type"]' );
		var $drawingSets = $form.find( 'select[name="attribute_pa_drawing_sets"]' );

		if ( $fileType.length ) {
			buildRadioGroup( $fileType );
		}

		if ( $drawingSets.length ) {
			buildCheckboxGroup( $drawingSets );
		}

		initWhatsappSync( $form );
	} );
} )( window.jQuery );
