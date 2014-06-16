<?php

namespace SendGrid;

class Web
{
    const WEB_API_URL_FORMAT = 'https://api.sendgrid.com/api/%1$s.%2$s.%3$s';

    private $username;

    private $password;

    private $options;

    private $format = 'json';

    public function __construct($username, $password, $options)
    {
        $this->username = $username;
        $this->password = $password;
        $this->options  = $options;
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getBlocks($params = array())
    {
        return $this->performWebApiRequest($params, 'blocks', 'get', 'get');
    }

    public function deleteBlock(array $params)
    {
        return $this->performWebApiRequest($params, 'blocks', 'delete');
    }

    public function countBlocks($params = array())
    {
        return $this->performWebApiRequest($params, 'blocks', 'count', 'get');
    }

    public function getBounces($params = array())
    {
        return $this->performWebApiRequest($params, 'bounces', 'get', 'get');
    }

    public function deleteBounce(array $params)
    {
        return $this->performWebApiRequest($params, 'bounces', 'delete');
    }

    public function countBounces($params = array())
    {
        return $this->performWebApiRequest($params, 'bounces', 'count', 'get', 'get');
    }

    public function getInvalidEmails($params = array())
    {
        return $this->performWebApiRequest($params, 'invalidemails', 'get', 'get');
    }

    public function deleteInvalidEmail(array $params)
    {
        return $this->performWebApiRequest($params, 'invalidemails', 'delete');
    }

    public function getSpamReports($params = array())
    {
        return $this->performWebApiRequest($params, 'spamreports', 'get', 'get');
    }

    public function deleteSpamReports(array $params)
    {
        return $this->performWebApiRequest($params, 'spamreports', 'delete');
    }

    public function getUnsubscribes($params = array())
    {
        return $this->performWebApiRequest($params, 'unsubscribes', 'get', 'get');
    }

    public function deleteUnsubscribe(array $params)
    {
        return $this->performWebApiRequest($params, 'unsubscribes', 'delete');
    }

    public function addUnsubscribe(array $params)
    {
        return $this->performWebApiRequest($params, 'unsubscribes', 'add');
    }

    public function getParseWebhookSettings($params = array())
    {
        return $this->performWebApiRequest($params, 'parse', 'get', 'get');
    }

    public function setWebhookSettings(array $params)
    {
        return $this->performWebApiRequest($params, 'parse', 'set');
    }

    public function deleteWebhook(array $params)
    {
        return $this->performWebApiRequest($params, 'parse', 'delete');
    }

    public function getAvailableFilters()
    {
        return $this->performWebApiRequest(array(), 'filter', 'getAvailable', 'get');
    }

    public function activateFilter(array $params)
    {
        return $this->performWebApiRequest($params, 'filter', 'activate');
    }

    public function deactivateFilter(array $params)
    {
        return $this->performWebApiRequest($params, 'filter', 'deactivate');
    }

    public function setupFilter(array $params)
    {
        return $this->performWebApiRequest($params, 'filter', 'setup');
    }

    public function getFilterSettings(array $params)
    {
        return $this->performWebApiRequest($params, 'filter', 'getsettings', 'get');
    }

    public function getProfile()
    {
        return $this->performWebApiRequest(array(), 'profile', 'get', 'get');
    }

    public function setProfile($params)
    {
        return $this->performWebApiRequest($params, 'profile', 'set');
    }

    public function setPassword(array $params)
    {
        return $this->performWebApiRequest($params, 'password', 'set');
    }

    public function setUsername(array $params)
    {
        return $this->performWebApiRequest($params, 'profile', 'setUsername');
    }

    public function setEmail(array $params)
    {
        return $this->performWebApiRequest($params, 'profile', 'setEmail');
    }

    public function getCredentials($params)
    {
        return $this->performWebApiRequest($params, 'credentials', 'get', 'get');
    }

    public function addCredentials($params)
    {
        return $this->performWebApiRequest($params, 'credentials', 'add');
    }

    public function editCredentials($params)
    {
        return $this->performWebApiRequest($params, 'credentials', 'edit');
    }

    public function deleteCredentials($params)
    {
        return $this->performWebApiRequest($params, 'credentials', 'remove');
    }

    public function getStats($params = array())
    {
        return $this->performWebApiRequest($params, 'stats', 'get');
    }

    public function getAdvancedStats(array $params)
    {
        return $this->performWebApiRequest($params, 'stats', 'getAdvanced');
    }

    protected function performWebApiRequest(array $params, $endpoint, $action, $method = 'post')
    {
        $form = $params;
        $form['api_user'] = $this->username;
        $form['api_key']  = $this->password;

        $url = self::buildUrl($endpoint, $action, $this->format);

        // option to ignore verification of ssl certificate
        if (isset($this->options['turn_off_ssl_verification']) && $this->options['turn_off_ssl_verification'] == true) {
            \Unirest::verifyPeer(false);
        }

        switch($method){
            case 'post':
                $response = \Unirest::post($url, array(), $form);
                break;
            case 'get':
                $response = \Unirest::get($url, array(), $form);
                break;
            case 'delete':
                $response = \Unirest::delete($url, array(), $form);
                break;
            case 'put':
                $response = \Unirest::put($url, array(), $form);
                break;
            case 'patch':
                $response = \Unirest::patch($url, array(), $form);
                break;
            default:
                $response = \Unirest::get($url, array(), $form);
        }

        return $response->body;
    }

    protected static function buildUrl($endpoint, $action, $format)
    {
        return sprintf(self::WEB_API_URL_FORMAT, $endpoint, $action, $format);
    }


}