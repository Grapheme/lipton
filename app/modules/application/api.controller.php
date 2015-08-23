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
            $result = self::$method(Input::all());
            if (is_array($result)):
                Helper::ta($result);
            elseif (is_json($result)):
                Helper::ta(json_decode($result, TRUE));
            elseif (is_string($result)):
                echo $result;
            endif;
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

        $operations = $this->availableOperations();
        if (is_array($operations)):
            if (in_array($operation, $operations)):
                return TRUE;
            else:
                return FALSE;
            endif;
        endif;
        return $operations;
    }

    /****************************************************************************/
    public function config() {

        Helper::ta($this->headers);
    }

    private function getErrorMessage($result) {

        if (isset($result['curl_result']) && !empty($result['curl_result'])):
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

    private function getXmlValue($xml, $items, $value, $tag = NULL) {

        if (isset($xml)):
            $xml_object = new SimpleXMLElement($xml);
            if (empty($items) && empty($value) && !is_null($tag)):
                return (string)$xml_object->attributes()->$tag;
            elseif (empty($items)):
                if (!is_null($tag)):
                    return (string)$xml_object->$value->attributes()->$tag;
                else:
                    return (string)$xml_object->$value;
                endif;
            elseif (is_string($items)):
                if (!is_null($tag)):
                    return (string)$xml_object->$items->$value->attributes()->$tag;
                else:
                    return (string)$xml_object->$items->$value;
                endif;
            endif;
        endif;
        return FALSE;
    }

    private function handle() {

        return array(
            'X-Customer-Agent' => $this->headers['x_customer_agent'],
            'Accept' => 'application/xml',
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

    public function getCurl($url, $headers = TRUE) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        if ($headers):
            curl_setopt($ch, CURLOPT_HTTPHEADER, self::curlHandle());
        endif;
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data['curl_result'] = curl_exec($ch);
        $data['curl_info'] = curl_getinfo($ch);
        $data['curl_headers'] = self::curlHandle();
        curl_close($ch);
        return $data;
    }

    public function postCurl($url, $post_data = NULL) {

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
        $data['curl_headers'] = self::curlHandle();
        curl_close($ch);
        return $data;
    }

    public function putCurl($url, $post_data = NULL) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::curlHandle());
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data['curl_result'] = curl_exec($ch);
        $data['curl_info'] = curl_getinfo($ch);
        $data['curl_headers'] = self::curlHandle();
        curl_close($ch);
        return $data;
    }

    /****************************************************************************/
    /***************************** Регистрация **********************************/
    /****************************************************************************/
    public function availableOperations() {

        $uri_request = $this->config['server_url'] . '/v2/customers/current/available-operations';
        $result = $this->getCurl($uri_request);
        if ($this->validCode($result, 200)):
            return $this->getXmlValues($result['curl_result'], '', 'allowedOperation', 'name');
        else:
            return -1;
        endif;
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

    public function send_register(array $params = [], $operation = 'Unilever.FillLightProfile') {

        if ($this->validAvailableOperation($operation) === FALSE):
            Config::set('api.message', 'Операция добавление новых пользователей недоступна.');
            return FALSE;
        endif;
        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers?operation=$operation";
        $sex = array('female', 'male');
        ob_start();
        ?>
        <customer>
        <name last="<?= $params['surname']; ?>" first="<?= $params['name']; ?>" middle=""/>
        <sex><?= @$sex[$params['sex']]; ?></sex>
        <email><?= $params['email']; ?></email>
        <mobilePhone><?= $params['phone'] ?></mobilePhone>
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
            if (empty($user['id']) && empty($user['sessionKey'])):
                if ($message = $this->getErrorMessage($result)):
                    Config::set('api.message', $message);
                endif;
                return FALSE;
            else:
                return $user;
            endif;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function get_register(array $params = [], $operation = 'Unilever.EditLightProfile') {

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];
        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            return $valid;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция обновление профиля пользователей недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current?operation=$operation";
        $result = $this->getCurl($uri_request);
        if ($this->validCode($result, 200)):
            $return['version'] = $this->getXmlValue($result['curl_result'], '', 'version');
            if (empty($return['version']) && empty($return['version'])):
                if ($message = $this->getErrorMessage($result)):
                    Config::set('api.message', $message);
                endif;
                return FALSE;
            else:
                return $return;
            endif;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function update_register(array $params = [], $operation = 'Unilever.EditLightProfile') {

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];

        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            return $valid;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция обновление профиля пользователей недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current?operation=$operation";
        $sex = array('female', 'male');
        ob_start();
        ?>
        <customer>
        <name last="<?= $params['surname']; ?>" first="<?= $params['name']; ?>" middle=""/>
        <version><?= $params['version']; ?></version>
        <sex><?= @$sex[$params['sex']]; ?></sex>
        <email><?= $params['email']; ?></email>
        <mobilePhone><?= $params['phone'] ?></mobilePhone>
        <password value="" value2=""/>
        <birthdate year="<?= (int)$params['yyyy']; ?>" month="<?= (int)$params['mm']; ?>"
                   day="<?= (int)$params['dd']; ?>"/>
        <subscription isActiveForCurrentBrand="true"/>
        </customer><?php
        $xml = ob_get_clean();
        $this->strlen_xml = strlen($xml);
        $result = $this->putCurl($uri_request, $xml);
        if ($this->validCode($result, 200)):
            $user = array();
            $user['id'] = $this->getXmlValue($result['curl_result'], '', 'id');
            $user['sessionKey'] = $this->getXmlValue($result['curl_result'], '', 'sessionKey');
            if (empty($user['id']) && empty($user['sessionKey'])):
                if ($message = $this->getErrorMessage($result)):
                    Config::set('api.message', $message);
                endif;
                return FALSE;
            else:
                return $user;
            endif;
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
            $message = $this->getXmlValue($result['curl_result'], 'messages', 'message');
            Config::set('api.message', $message);
            $user['id'] = $this->getXmlValue($result['curl_result'], '', 'id');
            $user['sessionKey'] = $this->getXmlValue($result['curl_result'], '', 'sessionKey');
            if (empty($user['id']) && empty($user['sessionKey'])):
                if ($message = $this->getErrorMessage($result)):
                    Config::set('api.message', $message);
                endif;
                return FALSE;
            else:
                return $user;
            endif;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function activatePhone(array $params = [], $operation = 'DirectCrm.MobilePhoneConfirmation') {

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];
        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            Config::set('api.message', 'Авторизуйтесь для выполнения операции.');
            return -1;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция подтвержения номера телефона недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/confirm-mobile-phone?operation=$operation&code=" . $params['code'];
        $result = $this->postCurl($uri_request);
        if ($this->validCode($result, 200)):
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
                return FALSE;
            endif;
            return TRUE;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function resendMobilePhoneConfirmation(array $params = [], $operation = 'DirectCrm.MobilePhoneConfirmationResend') {

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];
        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            Config::set('api.message', 'Авторизуйтесь для выполнения операции.');
            return -1;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция повторной отправки SMS для подтверждения номера мобильного телефона недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/resend-mobile-phone-confirmation?operation=$operation";
        $result = $this->postCurl($uri_request);
        if ($this->validCode($result, 200)):
            $message = $this->getXmlValue($result['curl_result'], 'messages', 'message');
            Config::set('api.message', $message);
            return TRUE;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function logon(array $params = [], $operation = 'DirectCrm.LogOn') {

        if ($this->validAvailableOperation($operation) === FALSE):
            Config::set('api.message', 'Операция авторизаици пользователей недоступна.');
            return FALSE;
        endif;
        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/logon/via-password?operation=" . $operation . "&credential=" . $params['login'] . "&password=" . $params['password'] . "&mode=session";
        $result = $this->postCurl($uri_request);
        if ($this->validCode($result, 200)):
            $user = array();
            $user['id'] = $this->getXmlValue($result['curl_result'], '', 'id');
            $user['sessionKey'] = $this->getXmlValue($result['curl_result'], '', 'sessionKey');
            if (empty($user['id']) && empty($user['sessionKey'])):
                if ($message = $this->getErrorMessage($result)):
                    Config::set('api.message', $message);
                endif;
                return FALSE;
            else:
                return $user;
            endif;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function social_logon(array $params = [], $operation = 'DirectCrm.ExternalLogOn') {

        if ($this->validAvailableOperation($operation) === FALSE):
            Config::set('api.message', 'Операция авторизаици пользователей недоступна.');
            return FALSE;
        endif;
        if (empty($params)):
            App::abort(404);
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/logon?operation=$operation";
        ob_start();
        ?>
        <credentials mode="session">
        <provider><?= $params['provider']; ?></provider>
        <identity><?= $params['identity']; ?></identity>
        </credentials><?php
        $xml = ob_get_clean();
        $this->strlen_xml = strlen($xml);
        $result = $this->postCurl($uri_request, $xml);
        if ($this->validCode($result, 200)):
            $user = array();
            $user['id'] = $this->getXmlValue($result['curl_result'], '', 'id');
            $user['sessionKey'] = $this->getXmlValue($result['curl_result'], '', 'sessionKey');
            if (empty($user['id']) && empty($user['sessionKey'])):
                if ($message = $this->getErrorMessage($result)):
                    Config::set('api.message', $message);
                endif;
                return FALSE;
            else:
                return $user;
            endif;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function restore_password(array $params = [], $operation = 'DirectCrm.RestorePassword') {

        if (empty($params)):
            App::abort(404);
        endif;
        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            return $valid;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/actions?operation=$operation&contact=" . $params['email'];
        $result = $this->postCurl($uri_request);
        if ($this->validCode($result, 201)):
            $message = $this->getXmlValue($result['curl_result'], 'messages', 'message');
            Config::set('api.message', $message);
            return TRUE;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }
    /****************************************************************************/
    /******************************* PROMO **************************************/
    /****************************************************************************/

    public function activate_promo_code(array $params = [], $operation = 'LiptonArkenstone2015PolzovatelAktivirovalPromoCode') {

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];
        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            Config::set('api.message', 'Авторизуйтесь для выполнения операции.');
            return $valid;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция регистрации промо кодов недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/activate-code?operation=$operation";
        ob_start();
        ?>
        <activate>
        <code><?= $params['code']; ?></code>
        </activate><?php
        $xml = ob_get_clean();
        $this->strlen_xml = strlen($xml);
        $result = $this->postCurl($uri_request, $xml);
        if ($this->validCode($result, 200)):
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
                return FALSE;
            else:
                $message = $this->getXmlValue($result['curl_result'], 'messages', 'message');
                Config::set('api.message', $message);
                return TRUE;
            endif;
        elseif ($this->validCode($result, 401)):
            return -1;
        elseif ($this->validCode($result, 400)):
            return -1;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }

    public function register_certificate(array $params = []){

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];
        $valid = $this->validAvailableOperation('DirectCrm.OrderPrize');
        if ($valid === -1):
            return $valid;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция обновление профиля пользователей недоступна.');
            return FALSE;
        endif;
        $uri_request = $this->config['server_url'] . "/v1/LiptonDiscovery2015/lipton/users/".$params['customerId']."/orderprize.xml?prizesystemname=".$params['prizesystemname'];
        if(!empty($params['wonLotteryTicketId'])):
            $uri_request .= "&wonLotteryTicketId=".$params['wonLotteryTicketId'];
        endif;
        $result = self::postCurl($uri_request);
        if ($this->validCode($result, 200)):
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
                return FALSE;
            else:
                $message = $this->getXmlValue($result['curl_result'], 'messages', 'message');
                Config::set('api.message', $message);
                return TRUE;
            endif;
        elseif ($this->validCode($result, 401)):
            return -1;
        elseif ($this->validCode($result, 400)):
            return -1;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;

        Helper::tad($result);
    }

    public function get_prizes(array $params = [], $operation = 'DirectCrm.GetCustomersPrizesGeneralData') {

        if (empty($params)):
            App::abort(404);
        endif;
        $this->headers['authorization']['customerId'] = $params['customerId'];
        $this->headers['authorization']['sessionKey'] = $params['sessionKey'];
        $valid = $this->validAvailableOperation($operation);
        if ($valid === -1):
            Config::set('api.message', 'Авторизуйтесь для выполнения операции.');
            return $valid;
        elseif ($valid === FALSE):
            Config::set('api.message', 'Операция получения призов недоступна.');
            return FALSE;
        endif;
        $this->strlen_xml = 0;
        $uri_request = $this->config['server_url'] . "/v2/customers/current/prizes.xml?operation=$operation&countToReturn=10&includeLotteryTickets=OnlyNotWon";
        $result = self::getCurl($uri_request);
        if ($this->validCode($result, 200)):
            $totalCount = $this->getXmlValue($result['curl_result'], '', '', 'totalCount');
            $prizes = array();
            if ($totalCount > 0):
                $xml_object = new SimpleXMLElement($result['curl_result']);
                $prizes_list = array();
                foreach ($xml_object as $item => $item_value):
                    $prizes_list[] = array(
                        'customerPrize_id' => (string)$item_value->attributes()->id,
                        'displayName' => (string)$item_value->displayName,
                        'systemName' => (string)$item_value->systemName,
                        'activatedCode' => (string)$item_value->activatedCode,
                        'certificateCode' => (string)$item_value->certificateCode,
                    );
                endforeach;
                if(count($prizes_list) > 1):
                    foreach($prizes_list as $index => $prize):
                        $prizes[$prize['systemName']] = $prize;
                    endforeach;
                else:
                    $prizes['LiptonLinguaLeoForTravellers'] = $prizes_list[0];
                endif;
            endif;
            return $prizes;
        elseif ($this->validCode($result, 401)):
            return -1;
        elseif ($this->validCode($result, 400)):
            return -1;
        else:
            Config::set('api.message', 'Возникла ошибка на сервере регистрации.');
            if ($message = $this->getErrorMessage($result)):
                Config::set('api.message', $message);
            endif;
            return FALSE;
        endif;
    }
}