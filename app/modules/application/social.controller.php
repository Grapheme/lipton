<?php

class SocialController extends BaseController {

    public static $name = 'social';
    public static $group = 'accounts';
    public static $entity = 'social';
    public static $entity_name = 'Работа с социальными сетями';

    /****************************************************************************/
    public static function returnRoutes() {
        $class = __CLASS__;
        Route::post('social-signin', ['as' => 'signin.ulogin', 'uses' => $class . '@postUlogin']);
    }

    public static function returnShortCodes() {
    }

    public static function returnActions() {
    }

    public static function returnInfo() {
    }

    public static function returnMenu() {
    }

    /****************************************************************************/
    public function __construct() {
    }

    /****************************************************************************/
    public function postUlogin() {

        $url_request = 'http://ulogin.ru/token.php?token=' . Input::get('token') . '&host=' . $_SERVER['HTTP_HOST'];
        $result = (new ApiController())->getCurl($url_request, FALSE);
        $_user = json_decode($result['curl_result'], true);
        $validate = Validator::make([], []);
        if (isset($_user['error'])):
            return Redirect::to(URL::route('page', 'registering'));
        endif;
        $post['provider'] = $_user['network'];
        $post['identity'] = $_user['uid'];
        $api_social = (new ApiController())->social_logon($post);
        if (is_array($api_social)):
            if(Ulogin::where('identity', '=', $_user['identity'])->exists() === FALSE):
                $post = array();
                $post['customerId'] = @$api_social['id'];
                $post['sessionKey'] = @$api_social['sessionKey'];
                $api = (new ApiController())->get_register($post);
                if (isset($api['email'])):
                    $password = Str::random(8);
                    $post['remote_id'] = $post['customerId'];
                    $post['email'] = $api['email'];
                    $post['name'] = @$api['name'];
                    $post['surname'] = @$api['surname'];
                    $post['sex'] = @$api['sex'] == 'female' ? 0 : 1;
                    $post['dd'] = @$api['dd'];
                    $post['mm'] = @$api['mm'];
                    $post['yyyy'] = @$api['yyyy'];
                    $post['phone'] = @$api['phone'];
                    $post['city'] = @$api['city'];
                    $post['password'] = Hash::make($password);
                    $post['code'] = Input::get('promo-code');
                    $user  = (new RegisterController())->getRegisterAccount($post);
                    (new RegisterController())->createULogin($user->id, $post);
                endif;
            endif;
        else:
            if (Config::has('api.message')):
                Session::flash('message', Config::get('api.message'));
            else:
                Session::flash('message', 'Возникла ошибка при авторизации через социальную сеть.');
            endif;
            return Redirect::to(pageurl('auth') . '#message');
        endif;
        if ($check = Ulogin::where('identity', '=', $_user['identity'])->first()):
            Auth::loginUsingId($check->user_id, FALSE);
            Auth::user()->active = 1;
            Auth::user()->remote_id = @$api_social['id'];
            Auth::user()->sessionKey = @$api_social['sessionKey'];
            Auth::user()->save();
            if (isset($_COOKIE['firstCodeCookie']) && !empty($_COOKIE['firstCodeCookie'])):
                $result = PromoController::registerPromoCode($_COOKIE['firstCodeCookie']);
                Session::flash('message', Config::get('api.message'));
                setcookie("firstCodeCookie", "", time() - 3600, '/');
                return Redirect::to(AuthAccount::getGroupStartUrl() . '#message');
            endif;
            return Redirect::to(AuthAccount::getGroupStartUrl());
        elseif (isset($_user['email']) && User::where('email', @$_user['email'])->exists()):
            return Redirect::to(URL::route('page', 'registering'))
                ->with('token', Input::get('token'))
                ->with('email', @$_user['email'])
                ->with('identity', @$_user['identity'])
                ->with('profile', @$_user['profile'])
                ->with('first_name', @$_user['first_name'])
                ->with('last_name', @$_user['last_name'])
                ->with('sex', @$_user['sex'] - 1)
                ->with('bdate', @$_user['bdate'])
                ->with('uid', @$_user['uid'])
                ->with('photo_big', @$_user['photo_big'])
                ->with('photo', @$_user['photo'])
                ->with('network', @$_user['network'])
                ->with('verified_email', @$_user['verified_email']);
        else:
            $rules = array('network' => 'required|max:255', 'identity' => 'required|max:255|unique:ulogin',
                'email' => 'required|unique:ulogin|unique:users');
            $validate = Validator::make($_user, $rules);
            if ($validate->passes()):
                return Redirect::to(URL::route('page', 'registering'))
                    ->with('token', Input::get('token'))
                    ->with('email', @$_user['email'])
                    ->with('identity', @$_user['identity'])
                    ->with('profile', @$_user['profile'])
                    ->with('first_name', @$_user['first_name'])
                    ->with('last_name', @$_user['last_name'])
                    ->with('sex', @$_user['sex'] - 1)
                    ->with('bdate', @$_user['bdate'])
                    ->with('uid', @$_user['uid'])
                    ->with('photo_big', @$_user['photo_big'])
                    ->with('photo', @$_user['photo'])
                    ->with('network', @$_user['network'])
                    ->with('verified_email', @$_user['verified_email']);
            else:
                return Redirect::to(URL::route('page', 'registering'));
            endif;
        endif;
    }
    /****************************************************************************/
}