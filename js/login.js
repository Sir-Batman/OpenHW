//login.js
//This file provides extra functionality to the login page.

//nojs:
//if javascript is allowed on the page, hides the 
//"Y NO JS, BUB?" message
function nojs(){
	$(".nojs").addClass("invisible");
}

function getParam(name) {

	return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}

function checkFail(){
	var param = getParam('fail');
	if(param == 1){
		console.log(param);
		$(".loginheader").before("<div style='text-align: center; background-color: #FF0000; height: 1rem; max-width: 500px; margin-right: auto; margin-left: auto'>The credentials provided were invalid<br></div>");
	}
}

$(document).ready(function (){
	console.log("document ready");
	
	//remove the nojs warning
	nojs();

	checkFail();
	
	
});
