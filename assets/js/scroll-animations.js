/**
 * Site-wide scroll-triggered entrance animations, built on top of the
 * vendored animate.css library. Elements carrying `data-animate="{name}"`
 * (matching an animate.css animation name, e.g. "fadeInUp") get
 * `animate__animated animate__{name}` added the first time they scroll
 * into view, then are left alone -- a one-time reveal, not a repeating
 * loop, so the site stays snappy on repeat scrolling.
 *
 * `data-animate-group` containers auto-stagger their direct
 * `data-animate` children via --animate-delay, so callers don't need to
 * hardcode a delay per card in PHP.
 */
(function () {
	'use strict';

	var STAGGER_STEP_MS = 90;

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	}

	function reveal(el) {
		var animation = el.getAttribute('data-animate');
		if (!animation) {
			return;
		}
		var delay = el.getAttribute('data-animate-delay');
		if (delay) {
			el.style.setProperty('--animate-delay', delay);
		}
		el.classList.add('animate__animated', 'animate__' + animation);
	}

	function revealImmediately(el) {
		el.style.opacity = '';
		el.removeAttribute('data-animate');
	}

	function tagFormidableFields() {
		// Formidable renders its own field markup from a shortcode, so it
		// can't carry a `data-animate` attribute from PHP -- tag it here
		// instead, once the shortcode's HTML is in the DOM.
		document.querySelectorAll('.contact-form-card .frm_form_field').forEach(function (field, index) {
			field.setAttribute('data-animate', 'fadeInUp');
			field.setAttribute('data-animate-delay', (index * STAGGER_STEP_MS) + 'ms');
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		tagFormidableFields();

		var groups = document.querySelectorAll('[data-animate-group]');
		groups.forEach(function (group) {
			var children = group.querySelectorAll(':scope > [data-animate]');
			children.forEach(function (child, index) {
				if (!child.hasAttribute('data-animate-delay')) {
					child.setAttribute('data-animate-delay', (index * STAGGER_STEP_MS) + 'ms');
				}
			});
		});

		var targets = document.querySelectorAll('[data-animate]');

		if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
			targets.forEach(revealImmediately);
			return;
		}

		var observer = new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					reveal(entry.target);
					obs.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.15,
			rootMargin: '0px 0px -40px 0px'
		});

		targets.forEach(function (el) {
			observer.observe(el);
		});
	});
})();
