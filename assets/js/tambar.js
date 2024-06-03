'use strict';

/** Toggles WordPress adminbar state */
function tambarSwitcherClick () {
	document.querySelectorAll( 'body' ).forEach( body => {
		body.classList.toggle( 'tambar-hidden' );

		document.cookie = `tambar-is-hidden=${body.classList.contains( 'tambar-hidden' )};max-age=${10*3600*24};path=/;`;
	});
}