'use strict';

/** Toggles WordPress adminbar state */
function tambarSwitcherClick () {
	document.querySelectorAll( 'body' ).forEach( body => {
		body.classList.toggle( 'tambar-hidden' );

		const isHidden = body.classList.contains( 'tambar-hidden' ) ? 1 : 0;

		document.cookie = `tambar-is-hidden=${isHidden};max-age=${10*3600*24};path=/;`;
	});
}