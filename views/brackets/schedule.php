<style>
    tr td {
        border:1px solid #000;
    }
</style>
<?php 

use yii\helpers\Html;
$this->title="Schedule";
?>
<?= "<h3>Feasibility estimation (Test, not actual) 2018 schedule" ?>
<?php if($print==0): ?>
	<?= Html::a('Print',['scheduler/sched','print'=>1],[

			'class'=>'btn btn-primary',
			'target'=>'_blank',
			'data-toggle'=>'tooltip',
			'title'=>'Will display a printable schedule in another window',
			'style'=>'float:right;'
	]);?>

<?php endif; ?> 

<?php
echo "<table style='width:100%'>";
foreach($sched as $week=>$slots){
	echo "<tr><td colspan=5></td></tr>";
	foreach($slots as $slot=>$dow) {
		if($slot==0) {

			echo "<tr><td colspan='16'>&nbsp;</td></tr>";
			echo "<tr>";
			echo "<td></td>";
			foreach([1=>'Mon','Tue','Wed','Thr','Fri'] as $ddooww=>$day){

				$date='';
				if( isset($calendar[$week][$ddooww]) ) $date = date("M d",$calendar[$week][$ddooww]);

				echo "<td colspan='3' style='text-align:center'>$day - $date</td>";
			}
			echo "</tr>";
			echo "<tr>";
			echo "<td></td>";
			foreach(['Mon','Tue','Wed','Thr','Fri'] as $day){
				foreach([1,2,3] as $diamond) {
					echo "<td style='text-align:center'>$diamond</td>";
				}
			}
			echo "</tr>";
		}
		echo "<tr>";
		$time = strtotime("2017-06-01") + 17*60*60+30*60 +  $slot*(60*60+30*60);
		echo "<td>".date("h:i",$time)."</td>";
		for($w=1; $w<6; $w++) {
			for($dia=1; $dia<4; $dia++) {
				if( isset($dow[$w][$dia]) )
					echo "<td style='padding: 0px 6px 0px 6px;'>".$dow[$w][$dia]."</td>";
					else
						echo "<td></td>";
			}
		}
		echo "</tr>";
	}
}
echo "</table>";



/*
 * Games per week
 */
echo "<h3>Games per week</h3>";
$labeled=false;
echo "<table>";
foreach($gamesWeek as $week=>$games) {
	if( ! isset($calendar[$week][1]) ) break;
	if( !$labeled) {
		echo "<tr><td></td>";
		foreach($games as $teamId=>$noGames) {
			if(in_array($teamId,[21,41]) ) {
				echo "<td style='width:24px'>&nbsp;</td>";
			}
			echo "<td style='text-align:center; padding: 0px 6px 0px 6px;'>$teamId</td>";
		}
		echo "</tr>";
		$labeled=true;
	}
	$bow='';
	if( isset($calendar[$week][1]))
		$bow = date("M d",$calendar[$week][1]);
		echo "<tr><td>$bow</td>";
		foreach($games as $teamId=>$noGames) {
			if(in_array($teamId,[21,41]) ) {
				echo "<td style='width:24px'>&nbsp;</td>";
			}
			echo "<td style='text-align:center; padding:0px 6px 0px 6px;'>$noGames</td>";
		}
		echo "</tr>";
}
echo "</table>";
/*
 * Games per time slot
 */
$labeled=false;
echo "<h3>Games per time slot";
echo "<table>";
foreach($gamesTime as $team=>$freq) {
	if(in_array($team,[21,41])) {
		echo "<tr><td colspan='5'>&nbsp;</td></tr>";
	}

	if( !$labeled) {
		echo "<tr><td></td><td style='text-align:center; width:54px'>5:30</td><td style='text-align:center; width:54px'>7:00</td><td style='text-align:center; width:54px'>8:30</td></tr>";
		$labeled=true;
	}
	echo "<tr><td>$team</td>";
	foreach($freq as $count) {
		echo "<td style='text-align:center; padding:0px 6px 0px 6px'>$count</td>";
	}
	echo "</tr>";
}
echo "</table>";
