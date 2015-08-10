<?php

class RegisterController extends BaseController {

    public static $name = 'registration';
    public static $group = 'application';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'guest', 'prefix' => ''), function () use ($class) {
            Route::post('registration', array('before' => 'csrf', 'as' => 'signup.participant',
                'uses' => $class . '@signup'));
        });
        Route::group(array('before' => '', 'prefix' => ''), function () use ($class) {
            Route::get('registration/activation/{activate_code}', array('as' => 'signup-activation',
                'uses' => $class . '@activation'));
        });
    }

    public static function returnShortCodes() {}

    public static function returnActions() {}

    public static function returnInfo() {}

    public static function returnMenu() {}

    /****************************************************************************/

    public function __construct() {}

    /****************************************************************************/

    public function signup() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), Accounts::$rules);
            if ($validator->passes()):
                if (User::where('email', Input::get('email'))->exists() == FALSE):
                    $password = Str::random(8);
                    $post = Input::all();
                    $post['password'] = $password;
                    $api = (new ApiController())->send_register($post);
                    if($api === FALSE):
                        $json_request['responseText'] = Config::get('api.message');
                        return Response::json($json_request, 200);
                    elseif(is_array($api)):
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
                        Auth::loginUsingId($account->id, TRUE);
                        $json_request['redirect'] = URL::to(AuthAccount::getGroupStartUrl());
                        $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        $json_request['status'] = TRUE;
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

    public function activation($ticket) {

        $api = (new ApiController())->activateEmail($ticket);
        if($api === FALSE):
            return Redirect::to('/')->with('message', Config::get('api.message'));
        endif;
        if(Auth::check()):
            return Redirect::route('dashboard');
        else:
            return Redirect::to('/');
        endif;
    }

    /**************************************************************************/
    private function getRegisterAccount($post = NULL) {

        $user = new User;
        if (!is_null($post)):
            $user->group_id = Group::where('name', 'participant')->pluck('id');
            $user->name = $post['name'];
            $user->surname = $post['surname'];
            $user->email = $post['email'];
            $user->active = $post['verified_email'] == 1 ? 1 : 0;

            $user->phone = $post['phone'];
            $user->sex = $post['sex'];
            $bdate = (new myDateTime())->setDateString($post['yyyy'].'-'.$post['mm'].'-'.$post['dd'])->format('Y-m-d');
            $user->bdate = $bdate;

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

    private function createULogin($user_id, $post) {

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

            $ulogin->bdate = $post['yyyy'].'-'.$post['mm'].'-'.$post['dd'];
            $ulogin->sex = $post['sex'];

            $ulogin->save();
            $ulogin->touch();
            return $ulogin;
        endif;
        return FALSE;
    }
}