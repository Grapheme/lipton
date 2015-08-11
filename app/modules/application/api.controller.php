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
    public static function returnInfo() {
    }

    public static function returnMenu() {
    }

    public static function returnActions() {
    }

    /****************************************************************************/
    public function debug($method = 'config') {

        if (method_exists(__CLASS__, $method)):
            return self::$method(Input::all());
        else:
            echo 'Error...';
        endif;
    }

    /****************************************************************************/
    private function validCode($result, $code = NULL) {

        if (isset($result['curl_info']['http_code'])):
            if (is_null($code)):
                if ($result['curl_info']['http_code'] >= 200 && $result['curl_info']['http_code'] <= 299):
                    return TRUE;
                endif;
            elseif ($result['curl_info']['http_code'] == $code):
                return TRUE;
            endif;
        endif;
        return FALSE;
    }

    private function validAvailableOperation($operation) {

        if ($operations = $this->availableOperations()):
            if (in_array($operation, $operations)):
                return TRUE;
            endif;
        endif;
        return FALSE;
    }

    /****************************************************************************/
    public function config() {

        Helper::ta($this->headers);
    }

    private function getErrorMessage($result) {

        if (isset($result['curl_result'])):
            $xml_object = new SimpleXMLElement($result['curl_result']);
            $message = array();
            foreach ($xml_object->validationError as $messages):
                $message[] = (string)$messages->message;
            endforeach;
            if (!empty($message)):
                return implode("<br>\n", $message);
            endif;
        endif;
        return FALSE;
    }

    private function create_xml($xml, array $vars = []) {

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

    private function getXmlValues($xml, $item, $value, $tag = NULL) {

        if (isset($xml)):
            $xml_object = new SimpleXMLElement($xml);
            $return_value = array();
            if (empty($items)):
                foreach ($xml_object as $item => $item_value):
                    if ($item == $value):
                        if (!is_null($tag)):
                            $return_value[] = (string)$item_value[$tag];
                        else:
                            $return_value[] = (string)$item_value->$value;
                        endif;
                    endif;
                endforeach;
            endif;
            return $return_value;
        endif;
        return FALSE;
    }

    private function getXmlValue($xml, $items, $value) {

        if (isset($xml)):
            $xml_object = new SimpleXMLElement($xml);
            if (empty($items)):
                return (string)$xml_object->$value;
            elseif (is_string($items)):
                return (string)$xml_object->$items->$value;
            elseif (is_array($items)):
                foreach ($items as $item):
                    print_r($item);
                    exit;
                endforeach;
            endif;
        endif;
        return FALSE;
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
            $headers[] = trim($header) . ': ' . trim($value);
        endforeach;
        return $headers;
    }

    private function getCurl($url) {

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
        $data['curl_result'] = curl_exec($ch);
        $data['curl_info'] = curl_getinfo($ch);
        curl_close($ch);
        return $data;
    }

    /****************************************************************************/
    /***************************** Регистрация **********************************/
    /****************************************************************************/
    public function availableOperations() {

        $uri_request = $this->config['server_url'] . '/v2/customers/current/available-operations';
        $result = $this->getCurl($uri_request);
        return $this->getXmlValues($result, '', 'allowedOperation', 'name');

    }

    public function email_availability(array $params = []) {

        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . '/v2/customers/email-availability?email=' . @$params['email'];
        $result = self::getCurl($uri_request);
        self::make_xml();
        return Response::xml([], 200, [], $result);
    }

    public function send_register(array $params = [], $operation = 'Unilever.FillSlimProfile') {

        if ($this->validAvailableOperation($operation) === FALSE):
            Config::set('api.message', 'Операция добавление новых пользователей недоступна.');
            return FALSE;
        endif;
        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers?operation=$operation";
        $sex = array('none', 'female', 'male');
        ob_start();
        ?>
        <customer>
        <name last="<?= $params['surname']; ?>" first="<?= $params['name']; ?>" middle=""/>
        <sex><?= @$sex[$params['sex']]; ?></sex>
        <email><?= $params['email']; ?></email>
        <birthdate year="<?= (int)$params['yyyy']; ?>" month="<?= (int)$params['mm']; ?>"
                   day="<?= (int)$params['dd']; ?>"/>
        <password value="<?= $params['password']; ?>" value2="<?= $params['password']; ?>"/>
        <subscription isActiveForCurrentBrand="true"/>
        <?php if (isset($params['network']) && !empty($params['network']) && isset($params['uid']) && !empty($params['uid'])): ?>
            <externalIdentities>
                <externalIdentity provider="<?= $params['network']; ?>" value="<?= $params['uid']; ?>"/>
            </externalIdentities>
        <?php endif; ?>
        </customer><?php
        $xml = ob_get_clean();
        $this->strlen_xml = strlen($xml);
        $result = $this->postCurl($uri_request, $xml);
        if ($this->validCode($result, 201)):
            $user = array();
            $user['id'] = $this->getXmlValue($result['curl_result'], '', 'id');
            $user['sessionKey'] = $this->getXmlValue($result['curl_result'], '', 'sessionKey');
            return $user;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function activateEmail($params) {

        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/ticket?ticket=$params";
        $result = $this->postCurl($uri_request);
        if ($this->validCode($result, 200)):
            $user = array();
            $user['id'] = $this->getXmlValue($result['curl_result'], '', 'id');
            $user['sessionKey'] = $this->getXmlValue($result['curl_result'], '', 'sessionKey');
            return $user;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }
    /****************************************************************************/
}