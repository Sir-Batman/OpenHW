//landing.js
//This file provides extra functionality to the main landing/logged in page.

//nojs:
//if javascript is allowed on the page, hides the 
//"Y NO JS, BUB?" message
function nojs(){
	$(".nojs").addClass("invisible");
}

//reads the JSON data and creates the page based on it.
function loadPage(data) {
	if(data.logged_in == 0){
		$(".content").append("<div class='warning'>You are not logged in. Prepare to be redirected.<br>If you are not redirected automatically, click <a href='./login.html'>here</a></div>");
		setTimeout(function() { 

			window.location.replace("./login.html");
		}, 5000);
	}
	else {
		$(".content").append("<h2>Welcome</h2><br>");


		for (var i = 0; i < data.assignments.length; i++) {

			var link = $("<a></a>").text("Assignment " + data.assignments[i]);
			link.attr('href', "./assignment?" + data.assignments[i] );
			link.appendTo($(".content"));
			$("<br>").appendTo($(".content"));
		}

	}


}


//straightforward ajax call to load from the json file
function loadData(){
	$.ajax({
		//console.log("Using the new loadData");
		url:"./src/landing.php",
		dataType:"json",
		success:function (d) {
		//console.dir(d);
			loadPage(d);
		},
		error:console.log("Error loading json")
	});
}
			
function logout(){
	$.get("./src/logout.php");
}

$(document).ready(function (){
	console.log("document ready");
	
	//firefox json fix
	$.ajaxSetup({'beforeSend': function(xhr){
		if (xhr.overrideMimeType)
			xhr.overrideMimeType("text/plain");
		}
	});
	//remove the nojs warning
	nojs();
	loadData();
	
	
});
