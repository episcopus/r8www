<!DOCTYPE HTML>
<html>
  <head>
<?php
  // error_reporting(E_ALL);
  require_once('../sql.php');

  $ds = new r8DB();
  $chartData = $ds->getScoreChartData();
  $ptmData = $ds->getPtmChartData();
  $topScores = "";
  $botScores = "";
  $ptm = "";
  $startDate = "";
  if ($chartData->num_rows > 0) {
    $topScores = "[";
    $botScores = "[";
    $ptm = "[";
    $i = 0;
    while ($row = $chartData->fetch_assoc()) {
      if ($i == 0) {
        $startDate = $row["day"];
      }
      $topScores .= $row["topScore"];
      $botScores .= $row["lowScore"];
      $min = isset($ptmData[$row["id"]]) ? $ptmData[$row["id"]] : 0;
      $ptm .= $min;
      if ($i < $chartData->num_rows - 1) {
        $topScores .= ",";
        $botScores .= ",";
        $ptm .= ",";
      }
      $i++;
    }
    $topScores .= "]";
    $botScores .= "]";
    $ptm .= "]";
  }
?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Robotron</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
    <script src="js/highcharts.js" type="text/javascript"></script>
    <script type="text/javascript">
      var chart1; // globally available
      $(document).ready(function() {
      chart1 = new Highcharts.Chart({
        chart: {
           renderTo: 'container'
        },
        title: {
           text: 'Leaderboard (high and low)'
        },
        xAxis: {
           type: 'datetime',
           title: {
             text: 'Date'
           }
        },
        yAxis: [{
           title: {
              text: 'Score'
           }
        }, {
           title: {
              text: 'Minutes played'
           },
           opposite: true
        }],
        series: [{
           name: 'High',
           pointInterval: 24 * 3600 * 1000, // one day
           pointStart: Date.parse('<?php echo "$startDate"; ?>'),
           data: <?php echo "$topScores"; ?>,
           type: 'line'
        },{
           name: 'Low',
           pointInterval: 24 * 3600 * 1000, // one day
           pointStart: Date.parse('<?php echo "$startDate"; ?>'),
           data: <?php echo "$botScores"; ?>,
           type: 'line'
        },{
           name: 'Minutes played',
           pointInterval: 24 * 3600 * 1000, // one day
           pointStart: Date.parse('<?php echo "$startDate"; ?>'),
           data: <?php echo "$ptm"; ?>,
           type: 'column',
           yAxis: 1
        }]
     });
  });
   </script>
  </head>
<body>
  <h1>Robotron</h1>
<?php
  $sqlResult = $ds->getLeaderboardData();
  $row = $sqlResult->fetch_assoc();
  $date = new DateTime($row['createdAt'], new DateTimeZone('America/New_York'));
  date_default_timezone_set('America/Los_Angeles');
  $dateString = strftime("%c", $date->getTimestamp());
  echo "<p>Last updated: $dateString</p>";
?>
 <table>
    <tr>
      <th scope="col">Position</th>
      <th scope="col">Initials</th>
      <th scope="col">Long Name</th>
      <th scope="col">Score</th>
      <th scope="col">Date set</th>
      <th scope="col">Set last week</th>
      <th scope="col">Set yesterday</th>
    </tr>
<?php
  $i = 0;
  $sqlResult->data_seek(0);
  $today = new DateTime();
  while ($row = $sqlResult->fetch_assoc()) {
    echo "<tr>";
	echo "<td>" . ++$i . "</td>";
	echo "<td>" . $row['initials'] . "</td>";
	echo "<td>" . $row['longname'] . "</td>";
	echo "<td>" . $row['score'] . "</td>";
	echo "<td>" . $row['setAt'] . "</td>";
    $setAt = new DateTime($row['setAt']);
    $dayDiff = $today->diff($setAt)->d;
	echo "<td style='text-align: center'>" . ($dayDiff < 7 ? "XXX" : "") . "</td>";    
	echo "<td style='text-align: center'>" . ($dayDiff == 1 ? "XXX" : "") . "</td>";    
	echo "</tr>";
  }

?>
  </table>
  <div id="container" style="width: 100%; height: 400px"></div>
 <h2>Stats</h2>
  <ul>
<?php
  $sqlResult = $ds->getStatsData();
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
 </body>
</html>
