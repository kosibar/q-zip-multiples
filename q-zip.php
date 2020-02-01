<?php

	/**
	 * Q-zip
	 *
	 * Q-zip is an example compression program created for a Quora answer.
	 * @link https://qr.ae/TmFqrB
	 * 
	 * This is very simplistic and will actually enlarge most files.
	 * 
	 * Some might dislike the heavy commenting. This is not intended to
	 * be serious code but rather instructional. I expect that people who
	 * don't know PHP will be reading it.
	 * 
	 * 
	 * Usage:
	 *   php q-zip.php <command> <input> [output]
	 * 
	 * Arguments:
	 *   command   Can be "compress", "decompress", or "calculate".
	 *   input     The path to the input file.
	 *   output    The path to the output file.
	 * 
	 * 
	 * Note: "PHP_EOL" is a constant that holds the "End Of Line" string
	 * for whatever platform you're running your script on.
	 */

	
	//
	// Command-Line Arguments
	//

	// These are the valid commands.
	$valid_commands = array("compress", "decompress", "calculate");

	// Initialize variables to hold the arguments.
	$command = null; // What is the user trying to do?
	$input = null;   // The input file.
	$output = null;  // The output file.

	// Command-line arguments are stored in an array called "$argv". (think "argument values")
	// The first argument (index 0) is the name of the script.
	foreach($argv as $index=>$value) {
		switch($index) {
			case 1:
				$command = $value;
				break;

			case 2:
				$input = $value;
				break;

			case 3:
				$output = $value;
				break;
		}
	}
	
	// Check the command.
	if($command === null) {
		die("No command was specified.".PHP_EOL.'Try "compress", "decompress" or "calculate".'.PHP_EOL);
	} elseif(!in_array($command, $valid_commands)) {
		die("Invalid command: ".$command.PHP_EOL.'Try "compress", "decompress" or "calculate".'.PHP_EOL);
	}

	// Check the input file.
	if($input === null) {
		die("No input file was specified.".PHP_EOL);
	} elseif(!is_file($input)) {
		die("The input file is not a file:".PHP_EOL.$input);
	} elseif(!is_readable($input)) {
		die("Access denied reading file:".PHP_EOL.$input.PHP_EOL);
	}

	// Check the output file.
	if($output === null) {
		if($command === "compress" || $command === "decompress") {
			die("No output file was specified.".PHP_EOL);
		}
	} elseif(file_exists($output)) {
		die("The output file already exists.".PHP_EOL."You don't really want to overwrite a file with output from Q-zip, do you?".PHP_EOL.$output.PHP_EOL);
	}


	//
	// Compression Algorithms
	//

	/**
	 * Compress data.
	 * @param {string}  $content   The content to be compressed.
	 * @return {string}  Returns the compressed file's content.
	 */
	function compress($content) {
		$result = "";

		// Loop for as long as we have content.
		while($content !== "") {
			// Get the next character from the content.
			preg_match('/^(.)(\1*)/s', $content, $match); // The matched string is stored in "$match" as an array.
			//   index: 0 1  2

			// Remove the match from the content.
			$content = substr($content, strlen($match[0]));

			// Add the first character to the result.
			$result = $result . $match[1];

			// If there was more than one character...
			if($match[2] !== "") {
				// Add the number of characters in the full match.
				$result = $result . strlen($match[0]);
			}

			// If this is not the end of the file then add a delimiter.
			if($content !== "") {
				$result = $result . "_";
			}
		}

		return $result;
	}

	/**
	 * Decompress data.
	 * @param {string}  $content   The content to be compressed.
	 * @return {string}  Returns the compressed file's content.
	 */
	function decompress($content) {
		$result = "";

		// Loop for as long as we have content.
		while($content !== "") {
			// Get the next character.
			preg_match("/^(.)([0-9]*)(?:_|$)/s", $content, $match);
			//   index: 0 1  2
			
			// Remove the match from the content.
			$content = substr($content, strlen($match[0]));

			// Add this character depending on whether or not it repeats.
			$result .= ($match[2] === "")
				? $match[1]                         // It does not repeat.
				: str_repeat($match[1], $match[2])  // It does repeat.
			;
		}

		return $result;
	}


	//
	// Perform Action
	//

	switch($command) {
		case "compress":
			file_put_contents($output, compress(file_get_contents($input)));

			echo "File compressed.".PHP_EOL;
			echo "  Input:  ".$input.PHP_EOL;
			echo "  Output: ".$output.PHP_EOL;
			echo "  Size:   ".number_format(filesize($input))." to ".number_format(filesize($output)).".".PHP_EOL;

			break;

		case "decompress":
			file_put_contents($output, decompress(file_get_contents($input)));

			echo "File decompressed.".PHP_EOL;
			echo "  Input:  ".$input.PHP_EOL;
			echo "  Output: ".$output.PHP_EOL;
			echo "  Size:   ".number_format(filesize($input))." to ".number_format(filesize($output)).".".PHP_EOL;

			break;

		case "calculate":
			$original_size = filesize($input);
			$compressed_size = strlen(compress(file_get_contents($input)));
			$difference = $compressed_size - $original_size;

			echo "Compression Calculation".PHP_EOL;
			echo "  Input:      ".$input.PHP_EOL;
			echo "  Original:   ".number_format($original_size).PHP_EOL;
			echo "  Compressed: ".number_format($compressed_size).PHP_EOL;

			if($difference < 0) {
				echo "  Difference: ".number_format(abs($difference))." bytes smaller".PHP_EOL;
			} elseif($difference > 0) {
				echo "  Difference: ".number_format(abs($difference))." bytes bigger".PHP_EOL;
			} else {
				echo "  Difference: none".PHP_EOL;
			}

			break;
	}