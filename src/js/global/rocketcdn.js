document.addEventListener( 'DOMContentLoaded', function() {
    document.querySelectorAll( '.wpr-rocketcdn-open' ).forEach( function(el) {
        el.addEventListener( 'click', function(e) {
            e.preventDefault();
        });
    });

    MicroModal.init({
        disableScroll: true
    });
});

window.addEventListener('load', function() {
    var openCTA  = document.querySelector( '#wpr-rocketcdn-open-cta' ),
        closeCTA = document.querySelector( '#wpr-rocketcdn-close-cta' ),
        smallCTA = document.querySelector( '#wpr-rocketcdn-cta-small' ),
        bigCTA   = document.querySelector( '#wpr-rocketcdn-cta' );

    openCTA.addEventListener('click', function(e) {
        e.preventDefault();

        smallCTA.classList.add('wpr-isHidden');
        bigCTA.classList.remove('wpr-isHidden');

        var httpRequest = new XMLHttpRequest(),
            postData = '';

            postData += 'action=toggle_rocketcdn_cta';
            postData += '&status=big';
            postData += '&nonce=' + rocket_ajax_data.nonce;

            httpRequest.open( 'POST', ajaxurl );
            httpRequest.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
            httpRequest.send( postData );
    });

    closeCTA.addEventListener('click', function(e) {
        e.preventDefault();

        smallCTA.classList.remove('wpr-isHidden');
        bigCTA.classList.add('wpr-isHidden');

        var httpRequest = new XMLHttpRequest(),
            postData = '';

            postData += 'action=toggle_rocketcdn_cta';
            postData += '&status=small';
            postData += '&nonce=' + rocket_ajax_data.nonce;

            httpRequest.open( 'POST', ajaxurl );
            httpRequest.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
            httpRequest.send( postData );
    });
});