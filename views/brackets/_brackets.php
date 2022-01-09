<?php
$firstRound = false;
foreach ($brackets as $lvl => $round) {

    if ($lvl+1 == count($brackets)) {
        $firstRound = true;
    }
    $rnd = 5-$lvl;
    foreach ($round as $id => $game) {
        // echo "<br>lvl=$lvl id=$id";
        // print_r($game);
        $x = $game['x'];
        $y = $game['y'];
        $id = $game['id'];
        $parent = ($rnd+1)."-".$game['parent'];
        $deltaY = $game['deltaY'];
        $team_w = $game['team_w'];
        $team_x = $game['team_x'];
        $team_y = $game['team_y'];
        if ($firstRound) {
            echo <<<EOD
                <div id='$rnd-$id' data-successor='$parent' style='position:fixed; top:{$team_y}px; left:{$team_x}px; width:{$team_w}px; padding:4px; border:solid 1px #000; background-color:#fff;'>Team z</div>
EOD;
        } else {
            $y2 = $y+$deltaY;
            $y1 = $y-$deltaY+15;
            $deltaYTop = $deltaY+15;
            $x12 = $x+135/2;
            $xText = $x + 95;
            $yText = $y-15;
            $heightText = 60;
            $widthText = 135;
            $link_x = $game['link_x'];
            $link_w = $game['link_w'];
            echo <<<EOD
                <div style='position:fixed; top:{$y1}px; left:{$link_x}px; border-left: 1px solid #000; border-top: 1px solid #000; width:{$link_w}px; height:{$deltaY}px'></div>
                <div style='position:fixed; top:{$y}px; left:{$link_x}px; border-left: 1px solid #ff0000; border-bottom: 1px solid #ff0000; width:{$link_w}px; height:{$deltaYTop}px;'></div>
                <div style='position:fixed; top:{$yText}px; left:{$xText}px; border: 1px dashed #ff0000;  width:{$widthText}px; height:{$heightText}px;'>8:30pm Tue<br>Mar 8<br>Camden #1</div>

EOD;

            echo <<<EOD
    <select id='$rnd-$id'  data-successor='$parent' style='position:fixed; top:{$team_y}px; left:{$team_x}px; width:{$team_w}px;'><option></option><option>Team 1</option><option>Team 2</option></select>
EOD;
        }
    }
}
