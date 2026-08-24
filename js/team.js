/**
 * Our Team — biography panels and specialty filtering.
 *
 * The panel slides up over the portrait from the bottom edge. Opening it is
 * CSS: `:hover` on pointer devices and `:focus-within` for keyboard, so it
 * works with JavaScript switched off.
 *
 * This file supplies the two things CSS cannot: a tap toggle for touch devices,
 * which have no hover at all, and keeping `aria-expanded` honest whichever way
 * the panel was opened.
 */
( function () {
	'use strict';

	var HOVER = window.matchMedia( '(hover: hover) and (pointer: fine)' );

	/* --------------------------------------------------------------------- */
	/* Biography panels                                                      */
	/* --------------------------------------------------------------------- */

	var cards = Array.prototype.slice.call( document.querySelectorAll( '.person-card--has-bio' ) );

	function setExpanded( card, open ) {
		var toggle = card.querySelector( '.person-card__open' );

		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}
	}

	function closeCard( card ) {
		card.classList.remove( 'is-open' );
		/*
		 * Closing returns focus to the toggle, which is inside the card, so
		 * :focus-within would match and reopen the panel immediately. This class
		 * holds it shut until focus or the pointer actually leaves.
		 */
		card.classList.add( 'is-dismissed' );
		setExpanded( card, false );
	}

	function openCard( card ) {
		card.classList.remove( 'is-dismissed' );
		card.classList.add( 'is-open' );
		setExpanded( card, true );
	}

	function release( card ) {
		card.classList.remove( 'is-dismissed' );
	}

	cards.forEach( function ( card ) {
		var toggle = card.querySelector( '.person-card__open' );
		var close = card.querySelector( '.person-card__close' );

		if ( toggle ) {
			toggle.addEventListener( 'click', function () {
				/*
				 * On a pointer device the panel is already open under the cursor,
				 * so a click would only pin it. Let it pin, and let a second click
				 * release it — but the common case is touch, where this is the
				 * only way in.
				 */
				if ( card.classList.contains( 'is-open' ) ) {
					closeCard( card );
				} else {
					openCard( card );
				}
			} );
		}

		if ( close ) {
			close.addEventListener( 'click', function ( event ) {
				event.stopPropagation();
				closeCard( card );

				if ( toggle ) {
					toggle.focus();
				}
			} );
		}

		/*
		 * Hover is handled in CSS. The class is kept in step anyway so that
		 * aria-expanded does not report "collapsed" while the panel is visibly
		 * open under someone's cursor.
		 */
		card.addEventListener( 'mouseenter', function () {
			if ( HOVER.matches ) {
				setExpanded( card, true );
			}
		} );

		card.addEventListener( 'mouseleave', function () {
			release( card );

			if ( HOVER.matches && ! card.classList.contains( 'is-open' ) ) {
				setExpanded( card, false );
			}
		} );

		// Once focus has genuinely left the card, the panel is free to respond
		// to hover and focus again.
		card.addEventListener( 'focusout', function ( event ) {
			if ( ! card.contains( event.relatedTarget ) ) {
				release( card );
				setExpanded( card, false );
			}
		} );

		card.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				closeCard( card );

				if ( toggle ) {
					toggle.focus();
				}
			}
		} );
	} );

	// Tapping elsewhere releases a pinned card.
	document.addEventListener( 'click', function ( event ) {
		cards.forEach( function ( card ) {
			if ( card.classList.contains( 'is-open' ) && ! card.contains( event.target ) ) {
				closeCard( card );
			}
		} );
	} );

	/* --------------------------------------------------------------------- */
	/* Specialty filter                                                      */
	/* --------------------------------------------------------------------- */

	var grid = document.querySelector( '[data-person-grid]' );
	var buttons = Array.prototype.slice.call( document.querySelectorAll( '.specialty-filter__btn' ) );

	if ( ! grid || ! buttons.length ) {
		return;
	}

	var status = document.querySelector( '.person-grid__status' );
	var items = Array.prototype.slice.call( grid.querySelectorAll( '.person-card' ) );

	function filter( slug ) {
		var shown = 0;

		items.forEach( function ( card ) {
			var list = ( card.dataset.specialties || '' ).split( /\s+/ );
			var match = 'all' === slug || list.indexOf( slug ) !== -1;

			card.hidden = ! match;

			if ( match ) {
				shown++;
			} else {
				// A hidden card must not keep a pinned panel, or its focus.
				closeCard( card );
			}
		} );

		buttons.forEach( function ( button ) {
			var active = button.dataset.filter === slug;
			button.classList.toggle( 'is-active', active );
			button.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
		} );

		if ( status ) {
			// Announced to screen readers; the visual change speaks for itself.
			status.textContent =
				'all' === slug
					? ''
					: shown + ( 1 === shown ? ' doctor' : ' doctors' ) + ' shown';
		}
	}

	buttons.forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			filter( button.dataset.filter );
		} );
	} );
}() );
