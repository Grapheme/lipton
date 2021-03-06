<?php

class RegisterController extends BaseController {

    public static $name = 'registration';
    public static $group = 'application';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'guest', 'prefix' => ''), function () use ($class) {
            Route::post('registration', array('as' => 'signup.participant', 'uses' => $class . '@signup'));
            Route::post('auth', array('as' => 'auth.participant', 'uses' => $class . '@signin'));
            Route::post('auth/restore', array('as' => 'auth.participant.restore',
                'uses' => $class . '@restorePassword'));
        });
        Route::group(array('before' => '', 'prefix' => ''), function () use ($class) {
            Route::get('account/confirm-email', array('as' => 'signup-activation',
                'uses' => $class . '@validEmail'));
            Route::post('registration/valid/phone', array('as' => 'signup.valid-phone',
                'uses' => $class . '@validPhone'));
            Route::post('registration/valid/phone/resend', array('as' => 'signup.resend-mobile-phone-confirmation',
                'uses' => $class . '@resendMobilePhoneConfirmation'));
        });
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
    public function signin() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirectURL' => FALSE);
        if (Request::ajax()):
            $rules = array('login' => 'required', 'password' => 'required|alpha_num|between:4,50');
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->passes()):
                $post['login'] = Input::get('login');
                $post['password'] = Input::get('password');
                $post['code'] = Input::get('promo-code');
                $api = (new ApiController())->logon($post);
                if ($api === FALSE):
                    $json_request['responseText'] = Config::get('api.message');
                    return Response::json($json_request, 200);
                elseif (is_array($api)):
                    if ($user = User::where('email', Input::get('login'))->first()):
                        Auth::login($user);
                        if (Auth::check()):
                            Auth::user()->remote_id = @$api['id'];
                            Auth::user()->sessionKey = @$api['sessionKey'];
                            Auth::user()->password = Hash::make(Input::get('password'));
                            Auth::user()->save();
                            $json_request['redirectURL'] = URL::to(AuthAccount::getGroupStartUrl());
                            $json_request['status'] = TRUE;
                            if (isset($post['code']) && !empty($post['code'])):
                                $result = PromoController::registerPromoCode($post['code']);
                                Session::flash('message', Config::get('api.message'));
                                $json_request['redirectURL'] = URL::to(AuthAccount::getGroupStartUrl() . '#message');
                                setcookie("firstCodeCookie", "", time() - 3600, '/');
                            endif;
                        endif;
                    else:
                        $post = array();
                        $post['customerId'] = @$api['id'];
                        $post['sessionKey'] = @$api['sessionKey'];
                        $api = (new ApiController())->get_register($post);
                        if (isset($api['email'])):
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
                            $post['password'] = Hash::make(Input::get('password'));
                            $post['code'] = Input::get('promo-code');
                            if ($account = self::getRegisterAccount($post)):
                                Auth::loginUsingId($account->id, FALSE);
                                if (Auth::check()):
                                    $json_request['redirectURL'] = URL::to(AuthAccount::getGroupStartUrl());
                                    $json_request['status'] = TRUE;
                                    if (isset($post['code']) && !empty($post['code'])):
                                        $result = PromoController::registerPromoCode($post['code']);
                                        Session::flash('message', Config::get('api.message'));
                                        $json_request['redirectURL'] = URL::to(AuthAccount::getGroupStartUrl() . '#message');
                                        setcookie("firstCodeCookie", "", time() - 3600, '/');
                                    endif;
                                endif;
                            else:
                                $json_request['responseText'] = 'Возникла ошибка при регистрации';
                            endif;
                        else:
                            $json_request['responseText'] = 'Неверное имя пользователя или пароль';
                        endif;
                    endif;
                else:
                    $json_request['responseText'] = 'Возникла ошибка при регистрации';
                endif;
            else:
                $json_request['responseText'] = 'Неверно заполнены поля';
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    public function restorePassword() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirectURL' => FALSE);
        if (Request::ajax()):
            $rules = array('login' => 'required', 'password' => 'required|alpha_num|between:4,50');
            $validator = Validator::make(Input::all(), array('emailRecovery' => 'required|email'));
            if ($validator->passes()):
                $post['email'] = Input::get('emailRecovery');
                $api = (new ApiController())->restore_password($post);
                $json_request['responseText'] = Config::get('api.message');
                if ($api === FALSE):
                    return Response::json($json_request, 200);
                endif;
                $json_request['status'] = TRUE;
            else:
                $json_request['responseText'] = 'Неверно заполнены поля';
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    /****************************************************************************/

    public function signup() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'valid_phone' => FALSE, 'redirectURL' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), Accounts::$rules);
            if ($validator->passes()):
                if (User::where('email', Input::get('email'))->exists() == FALSE):
                    $password = Str::random(8);
                    $post = Input::all();
                    $post['password'] = $password;
                    $api = (new ApiController())->send_register($post);
                    if ($api === FALSE):
                        $json_request['responseText'] = Config::get('api.message');
                        return Response::json($json_request, 200);
                    elseif (is_array($api)):
                        $post['remote_id'] = @$api['id'];
                        $post['sessionKey'] = @$api['sessionKey'];
                    endif;
                    $post['password'] = Hash::make($password);
                    if ($account = self::getRegisterAccount($post)):
                        if (Config::get('directcrm.send_local_messages')):
                            Mail::send('emails.auth.signup', array('account' => $account, 'password' => $password,
                                'verified_email' => $post['verified_email']), function ($message) {
                                $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
                                $message->to(Input::get('email'))->subject('Lipton - регистрация');
                            });
                        endif;
                        self::createULogin($account->id, $post);
                        Auth::loginUsingId($account->id, FALSE);
                        $json_request['status'] = TRUE;
                        $json_request['valid_phone'] = TRUE;
                        $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        if (isset($post['code']) && !empty($post['code'])):
                            $result = PromoController::registerPromoCode($post['code']);
                            Session::flash('message', Config::get('api.message'));
                            $json_request['redirectURL'] = URL::to(AuthAccount::getGroupStartUrl() . '#message');
                            setcookie("firstCodeCookie", "", time() - 3600, '/');
                        endif;
                    endif;
                else:
                    $json_request['responseText'] = Lang::get('interface.SIGNUP.email_exist');
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.SIGNUP.fail');
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    public function validEmail() {

        if (Input::has('hash')):
            $api = (new ApiController())->activateEmail(Input::get('hash'));
            if ($api === FALSE):
                return Redirect::to('/#message')->with('message', Config::get('api.message'));
            else:
                if ($account = User::where('remote_id', $api['id'])->first()):
                    $account->active = 1;
                    $account->remote_id = $api['id'];
                    $account->sessionKey = $api['sessionKey'];
                    $account->save();
                    Auth::loginUsingId($account->id, FALSE);
                endif;
            endif;
            if (Auth::check()):
                return Redirect::route('dashboard');
            else:
                return Redirect::to('/');
            endif;
        else:
            return Redirect::to('/');
        endif;
    }

    public function validPhone() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirectURL' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), array('code' => 'required'));
            if ($validator->passes()):
                if (Auth::check()):
                    $post['code'] = Input::get('code');
                    $post['customerId'] = Auth::user()->remote_id;
                    $post['sessionKey'] = Auth::user()->sessionKey;
                    $api = (new ApiController())->activatePhone($post);
                    if ($api === -1):
                        Auth::logout();
                        $json_request['status'] = TRUE;
                        $json_request['redirectURL'] = pageurl('auth');
                        return Response::json($json_request, 200);
                    elseif ($api === FALSE):
                        $json_request['status'] = FALSE;
                    else:
                        $json_request['status'] = TRUE;
                        $json_request['responseText'] = Config::get('api.message');
                        $json_request['redirectURL'] = URL::to(AuthAccount::getGroupStartUrl());
                    endif;
                    $json_request['responseText'] = Config::get('api.message');
                endif;
            else:
                $json_request['responseText'] = 'Неверно заполнены поля';
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    public function resendMobilePhoneConfirmation() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirectURL' => FALSE);
        if (Request::ajax()):
            if (Auth::check()):
                $post['customerId'] = Auth::user()->remote_id;
                $post['sessionKey'] = Auth::user()->sessionKey;
                $api = (new ApiController())->resendMobilePhoneConfirmation($post);
                if ($api === -1):
                    Auth::logout();
                    $json_request['redirectURL'] = pageurl('auth');
                    return Response::json($json_request, 200);
                elseif ($api === FALSE):
                    $json_request['status'] = FALSE;
                else:
                    $json_request['status'] = TRUE;
                    $json_request['responseText'] = Config::get('api.message');
                endif;
                $json_request['responseText'] = Config::get('api.message');
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    /**************************************************************************/
    public function getRegisterAccount($post = NULL) {

        $user = new User;
        if (!is_null($post)):
            $user->group_id = Group::where('name', 'participant')->pluck('id');
            $user->name = $post['name'];
            $user->surname = $post['surname'];
            $user->email = $post['email'];
            $user->active = 1;

            $user->phone = $post['phone'];
            $user->sex = $post['sex'];
            $bdate = Carbon::createFromFormat('Y-m-d', $post['yyyy'] . '-' . $post['mm'] . '-' . $post['dd'])->format('Y-m-d 00:00:00');
            $user->bdate = $bdate;
            $user->city = isset($post['city']) ? $post['city'] : '';

            $user->remote_id = @$post['remote_id'];
            $user->sessionKey = @$post['sessionKey'];

            $user->password = $post['password'];
            $user->photo = '';
            $user->thumbnail = '';
            $user->temporary_code = '';
            $user->code_life = '';
            $user->save();
            $user->touch();
            return $user;
        endif;
        return FALSE;
    }

    public function createULogin($user_id, $post) {

        $ulogin = new Ulogin();

        if (!is_null($post) && isset($post['network']) && !empty($post['network'])):
            $ulogin->user_id = $user_id;
            $ulogin->network = $post['network'];
            $ulogin->identity = $post['identity'];
            $ulogin->email = $post['email'];
            $ulogin->first_name = $post['name'];
            $ulogin->last_name = $post['surname'];
            $ulogin->nickname = '';
            $ulogin->city = '';
            $ulogin->photo = $post['photo'];
            $ulogin->photo_big = $post['photo_big'];
            $ulogin->profile = $post['profile'];
            $ulogin->uid = $post['uid'];
            $ulogin->access_token = $post['token'];
            $ulogin->verified_email = $post['verified_email'];
            $ulogin->token_secret = '';

            $ulogin->bdate = $post['yyyy'] . '-' . $post['mm'] . '-' . $post['dd'];
            $ulogin->sex = $post['sex'];

            $ulogin->save();
            $ulogin->touch();
            return $ulogin;
        endif;
        return FALSE;
    }
}