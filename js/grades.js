//nojs:
//if javascript is allowed on the page, hides the 
//"Y NO JS, BUB?" message
function nojs(){
	$(".nojs").addClass("invisible");
}

function logout(){
	$.get("./src/logout.php");
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
		$(".content").append("<h2>Grades:</h2><br>");


		//We start at one because the first 
		var table = $("<table></table>");
		table.append("<tr><th>Assignment</th><th>Grade</th></tr>");
		var count = 0;
		for ( var entry in data.grades) {
			count += 1;
			console.log("entry: ", entry);
			console.log("entry value: ", data.grades[entry]);
			var row = $("<tr></tr>");
			var name = $("<td></td>").text(entry);
			var grade = $("<td></td>").text(data.grades[entry]);
			row.append(name);
			row.append(grade);
			table.append(row);
		}
		if(count == 0){
			$(".content").append("<p>You have assignments graded yet. Time to get to work!</p>");
		}
		else{
			$(".content").append(table);
		}

	}
}

//straightforward ajax call to load from the json file
function loadData(){
	$.ajax({
		//console.log("Using the new loadData");
		url:"./src/grades.php",
		dataType:"json",
		success:function (d) {
		//console.dir(d);
			loadPage(d);
		},
		error:console.log("Error loading json")
	});
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
