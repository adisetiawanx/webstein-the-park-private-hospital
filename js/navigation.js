/**
 * Primary navigation.
 *
 * Desktop opens submenus on hover through CSS, matching the XD interaction.
 * This file supplies everything hover cannot: keyboard operation, the mobile
 * drawer, and the sticky-header shadow.
 */
( function () {
	'use strict';

	var DESKTOP = window.matchMedia( '(min-width: 1024px)' );

	var header = document.getElementById( 'site-header' );
	var nav = document.getElementById( 'primary-navigation' );
	var burger = document.querySelector( '.site-header__burger' );

	if ( ! header || ! nav || ! burger ) {
		return;
	}

	var parents = Array.prototype.slice.call( nav.querySelectorAll( '.nav__item--has-children' ) );

	/* --------------------------------------------------------------------- */
	/* Submenus                                                              */
	/* --------------------------------------------------------------------- */

	function closeAllSubmenus( except ) {
		parents.forEach( function ( item ) {
			if ( item === except ) {
				return;
			}

			item.classList.remove( 'is-open' );

			var toggle = item.querySelector( '.nav__toggle' );

			if ( toggle ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	}

	function setSubmenu( item, open ) {
		var toggle = item.querySelector( '.nav__toggle' );

		item.classList.toggle( 'is-open', open );

		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}
	}

	parents.forEach( function ( item ) {
		var toggle = item.querySelector( '.nav__toggle' );

		if ( ! toggle ) {
			return;
		}

		toggle.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			var willOpen = ! item.classList.contains( 'is-open' );

			closeAllSubmenus( item );
			setSubmenu( item, willOpen );
		} );

		/*
		 * Hover is handled in CSS, but the class has to be kept in step so that
		 * aria-expanded does not lie to a screen reader driving a pointer.
		 */
		item.addEventListener( 'mouseenter', function () {
			if ( DESKTOP.matches ) {
				setSubmenu( item, true );
			}
		} );

		item.addEventListener( 'mouseleave', function () {
			if ( DESKTOP.matches ) {
				setSubmenu( item, false );
			}
		} );

		// Tabbing out of the last link in a submenu should close it.
		item.addEventListener( 'focusout', function ( event ) {
			if ( DESKTOP.matches && ! item.contains( event.relatedTarget ) ) {
				setSubmenu( item, false );
			}
		} );
	} );

	/* --------------------------------------------------------------------- */
	/* Mobile drawer                                                         */
	/* --------------------------------------------------------------------- */

	function setDrawer( open ) {
		nav.classList.toggle( 'is-open', open );
		burger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		document.body.classList.toggle( 'nav-open', open );

		if ( ! open ) {
			closeAllSubmenus( null );
		}
	}

	burger.addEventListener( 'click', function () {
		setDrawer( ! nav.classList.contains( 'is-open' ) );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' !== event.key ) {
			return;
		}

		if ( nav.classList.contains( 'is-open' ) ) {
			setDrawer( false );
			burger.focus();

			return;
		}

		var open = nav.querySelector( '.nav__item--has-children.is-open' );

		if ( open ) {
			setSubmenu( open, false );

			var toggle = open.querySelector( '.nav__toggle' );

			if ( toggle ) {
				toggle.focus();
			}
		}
	} );

	// Clicking outside the header closes anything left open.
	document.addEventListener( 'click', function ( event ) {
		if ( ! header.contains( event.target ) ) {
			closeAllSubmenus( null );

			if ( nav.classList.contains( 'is-open' ) ) {
				setDrawer( false );
			}
		}
	} );

	// Crossing the breakpoint must not strand the drawer in an open state.
	function handleBreakpoint() {
		if ( DESKTOP.matches ) {
			setDrawer( false );
		}
	}

	if ( DESKTOP.addEventListener ) {
		DESKTOP.addEventListener( 'change', handleBreakpoint );
	} else if ( DESKTOP.addListener ) {
		DESKTOP.addListener( handleBreakpoint );
	}

	/* --------------------------------------------------------------------- */
	/* Sticky header shadow                                                  */
	/* --------------------------------------------------------------------- */

	var sentinel = document.createElement( 'div' );
	sentinel.setAttribute( 'aria-hidden', 'true' );
	sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;';
	document.body.insertBefore( sentinel, document.body.firstChild );

	if ( 'IntersectionObserver' in window ) {
		new IntersectionObserver( function ( entries ) {
			header.classList.toggle( 'is-stuck', ! entries[ 0 ].isIntersecting );
		} ).observe( sentinel );
	}
}() );
