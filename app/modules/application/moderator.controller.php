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
                Route::post('participants', array('as' => 'moderator.participants.export',
                    'uses' => $class . '@participantsListsExport'));
                Route::post('participants/likes', array('as' => 'moderator.participants.likes',
                    'uses' => $class . '@participantsLikes'));
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

        $users = $this->getParticipants();
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
    public function participantsListsExport() {

        $users_list = $this->getParticipants();
        $glue = Input::get('glue');
        $headers = array(
            'local_id',
            'remote_id',
            iconv("UTF-8", Input::get('coding'), 'Имя'),
            iconv("UTF-8", Input::get('coding'), 'Фамилия'),
            iconv("UTF-8", Input::get('coding'), 'Элект.почта'),
            iconv("UTF-8", Input::get('coding'), 'Фотография'),
            iconv("UTF-8", Input::get('coding'), 'Пол. 1 - жен, 2 - муж.'),
            iconv("UTF-8", Input::get('coding'), 'Телефон'),
            iconv("UTF-8", Input::get('coding'), 'Город'),
            iconv("UTF-8", Input::get('coding'), 'Победитель'),
            iconv("UTF-8", Input::get('coding'), 'Номер недели'),
            iconv("UTF-8", Input::get('coding'), 'Дата рождения'),
            iconv("UTF-8", Input::get('coding'), 'Дата регистрации'),
            iconv("UTF-8", Input::get('coding'), 'Всего лайков'),
            iconv("UTF-8", Input::get('coding'), 'Лайки по соц.сетям'),
            iconv("UTF-8", Input::get('coding'), 'Фотография из соц.сети'),
            iconv("UTF-8", Input::get('coding'), 'Кол.введ.кодов'),
            iconv("UTF-8", Input::get('coding'), 'Рассказ'),
            iconv("UTF-8", Input::get('coding'), 'ID рассказа'),
            iconv("UTF-8", Input::get('coding'), 'Статус рассказа. 0-отсутсвует. 1-одобрен. 2-на модерации. 3-отклонен'),
        );
        if ($glue === 'tab'):
            $output = implode("\t", $headers) . "\n";
        else:
            $output = implode("$glue", $headers) . "\n";
        endif;
        foreach ($users_list as $user):
            $user->name = iconv("UTF-8", Input::get('coding'), $user->name);
            $user->surname = iconv("UTF-8", Input::get('coding'), $user->surname);
            $user->total_extend = iconv("UTF-8", Input::get('coding'), $user->total_extend);
            $user->writing = iconv("UTF-8", Input::get('coding'), strip_tags(nl2br($user->writing)));
            $user->city = iconv("UTF-8", Input::get('coding'), $user->city);
            $fields = (array)$user;
            if ($glue === 'tab'):
                $output .= implode("\t", $fields) . "\n";
            else:
                $output .= implode("$glue", $fields) . "\n";
            endif;
        endforeach;
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ExportList.csv"',
        );
        return Response::make(rtrim($output, "\n"), 200, $headers);
    }

    /****************************************************************************/

    public function participantsLikes() {

        $validator = Validator::make(Input::all(), array('begin' => 'required', 'end' => 'required'));
        if ($validator->passes()):
            $api = new ApiController();
            $begin = (new myDateTime())->setDateString(Input::get('begin'))->format('Y-m-d 00:00:00');
            $end = (new myDateTime())->setDateString(Input::get('end'))->format('Y-m-d 23:59:59');
            foreach (Accounts::where('group_id', 4)->where('created_at', '>=', $begin)->where('created_at', '<=', $end)->with('writing')->get() as $user):
                if (!empty($user->writing) && !empty($user->writing->writing)):
                    $post['url'] = URL::route('show.participant.writing', $user->writing->id . '-' . BaseController::stringTranslite($user->name . '-' . $user->surname));
                    $likes = $api->social_likes($post);
                    $user->total_extend = $likes['extend'];
                    $user->total_likes = $likes['total'];
                    $user->save();
                endif;
            endforeach;
        endif;
        return Redirect::back();
    }

    private function getParticipants() {

        $users = array();
        $users_list = DB::table('users')
            ->select(DB::raw('users.id, users.remote_id, users.name, users.surname, users.email, users.photo, users.sex, users.phone, users.city, users.winner, users.number_week, users.bdate, users.created_at, users.total_likes, users.total_extend, ulogin.photo_big, count(user_codes.id) as codes, user_writings.writing, user_writings.id as writing_id, user_writings.status as writing_status'))
            ->where('group_id', 4)
            ->leftJoin('ulogin', 'users.id', '=', 'ulogin.user_id')
            ->leftJoin('user_codes', 'users.id', '=', 'user_codes.user_id')
            ->leftJoin('user_writings', 'users.id', '=', 'user_writings.user_id')
            ->groupBy('users.id')
            ->orderBy('users.total_likes', 'DESC');

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
                foreach ($users_list->get() as $user):
                    if ($user->codes > 0):
                        $users_ids[] = $user->id;
                    endif;
                endforeach;
                if (count($users_ids)):
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
                foreach ($users_list->get() as $user):
                    if ($user->writing != ''):
                        $users_ids[] = $user->id;
                    endif;
                endforeach;
                if (count($users_ids)):
                    $users = $users_list->whereIn('users.id', $users_ids)->orderBy('users.created_at', 'DESC')->paginate(20);
                else:
                    $users = $users_list->orderBy('users.created_at', 'DESC')->paginate(20);
                endif;
            elseif (Input::get('filter_status') == 'winners'):
                $users = $users_list->where('winner', 1)->orderBy('number_week')->paginate(20);
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
        return $users;
    }
}