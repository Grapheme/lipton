<?php

class ParticipantController extends BaseController {

    public static $name = 'participant';
    public static $group = 'accounts';

    /****************************************************************************/

    public static function returnRoutes() {

        $class = __CLASS__;

        Route::group(array('before' => 'user.auth', 'prefix' => 'participant'), function () use ($class) {
            Route::get('profile', array('as' => 'profile.edit', 'uses' => $class . '@profileEdit'));
            Route::get('tell-story', array('as' => 'profile.tell-story', 'uses' => $class . '@tellStory'));
            Route::post('profile', array('as' => 'profile.save', 'uses' => $class . '@profileSave'));
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

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

        );
        View::share('module', $this->module);
    }

    /****************************************************************************/

    public function profileEdit() {

        $page_data = array(
            'page_title' => 'Личный кабинет',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => Accounts::where('id', Auth::user()->id)->with('ulogin')->first(),
        );
        return View::make(Helper::acclayout('profile'), $page_data);
    }

    public function profileSave() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirectURL' => FALSE);
        $validator = Validator::make(Input::all(), Accounts::$update_rules);
        if ($validator->passes()):
            $post = Input::all();
            if (self::accountUpdate($post)):
                $result = self::crmAccountUpdate($post);
                if ($result === -1):
                    Auth::logout();
                    $json_request['responseText'] = Config::get('api.message');
                    $json_request['redirectURL'] = pageurl('auth');
                    return Response::json($json_request, 200);
                endif;
                $json_request['redirectURL'] = URL::route('dashboard');
                $json_request['responseText'] = Lang::get('interface.DEFAULT.success_save');
                $json_request['status'] = TRUE;
            else:
                $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
            endif;
        else:
            $json_request['responseText'] = $validator->messages()->all();
        endif;
        if (Request::ajax()):
            return Response::json($json_request, 200);
        else:
            return Redirect::route('dashboard');
        endif;
    }

    private function accountUpdate($post) {

        try {
            $user = Auth::user();
            if ($uploaded = AdminUploadsController::createImageInBase64String('avatar')):
                if (!empty($user->photo) && File::exists(Config::get('site.uploads_photo_dir') . '/' . $user->photo)):
                    File::delete(Config::get('site.uploads_photo_dir') . '/' . $user->photo);
                endif;
                if (!empty($user->photo) && File::exists(Config::get('site.uploads_thumb_dir') . '/' . $user->thumbnail)):
                    File::delete(Config::get('site.uploads_thumb_dir') . '/' . $user->thumbnail);
                endif;
                $user->photo = @$uploaded['main'];
                $user->thumbnail = @$uploaded['thumb'];
            endif;
            $user->name = $post['name'];
            $user->surname = $post['surname'];
            $user->city = $post['city'];
            $user->phone = $post['phone'];
            $user->sex = $post['sex'];
            $bdate = (new myDateTime())->setDateString($post['yyyy'] . '-' . $post['mm'] . '-' . $post['dd'])->format('Y-m-d');
            $user->bdate = $bdate;
            $user->save();
            $user->touch();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

    private function crmAccountUpdate($post) {

        $post['customerId'] = Auth::user()->remote_id;
        $post['sessionKey'] = Auth::user()->sessionKey;
        $api = (new ApiController())->get_register($post);
        if ($api === -1):
            return -1;
        elseif (isset($api['version'])):
            $post['version'] = $api['version'];
            $post['email'] = Auth::user()->email;
            $api = (new ApiController())->update_register($post);
            if ($api === -1):
                return -1;
            elseif (is_array($api)):
                Auth::user()->remote_id = @$api['id'];
                Auth::user()->sessionKey = @$api['sessionKey'];
                Auth::user()->save();
            else:
                return TRUE;
            endif;
        endif;
    }

    /****************************************************************************/
    public function tellStory() {

        $story = UserWritings::where('user_id', Auth::user()->id)->first();
        if (!$story):
            $story = new UserWritings();
            $story->user_id = Auth::user()->id;
            $story->writing = '';
            $story->status = 0;
            $story->save();
        elseif ($story->status == 2):
            return Redirect::route('dashboard')->with('message', 'Ваш рассказ находится на модерации. Дождитесь пожалуйста ответа.');
        elseif ($story->status == 1):
            return Redirect::route('dashboard')->with('message', 'Ваш рассказ прошел модерацию. Дождитесь завершения розыгрыша.');
        endif;

        $page_data = array(
            'page_title' => 'Личный кабинет',
            'page_description' => '',
            'page_keywords' => '',
            'story' => $story
        );
        return View::make(Helper::acclayout('tell-story'), $page_data);
    }
}