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
        $users_list = DB::table('users')
            ->select(DB::raw('users.id, users.name, users.surname, users.email, users.photo, users.sex, users.phone, users.city, users.winner, users.number_week, users.bdate, users.created_at, ulogin.photo_big, count(user_codes.id) as codes, user_writings.writing, user_writings.id as writing_id, user_writings.status as writing_status'))
            ->where('group_id', 4)
            ->leftJoin('ulogin', 'users.id', '=', 'ulogin.user_id')
            ->leftJoin('user_codes', 'users.id', '=', 'user_codes.user_id')
            ->leftJoin('user_writings', 'users.id', '=', 'user_writings.user_id')
            ->groupBy('users.id');

        if (Input::has('search')):
            $users = $users_list
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . Input::get('search') . '%');
                    $query->orWhere('surname', 'like', '%' . Input::get('search') . '%');
                })
                ->paginate(20);
        elseif (Input::has('filter_status')):
            if (Input::get('filter_status') == 'codes'):
                if (Input::has('begin') && Input::has('end')):
                    $begin = (new myDateTime())->setDateString(Input::get('begin'))->format('Y-m-d 00:00:00');
                    $end = (new myDateTime())->setDateString(Input::get('end'))->format('Y-m-d 23:59:59');
                    $users = $users_list
                        ->where('users.created_at', '>=', $begin)
                        ->where('users.created_at', '<=', $end)
                        ->orderBy('users.created_at', 'DESC');
                endif;
                $users_ids = array();
                foreach($users_list->get() as $user):
                    if($user->codes > 0):
                        $users_ids[] = $user->id;
                    endif;
                endforeach;
                if(count($users_ids)):
                    $users = $users_list->whereIn('users.id', $users_ids)->orderBy('users.created_at', 'DESC')->paginate(20);
                else:
                    $users = $users_list->orderBy('users.created_at', 'DESC')->paginate(20);
                endif;
            elseif (Input::get('filter_status') == 'writing'):
                if (Input::has('begin') && Input::has('end')):
                    $begin = (new myDateTime())->setDateString(Input::get('begin'))->format('Y-m-d 00:00:00');
                    $end = (new myDateTime())->setDateString(Input::get('end'))->format('Y-m-d 23:59:59');
                    $users = $users_list
                        ->where('users.created_at', '>=', $begin)
                        ->where('users.created_at', '<=', $end)
                        ->orderBy('users.created_at', 'DESC');
                endif;
                $users_ids = array();
                foreach($users_list->get() as $user):
                    if($user->writing != ''):
                        $users_ids[] = $user->id;
                    endif;
                endforeach;
                if(count($users_ids)):
                    $users = $users_list->whereIn('users.id', $users_ids)->orderBy('users.created_at', 'DESC')->paginate(20);
                else:
                    $users = $users_list->orderBy('users.created_at', 'DESC')->paginate(20);
                endif;
            elseif (Input::get('filter_status') == 'winners'):
                if (Input::has('begin') && Input::has('end')):
                    $begin = (new myDateTime())->setDateString(Input::get('begin'))->format('Y-m-d 00:00:00');
                    $end = (new myDateTime())->setDateString(Input::get('end'))->format('Y-m-d 23:59:59');
                    $users = $users_list
                        ->where('users.created_at', '>=', $begin)
                        ->where('users.created_at', '<=', $end)
                        ->orderBy('users.created_at', 'DESC')
                        ->paginate(20);
                else:
                    $users = $users_list->where('winner', 1)->orderBy('number_week')->paginate(20);
                endif;
            endif;
        else:
            if (Input::has('begin') && Input::has('end')):
                $begin = (new myDateTime())->setDateString(Input::get('begin'))->format('Y-m-d 00:00:00');
                $end = (new myDateTime())->setDateString(Input::get('end'))->format('Y-m-d 23:59:59');
                $users = $users_list
                    ->where('users.created_at', '>=', $begin)
                    ->where('users.created_at', '<=', $end)
                    ->orderBy('users.created_at', 'DESC')
                    ->paginate(20);
            else:
                $users = $users_list->orderBy('users.created_at', 'DESC')->paginate(20);
            endif;
        endif;
        if ((Input::has('likes') || Input::get('filter_status') == 'winners') && count($users)):
            $api = new ApiController();
            foreach ($users as $index => $user):
                $user = (array) $user;
                if(!empty($user['writing'])):
                    $post['url'] = URL::route('show.participant.writing', $user['writing_id'] . '-' . BaseController::stringTranslite($user['name'] . '-' . $user['surname']));
                    $likes = $api->social_likes($post);
                    $users[$index]->likes = $likes['extend'];
                    $users[$index]->total_likes = $likes['total'];
                else:
                    $users[$index]->total_likes = 0;
                endif;
            endforeach;
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