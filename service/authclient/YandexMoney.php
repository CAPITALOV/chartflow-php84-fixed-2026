<?php


namespace app\service\authclient;

use cs\services\Url;
use yii\authclient\OAuth2;
use yii\helpers\VarDumper;

/**
 * YandexMoney allows authentication via Yandex OAuth.
 *
 * In order to use Yandex OAuth you must register your application at <https://oauth.yandex.ru/client/new>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'yandex' => [
 *                 'class' => 'yii\authclient\clients\YandexMoney',
 *                 'clientId' => 'yandex_client_id',
 *                 'clientSecret' => 'yandex_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see    https://oauth.yandex.ru/client/new
 * @see    http://api.yandex.ru/login/doc/dg/reference/response.xml
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since  2.0
 */
class YandexMoney extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://sp-money.yandex.ru/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://sp-money.yandex.ru/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://money.yandex.ru/api';


    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('info', 'GET');
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }
        $params['oauth_token'] = $accessToken->getToken();

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'yandex_money';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'YandexMoney';
    }

    public function auth22()
    {
        $defaultParams = [
            'client_id'     => $this->clientId,
            'response_type' => 'code',
            'redirect_uri'  => 'http://c.galaxysss.ru/yandexMoney',
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }

        $params = $this->sendRequest2('POST', $this->authUrl, $defaultParams, []);
        $url = new Url($params[1]['redirect_url']);
        $code = $url->getParam('requestid');

        $defaultParams = [
            'code'         => $code,
            'client_id'    => $this->clientId,
            'grant_type'   => 'authorization_code',
            'redirect_uri' => 'http://c.galaxysss.ru/yandexMoney',
        ];
        $params = $this->sendRequest2('POST', $this->tokenUrl, $defaultParams, []);

        \cs\services\VarDumper::dump($params);
    }


    /**
     * Посылает HTTP запрос
     *
     * @param string $method  request type.
     * @param string $url     request URL.
     * @param array  $params  request params.
     * @param array  $headers additional request headers.
     *
     * @return array [$response, $responseHeaders]
     */
    protected function sendRequest2($method, $url, array $params = [], array $headers = [])
    {
        $curlOptions = $this->mergeCurlOptions(
            $this->defaultCurlOptions(),
            $this->getCurlOptions(),
            [
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL            => $url,
            ],
            $this->composeRequestCurlOptions(strtoupper($method), $url, $params)
        );
        $curlResource = curl_init();
        foreach ($curlOptions as $option => $value) {
            curl_setopt($curlResource, $option, $value);
        }
        $response = curl_exec($curlResource);
        $responseHeaders = curl_getinfo($curlResource);

        // check cURL error
        $errorNumber = curl_errno($curlResource);
        $errorMessage = curl_error($curlResource);

        curl_close($curlResource);

        return [$response, $responseHeaders];
    }

}
