<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\profile\models\ProfileField */

use backend\modules\profile\assets\ProfileFieldAsset;
// now Yii puts your css and javascript files into your view's html.
ProfileFieldAsset::register($this);

$this->title = Yii::t('app', 'Create Profile Field');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-field-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
