<?php
namespace app\controllers;

use app\models\Configuration;
use app\models\Brackets;

use yii;

use yii\base\Model;
use yii\filters\VerbFilter;
use yii\db\Query;

class BracketsController extends \yii\web\Controller
{
    public $brackets;
    public $team_w;
    public $team_h;
    public $link_w;
    public $yOffset;
    public $ySpace;
    public $noTeams;
    public $team_offSet_x;
    public $base0;
    public $nodes;
    public $buffer;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'ruleConfig' => ['class' => 'app\components\AccessRule'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    /*
    *
    */
    public function actionEditNode($node_id)
    {

        list(,$node_id) = explode("_", $_GET['node_id']);

        $models[0] = Brackets::findOne($node_id);
        $models[0]->winner = 56;
        if ($models[0]->round > 1) {
            $models[1] = Brackets::findOne($models[0]->lChild);
            $models[2] = Brackets::findOne($models[0]->rChild);


        }
        // print_r($_POST);
        // exit;

        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            if ($models[0]->winner == 1) {
                $models[0]->team = $models[1]->team;
            } elseif ($models[0]->winner == 2) {
                $models[0]->team = $models[2]->team;
            }
            foreach ($models as $model) {
                $model->save();
            }

            return $this->redirect(['view']);
        }

        if ($models[0]->team) {
            if ($models[1]->team == $models[0]->team) {
                $models[0]->winner = 1;
            } elseif ($models[2]->team == $models[0]->team) {
                $models[0]->winner = 2;
            }
        }
        return $this->renderAjax(
            '_editNode',
            [
                'models' => $models,
            ]
        );
    }
    public function printTree($index)
    {
        $this->buffer .= "  $index  ";
        if ($this->nodes[$index]['lChild']) {
            $this->printTree($this->nodes[$index]['lChild']);
        } else {
            $this->buffer .= "<br>";
        }
        if ($this->nodes[$index]['rChild']) {
            $this->printTree($this->nodes[$index]['rChild']);
        } else {
            $this->buffer .= "<br>";
        }


    }
    // ilevel corresponds to 2**ilevel.
    // ilevel = 0 is the resulting champion, single team
    // ilevel = 2**nlevel is the opening round of all teams
    // nlevel = 3 => 2**3 = 8 teams in the tournament
    public function seq($ilevel, $nlevels, $parent)
    {
        if ($ilevel < $nlevels) {
            $round = $nlevels - $ilevel;
            $this->nodes[] = ['level'=>$ilevel, 'round' => $round, 'parent'=>$parent];
            $this->nodes[$parent]['lChild'] = count($this->nodes)-1;
            $this->seq($ilevel+1, $nlevels, count($this->nodes)-1);

            $this->nodes[] = ['level'=>$ilevel, 'round' => $round, 'parent'=>$parent];
            $this->nodes[$parent]['rChild'] = count($this->nodes)-1;
            $this->seq($ilevel+1, $nlevels, count($this->nodes)-1);
        }
        return;
    }
    public function actionView()
    {
        $bc = Configuration::findOne(1);

        $this->yOffset = $bc->yBracketsStart;
        $this->ySpace = $bc->yTeamSeparationFactor; //1.75;  // 'y' separation of team boxes in multiple of team box height
        $this->team_w = $bc->team_w;    //135;
        $this->team_h = $bc->team_h;    //30;
        $this->team_offSet_x = $bc->xTeamSeparationFactor*$this->team_w;  //3.0*$this->team_w;
        $this->link_w =  $this->team_offSet_x - 0.50*$this->team_w;

        $this->nodes= [];
        $brackets = Brackets::find()->all();
        foreach ($brackets as $index => $bracket) {
            $this->nodes[] = $bracket->attributes;
        }
        return $this->render("_dev03");
    }
    /*
    *   Nodes created from 1, 2, 4, 8 teams... going from chanpion back to all participants
    *   dummy change for tutorial
    */
    public function actionCreate($bracket_id)
    {
        $config = Configuration::findOne(1);

        $this->yOffset = $config->yBracketsStart;      //51;
        $this->ySpace = $config->yTeamSeparationFactor; //1.75;  // 'y' separation of team boxes in multiple of team box height
        $this->team_w = $config->team_w;                //135;
        $this->team_h = $config->team_h;                //30;
        $this->team_offSet_x = $config->xTeamSeparationFactor*$this->team_w;
        $this->link_w =  $this->team_offSet_x - 0.50*$this->team_w;

        $this->nodes = [];
        $this->noTeams = $config->noTeams;
        $noTeam = $this->noTeams;
        $noRnds = log($this->noTeams)/log(2);
        $this->nodes[0] = ['level'=>0, 'round'=> $noRnds+1, 'parent'=>-1];

        $this->seq(1, $noRnds+1, 0);

        // add sibling pointers
        foreach ($this->nodes as $cIndex => $child) {
            foreach ($this->nodes as $pIndex => $parent) {

                // link together all nodes in the same round
                if ($pIndex > $cIndex) {
                    if ($child['level'] == $parent['level']) {
                        // sibling
                        if (!$child['sibling']) {
                            $this->nodes[$cIndex] += ['sibling'=>$pIndex];
                        }
                    }
                }
            }
        }

        // Calc locations
        $last_x=null;
        for ($r=1, $t=-1; $r<$noRnds+2; $r++) {
            foreach ($this->nodes as $index => $node) {
                $id = $index;
                $this->nodes[$index]['id'] = $id;
                $this->nodes[$index]['team_w'] = $this->team_w;
                $this->nodes[$index]['team_h'] = $this->team_h;
                $this->nodes[$index]['link_w'] = $this->link_w;

                if ($node['round'] == $r) {
                    if ($r ==1) {
                        $t++;
                        $team_x = 50;
                        $this->nodes[$index]['team_y'] = $this->yOffset + $t*$this->ySpace*$this->team_h;
                        $this->nodes[$index]['team_x'] = $team_x;

                    } else {
                        $lChild = $node['lChild'];
                        $rChild = $node['rChild'];
                        $team_y = ($this->nodes[$lChild]['team_y'] + $this->nodes[$rChild]['team_y'])/2;
                        $this->nodes[$index]['team_y'] = $team_y;
                        $team_x = 50 + ($r-1)* $this->team_offSet_x;
                        $this->nodes[$index]['team_x'] = $team_x;


                        $last_y0 = $this->nodes[$lChild]['team_y'];
                        $last_y1 = $this->nodes[$rChild]['team_y'];

                        $link_h = ($last_y1 - $last_y0 - $this->team_h)/2;
                        $link_y0 = $team_y - $link_h;
                        $link_y1 = $team_y + $this->team_h;

                        $this->nodes[$index] += ['link_x'=>$last_x, 'link_yLeft'=>$link_y0, 'link_yRight'=>$link_y1,
                                                'link_h'=>$link_h];
                    }
                }
            }

            $last_x = $team_x + $this->team_w;
        }

        // $x = '';
        // foreach ($this->nodes as $index => $node) {
        //     $x .= "<br>index=$index";
        //     $x .= "   ".print_r($node, true);
        // }
        // return $x;


        //Saved to table
        yii::$app->db->createCommand("truncate table brackets")->execute();
        foreach ($this->nodes as $index => $node) {
            $model = new Brackets();
            $model->attributes = $node;
            if (!$model->save()) {
                print_r($model->getErrors());
                exit;
            }
        }
        return $this->redirect(['view']);

        $x = '';
        foreach ($this->nodes as $index => $node) {
            $x .= "<br>index=$index";
            $x .= "   ".print_r($node, true);
        }
        $this->buffer = '';
        $this->printTree(0);
        return $x."<br><br>".$this->buffer;

    }
    /*
    *   Develop data structure
    */
    public function actionDev02()
    {
        $this->noTeams=8;
        $x='';
        $node_id=0;
        $noRnds = log($this->noTeams)/log(2);
        $nodes = [];
        for ($r=1; $r < $noRnds+2; $r++) {

            // calc future node_id for next round
            $noTeams = $this->noTeams;
            $parent0 = 0;
            $child0 = 0;
            if ($r < $noRnds+1) {
                for ($p=1; $p<$r+1; $p++) {
                    $parent0 += $noTeams;
                    if ($p < $r) {
                        $child0 += $noTeams;
                    }
                    $noTeams /= 2;
                }
                $x .= "<br>r=$r parent0=$parent0 child0=$child0 noTeams=$noTeams";
            }
            for ($t=0; $t < floor($this->noTeams/2**($r-1)); $t++) {
                $node_id++;
                $parent = $parent0 + 1+ floor($t/2);
                if ($r ==1) {
                    $nodes[$node_id] = ['r'=>$r, 'hiChild'=>$child0+$t,'loChild'=>-1,'parent'=>$parent];
                } else {
                    $nodes[$node_id] = ['r'=>$r, 'hiChild'=>$child0+$t+1,'loChild'=>-1,'parent'=>$parent];
                }
            }
        }
        foreach ($nodes as $index => $node) {
            $x .= "<br>r=$r  index=$index";
            $x .= print_r($node, true);
        }
        return $x;

    }
    /*
    *   Dev seeeding
    */
    public function seed($noRounds)
    {

        $x = '';
        $match[1] = 2;
        for ($r=1; $r<$noRounds; $r++) {
            // convert match[home]=visitor into list of teams [home, visitor., ..]
            $teams = [];
            foreach ($match as $home => $visitor) {
                    $teams[] = $home;
                    $teams[] = $visitor;
            }
            // save team as it has the correct sequence of the games... these denoted as home teams
            $works = $teams;

            // order teams into ascending
            sort($works);

            // generate all teams in this round
            $potential = range(1, 2**($r+1));
            // visitor teams
            $potential = array_diff($potential, $works);

            // pair all home with visitors ... each home paired with LAST visitor
            $katch = [];
            foreach ($works as $home) {
                    $katch[$home] = array_pop($potential);
            }
            $match = [];
            foreach ($teams as $home) {
                $match[$home] = $katch[$home];
            }
            $x .= "<br><br>match=".print_r($match, true);

        }
        //return $x;
        $sequence = [];
        foreach ($match as $home => $visitor) {
            $sequence[] = $home;
            $sequence[] = $visitor;
        }
        return $sequence;


    }
    /*
    *   Diagram brackets
    *   <--- Ids --->
    *   node_id
    *   <---Pointers--->
    *   parent (next round)
    *   next (sibling)
    *   left (child)
    *   right (child)
    *   <--- structure --->
    *   left_ptr
    *   right_ptr
    *   next_ptr
    *   winner_ptr
    *   parent_ptr
    *   <--- outcomes --->
    *   left_score  (73)
    *   right_score (77)
    *   winner_id   (unique team id)
    *
    */
    public function actionIndex03()
    {
        $this->yOffset = 51;
        $this->ySpace =1.75;  // 'y' separation of team boxes in multiple of team box height
        $this->team_w = 135;
        $this->team_h = 30;
        $this->team_offSet_x = 3.0*$this->team_w;
        $this->link_w =  $this->team_offSet_x - 0.50*$this->team_w;
        $this->noTeams = 16;

        $x='';
        $gameId=0;
        $noRnds = log($this->noTeams)/log(2);
        $last_x = null;
        $sequence = $this->seed($noRnds);
        for ($r=1; $r < $noRnds+2; $r++) {
            $team_x = 50 + ($r-1)* $this->team_offSet_x;

            for ($t=0; $t < floor($this->noTeams/2**($r-1)); $t++) {

                if ($r == 1) {
                    $team_y = $this->yOffset + $t*$this->ySpace*$this->team_h;
                    $seed = array_shift($sequence);
                    $team_id = "team$seed";
                } else {
                    $gameId++;
                    $last_y0 = $this->brackets[$r-1][2*$t]['team_y'];
                    $last_y1 = $this->brackets[$r-1][2*$t+1]['team_y'];
                    $team_y = ($last_y0+$last_y1)/2;
                    $link_h = ($last_y1 - $last_y0 - $this->team_h)/2;
                    $link_y0 = $team_y - $link_h;
                    $link_y1 = $team_y + $this->team_h;
                    $team_id = '';
                    $home =  $this->brackets[$r-1][2*$t]['team_id'];
                    $visitor =  $this->brackets[$r-1][2*$t+1]['team_id'];
                }

                $this->brackets[$r][$t] = ['gameId'=>$gameId, 'round'=>$r, 'team_x'=>$team_x,
                'team_y'=>$team_y, 'link_x'=>$last_x, 'link_y0'=>$link_y0, 'link_y1'=>$link_y1,
                'link_h'=>$link_h,
                'team_id'=>$team_id,
                'home'=>$home, 'visitor'=>$visitor,
                'home_score'=>'', 'visitor_score'=>''
                ];


            }
            $last_x = $team_x + $this->team_w;
        }
        return $this->render('_brackets03', [
            'sequence' => $this->seed($noRnds)
        ]);

        $x='';
        foreach ($this->brackets as $r => $bracket) {
            foreach ($bracket as $t => $game) {
                $x .= "<br>r=$r  t=$t";
                $x .= print_r($game, true);
            }
        }
        return $x;
    }
    /*
    *
    */
    public function generate02($ilevel, $nlevel)
    {
        if ($ilevel > $nlevel) return;
        $no=1;
        for ($k=$nlevel; $k>$ilevel; $k--) {
            $no += 2**$k;
        }
        $round = $nlevel+1-$ilevel;

        $seqNo = count($this->brackets[$round]);
        $gameNo = $no + $seqNo;
        $this->brackets[$round][$seqNo]=['box_id'=>$gameNo, 'team_x'=>$ilevel*2.00*$this->team_w,
            'link_w' => $this->link_w];
        if ($round==1) {
            $this->brackets[$round][$seqNo] += ['team_y'=>$this->yOffset+$seqNo*$this->ySpace*$this->team_h];
        }

        $this->generate02($ilevel+1, $nlevel, $seqNo);

        $seqNo++;
        $gameNo = $no + $seqNo;
        $this->brackets[$round][$seqNo]=['box_id'=>$gameNo, 'team_x'=>$ilevel*2.0*$this->team_w,
            'link_w' => $this->link_w];
        if ($round==1) {
            $this->brackets[$round][$seqNo] += ['team_y'=>$this->yOffset+$seqNo*$this->ySpace*$this->team_h];
        }
        $this->generate02($ilevel+1, $nlevel, $seqNo);

    }
    public function actionIndex02()
    {
        $nlimit = 5;
        for ($i=1; $i<$nlimit; $i++) {
            $this->brackets[$i] = [];
        }
        $this->yOffset = 75;
        $this->ySpace =2.00;  // 'y' separation of team boxes in multiple of team box height
        $this->team_w = 135;
        $this->team_h = 30;
        $this->link_w = 1.5*$this->team_w;

        $this->brackets[5][0] = ['box_id'=>31, 'team_x'=>0];
        $this->generate02(1, 4);

        for ($round=1; $round<count($this->brackets); $round++) {
            for ($i=0; $i<count($this->brackets[$round]); $i+=2) {
                $parent = floor($i/2);
                $yUp = $this->brackets[$round][$i]['team_y'];
                $yLo = $this->brackets[$round][$i+1]['team_y'];
                $this->brackets[$round+1][$parent]['team_y'] = ($yUp+$yLo)/2;
                $this->brackets[$round+1][$parent]['link_h'] = ($yLo-$yUp)/2;
            }
        }

        return $this->render('_brackets02', [
            'brackets' => $this->brackets,
        ]);

        // $x = "<br><br>";
        // foreach ($this->brackets as $rnd => $bracket) {
        //     $x .= "<br>round=$rnd";
        //     foreach ($bracket as $j => $tmp) {
        //         $x .= "<br> ---- j=$j  ";
        //         $x .= print_r($tmp, true);
        //     }
        // }
        // return $x;


    }
    public function generate($ilevel, $parent, $nlevel)
    {
        // ['round','gameNo','nextGameNo','team_x','team_y','team_w','team_h','link_x','link_w'
        if ($ilevel < $nlevel) {
            //$x = 200*$ilevel;
            $x = $ilevel*($this->link_w + $this->team_w/2);
            $id = is_array($this->brackets[$ilevel]) ? count($this->brackets[$ilevel]) : 0;
            $y=0;
            $team_y = 0;
            if ($nlevel-$ilevel == 1) {
                // round 0
                $y = 55+$id*50;
                $team_y = 55+$id*2*$this->team_h;
            }
            $team_x = $ilevel*($this->link_w + $this->team_w/2);
            $link_x = $team_x +  $this->team_w/2;

            $this->brackets[$ilevel][] =  ['id'=>$id, 'parent'=>$parent, 'x'=>$x, 'y'=> $y,
                'team_x'=>$team_x, 'team_y'=>$team_y, 'team_w'=>$this->team_w,
                'link_x'=>$link_x, 'link_w'=>$this->link_w];
            $this->generate($ilevel+1, $id, $nlevel);

            $id = is_array($this->brackets[$ilevel]) ? count($this->brackets[$ilevel]) : 0;

            if ($nlevel-$ilevel == 1) {
                // round 0
                $y = 55+$id*50;
                $team_y = 55+$id*2*$this->team_h;
            }
            $this->brackets[$ilevel][] = ['id'=>$id, 'parent'=>$parent, 'x'=>$x, 'y'=>$y,
                'team_x'=>$team_x, 'team_y'=>$team_y, 'team_w'=>$this->team_w,
                'link_x'=>$link_x, 'link_w'=>$this->link_w];
            $this->generate($ilevel+1, $id, $nlevel);


        } else {
            return;
        }
    }
    public function actionIndex()
    {
        $this->team_w = 135;
        $this->team_h = 30;
        $this->link_w = 200;
        $this->brackets[0][] = ['id'=>0, 'parent'=>-1, 'team_x'=>0, 'y'=>55];

        $this->generate(1, 0, 5);




        $x='';
        for ($lvl = 4; $lvl>0; $lvl--) {

            for ($k=0; $k<count($this->brackets[$lvl]); $k+=2) {

                $parentId = $this->brackets[$lvl][$k]['parent'];
                $y1 = $this->brackets[$lvl][$k]['team_y'];
                $y2 = $this->brackets[$lvl][$k+1]['team_y'];

                $this->brackets[$lvl-1][$parentId]['y'] = ($y1+$y2)/2;
                $this->brackets[$lvl-1][$parentId]['deltaY'] = ($y2-$y1)/2;

                $this->brackets[$lvl-1][$parentId]['team_y'] = ($y1+$y2)/2;
                $this->brackets[$lvl-1][$parentId]['link_h'] = ($y2-$y1)/2;

            }
        }
        $x = "<br><br>";
        foreach ($this->brackets as $lvl => $bracket) {
            $x .= "<br>lvl=$lvl";
            foreach ($bracket as $j => $tmp) {
                $x .= "<br> ---- j=$j  ";
                $x .= print_r($tmp, true);
            }
        }
        return $x;

        return $this->render('_brackets', [
            'brackets' => $this->brackets,
        ]);

    }

}
