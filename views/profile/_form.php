<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\profile\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin();

    $attributes=$model->attributeLabels();
    $properties=$model->attributeProperties();
    foreach($attributes as $attribute=>$value){
        if ($attribute!='user_id'){
            //git
            $property=$properties[$attribute];
            if ($property['field_type']=='TEXT')
                echo $form->field($model, $attribute)->textarea();
            else
                echo $form->field($model, $attribute)->textInput();
        }
    }
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
