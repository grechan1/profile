<?php

namespace backend\modules\profile;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\profile\controllers';
    public $defaultRoute = 'profile';
    public $controllerMap = [
        'profilefield'    => 'backend\modules\profile\controllers\ProfileFieldController',
    ];
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->setAliases([
            '@profile-assets' => __DIR__ . '/assets'
        ]);
    }
}
