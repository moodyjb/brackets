<?php

use app\assets\BracketsAsset;

BracketsAsset::register($this);
echo "<div style='overflow-y:scroll'>";
$gameId=0;
foreach ($this->context->nodes as $node) {

    if ($node['round']==1) {
        echo <<<EOD
            <button id='node_{$node["id"]}' class='btn btn-default'
            style='position:absolute; top:{$node["team_y"]}px; left:{$node["team_x"]}px;
            width:{$this->context->team_w}px; height:{$this->context->team_h}px;
            border:solid 1px #000; background-color:#fff;'>
            {$node["team"]}
            </button>
EOD;

    } else {
        $gameId++;
        echo <<<EOD
            <button  id = 'node_{$node["id"]}' style='position:absolute; top:{$node["team_y"]}px; left:{$node["team_x"]}px;
            width:{$this->context->team_w}px; height:{$this->context->team_h}px; padding:4px;
            border:solid 1px #000; background-color:#fff;'
            data-parent='{$node["parent"]}'
            data-lChild='{$node["lChild"]}'
            data-rChild='{$node["rChild"]}'
            >{$node["team"]}</button>

            <div style='zindex:100; position:absolute; top:{$node["link_yLeft"]}px; left:{$node["link_x"]}px;
            border-right: 1px solid #000; border-top: 1px solid #000;
            width:{$this->context->link_w}px; height:{$node["link_h"]}px'></div>

            <div style='zindex:100; position:absolute; top:{$node["link_yRight"]}px; left:{$node["link_x"]}px;
            border-right: 1px solid #000; border-bottom: 1px solid #000;
            width:{$this->context->link_w}px; height:{$node["link_h"]}px'></div>
EOD;
    }
}
?>
<div>
