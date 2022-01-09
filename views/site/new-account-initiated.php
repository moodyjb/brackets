<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'New Account Process Started';
?>

<style>
.wrapper {
  display: grid;
  grid-template-columns:  40%;
  grid-gap: 20px;
  background-color: #fff;
  color: #444;
}
</style>
<div class="site-login">

        <h1><?= $this->title ?></h1>
        <div style='font-size:1.33em;'>
            Check you email inbox for a confirmation email. Open the email and click the "here" link.

        </div>

</div>
