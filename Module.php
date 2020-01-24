<?php

namespace rushstart\user;

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * user module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\user\controllers';

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        dpm('jjj');
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                'user' => "{$this->id}/user/index",
                'user/<action>' => "{$this->id}/user/<action>",
            ], false);
        }
    }
}
