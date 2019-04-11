/*$(document).ready(function(){
	let html = '<div id="dialog"><p>Your session is about to expire due to inactivity.</p><input type="button" id="dialog-continue" value="CONTINUE"></input><input type="button" id="dialog-logout" value="LOGOUT NOW"></input></div>';
	let style= '<style>#dialog { display: none; position: fixed; top: 0; left: 0; right: 0; padding: 20px; background: black; font-size: 16px; text-align: center; color: #f2f2f2; z-index: 100; } #dialog input { background: none; border: none; text-decoration: underline; margin: 5px; padding: 5px; color: #f2f2f2; cursor: pointer; }</style>';
	let timeoutLimit = 10800 * 1000;
	let timeoutBuffer = 60 * 1000;
	
	setTimeout(function(){
		$('body').prepend(style);
		$('body').prepend(html);
		$('#dialog').slideDown(1000);
		let t = (timeoutBuffer / 1000);
		let refreshInterval = setInterval(function(){
			$('#dialog-continue').val("CONTINUE (" + t + ")");
			if (t <= 0) {
				clearInterval(refreshInterval);
				window.location = "/logout";
			}
			t--;
		}, 1000);
		$('#dialog-continue').click(function(){
			location.reload();
		});
		$('#dialog-logout').click(function(){
			window.location = "/logout";
		});
	}, timeoutLimit - timeoutBuffer);
});*/
