<?php

require_once("../../sql.php");

$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

if ($user == "abcdef" && $password == "123456") {
	if (!isset($_POST['json'])) {
		echo "You need to post 'json'\n";
	}

	$json = $_POST['json'];
	if (empty($json)) {
		echo "Failed to decode 'json'\n";
	}
	else {
        $json = stripslashes($json);

		$array = json_decode($json, true);
		$hs = $array[0];
		$stats = $array[1];

		$ds = new r8DB();
		$runId = $ds->createRun();
		echo "Created run $runId\n";

		$result = $ds->saveStats($runId, $stats);
		if (!$result) {
			echo "Failed to save stats.\n";
		} 
		else {
			echo "Saved stats.\n";
		}

		$result = $ds->saveScores($runId, $hs);
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

