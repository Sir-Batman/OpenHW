$(document).ready(
	function() {
		// Creating the console.
		var header = 'Welcome to JQConsole!\n' +
			'Use jqconsole.Write() to write and ' +
			'jqconsole.Input() to read.\n';
		window.jqconsole = $('.console').jqconsole(header, 'JS> ');

		// Abort prompt on Ctrl+C.
		jqconsole.RegisterShortcut('C', function() {
			jqconsole.AbortPrompt();
			handler();
		});
		// Move to line start Ctrl+A.
		jqconsole.RegisterShortcut('A', function() {
			jqconsole.MoveToStart();
			handler();
		});
		// Move to line end Ctrl+E.
		jqconsole.RegisterShortcut('E', function() {
			jqconsole.MoveToEnd();
			handler();
		});
		jqconsole.RegisterMatching('{', '}', 'brace');
		jqconsole.RegisterMatching('(', ')', 'paran');
		jqconsole.RegisterMatching('[', ']', 'bracket');
		// Handle a command.
		var handler = function(command) {
//			jqconsole.Write("Enter some text: ");
//			jqconsole.Input(function(input){
//				jqconsole.Write('>'+input+'<');
//			});
			if (command) {
				
				try {
					var output = window.eval(command);
					//jqconsole.Write('>>> ' + window.eval(command) + '\n');
					if(!output){
						jqconsole.Write('');
					}
					else{
						jqconsole.Write('>>> ' + output + '\n');
						jqconsole.Append($('<div>hello</div>'));
					}
				} catch (e) {
					jqconsole.Write('ERROR: ' + e.message + '\n');
				}
			}
			jqconsole.Prompt(true, handler, function(command) {
				// Continue line if can't compile the command.
				try {
					Function(command);
				} catch (e) {
					if (/[\[\{\(]$/.test(command)) {
						return 1;
					} else {
						return 0;
					}
				}
				return false;
			});
		};

		// Initiate the first prompt.
		handler();
	});

