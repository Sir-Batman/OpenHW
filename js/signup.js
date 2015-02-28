//signup.js
//This file provides extra functionality to the signup form


//nojs:
//if javascript is allowed on the page, hides the 
//"Y NO JS, BUB?" message
function nojs(){
	$(".nojs").addClass("invisible");
}

function MyValidate(){
	console.log("In My validate");
		// validate signup form on keyup and submit
		$("#signupForm").validate({
			rules: {
				first_name: {required: true},
				last_name: {required: true},
				username: {
					required: true,
					minlength: 2
				},
				pass: {
					required: true,
					minlength: 5
				},
				pass_check: {
					required: true,
					minlength: 5,
					equalTo: "#pass"
				},
				e_mail: {
					required: true,
					email: true
				},
				email_check: {
					required: true,
					email: true,
					equalTo: "email"
				},
			},
			messages: {
				first_name: "Please enter your firstname",
				last_name: "Please enter your lastname",
				username: {
					required: "Please enter a username",
					minlength: "Your username must consist of at least 2 characters"
				},
				pass: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				pass_check: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				e_mail: "Please enter a valid email address",
			}
		});
}


$(document).ready(function (){
	console.log("document ready");
	
	//remove the nojs warning
	nojs();
	//MyValidate();
	
	
});
