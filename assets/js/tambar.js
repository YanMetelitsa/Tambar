'use strict';

/**
 * Toggles WordPress admin bar state.
 */
function tambarToggle () {
	document.body.classList.toggle( 'tambar-hidden' );

	const isHidden = document.body.classList.contains( 'tambar-hidden' ) ? 1 : 0;

	document.cookie = `tambar-is-hidden=${isHidden};max-age=${10*3600*24};path=/;`;
}

window.addEventListener( 'load', e => {
	if ( typeof qm == 'object' && qm?.menu?.sub[ 'query-monitor-warnings' ] ) {
		document.body.classList.remove( 'tambar-hidden' );
	}
});
window.addEventListener( 'keydown', e => {
    if ( e.shiftKey && [ 'ArrowUp', 'ArrowDown' ].includes( e.key ) ) {
       tambarToggle();
    }
});