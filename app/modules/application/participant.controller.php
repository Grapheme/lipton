<?php

class ParticipantController extends BaseController {

    public static $name = 'participant';
    public static $group = 'accounts';

    /****************************************************************************/

    public static function returnRoutes() {

        $class = __CLASS__;

        Route::group(array('before' => 'user.auth', 'prefix' => 'participant'), function () use ($class) {
            Route::get('profile', array('as' => 'profile.edit', 'uses' => $class . '@profileEdit'));
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

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
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

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        $validator = Validator::make(Input::all(), Accounts::$update_rules);
        if (Auth::user()->email != Input::get('email') && User::where('email', Input::get('email'))->exists()):
            $json_request['responseText'] = Lang::get('interface.DEFAULT.email_exist');
            return Response::json($json_request, 200);
        endif;
        if ($validator->passes()):
            if (self::accountUpdate(Input::all())):
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
            if ($uploaded = AdminUploadsController::createImageInBase64String('photo')):
                if (!empty($user->photo) && File::exists(Config::get('site.uploads_photo_dir') . '/' . $user->photo)):
                    File::delete(Config::get('site.uploads_photo_dir') . '/' . $user->photo);
                endif;
                if (!empty($user->photo) && File::exists(Config::get('site.uploads_thumb_dir') . '/' . $user->thumbnail)):
                    File::delete(Config::get('site.uploads_thumb_dir') . '/' . $user->thumbnail);
                endif;
                $user->photo = @$uploaded['main'];
                $user->thumbnail = @$uploaded['thumb'];
            endif;
            $names = explode(' ', $user->name);
            if (count($names) > 2):
                $user->name = @$names[0] . ' ' . @$names[1];
            else:
                $user->name = $post['name'];
            endif;
            $user->email = $post['email'];
            $user->surname = '';
            $user->location = $post['location'];
            $user->phone = $post['phone'];
            $user->age = $post['age'];
            $user->way = $post['way'];
            $user->save();
            $user->touch();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }
}