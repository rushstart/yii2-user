<?php


namespace rushstart\user\components;



use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use rushstart\user\models\Identity;
use rushstart\user\models\UserAccount;
use Yii;
use rushstart\user\ClientInterface;
use yii\db\Exception;
use yii\web\IdentityInterface;

class AuthHandler
{
    /**
     * @var ClientInterface
     */
    protected $client;
    /**
     * The source name
     * @var string
     */
    protected $source;
    /**
     * The unique identifier of the source
     * @var string
     */
    protected $sourceId;
    /**
     * An user name received from the source
     * @var mixed
     */
    protected $username;

    protected $_account = false;

    /**
     * AuthHandler constructor.
     * @param ClientInterface|\yii\authclient\ClientInterface $client
     * @throws InvalidConfigException
     */
    public function __construct($client)
    {
        if($client instanceof ClientInterface || $client instanceof \yii\authclient\ClientInterface){
            $this->client = $client;
            $attributes = $client->getUserAttributes();
            $this->source = $this->client->getId();
            $this->sourceId = ArrayHelper::getValue($attributes, 'id');
            $this->username = ArrayHelper::getValue($attributes, 'name', 'noname');
        }else{
            throw new InvalidConfigException('$client must implement the ClientInterface');
        }

    }

    /**
     * @return UserAccount|null
     */
    protected function getAccount()
    {
        if ($this->_account === false) {
            $this->_account = UserAccount::findOne([
                'source' => $this->source,
                'source_id' => $this->sourceId,
            ]);
        }
        return $this->_account;
    }

    /**
     * @return bool
     */
    public function login()
    {
        if ($this->getAccount()) {
            return Yii::$app->user->login($this->getAccount()->identity, Yii::$app->params['user.rememberMeDuration'] ?? 3600);
        }
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function signup()
    {
        $identity = new Identity([
            'name' => $this->username,
        ]);
        $identity->generateAuthKey();
        $transaction = Identity::getDb()->beginTransaction();
        if ($identity->save() && $this->attachAccount($identity)) {
            $transaction->commit();
            $this->login();
            return true;
        } else {
            Yii::$app->getSession()->addFlash('error', Yii::t('app', 'Unable to save user'));
        }
        return false;
    }

    /**
     * @param IdentityInterface $identity
     * @return bool
     */
    public function attachAccount(IdentityInterface $identity)
    {
        $this->_account = new UserAccount([
            'user_id' => $identity->getId(),
            'source' => $this->source,
            'source_id' => $this->sourceId,
        ]);
        if ($this->getAccount()->save()) {
            Yii::$app->getSession()->addFlash('success', Yii::t('app', 'Linked {client} account.'));
            return true;
        }
        Yii::$app->getSession()->addFlash('error', Yii::t('app', 'Unable to save {client} account'));
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function handle()
    {
        if (Yii::$app->user->isGuest) {
            return $this->login() || $this->signup();
        } else {
            if (!$this->getAccount()) {
                return $this->attachAccount(Yii::$app->user->identity);
            } else {
                Yii::$app->getSession()->addFlash('error',
                    Yii::t('app', 'Unable to link {client} account. There is another user using it.')
                );
            }
        }
        return false;
    }

}