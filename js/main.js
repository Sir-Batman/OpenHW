
//nojs:
//if javascript is allowed on the page, hides the 
//"Y NO JS, BUB?" message
function nojs(){
	$(".nojs").addClass("invisible");
}


$(document).ready(function (){
	console.log("document ready");
	
	//remove the nojs warning
	nojs();

	checkFail();
	
	
});
