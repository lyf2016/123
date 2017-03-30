
	$(function(){
		
//		导航鼠标移入效果
		$('.menu ul li').hover(function(even){
			$(this).children('ol').filter(':not(:animated)').slideDown();
		},function(even){
			$(this).children('ol').slideUp();
		});
		    
	});   

