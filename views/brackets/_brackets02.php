<?php

for ($round=1; $round<count($brackets); $round++) {

    foreach ($brackets[$round] as $index => $bracket) {
        $team_x = $bracket['team_x'];
        $team_y = $bracket['team_y'];
        $team_h = $this->context->team_h;
        $team_w = $this->context->team_w;
        $link_x = $team_x + $this->context->team_w/2;

        if ($round==1) {
            echo <<<EOD
    <select style='position:fixed; top:{$team_y}px; left:{$team_x}px; width:{$team_w}px;'><option></option><option>Team 1</option><option>Team 2</option></select>
EOD;
        } else {
            $link_w = $bracket['link_w'];
            $link_h = $bracket['link_h']-$team_h/2;
            $link_yUp = $team_y-$link_h;
            $link_yLo = $team_y+$this->context->team_h;
            echo <<<EOD
                <div  style='position:fixed; top:{$team_y}px; left:{$team_x}px; width:{$team_w}px; height:{$team_h}px; padding:4px; border:solid 1px #000; background-color:#fff;'>$round  $index</div>
EOD;

            echo <<<EOD
                <div style='zindex:100; position:fixed; top:{$link_yUp}px; left:{$link_x}px; border-left: 1px solid #000; border-top: 1px solid #000;  width:{$link_w}px; height:{$link_h}px'>$round  $index</div>
                <div style='zindex:100; position:fixed; top:{$link_yLo}px; left:{$link_x}px; border-left: 1px solid #ff0000; border-bottom: 1px solid #ff0000;  width:{$link_w}px; height:{$link_h}px;'>$round  $index</div>

EOD;

        }
    }
}
