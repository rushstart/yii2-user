Yii 2 User
=========

Yii2-user is designed to work out of the box. It means that installation requires
minimal steps. Only one configuration step should be taken and you are ready to
have user management on your Yii2 website.

### 1. Download

Yii2-user can be installed using composer. Run following command to download and
install Yii2-user:

```bash
composer require rushstart/yii2-user
```

### 2. Configure

> **NOTE:** Make sure that you don't have `user` component configuration in your config files.

```php
return [
    'bootstrap' => [
        'userModule',
    ],
    'components' => [
        'user' => [
            'class' => 'rushstart\user\components\UserComponent',
            'identityClass' => 'rushstart\user\models\Identity',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
            'urlPrefix' => 'user', //The prefix for user module URL.
        ],
    ],
    'modules' => [
        'userModule' => [
            'class' => 'rushstart\user\Module',
        ],
    ],
];

```

### 3. Update database schema

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

```bash
$ php yii migrate/up --migrationPath=@vendor/rushstart/yii2-user/migrations
```

or add the migrate configuration to the console config file `config/console.php`
```php
return [
    'id' => 'app-console',
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
                '@rushstart/user/migrations',
            ],
        ],
    ],
];
```