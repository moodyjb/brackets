<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LeagueMgtAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function init()
    {

        $this->jsOptions['position'] = View::POS_END;

        parent::init();
    }

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/leagueMgt.js',
    ];

    public $depends = [

         'yii\web\JqueryAsset',
         'yii\bootstrap\BootstrapAsset',
         'yii\bootstrap\BootstrapPluginAsset',
    ];

}
