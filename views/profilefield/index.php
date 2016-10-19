<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Profile Fields');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-field-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Profile Field'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'varname',
            'title',
            'field_type',
            'field_size',
            // 'field_size_min',
            // 'required',
            // 'match',
            // 'range',
            // 'error_message',
            // 'other_validator',
            // 'default',
            // 'widget',
            // 'widgetparams',
            // 'position',
            // 'visible',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
