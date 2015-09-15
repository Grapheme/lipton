<?
/**
* TITLE: Победители
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
<?php
$now = Carbon::now();
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content">
        <div class="block winners">
            <div class="mosaic-holder data-autoplay="true"">
                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/winners-slider/1.jpg') }}" class="slide">&nbsp</div>
                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/winners-slider/2.jpg') }}" class="slide">&nbsp</div>
                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/winners-slider/3.jpg') }}" class="slide">&nbsp</div>
                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/winners-slider/4.jpg') }}" class="slide">&nbsp</div>
                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/winners-slider/5.jpg') }}" class="slide">&nbsp</div>
                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/winners-slider/6.jpg') }}" class="slide">&nbsp</div>
                <!-- <div class="mosaic-fuckup"></div> -->
            </div>
            <div class="content">
                <h2>{{ $page->seo->h1 }}</h2>

                <div class="block-plain">
                    <div class="illuminators">
                    @foreach(Accounts::where('winner', 1)->orderBy('number_week')->with('ulogin')->get() as $index => $user)
                        <?php
                            $bdate = new Carbon($user->bdate);
                        ?>
                        <div class="illuminator">
                            <div class="number">{{ str_pad($user->number_week, 2, 0, STR_PAD_LEFT); }}</div>
                        @if(!empty($user->photo) && File::exists(public_path($user->photo)))
                            <div style="background-image: url({{ asset($user->photo) }});" class="avatar"></div>
                        @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                            <div style="background-image: url({{ asset($user->ulogin->photo_big) }});"
                                 class="avatar"></div>
                        @else
                            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }});"
                                 class="avatar"></div>
                        @endif
                            <div class="magnifier">
                                <div class="number">{{ str_pad($user->number_week, 2, 0, STR_PAD_LEFT); }}</div>
                            @if(!empty($user->photo) && File::exists(public_path($user->photo)))
                                <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
                            @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                                <div style="background-image: url({{ asset($user->ulogin->photo_big) }});"
                                     class="avatar"></div>
                            @else
                                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }});"
                                     class="avatar"></div>
                            @endif
                                <div class="name">{{ $user->name }} {{ $user->surname }}</div>
                                <div class="info">{{ $bdate->diffInYears($now).' '.Lang::choice('год,|года,|лет,', $bdate->diffInYears($now)) }} {{ !empty($user->city) ? $user->city : '' }}</div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop