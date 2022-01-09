

$(document).ready(function(){

	function sender(k) { 
			var csrfToken = $('meta[name="csrf-token"]').attr("content");
		 	$.ajax({
	             url: message_man,
	             type: 'post',
	             data: {_csrf : csrfToken},
	             dataType: 'json',
	             async: true,
	             cache: false
	       })
	       .done(function (msg) {
	    	   k = k + 1;
	  		   progress = parseInt(100*(k) /count);  
	           $(".progress-bar").css('width', progress+'%');
	           
	           if (msg.status == 'EOF') {
	        	   // do not invoke self
	        	   
	           } else {
	        	   if (msg.status == 'ERROR') {
	        		   $("#errors").append("<br>"+msg.errMsg);
	        	   }

	        	   $("#sent").text(k);
	        	   sender(k);
	           }
	           
	        })
	       .fail(function (x, e) {
	             alert("The call to the server side failed. " + x.responseText);
	                     
	       });
		
	}

	$("#total").text(count); 	 
	sender(0);
});