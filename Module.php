<?php

namespace rushstart\user;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\i18n\PhpMessageSource;

/**
 * User module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{

    /**
     * The prefix for user module URL.
     * @var string
     */
    public $urlPrefix = 'user';

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     * @throws InvalidConfigException
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                $this->urlPrefix => "{$this->id}/user/index",
                "{$this->urlPrefix}/<action>" => "{$this->id}/user/<action>",
            ], false);
        }
        if (!isset($app->get('i18n')->translations['user*'])) {
            $app->get('i18n')->translations['user*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US'
            ];
        }
    }
}
