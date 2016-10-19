<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleg
 * Date: 25.05.16
 * Time: 11:57
 * To change this template use File | Settings | File Templates.
 */
namespace backend\modules\profile\assets;
use yii\web\AssetBundle;

class ProfileFieldAsset extends AssetBundle
{
    // the alias to your assets folder in your file system
    public $sourcePath = '@profile-assets';
    // finally your files..
    public $css = [
        //'css/first-css-file.css',
        //'css/second-css-file.css',
    ];
    public $js = [
        'js/profile-field.js',
        //'js/second-js-file.js',
    ];
    // that are the dependecies, for makeing your Asset bundle work with Yii2 framework
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}