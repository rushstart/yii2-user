<?php


namespace rushstart\user\components;


use yii\helpers\ArrayHelper;
use rushstart\user\models\Identity;
use rushstart\user\models\User;
use rushstart\user\models\UserAuth;
use Yii;
use yii\authclient\ClientInterface;
use yii\db\Exception;

class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $sourceId = ArrayHelper::getValue($attributes, 'id');
        $name = ArrayHelper::getValue($attributes, 'name');

        /* @var UserAuth $auth */
        $auth = UserAuth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $sourceId,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var Identity $user */
                $user = $auth->identity;
                $this->updateUserInfo($user);
                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration'] ?? 3600);
            } else { // signup
                $user = new Identity([
                    'name' => $name,
                ]);
                $user->generateAuthKey();

                $transaction = User::getDb()->beginTransaction();

                if ($user->save()) {
                    $auth = new UserAuth([
                        'user_id' => $user->id,
                        'source' => $this->client->getId(),
                        'source_id' => (string)$sourceId,
                    ]);
                    if ($auth->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration'] ?? 3600);
                    } else {
                        /** @noinspection PhpComposerExtensionStubsInspection */
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save {client} account: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($auth->getErrors()),
                            ]),
                        ]);
                    }
                } else {
                    /** @noinspection PhpComposerExtensionStubsInspection */
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to save user: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($user->getErrors()),
                        ]),
                    ]);
                }
            }

        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new UserAuth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$sourceId,
                ]);
                if ($auth->save()) {
                    /** @var Identity $user */
                    $user = $auth->identity;
                    $this->updateUserInfo($user);
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                } else {
                    /** @noinspection PhpComposerExtensionStubsInspection */
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        //pass
    }
}