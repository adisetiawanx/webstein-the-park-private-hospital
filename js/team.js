/**
 * Our Team — biography toggles and specialty filtering.
 *
 * The two artboards show a portrait card and a biography card of the same size.
 * Clicking swaps them in place. Click, not hover: the biographies run to about
 * 200 words, and hover does not exist on touch.
 */
( function () {
	'use strict';

	/* --------------------------------------------------------------------- */
	/* Biography toggles                                                     */
	/* --------------------------------------------------------------------- */

	var cards = Array.prototype.slice.call( document.querySelectorAll( '.person-card--has-bio' ) );

	function closeCard( card ) {
		var open = card.querySelector( '.person-card__open' );
		var bio = card.querySelector( '.person-card__bio' );

		card.classList.remove( 'is-open' );

		if ( open ) {
			open.setAttribute( 'aria-expanded', 'false' );
		}

		if ( bio ) {
			bio.hidden = true;
		}
	}

	function openCard( card ) {
		var open = card.querySelector( '.person-card__open' );
		var bio = card.querySelector( '.person-card__bio' );

		card.classList.add( 'is-open' );

		if ( open ) {
			open.setAttribute( 'aria-expanded', 'true' );
		}

		if ( bio ) {
			bio.hidden = false;
		}
	}

	cards.forEach( function ( card ) {
		var open = card.querySelector( '.person-card__open' );
		var close = card.querySelector( '.person-card__close' );

		if ( open ) {
			open.addEventListener( 'click', function () {
				if ( card.classList.contains( 'is-open' ) ) {
					closeCard( card );
					return;
				}

				openCard( card );

				// Move focus into the panel that just appeared, so a keyboard
				// user is not left on a control that is now behind the card.
				var closeBtn = card.querySelector( '.person-card__close' );

				if ( closeBtn ) {
					closeBtn.focus();
				}
			} );
		}

		if ( close ) {
			close.addEventListener( 'click', function () {
				closeCard( card );

				if ( open ) {
					open.focus();
				}
			} );
		}

		card.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && card.classList.contains( 'is-open' ) ) {
				closeCard( card );

				if ( open ) {
					open.focus();
				}
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
				// A hidden card must not keep an open biography, or its focus.
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
