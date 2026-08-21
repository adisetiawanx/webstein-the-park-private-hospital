/**
 * Testimonial rotation.
 *
 * The design shows one quote and no slider controls, so a single quote renders
 * static and this script never loads. It only comes into play once a second
 * quote is added, which is where a testimonial section always ends up.
 */
( function () {
	'use strict';

	var section = document.querySelector( '[data-testimonial-slider]' );

	if ( ! section ) {
		return;
	}

	var slides = Array.prototype.slice.call( section.querySelectorAll( '.testimonial' ) );

	if ( slides.length < 2 ) {
		return;
	}

	var INTERVAL = 7000;
	var current = 0;
	var timer = null;

	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	function show( index ) {
		slides.forEach( function ( slide, i ) {
			var active = i === index;

			slide.classList.toggle( 'is-active', active );

			if ( active ) {
				slide.removeAttribute( 'aria-hidden' );
			} else {
				slide.setAttribute( 'aria-hidden', 'true' );
			}
		} );

		current = index;
	}

	function advance() {
		show( ( current + 1 ) % slides.length );
	}

	function start() {
		if ( timer || reduced.matches ) {
			return;
		}

		timer = window.setInterval( advance, INTERVAL );
	}

	function stop() {
		window.clearInterval( timer );
		timer = null;
	}

	// Auto-rotating content has to be pausable, and it must not keep moving
	// under someone who is reading it.
	section.addEventListener( 'mouseenter', stop );
	section.addEventListener( 'mouseleave', start );
	section.addEventListener( 'focusin', stop );
	section.addEventListener( 'focusout', start );

	document.addEventListener( 'visibilitychange', function () {
		if ( document.hidden ) {
			stop();
		} else {
			start();
		}
	} );

	if ( reduced.addEventListener ) {
		reduced.addEventListener( 'change', function () {
			if ( reduced.matches ) {
				stop();
			} else {
				start();
			}
		} );
	}

	show( 0 );
	start();
}() );
