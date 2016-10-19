<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\profile\models\ProfileField */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Profile Field',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="profile-field-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
