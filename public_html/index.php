<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Robotron</title>
  </head>
<body>
  <h1>Robotron</h1>
<?php
  // error_reporting(E_ALL);
  require_once('../sql.php');

  $conn = mysqli_init();
  if (!$conn->real_connect(R8_DB_HOST, R8_DB_USER, R8_DB_PASS, R8_DB_NAME, 3306)) {
    $errorStr = "Failed to connect to DB, err no: " . mysqli_connect_errno();
    throw new Exception($errorStr);
  }

//  $query = "select s.initials, s.longname, s.score, r.createdAt from
// Scores s join Runs r on r.id=s.runId where s.runId = (select id from
// Runs order by id desc limit 1)";
$query = "select s.initials, s.longname, s.score, r.createdAt, (select date_add(date(sr.createdAt),interval -1 day) from Scores ss join Runs sr on sr.id = ss.runId where ss.initials = s.initials  and ss.score = s.score order by sr.id limit 1) as 'setAt' from Scores s join Runs r on r.id=s.runId where s.runId = (select id from Runs order by id desc limit 1)";
  $sqlResult = $conn->query($query);
  if (empty($sqlResult)) {
    $errorStr = "T8: Failed to query scores: err: " . $conn->error;
    error_log($errorStr);
    throw new Exception($errorStr);
  }
  $row = $sqlResult->fetch_assoc();
  $date = new DateTime($row['createdAt'], new DateTimeZone('America/New_York'));
  date_default_timezone_set('America/Los_Angeles');
  $dateString = strftime("%c", $date->getTimestamp());
  echo "<p>Last updated: $dateString</p>"
?>
 <table>
    <tr>
      <th scope="col">Position</th>
      <th scope="col">Initials</th>
      <th scope="col">Long Name</th>
      <th scope="col">Score</th>
      <th scope="col">Date set</th>
    </tr>
<?php
  $i = 0;
  $sqlResult->data_seek(0);
  while ($row = $sqlResult->fetch_assoc()) {
    echo "<tr>";
	echo "<td>" . ++$i . "</td>";
	echo "<td>" . $row['initials'] . "</td>";
	echo "<td>" . $row['longname'] . "</td>";
	echo "<td>" . $row['score'] . "</td>";
	echo "<td>" . $row['setAt'] . "</td>";
	echo "</tr>";
  }

  $query = "select s.lsc, s.msc, s.rsc, s.tc, s.eme, s.ptm, s.mp, s.cp from Stats s join Runs r on r.id= s.runId where s.runId = (select id from Runs order by id desc limit 1)";
  $sqlResult = $conn->query($query);
  if (empty($sqlResult)) {
    $errorStr = "T8: Failed to query stats: err: " . $conn->error;
    error_log($errorStr);
    throw new Exception($errorStr);
  }		
?>
  </table>
 <h2>Stats</h2>
  <ul>
<?php
  $row = $sqlResult->fetch_assoc();
  echo "<li>Left slot coins: {$row['lsc']}</li>";
  echo "<li>Middle slot coins: {$row['msc']}</li>";
  echo "<li>Right slot coins: {$row['rsc']}</li>";
  echo "<li>Total coins: {$row['tc']}</li>";
  echo "<li>Extra men earned: {$row['eme']}</li>";
  echo "<li>Played total minutes: {$row['ptm']}</li>";
  echo "<li>Men played: {$row['mp']}</li>";
  echo "<li>Credits played: {$row['cp']}</li>";
?>
  </ul>
<?php
	$conn->close();
?>
  </body>
</html>
