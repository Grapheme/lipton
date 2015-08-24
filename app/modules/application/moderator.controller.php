<?php

class ModeratorController extends BaseController {

    public static $name = 'moderator';
    public static $group = 'application';

    /****************************************************************************/
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        if (Auth::check() && Auth::user()->group_id < 4):
            Route::group(array('before' => '', 'prefix' => 'admin'), function () use ($class) {
                Route::get('participants', array('as' => 'moderator.participants',
                    'uses' => $class . '@participantsList'));
                Route::post('participant/{user_id}/save', array('before' => 'csrf',
                    'as' => 'moderator.participants.save', 'uses' => $class . '@participantsSave'));
            });
            Route::get('participants/{user_id}/{writing_id}/status/{status_number}', array('as' => 'moderator.participants.status',
                'uses' => $class . '@participantsSetStatus'));
        endif;
    }

    /****************************************************************************/
    public function __construct() {

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,
        );
        View::share('module', $this->module);
    }

    /****************************************************************************/
    public static function returnInfo() {
    }

    public static function returnMenu() {
    }

    public static function returnActions() {
    }

    /****************************************************************************/

    public function participantsList() {

        $users = array();
        if (Input::has('search')):
            $users = Accounts::where('group_id', 4)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . Input::get('search') . '%');
                    $query->orWhere('surname', 'like', '%' . Input::get('search') . '%');
                })
                ->orderBy('created_at', 'DESC')->with('ulogin', 'writing')->paginate(20);
        elseif (Input::has('filter_status')):
            if (Input::get('filter_status') == 'codes'):
                $users = UserCodes::groupBy('user_id')->with('users.ulogin', 'users.writing')->paginate(20);
            elseif (Input::get('filter_status') == 'writing'):
                $users = UserWritings::groupBy('user_id')->with('users.ulogin', 'users.writing')->paginate(20);
            elseif (Input::get('filter_status') == 'winners'):
                if (Input::has('begin') && Input::has('end')):
                    $begin = (new myDateTime())->setDateString(Input::get('begin'))->format('Y-m-d 00:00:00');
                    $end = (new myDateTime())->setDateString(Input::get('end'))->format('Y-m-d 23:59:59');
                    $users = Accounts::where('group_id', 4)->where('created_at', '>=', $begin)->where('created_at', '<=', $end)->orderBy('created_at', 'DESC')->with('ulogin', 'writing')->paginate();
                else:
                    $users = Accounts::where('group_id', 4)->where('winner', 1)->orderBy('number_week')->with('ulogin', 'writing')->paginate();
                endif;
            endif;
        else:
            $users = Accounts::where('group_id', 4)->orderBy('created_at', 'DESC')->with('ulogin', 'writing')->paginate(20);
        endif;
        return View::make($this->module['tpl'] . 'participants', compact('users'));
    }

    public function participantsSave($user_id) {

        if ($user = User::where('id', $user_id)->first()):
            $user->winner = Input::has('winner') ? 1 : 0;
            $user->number_week = Input::get('number_week');
            $user->save();
        endif;
        return Redirect::back();

    }

    public function participantsSetStatus($user_id, $writing_id, $status) {

        if ($writing = UserWritings::where('id', $writing_id)->where('user_id', $user_id)->first()):
            $writing->status = (int)$status;
            $writing->save();
        endif;
        return Redirect::back();

    }
    /****************************************************************************/
}