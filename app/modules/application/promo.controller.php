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
        Route::get('/participant/{url}', array('as' => 'show.participant.writing', 'uses' => $class . '@showWriting'));
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
    public function showWriting($url) {

        if ($writing = UserWritings::where('id', (int)$url)->where('status', '>', 0)->with('user.ulogin')->first()):
            $page_data = array(
                'page_title' => 'Рассказ от ' . @$writing->user->name . ' ' . @$writing->user->surname,
                'page_description' => '',
                'page_keywords' => '',
                'writing' => $writing,
            );
            return View::make(Helper::layout('writing'), $page_data);
        else:
            App::abort(404);
        endif;

    }

    /****************************************************************************/

    public function firstRegister() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'next_code' => FALSE, 'redirectURL' => FALSE);
        $validator = Validator::make(Input::all(), array('promoCode1' => 'required'));
        if ($validator->passes()):
            $result = self::registerPromoCode(Input::get('promoCode1'));
            if ($result === -1):
                Auth::logout();
                $json_request['responseText'] = Config::get('api.message');
                $json_request['redirectURL'] = pageurl('auth');
                return Response::json($json_request, 200);
            elseif ($result === FALSE):
                $json_request['status'] = FALSE;
            else:
                $json_request['status'] = TRUE;
                $json_request['next_code'] = TRUE;
            endif;
            $json_request['responseText'] = Config::get('api.message');
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

        $json_request = array('status' => FALSE, 'responseText' => '', 'next_code' => FALSE, 'redirectURL' => FALSE);
        $validator = Validator::make(Input::all(), array('promoCode2' => 'required'));
        if ($validator->passes()):
            $user_codes_count = UserCodes::where('user_id', Auth::user()->id)->count();
            if ($user_codes_count == 0):
                $json_request['responseText'] = 'Сначала вводите первый промо-код.';
                return Response::json($json_request, 200);
            elseif ($user_codes_count == 2):
                $json_request['responseText'] = 'Вы уже вводили второй промо-код.';
                return Response::json($json_request, 200);
            elseif ($user_codes_count >= 3):
                $json_request['responseText'] = 'Вы не можете больше вводить промо-коды.';
                return Response::json($json_request, 200);
            endif;
            $result = self::registerPromoCode(Input::get('promoCode2'));
            if ($result === -1):
                Auth::logout();
                $json_request['redirectURL'] = pageurl('auth');
                return Response::json($json_request, 200);
            elseif ($result === FALSE):
                $json_request['status'] = FALSE;
            else:
                $json_request['status'] = TRUE;
                $json_request['next_code'] = FALSE;
                Session::flash('message', Config::get('api.message'));
                $json_request['redirectURL'] = URL::route('dashboard');
            endif;
            $json_request['responseText'] = Config::get('api.message');
        else:
            $json_request['responseText'] = $validator->messages()->all();
        endif;
        if (Request::ajax()):
            return Response::json($json_request, 200);
        else:
            return Redirect::route('mainpage');
        endif;

    }

    public function thirdRegister() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirectURL' => FALSE);
        $validator = Validator::make(Input::all(), array('message' => 'required'));
        if ($validator->passes()):
            $story = UserWritings::where('user_id', Auth::user()->id)->first();
            if (!$story):
                $story = new UserWritings();
                $story->user_id = Auth::user()->id;
                $story->writing = Input::get('message');
                $story->status = 0;
                $story->save();
            elseif ($story->status == 2):
                $json_request['responseText'] = 'Ваш рассказ находится на модерации. Дождитесь пожалуйста ответа.';
                Session::flash('message', $json_request['responseText']);
                return Response::json($json_request, 200);
            elseif ($story->status == 1):
                $json_request['responseText'] = 'Ваш рассказ прошел модерацию. Дождитесь завершения розыгрыша.';
                Session::flash('message', $json_request['responseText']);
                return Response::json($json_request, 200);
            else:
                $story->writing = Input::get('message');
                $story->status = 2;
                $story->save();
                $json_request['responseText'] = 'Ваш рассказ отправлен на модерацию';
                Session::flash('message', $json_request['responseText']);
                $json_request['redirectURL'] = URL::route('dashboard');
                $json_request['status'] = TRUE;
            endif;
        else:
            $json_request['responseText'] = $validator->messages()->all();
        endif;
        if (Request::ajax()):
            return Response::json($json_request, 200);
        else:
            return Redirect::route('mainpage');
        endif;

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
        elseif ($api_result === -1):
            return -1;
        endif;
    }
}