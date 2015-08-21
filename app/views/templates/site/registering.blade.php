<?
/**
 * TITLE: Регистрация
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <div class="participate-block">
                    <h2>Принять участие</h2>
                    @if(Auth::guest())
                    <div class="forms-holder full-registration-holder">
                        @include(Helper::layout('forms.signup'))
                    </div>
                    @include(Helper::layout('forms.valid-phone'))
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@if(Auth::check()):
<script type="application/javascript">
    $(function(){
        window.location.href = '{{ URL::route('dashboard') }}';
    })
</script>
@endif
@stop