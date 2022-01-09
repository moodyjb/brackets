var myLength=0;
var mySelected;
var myName;


$(document).ready(function() {
	$('#newEntity').on('click',function(e){
	
	$('#'+myType+'-id').val(-1); 
	$('#'+myType+'-mobile').val(''); 
	$('#'+myType+'-email').val('');
	$('#'+myType+'-street').val('');
	$('#'+myType+'-street2').val('');
	$('#'+myType+'-zipcode').val('');
				
	});
	
	$('#correction').on('click',function(e){
		console.log('correction clicked');
		$('#'+myType+'-id').val(myId);
		$('#'+myType+'-mobile').val(myMobile);
		$('#'+myType+'-email').val(myEmail);
	});
	
	
	$('#deleteEntity').on('click',function(e){
	
	$('#'+myType+'-id').val(-999); 
	$('#'+myType+'-mobile').val(''); 
	$('#'+myType+'-email').val('');
	$('#'+myType+'-street').val('');
	$('#'+myType+'-street2').val('');
	$('#'+myType+'-zipcode').val('');
				
	});
})