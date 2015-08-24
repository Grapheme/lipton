<form class="header-search pull-right" method="get" action="{{ URL::route('moderator.participants') }}">
    <input name="search" type="text" id="search-fld" placeholder="Поиск по имени">
    <button type="submit"><i class="fa fa-search"></i></button>
    <a title="Cancel Search" id="cancel-search-js" href="javascript:void(0);"><i class="fa fa-times"></i></a>
</form>
<h1 class="top-module-menu">
    <a href="{{ URL::route('moderator.participants') }}">Участники</a>
</h1>
<p>
    <a href="{{ URL::route('moderator.participants') }}" class="btn btn-default">
        {{ $filter_status == '' ? '<i class="fa fa-check"></i>' : '' }}
        Все
    </a>
    <a href="{{ URL::route('moderator.participants') }}?filter_status=codes" class="btn btn-default">
        {{ $filter_status == 'codes' ? '<i class="fa fa-check"></i>' : '' }}
        Ввели промо коды
    </a>
    <a href="{{ URL::route('moderator.participants') }}?filter_status=writing" class="btn btn-default">
        {{ $filter_status == 'writing' ? '<i class="fa fa-check"></i>' : '' }}
        Только рассказ
    </a>
</p>
<br/>