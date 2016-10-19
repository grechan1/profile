<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\profile\models\ProfileField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-field-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'varname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field_type')->dropDownList($model::itemAlias('field_type')) ?>

    <?= $form->field($model, 'field_size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field_size_min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'required')->dropDownList($model::itemAlias('required')) ?>

    <?= $form->field($model, 'match')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'range')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'error_message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_validator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'default')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'widget')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'widgetparams')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['value' => 0]) ?>

    <?= $form->field($model, 'visible')->dropDownList($model::itemAlias('visible')) ?>

    <?= $form->field($model, 'change')->dropDownList($model::itemAlias('change')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
