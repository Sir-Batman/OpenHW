
//nojs:
//if javascript is allowed on the page, hides the 
//"Y NO JS, BUB?" message
function nojs(){
	$(".nojs").addClass("invisible");
}

function logout(){
	$.get("./src/logout.php");
}

//multipleChoice:
//Creates the multiple choice section for the question, and returns the
//jquery form object populated with the proper data
function multipleChoice(question){
	
	var answers = $("<div></div>").addClass("answers");
	var form = $("<form></form>").addClass("multipleChoice");
	form.attr("id", question.questionname);
	
	for (var j in question.answers){
		var name = question.questionname;
		var value = question.answers[j];
		var ans = $('<input type="radio">');
		ans.attr("name", name);
		ans.attr("value", value);
		ans.attr("id", value);
		ans.appendTo(form).after($("<br>")).after(question.answers[j]);
	}
	
	form.appendTo(answers);
	
	return answers;
}


//loadQuestion:
//takes in a question object, and creates and populates a div for 
//the single question
//returns a jquery div 
function loadQuestion(question) {
	//alert(current.question);
	var wrapper = $("<div></div>").addClass("question");
	var header = $("<h1></h1>").text(question.questionname);
	var quest = $("<p></p>").text(question.question);
	
	//This section creates the answer/input section, based on the question type
	if(question.type == "multiplechoice"){
		var answers = multipleChoice(question);		
	}
	
	else{
		console.log("UNSUPORTED QUESTION TYPE");
	}
	
	var submit = $("<button  onclick='submitAnswer(this);'></button>").text("Submit Answer");
	submit.attr("id", question.questionname);
	var submitdiv = $("<div></div>").addClass("submitbuttondiv");
	
	submit.appendTo(submitdiv);					
	header.appendTo(wrapper);
	quest.appendTo(wrapper);
	answers.appendTo(wrapper);
	submitdiv.appendTo(wrapper);
	
	return wrapper;	
}

//loadAssignment:
//loads the assignment data from the static json located in the same 
//directory
function loadAssignment(d) {
	//check to see if the user is logged in to see this page
	if(d.logged_in == 0){
		$(".content").append("<div class='warning'>You are not logged in. Prepare to be redirected.<br>If you are not redirected automatically, click <a href='./login.html'>here</a></div>");
		$(".assignment").addClass("invisible");
		$(".console").addClass("invisible");
		$(".collapsebutton").addClass("invisible");
		setTimeout(function() {window.location.replace("./login.html");}, 3000);
	}
	else{
		//Add the assignment specific data
		var addtopage = $(".assignment");	

		var assname = $("<h1></h1>").text(d.assignment.name).addClass("assignmentName");
		assname.appendTo(addtopage);

		//load the questions
		for(var i = 0; i < d.assignment.questions.length; i++) {
			var current = d.assignment.questions[i];
			var wrapper = loadQuestion(current);

			wrapper.appendTo(addtopage);		
		}

	}
}


//straightforward ajax call to load from the json file
function loadData(){
	$.ajax({
		url:"./src/loadassignment.php",
		dataType:"json",
		success:function (d) {
		loadAssignment(d);
		},
		error:console.log("Error loading json")
	});
}

function toggle(){
	var collapsed = false;
	$(".collapsebutton").click(function() {

			if(collapsed){
			$(".assignment").animate({"width": "40%"});
			$(".collapsebutton").animate({"right": "40%"});
			$(".console").animate({"width": "40%"});

			}
			else{
			$(".collapsebutton").animate({"right": "0%"});
			$(".assignment").animate({"width": "80%"});
			$(".console").animate({"width": "0%"});
			}
		collapsed = !collapsed;

		});
}

function submitAnswer(button){
	var text = "form#" + button.id;
	var form = $(text);

	var formarray = form.serializeArray();
	for (var i in formarray){
//		console.log(formarray[i]);
//		console.log("i: " ,i);
		var questionid = formarray[i].name;
		var answertext = formarray[i].value;
//		console.log("questionid: ",questionid);
//		console.log("answertext: ",answertext);
	}
//	console.log(form.serializeArray());
//	console.log(button.id);

	$.ajax({
		url:"./src/grading.php",
		data:{q_id: questionid, ass_id: 1, sol_t: answertext},
		type: "POST"});

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
	
	//load the assignment data
	loadData();
	createConsole();

	toggle();
	
});
