<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class MatchForm extends Model
{
    public $home;
    public $hScore;
    public $visitor;
    public $vScore;


    public function rules()
    {
        return [
            // email and password are both required
            [['home', 'hScore', 'visitor', 'vScore'], 'safe'],

        ];
    }
}
