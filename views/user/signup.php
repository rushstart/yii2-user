<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model rushstart\user\models\SignupForm */

$this->title = 'Регистрация';

?>
<div class="signup-box">
    <div class="signup-box__title">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="signup-box__body">
        <p class="signup-box__msg">Заполните все поля для регистрации</p>
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>


        <div class="form-group">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn--primary btn--block', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <?= Html::a('Войти', ['login']) ?><br>
    </div>
</div>

