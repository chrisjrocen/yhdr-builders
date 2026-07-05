/**
 * Vanilla prev/next testimonial carousel. Works identically wherever
 * .testimonial-carousel markup appears (Home page and, if extended, other
 * pages), since each instance tracks its own state independently.
 */
( function () {
	'use strict';

	function initCarousel( carousel ) {
		var slides = carousel.querySelectorAll( '.testimonial-card' );
		var prevBtn = carousel.querySelector( '.testimonial-carousel__btn--prev' );
		var nextBtn = carousel.querySelector( '.testimonial-carousel__btn--next' );

		if ( slides.length === 0 ) {
			return;
		}

		var activeIndex = 0;

		function show( index ) {
			activeIndex = ( index + slides.length ) % slides.length;
			slides.forEach( function ( slide, i ) {
				slide.classList.toggle( 'is-active', i === activeIndex );
			} );
		}

		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () {
				show( activeIndex - 1 );
			} );
		}

		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () {
				show( activeIndex + 1 );
			} );
		}

		show( 0 );

		if ( slides.length > 1 ) {
			var timer = setInterval( function () {
				show( activeIndex + 1 );
			}, 7000 );

			carousel.addEventListener( 'mouseenter', function () {
				clearInterval( timer );
			} );
		}
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.testimonial-carousel' ).forEach( initCarousel );
	} );
} )();
