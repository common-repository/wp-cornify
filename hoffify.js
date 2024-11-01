jQuery(function($){
	var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e) {
		kkeys.push(e.keyCode);
		if (kkeys.toString().indexOf(konami) >= 0){
			$(document).unbind('keydown',arguments.callee);
			$.getScript('http://hoffify.co.uk/hoff_data.json');
			$.getScript('http://hoffify.co.uk/hoffify.js',function(){
				hoffify_add();
				$(document).keydown(hoffify_add);
			});

		}
	});
});
