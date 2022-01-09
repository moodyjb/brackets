<?php

echo "<div style='overflow-y:scroll'>";
$gameId=0;
foreach ($this->context->brackets as $rnd => $round) {
    foreach ($round as $t => $bracket) {
        $seed = array_shift($sequence);
        if ($bracket['round']==1) {
            echo <<<EOD
                <button id='team$seed' class='btn btn-default'
                style='position:absolute; top:{$bracket["team_y"]}px; left:{$bracket["team_x"]}px;
                width:{$this->context->team_w}px; height:{$this->context->team_h}px;
                border:solid 1px #000; background-color:#fff;'>
                Seed {$seed}:
                </button>
EOD;
        } else {
            $gameId++;
            echo <<<EOD
                <div  id = 'gm{$gameId}' style='position:absolute; top:{$bracket["team_y"]}px; left:{$bracket["team_x"]}px;
                width:{$this->context->team_w}px; height:{$this->context->team_h}px; padding:4px;
                border:solid 1px #000; background-color:#fff;'>{$bracket["gameId"]} - {$gameId}</div>

                <div style='zindex:100; position:absolute; top:{$bracket["link_y0"]}px; left:{$bracket["link_x"]}px;
                border-right: 1px solid #000; border-top: 1px solid #000;
                width:{$this->context->link_w}px; height:{$bracket["link_h"]}px'></div>

                <div style='zindex:100; position:absolute; top:{$bracket["link_y1"]}px; left:{$bracket["link_x"]}px;
                border-right: 1px solid #000; border-bottom: 1px solid #000;
                width:{$this->context->link_w}px; height:{$bracket["link_h"]}px'></div>




EOD;
        }
    }
}
?>
<div>
