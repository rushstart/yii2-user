<?php


use app\extensions\widgets\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\LoginForm */

$this->title = 'Вход';
?>

<div class="login-box">
    <div class="login-box__title">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="login-box__body">

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
        <?= Html::submitButton('Войти', [
            'class' => 'btn btn--primary btn--block',
            'name' => 'login-button'
        ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <?= Html::a('Зарегистрироваться', ['signup']) ?>
        <br>
    </div>
</div>