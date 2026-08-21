/**
 * Google Maps facade.
 *
 * The map in the design is custom styled — cream base, green roads — which only
 * the Maps JavaScript API can do. That API is 200KB+ of third-party script, and
 * the map sits below the fold on both pages that use it, so loading it eagerly
 * would cost real Lighthouse points for something most visitors never scroll to.
 *
 * The facade is painted in CSS and always present. The real map is fetched only
 * when the visitor scrolls within 300px of it.
 */
( function () {
	'use strict';

	var container = document.querySelector( '[data-map]' );

	if ( ! container || ! window.tpphMap || ! window.tpphMap.key ) {
		return;
	}

	var loading = false;

	// Roads, water and land in the brand palette, transcribed from the artboard.
	var STYLE = [
		{ elementType: 'geometry', stylers: [ { color: '#f7fbea' } ] },
		{ elementType: 'labels.text.fill', stylers: [ { color: '#386c5f' } ] },
		{ elementType: 'labels.text.stroke', stylers: [ { color: '#f7fbea' }, { weight: 3 } ] },
		{ featureType: 'administrative', elementType: 'geometry.stroke', stylers: [ { color: '#c8d6b6' } ] },
		{ featureType: 'landscape.natural', elementType: 'geometry', stylers: [ { color: '#eef4dc' } ] },
		{ featureType: 'poi', elementType: 'geometry', stylers: [ { color: '#e3e7d9' } ] },
		{ featureType: 'poi.park', elementType: 'geometry', stylers: [ { color: '#dbe8cd' } ] },
		{ featureType: 'road', elementType: 'geometry', stylers: [ { color: '#ffffff' } ] },
		{ featureType: 'road', elementType: 'geometry.stroke', stylers: [ { color: '#cfdcc4' } ] },
		{ featureType: 'road.arterial', elementType: 'geometry', stylers: [ { color: '#e8f0dd' } ] },
		{ featureType: 'road.highway', elementType: 'geometry', stylers: [ { color: '#386c5f' } ] },
		{ featureType: 'road.highway', elementType: 'labels.text.fill', stylers: [ { color: '#ffffff' } ] },
		{ featureType: 'road.highway', elementType: 'labels.text.stroke', stylers: [ { color: '#2c554a' } ] },
		{ featureType: 'transit', elementType: 'geometry', stylers: [ { color: '#dfe7d2' } ] },
		{ featureType: 'water', elementType: 'geometry', stylers: [ { color: '#d7e8e2' } ] },
		{ featureType: 'water', elementType: 'labels.text.fill', stylers: [ { color: '#4a8375' } ] }
	];

	function draw() {
		var canvas = document.createElement( 'div' );
		canvas.className = 'map__canvas';
		container.appendChild( canvas );

		var position = {
			lat: parseFloat( container.dataset.lat ),
			lng: parseFloat( container.dataset.lng )
		};

		var map = new window.google.maps.Map( canvas, {
			center: position,
			zoom: parseInt( container.dataset.zoom, 10 ) || 14,
			styles: STYLE,
			disableDefaultUI: true,
			zoomControl: true,
			// The map is decorative until the visitor engages with it. Scroll
			// wheel zoom would otherwise trap the page scroll on the way past.
			gestureHandling: 'cooperative'
		} );

		new window.google.maps.Marker( {
			position: position,
			map: map,
			title: container.dataset.title || ''
		} );

		window.google.maps.event.addListenerOnce( map, 'idle', function () {
			container.classList.add( 'is-loaded' );
		} );
	}

	function load() {
		if ( loading ) {
			return;
		}

		loading = true;

		// The API calls this back once it has finished parsing.
		window.tpphMapReady = draw;

		var script = document.createElement( 'script' );
		script.src = 'https://maps.googleapis.com/maps/api/js?key='
			+ encodeURIComponent( window.tpphMap.key )
			+ '&callback=tpphMapReady&loading=async';
		script.async = true;
		document.head.appendChild( script );
	}

	if ( 'IntersectionObserver' in window ) {
		new IntersectionObserver(
			function ( entries, observer ) {
				if ( entries[ 0 ].isIntersecting ) {
					observer.disconnect();
					load();
				}
			},
			{ rootMargin: '300px' }
		).observe( container );
	} else {
		load();
	}
}() );
