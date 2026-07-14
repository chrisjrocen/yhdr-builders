/**
 * Animated 0-to-target count-up for `.stat-value` elements (e.g. "48+",
 * "98%"), triggered once when each stat card scrolls into view. Reads the
 * target from `data-count` (a bare integer set alongside the original
 * display text) and keeps any non-numeric prefix/suffix (e.g. "+", "%")
 * from the element's own text content.
 */
(function () {
	'use strict';

	var DURATION_MS = 1000;

	function easeOutQuad(t) {
		return t * (2 - t);
	}

	function animateCount(el) {
		var target = parseInt(el.getAttribute('data-count'), 10);
		if (isNaN(target)) {
			return;
		}
		var text = el.textContent.trim();
		var prefix = text.match(/^[^\d]*/)[0];
		var suffix = text.match(/[^\d]*$/)[0];
		var start = null;

		function step(timestamp) {
			if (start === null) {
				start = timestamp;
			}
			var progress = Math.min((timestamp - start) / DURATION_MS, 1);
			var value = Math.round(target * easeOutQuad(progress));
			el.textContent = prefix + value + suffix;
			if (progress < 1) {
				window.requestAnimationFrame(step);
			} else {
				el.textContent = prefix + target + suffix;
			}
		}

		window.requestAnimationFrame(step);
	}

	document.addEventListener('DOMContentLoaded', function () {
		var stats = document.querySelectorAll('.stat-value[data-count]');
		if (!stats.length) {
			return;
		}

		var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reducedMotion || !('IntersectionObserver' in window)) {
			return;
		}

		var observer = new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					animateCount(entry.target);
					obs.unobserve(entry.target);
				}
			});
		}, { threshold: 0.4 });

		stats.forEach(function (el) {
			observer.observe(el);
		});
	});
})();
