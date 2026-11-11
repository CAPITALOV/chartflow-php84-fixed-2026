<?php

namespace app\controllers;

use app\models\User;
use cs\base\BaseController;
use cs\services\VarDumper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\Request;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\service\RegistrationDispatcher;
use app\service\PasswordRecoverDispatcher;
use cs\web\Exception;
use \YandexMoney\API;

class Yandex_moneyController extends BaseController
{
    public $layout = 'main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionAuth()
    {
        /** @var \app\service\authclient\YandexMoney $client */
        $client = Yii::$app->authClientCollection->getClient('yandex_money');
        $code = self::getParam('code');
        $access_token_response = API::getAccessToken($client->clientId, $code, 'https://kapitalov.localhost/yandexMoney', $client->clientSecret);
        if(property_exists($access_token_response, "error")) {
            // process error
        }
        $access_token = $access_token_response->access_token;
    }

    public function actionTest2()
    {
        $access_token = '410011473018906.8B0BD62ED86765ED98DE3B5EBE22348AC68900066E8EAC7093EB9B8A831D00DAC6556C6B40BA284F2B5391A5EEBCA47C9F755BC4A713584F71D8470D8D660CEDA8A455E290868CC1817ED867D350B5C1074A37CE62F662D94025D799638A30034651FBF656A74BBD003FC402E77BAD140883D414C77B4228BC7A7940B9833164';
        $api = new API($access_token);

        // get account info
        $acount_info = $api->accountInfo();
        VarDumper::dump($acount_info);
    }

    /**
     * Попытка сгенерировать auth_url перейти на него в новом окне
     */
    public function actionTest1()
    {
        /** @var \app\service\authclient\YandexMoney $client */
        $client = Yii::$app->authClientCollection->getClient('yandex_money');
        $auth_url = API::buildObtainTokenUrl($client->clientId, 'http://kapitalov.localhost/yandexMoney', ['account-info']);

        return $this->render([
            'url' => $auth_url,
        ]);
    }
}
