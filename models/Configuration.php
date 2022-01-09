<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bracketsConfiguration".
 *
 * @property int $user_id
 * @property int|null $yBracketsStart Distance from top of content; have to space down pass the navigation bar
 * @property float|null $yTeamSeparationFactor vertical space between team boxes; multiple of team box height
 * @property int|null $team_w width of team boxes
 * @property int|null $team_h height of team boxes
 * @property int|null $noTeams
 * @property float|null $xTeamSeparationFactor multiple of team width horizon distance between team boxes
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bracketsConfiguration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','brackets_id'], 'required'],
            [['user_id', 'yBracketsStart', 'team_w', 'team_h', 'noTeams'], 'integer'],
            [['yTeamSeparationFactor', 'xTeamSeparationFactor'], 'number'],
            [['user_id'], 'unique'],
            [['name'],'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'yBracketsStart' => 'Y Brackets Start',
            'yTeamSeparationFactor' => 'Y Team Separation Factor',
            'team_w' => 'Team W',
            'team_h' => 'Team H',
            'noTeams' => 'No Teams',
            'xTeamSeparationFactor' => 'X Team Separation Factor',
        ];
    }
}
