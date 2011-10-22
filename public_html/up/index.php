<?php

require_once("../../sql.php");

$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

if ($user == "abcdef" && $password == "123456") {
// if (true) {
	if (!isset($_POST['json'])) {
		echo "You need to post 'json'\n";
	}

	$json = $_POST['json'];
	if (empty($json)) {
		echo "Failed to decode 'json'\n";
	}
	else {
        $json = stripslashes($json);
        // echo "json = $json";
        
		// var_dump(json_decode($json, true));

		$array = json_decode($json, true);
		$hs = $array[0];
		$stats = $array[1];

		$runId = r8DB::createRun();
		echo "Created run $runId\n";

		$result = r8DB::saveStats($runId, $stats);
		if (!$result) {
			echo "Failed to save stats.\n";
		} 
		else {
			echo "Saved stats.\n";
		}

		$result = r8DB::saveScores($runId, $hs);
		if (!$result) {
			echo "Failed to save scores.\n";
		} 
		else {
			echo "Saved scores.\n";
		}
	}
}
else {
	echo "Hello, world!\n";
	// http_redirect("../");			
}

