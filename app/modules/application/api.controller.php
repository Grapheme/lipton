<?php


class ApiController extends BaseController {

    public static $name = 'api';
    public static $group = 'application';

    private $config;
    private $headers;
    private $xml;
    private $strlen_xml;

    /****************************************************************************/
    public static function returnRoutes() {
        $class = __CLASS__;
        Route::group(array('prefix' => 'api'), function () use ($class) {
            Route::any('debug/{method}', array('uses' => $class . '@debug'));
        });
    }

    /****************************************************************************/
    public function __construct() {

        $this->headers = Config::get('directcrm.headers');
        $this->config['server_url'] = Config::get('directcrm.server_url');
        $this->config['host'] = Config::get('directcrm.host');
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');
        $this->strlen_xml = 0;
    }

    /****************************************************************************/
    public static function returnInfo() {}

    public static function returnMenu() {}

    public static function returnActions() {}

    /****************************************************************************/
    public function debug($method = 'config') {

        if (method_exists(__CLASS__, $method)):
            return self::$method(Input::all());
        else:
            echo 'Error...';
        endif;
    }

    /****************************************************************************/
    public function config() {

        Helper::ta($this->headers);
    }

    private function create_xml($xml, array $vars = []){

        if (is_null($xml)):
            $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');
        elseif (is_string($xml)):
            $this->xml = new SimpleXMLElement($xml);
        endif;
        foreach ($vars as $key => $value):
            $this->xml->addChild($key, $value);
        endforeach;
    }

    private function make_xml() {

        Response::macro('xml', function (array $vars, $status = 200, array $headers = [], $xml = null) {
            $xml_out = self::create_xml($xml, $vars);
            if (empty($headers)):
                $headers = self::handle();
            endif;
            return Response::make($xml_out->asXML(), $status, $headers);
        });
    }

    private function getXmlValue($xml, $value) {

        if (is_string($xml)):
            $xml = new SimpleXMLElement($xml);
        endif;
        #return $xml->addChild($key, $value);
    }

    private function handle() {

        return array(
            'X-Customer-Agent' => $this->headers['x_customer_agent'],
            'Accept' => 'application/xml',
            #'Authorization' => 'DirectCrm key="'.$this->headers['authorization']['key'].'" staffLogin="'.$this->headers['authorization']['staffLogin'].'" staffPassword="'.$this->headers['authorization']['staffPassword'].'"',
            'Authorization' => 'DirectCrm key="' . $this->headers['authorization']['key'] . '" customerId="' . $this->headers['authorization']['customerId'] . '" sessionKey="' . $this->headers['authorization']['sessionKey'] . '"',
            'X-Brand' => $this->headers['x_brand'],
            'X-Channel' => $this->headers['x_chanel'],
            'X-Customer-IP' => Request::ip(),
            'X-Customer-URI' => $this->headers['x_customer_url'],
            'Content-Type' => 'application/xml; charset=utf-8',
            'User-Agent' => 'Fiddler',
            'Host' => $this->config['host'],
            'Content-Length' => $this->strlen_xml
        );
    }

    private function curlHandle() {

        $headers = array();
        foreach (self::handle() as $header => $value):
            $headers[] = $header . ': ' . $value;
        endforeach;
        return $headers;
    }

    private function getCurl($url, $post_data = NULL) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::curlHandle());
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function postCurl($url, $post_data = NULL) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::curlHandle());
        if (!is_null($post_data)):
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        endif;
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    /****************************************************************************/
    /***************************** Регистрация **********************************/
    /****************************************************************************/
    public function email_availability(array $params = []) {

        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . '/v2/customers/email-availability?email=' . @$params['email'];
        $result = self::getCurl($uri_request);
        self::make_xml();
        return Response::xml([], 200, [], $result);
    }

    public function send_register(array $params = []) {

        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . '/v2/customers?operation=Unilever.FillSlimProfile';
        ob_start();
?><customer>
    <name last="Харсеев" first="Владимир" middle="Александрович" />
    <sex>male</sex>
    <email>test@test.ru</email>
    <birthdate year="1980" month="06" day="20" />
    <password value="123456" value2="123456" />
    <subscription isActiveForCurrentBrand="true" />
    <externalIdentities>
        <externalIdentity provider="vkontakte" value="123456" />
    </externalIdentities>
</customer><?php
        $xml = ob_get_clean();
        $this->strlen_xml = mb_strlen($xml);
        Helper::ta(self::curlHandle());
        Helper::ta($xml);

        $result = $this->postCurl($uri_request, $xml);
        Helper::tad($result);
    }
    /****************************************************************************/
}