<?php

namespace rushstart\user;

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * User module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{

    /**
     * The root URL of the module.
     * @var string
     */
    public $baseUrl = 'user';

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                $this->baseUrl => "{$this->id}/user/index",
                "{$this->baseUrl}/<action>" => "{$this->id}/user/<action>",
            ], false);
        }
    }
}
