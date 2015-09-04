<?
/**
* TITLE: FAQ
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
<?php
$questions = array();
$medium = 0;
if (isset($page['blocks']) && count($page['blocks'])):
    foreach($page['blocks'] as $index => $question):
        if(!empty($question->meta->content)):
            $question_content = json_decode($question->meta->content, TRUE);
            if(isset($question_content['question']) && isset($question_content['answer'])):
                $questions[$index] = array('question' => $question_content['question'], 'answer' => $question_content['answer']);
            endif;
        endif;
    endforeach;
    $medium_real = round(count($questions) / 2, 0);
    if($medium_real < 5):
        $medium = 5;
    else:
        $medium = $medium_real;
    endif;
endif;
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <h2>{{ $page->seo->h1 }}</h2>
                <div class="faq-mask">
                    <div class="faq-block">
                        <div class="js-scroll-cont faq-columns">
                            <div class="column-left">
                                <div class="accordion">
                                    @for($i = 1; $i <= $medium; $i++ )
                                    <div class="accordion-item">
                                        <div class="accordion-head">
                                            <p>{{ @$questions['question_'.$i]['question'] }}</p>
                                        </div>
                                        <div class="accordion-body">
                                            {{ @$questions['question_'.$i]['answer'] }}
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="devider no-dots"></div>
                            <div class="column-right">
                                <div class="accordion">
                                    @for($i = $medium + 1; $i <= count($questions); $i++ )
                                    <div class="accordion-item">
                                        <div class="accordion-head">
                                            <p>{{ @$questions['question_'.$i]['question'] }}</p>
                                        </div>
                                        <div class="accordion-body">
                                            {{ @$questions['question_'.$i]['answer'] }}
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="scroll-nav">
                    <div class="scroll-button scroll-top">
                        <div class="ico-holder"></div>
                    </div>
                    <div class="scroll-button scroll-bottom">
                        <div class="ico-holder"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop