<?php

define(R8_DB_HOST, "localhost");
define(R8_DB_USER, "invocare_r8");
define(R8_DB_PASS, "invocare_r8");
define(R8_DB_NAME, "invocare_r8");

class r8DB {
	public static function createRun() {
		$conn = self::connect();
		$escName = $conn->real_escape_string($name);
		$escDesc = $conn->real_escape_string($desc);

		$query = "INSERT INTO Runs (createdAt) values (NOW())";
		self::query($conn, $query);

		// Retrieve ID of newly inserted row and pass back to client.
		$query = "SELECT LAST_INSERT_ID() AS 'id'";
		$result = self::query($conn, $query);

		$row = $result->fetch_assoc(); 
		$id = intval($row["id"]);
		
		self::closeConnection($conn);
		return $id;
	}

	public static function saveStats($runId, $stats) {
		$conn = self::connect();
		$escRunId = $conn->real_escape_string($runId);
		/* array(8) { */
		/* 	["left slot coins"]=> */
		/* 	int(4) */
		/* 	["middle slot coins"]=> */
		/* 	int(0) */
		/* 	["right slot coins"]=> */
		/* 	int(0) */
		/* 	["total coins"]=> */
		/* 	int(4) */
		/* 	["extra men earned"]=> */
		/* 	int(12) */
		/* 	["play time in minutes"]=> */
		/* 	int(6) */
		/* 	["men played"]=> */
		/* 	int(24) */
		/* 	["credits played"]=> */
		/* 	int(4) */
		/* 	} */
		$query = "INSERT INTO Stats (lsc, msc, rsc, tc, eme, ptm, mp, cp, runId) VALUES ({$stats['left slot coins']}, {$stats['middle slot coins']}, {$stats['right slot coins']}, {$stats['total coins']}, {$stats['extra men earned']}, {$stats['play time in minutes']}, {$stats['men played']}, {$stats['credits played']}, $runId)";
		$result = self::query($conn, $query);
		
		self::closeConnection($conn);
		return $result;		
	}

	public static function saveScores($runId, $scores) {
		$conn = self::connect();
		$escRunId = $conn->real_escape_string($runId);

		foreach ($scores as $score) {
			$query = "INSERT INTO Scores (initials, longname, score, runId) VALUES ('{$score['name']}', '{$score['longname']}', '{$score['score']}', $runId)";
			$result = self::query($conn, $query);
		}
		
		self::closeConnection($conn);
		return $result;		
	}

	private static function query($conn, $query) {	
		if (!$conn) {
			return false;
		}

		$result = $conn->query($query);
		if (empty($result)) {
			$errorStr = "R8: Failed to run query: $query, error: " . $conn->error;
			self::closeConnection($conn);
			error_log($errorStr);
			throw new Exception($errorStr);
		}		
		
		return $result;		
	}

	private static function connect() {
		$conn = mysqli_init();
		if (!$conn->real_connect(R8_DB_HOST, R8_DB_USER, R8_DB_PASS, R8_DB_NAME, 3306)) {
			$errorStr = "R8: Failed to connect to T8 DB, err no: " . mysqli_connect_errno();
			error_log($errorStr);
			$conn = false;
			throw new Exception($errorStr);
		}
		
		return $conn;
	}
	
	private static function closeConnection($conn) {
		if (!empty($conn)) {
			try {
				$conn->close();
			}
			catch (Exception $e) {
				error_log("R8: Failed to close connection, error: " . $e->getMessage());
			}
		}
	}
}