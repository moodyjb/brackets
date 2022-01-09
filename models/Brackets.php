<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brackets".
 *
 * @property int $id
 * @property int|null $level
 * @property int|null $round
 * @property int|null $parent
 * @property int|null $sibling
 * @property int|null $rChild
 * @property int|null $lChild
 * @property float|null $team_x
 * @property float|null $team_y
 * @property float|null $link_x
 * @property float|null $link_yLeft
 * @property float|null $link_yRight
 * @property float|null $link_h
 * @property string|null $team
 */
class Brackets extends \yii\db\ActiveRecord
{
    public $winner;

    public static function tableName()
    {
        return 'brackets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'level', 'round', 'parent', 'sibling', 'rChild', 'lChild', 'score','winner'], 'integer'],
            [['team_x', 'team_y',
                'team_w','team_h',
            'link_x', 'link_yLeft', 'link_yRight',
                'link_w','link_h'], 'number'],
            [['team'],'string'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'round' => 'Round',
            'parent' => 'Parent',
            'sibling' => 'Sibling',
            'rChild' => 'R Child',
            'lChild' => 'L Child',
            'team_x' => 'Team X',
            'team_y' => 'Team Y',
            'team_w' => 'Team W',
            'team_h' => 'Team H',
            'link_x' => 'Link X',
            'link_yLeft' => 'Link Y Left',
            'link_yRight' => 'Link Y Right',
            'link_h' => 'Link H',
            'link_w' => 'Link W',
            'team' =>'Team',
            'score' => 'Score',
        ];
    }
}
