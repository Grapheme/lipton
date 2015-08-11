<?php

class PromoController extends BaseController {

    public static $name = 'promo';
    public static $group = 'accounts';

    /****************************************************************************/

    public static function returnRoutes() {

        $class = __CLASS__;

        Route::group(array('before' => 'user.auth', 'prefix' => 'promo'), function () use ($class) {
            Route::post('/first/register', array('before' => 'csrf', 'as' => 'promo.first.register',
                'uses' => $class . '@firstRegister'));
            Route::post('/second/register', array('before' => 'csrf', 'as' => 'promo.second.register',
                'uses' => $class . '@secondRegister'));
            Route::post('/third/register', array('before' => 'csrf', 'as' => 'promo.third.register',
                'uses' => $class . '@thirdRegister'));
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

    public function firstRegister() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'next_code' => FALSE, 'redirectURL' => FALSE);
        $validator = Validator::make(Input::all(), array('promoCode1' => 'required'));
        if ($validator->passes()):
            self::registerPromoCode(Input::get('promoCode1'));
            $json_request['responseText'] = 'Код зарегистрирован';
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = $validator->messages()->all();
        endif;
        if (Request::ajax()):
            return Response::json($json_request, 200);
        else:
            return Redirect::route('mainpage');
        endif;
    }

    public function secondRegister() {

        Helper::tad(Input::all());

    }

    public function thirdRegister() {

        Helper::tad(Input::all());
    }

    public static function registerPromoCode($code) {

        $post['code'] = str_replace(' ', '', $code);
        $post['customerId'] = Auth::user()->remote_id;
        $post['sessionKey'] = Auth::user()->sessionKey;
        $api_result = (new ApiController())->activate_promo_code($post);
        if ($api_result === FALSE):
            return FALSE;
        elseif ($api_result === TRUE):
            $user_codes_count = UserCodes::where('user_id', Auth::user()->id)->count();
            $user_code = new UserCodes();
            $user_code->user_id = Auth::user()->id;
            $user_code->code_number = $user_codes_count + 1;
            $user_code->code = $code;
            $user_code->save();
            return TRUE;
        elseif ($api_result == -1):
            Auth::logout();
            return Redirect::to(pageurl('auth'));
        endif;
    }
}