$(document).ready(function(){
	// This function is executed once the document is loaded
    function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);

    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('page', true);
    show('loading', false);
});
	// Caching the jQuery selectors:
	var count = $('.row');
	// Loading the number of users online into the count div with the load AJAX method:
	count.load('../php/online.php');
    
	var loaded=false;	// A flag which prevents multiple AJAX calls to geodata.php;

//	// Binding functions to custom events:
//
//	panel.bind('open',function(){
//		panel.slideDown(function(){
//			if(!loaded)
//			{
//				// Loading the countries and the flags
//				// once the sliding panel is shown:
//
//				panel.load('who-is-online/geodata.php');
//				loaded=true;
//			}
//		});
//	}).bind('close',function(){
//		panel.slideUp();
//	});

});