// PRELOADER //

// preloading sourse

var bgCounter = 0;

$('*').filter(function () {
    return $(this).css('background-image') != 'none' && !$(this).attr('style')
}).each(
    function loading() {
        var imgHack = $(this).css('background-image');

        $.each(imgHack.split(','), function (i, v) {
            var protoImgUrl = v.split('//')[1].split('/');
            var imgName = protoImgUrl[protoImgUrl.length - 1].replace(')', '');
            $('body').prepend('<img class="preloaded__img" style="display: none;" src="../theme/build/images/' + imgName + '">');
            bgCounter++;
        });
    });

var oneImgPercent = 100 / bgCounter;
var loadedPercents = 0;
var oneAnimStage = 100 / 6;
var activeStage = 0;

$('.preloaded__img').each(function () {
    $(this).on('load', function () {
        loadedPercents = loadedPercents + oneImgPercent;
        var thisStage = Math.round(loadedPercents / oneAnimStage);
        if (activeStage != thisStage || activeStage == 0) {
            activeStage = thisStage;
            var preloaderBgPosition = -25;
            function plainSteps() {
                $('.preloader-plain').css('background-position', ((190 + 50) * (-thisStage)) + 'px 0px');
            };
            setTimeout(plainSteps, 500);
        }
    });
});

$(window).on('load', function () {
    $('.preloader').fadeOut();
});

if (top.location.href != self.location.href)
    top.location.href = self.location.href;

$(document).ready(function () {

    // CLOSE BUTTONS ETC //

    $('form.select-gain button').click(function() {
        $('block.select-gain').fadeOut();
    });

    $('.password-recovery').click(function (e) {
        e.preventDefault();
        $('.password-recovery-holder').fadeIn();
    });

    $('.password-recovery-holder a').click(function (e) {
        e.preventDefault();
        $('.password-recovery-holder').fadeOut();
    });

    if ((window.location.pathname == '/') ||
        (window.location.pathname == '/participant') ||
        (window.location.pathname == '/countries') ||
        (window.location.pathname == '/gallery-prizes')) {

        $('body').addClass('no-cup');
    }

    $('.ico-burger').click(function () {
        $('.right-menu-holder').slideToggle(function () {
            $('.ico-burger').toggleClass('close-ico');
            return false;
        });
    });

    $(document).on('click', 'div.error-block a', function () {
        $('.block.second-code').fadeOut();
        $('form.promo-code button').html('Отправить');

        $('form.promo-code input:not([name="_token"], [name="ticket_id"])').val('');
    });

    $('.second-code .fields-holder a, a.popup-close-cross').click(function () {
        window.location.reload();
    });

    $('.gained-prizes .prize a').click(function () {
        $('.send-instructions').fadeIn();
    });

    $('.sms-wrapper a.popup-close-cross').click(function () {
        $('.sms-wrapper').fadeOut();
    });

    // $('.profile-head .request a').click(function(){
    //   $('.story').fadeIn();
    // });

    $('.send-instructions .fields-holder a').click(function () {
        $('.send-instructions').fadeOut();
    });

    $('#sex').selectmenu();
    $('#accept').button();
    $('#gain-list').selectmenu();
    $('#gain-list1').selectmenu();

    $('.accordion-item .accordion-head').click(function () {
        $(this).next().slideToggle();
        $(this).toggleClass('active');
        checkScroll();
    });

    $('.error-block a').click(function (e) {
        e.preventDefault();
        $('.profile-error-wrapper').fadeOut();
        window.location.hash = '';
    })

    if ($(window).width() > 640) {
        $('.country').click(function () {
            var popupContent = $(this).find('.content').clone();
            $('.learn-more-popup').html(popupContent);
            $('.learn-more-popup').append('<a href="#" class="popup-close-cross"></a>');
            $('.learn-more-popup-holder').fadeIn();
        });
    } else {
        $('.country').click(function () {
            var popupContent = $(this).find('.content').slideToggle();
        });
    }

    $('.story a.popup-close-cross').click(function () {
        $('.story').fadeOut();
        return false;
    });

    $(document).on('click', '.learn-more-popup a.popup-close-cross', function (e) {
        e.preventDefault();
        $('.learn-more-popup-holder').fadeOut();
    });


    $('#js-sms-again').click(function (e) {
        e.preventDefault();
        $('#msg-sms-response').html('');
        var smsAjaxAdress = $(this).attr('href');
        $.post(smsAjaxAdress, function (data) {
            if (data.status === false) {
                $('form.sms-chesk button').removeClass('loading');
                $('form.sms-chesk button').html('Отправить');
            }
            if(data.responseText != ''){
                $("#msg-sms-response").append(data.responseText);
            }
            if (data.redirectURL) {
                function goToCabinet() {
                    window.location.href = data.redirectURL;
                };
                setTimeout(goToCabinet, 3000);
            }
        });
    })

// WONDERFULL MASKS //

    $.mask.definitions['c'] = "[A-Za-z0-9]";
    $('form.promo-code .promoCode1, form.promo-code-2 .promoCode2').mask('cccccccc');
    $('form.full-registration input[name="phone"]').mask('+7 (999) 999 99 99');
    $('form.full-registration input.dd, form.full-registration input.mm').mask('99');
    $('form.full-registration input.yyyy').mask('9999');

    $('form.profile-edit input[name="phone"]').mask('+7 (999) 999 99 99');
    $('form.profile-edit input.dd, form.profile-edit input.mm').mask('99');
    $('form.profile-edit input.yyyy').mask('9999');
    $('form.sms-chesk input[name="code"]').mask('99999999');

// SCROLL BLOCK //

    function checkScroll() {
        setTimeout(function () {
            // console.log($('.faq-block').scrollTop(), $('.js-scroll-cont').height() - $('.faq-block').height());
            if ($('.faq-block').scrollTop() >= $('.js-scroll-cont').height() - $('.faq-block').height()) {
                $('.scroll-top').addClass('disabled');
            } else {
                $('.scroll-top').removeClass('disabled');
            }
            if ($('.faq-block').scrollTop() == 0) {
                $('.scroll-bottom').addClass('disabled');
            } else {
                $('.scroll-bottom').removeClass('disabled');
            }
        }, 200);
    }

    checkScroll();
    $('.scroll-top').click(function () {
        $('.faq-block').animate({scrollTop: $('.faq-block').scrollTop() + 200}, 500)
        checkScroll();
    });

    $('.scroll-bottom').click(function () {
        $('.faq-block').animate({scrollTop: $('.faq-block').scrollTop() - 200}, 500)
        checkScroll();
    });

// PROMO-CODE FORM VALIDATION //

    var auth = $('#promo-code-form').attr('data-user-auth');

    $('form#promo-code-form').validate({
        rules: {
            promoCode1: {
                required: true,
            }
        },

        messages: {
            promoCode1: {
                required: "Необходимо заполнить поле!",
            }
        },

        submitHandler: function (form) {
            if (auth == 'authorized') {
                $('#promo-code-form button').addClass('loading');
                $('#promo-code-form button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
                var options = {
                    dataType: 'json',
                    success: function (data) {
                        $('#promo-code-form button').removeClass('loading');
                        $('#promo-code-form button').html('Отправить');

                        if (true === data.status) {
                            if (data.next_code) {
                                $('.second-code-holder').fadeIn();

                            }
                        } else if (undefined != data.responseText && data.responseText.length > 0) {
                            $('#js-profile-error').html(data.responseText);
                            $('.profile-error-wrapper').fadeIn();
                        }

                        if (data.redirectURL) {
                            setTimeout(function() { window.location.href = data.redirectURL; }, 3000);
                        }
                    },
                    error: function (data) {
                        $('#promo-code-form button').removeClass('loading');
                        $('#promo-code-form button').html('Отправить');
                    }
                };
                $(form).ajaxSubmit(options);

            } else { // UNAUTORIZED USER
                $('#promo-code-form button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
                var firstCodeCookie = $(".promo-code .promoCode1").val();
                $.cookie('firstCodeCookie', firstCodeCookie);
                var unauthorisedRedirectURL = $(form).find('button').data('redirect-authorization');
                window.location.href = unauthorisedRedirectURL;
            }
        }
    });

// PROMO-CODE PROFILE FORM 2 VALIDATION //

    $('.profile-promo-code form.promo-code-2').validate({
        rules: {
            promoCode2: {
                required: true,
            }
        },

        messages: {
            promoCode2: {
                required: "Необходимо заполнить поле",
            }
        },

        submitHandler: function (form) {
            $('.promo-code-2 button').addClass('loading');
            $('.promo-code-2 button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            var options = {
                success: function (data) {
                    $('.promo-code-2 button').html('Отправить');
                    $('.promo-code-2 button').removeClass('loading');
                    if (true === data.status) {
                        if (data.select_certificates) {
                            $('.select-gain').fadeIn();
                        }
                        if (data.wonLotteryTicketId !== false) {
                            $('#select-certificates-form input[name="ticket_id"]').val(data.wonLotteryTicketId);
                        }
                    } else if (undefined != data.responseText && data.responseText.length > 0) {
                        $('#js-profile-error').html(data.responseText);
                        $('.profile-error-wrapper').fadeIn();
                    }

                    if (data.redirectURL) {
                        setTimeout(function() { window.location.href = data.redirectURL; }, 3000);
                    }
                },
                error: function (data) {
                    $('.promo-code-2 button').html('Отправить');
                    $('.promo-code-2 button').removeClass('loading');
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// PROMO-CODE PROFILE FORM 2 VALIDATION //

    $('.second-code-holder form.promo-code-2').validate({
        rules: {
            promoCode2: {
                required: true,
            }
        },

        messages: {
            promoCode2: {
                required: "Необходимо заполнить поле",
            }
        },

        submitHandler: function (form) {
            $('.promo-code-2 button').addClass('loading');
            $('.promo-code-2 button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            var options = {
                success: function (data) {
                    $('.promo-code-2 button').html('Отправить');
                    $('.promo-code-2 button').removeClass('loading');
                    if (true === data.status) {
                        if (data.select_certificates) {
                            $(".second-code-holder").hide();
                            $('.select-gain').fadeIn();
                        }
                        if (data.wonLotteryTicketId !== false) {
                            $('#select-certificates-form input[name="ticket_id"]').val(data.wonLotteryTicketId);
                        }
                    } else if (undefined != data.responseText && data.responseText.length > 0) {
                        $('#js-profile-error').html(data.responseText);
                        $('.profile-error-wrapper').fadeIn();
                    }

                    if (data.redirectURL) {
                        setTimeout(function() { window.location.href = data.redirectURL; }, 1000);
                    }
                },
                error: function (data) {
                    $('.promo-code-2 button').html('Отправить');
                    $('.promo-code-2 button').removeClass('loading');
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

    $("#select-certificates-form").validate({
        rules: {
            certificate: {
                required: true,
            }
        },
        messages: {
            certificate: {
                required: "Необходимо выбрать курс",
            }
        },
        submitHandler: function (form) {
            var options = {
                dataType : 'json',
                beforeSubmit: function(arr, $form, options){
                    $($form).find('button').addClass('loading');
                    $($form).find('button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
                },
                success: function (data, statusText, xhr, $form) {
                    $($form).find('button').html('Отправить');
                    $($form).find('button').removeClass('loading');
                    if (true === data.status) {
                        $('.select-gain').hide();
                    } else if (undefined != data.responseText && data.responseText.length > 0) {
                        $('#js-profile-error').html(data.responseText);
                        $('.profile-error-wrapper').fadeIn();
                    }

                    if (data.redirectURL) {
                        setTimeout(function() { window.location.href = data.redirectURL; }, 3000);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $(form).find('button').html('Отправить');
                    $(form).find('button').removeClass('loading');
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// LOGIN FORM VALIDATION //

    $('form.registration').validate({
        rules: {
            login: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 4,
            }
        },

        messages: {
            login: {
                required: 'Необходимо заполнить это поле!',
                email: 'Проверте правильность адреса',
            },
            password: {
                required: 'Необходимо заполнить это поле!',
                minlength: 'Cлишком короткий пароль',
            }
        },
        submitHandler: function (form) {
            $('form.registration button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
            $('form.registration button').addClass('loading');
            $('.erros-message-block').remove();
            var options = {
                success: function (data) {
                    if (data.redirectURL) {
                        function goToCabinet() {
                            window.location.href = data.redirectURL;
                        };
                        setTimeout(goToCabinet, 3000);
                    } else {
                        $('form.registration button').removeClass('loading');
                        $('form.registration button').html('Отправить');
                        $('form.registration').append('<div class="erros-message-block">' + data.responseText + '</div>');
                    }
                },
                error: function (data) {
                    $('form.registration button').html('Отправить');
                    $('form.registration button').removeClass('loading');
                    // ERROR
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// STORY FORM VALIDATION //

    $('form.story').validate({
        rules: {
            message: {
                required: true,
                maxlength: 1500,
            },
        },

        messages: {
            message: {
                required: 'Необходимо написать историю!',
                maxlength: 'Введите не более 500 символов'
            },
        },
        submitHandler: function (form) {
            $('form.story button').addClass('loading');
            $('form.story button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
            var options = {
                success: function (data) {
                    if (data.redirectURL) {
                        function goToCabinet() {
                            window.location.href = data.redirectURL;
                        };
                        setTimeout(goToCabinet, 3000);
                    } else {
                        $('form.story').append('<div class="erros-message-block">' + data.responseText + '</div>');
                    }
                },
                error: function (data) {
                    $('form.story button').html('Отправить');
                    $('form.story button').removeClass('loading');
                    // ERROR
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// FEEDBACK FORM VALIDATION //

    $('form.feedback').validate({
        rules: {
            _token: {
                required: true,
            },
            fio: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            message: {
                required: true,
            },
        },

        messages: {
            message: {
                required: 'Необходимо написать сообщение!',
            },
            fio: {
                required: 'Необходимо заполнить поле!',
            },
            email: {
                required: 'Необходимо заполнить поле!',
                email: 'Неверный адрес!',
            },
            message: {
                required: 'Напишите ваше сообщение',
            },
        },
        submitHandler: function (form) {
            // $('form.feedback button').html('');
            $('form.feedback button').addClass('loading');
            $('form.feedback button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            var options = {
                success: function (data) {
                    if (data.status) {
                        $('form.feedback').html('');
                        $('form.feedback').append('<div class="erros-message-block success-message">' + data.responseText + '</div>');
                    } else {
                        $('form.feedback').append('<div class="erros-message-block">' + data.responseText + '</div>');
                    }
                },
                error: function (data) {
                    $('form.feedback button').html('Отправить');
                    $('form.feedback button').removeClass('loading');
                    // ERROR
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// EMAIL-REQUEST FORM VALIDATION //

    $('form.send-instructions').validate({
        rules: {
            instructionsEmail: {
                required: true,
                email: true,
            },
        },

        messages: {
            message: {
                required: 'Необходимо заполнить поле!',
                email: 'Проверьте правильность адреса!',
            },
        },
        submitHandler: function (form) {
            // $('form.send-instructions button').html('');
            $('form.send-instructions button').addClass('loading');
            $('form.send-instructions button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            var options = {
                success: function (data) {
                    if (data.redirectURL) {
                        function instructionsSended() {
                            window.location.href = data.redirectURL;
                        };
                        setTimeout(goToCabinet, 3000);
                    }
                },
                error: function (data) {
                    $('form.send-instructions button').html('Отправить');
                    $('form.send-instructions button').removeClass('loading');
                    // ERROR
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// REGISTRATION FORM VALIDATION //

    $('form.full-registration').validate({
        rules: {
            name: {
                required: true,
            },
            surname: {
                required: true,
            },
            // city: {
            //     required: true,
            // },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
            },
            acceptCheckbox: {
                required: true,
            },
        },

        messages: {
            name: {
                required: 'Необходимо заполнить это поле!',
            },
            surname: {
                required: 'Необходимо заполнить это поле!',
            },
            // city: {
            //     required: 'Необходимо заполнить это поле!',
            // },
            email: {
                required: 'Необходимо заполнить это поле!',
                email: 'Проверте правильность адреса'
            },
            phone: {
                required: 'Необходимо заполнить это поле!',
            },
            acceptCheckbox: {
                required: 'Необходимо заполнить это поле!',
            },
        },

        submitHandler: function (form) {

            $('form.full-registration button').addClass('loading');
            $('form.full-registration button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            $('.erros-message-block').remove();

            var options = {
                success: function (data) {
                  $('form.full-registration button').html('Отправить');
                  $('form.full-registration button').removeClass('loading');
                    if (data.status) {
                        if(data.valid_phone) {
                          $('.sms-wrapper').fadeIn();
                        }

                    } else {
                        $('form.full-registration').append('<div class="erros-message-block">' + data.responseText + '</div>');
                    }

                    if (data.redirectURL) {
                        function goToCabinet() {
                            window.location.href = data.redirectURL;
                        };
                        setTimeout(goToCabinet, 3000);
                    }
                },
                error: function (data) {
                    $('form.full-registration button').html('Отправить');
                    $('form.full-registration button').removeClass('loading');
                    // ERROR
                }
            };
                $(form).ajaxSubmit(options);
        }
    });

// PROFILE EDITING FORM VALIDATION //

    var userPhone = $('input[name="phone"]').val();

    $('form.profile-edit').validate({
        rules: {
            name: {
                required: true,
            },
            surname: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
            },
            acceptСheckbox: {
                required: true,
            },
            city: {
                required: true,
            },
        },

        messages: {
            name: {
                required: 'Необходимо заполнить это поле!',
            },
            surname: {
                required: 'Необходимо заполнить это поле!',
            },
            email: {
                required: 'Необходимо заполнить это поле!',
                email: 'Проверте правильность адреса'
            },
            phone: {
                required: 'Необходимо заполнить это поле!',
            },
            acceptСheckbox: {
                required: 'Необходимо согласиться с правилами акции и условиями использования!',
            },
            city: {
                required: 'Необходимо заполнить это поле!',
            },
        },

        submitHandler: function (form) {
            var userPhoneNew = $('input[name="phone"]').val();
            $('.erros-message-block').remove();
            $('form.profile-edit button').addClass('loading');
            $('form.profile-edit button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            var options = {
                success: function (data) {
                    if (data.status) {
                        if (userPhone != userPhoneNew) {
                            $('.sms-wrapper').fadeIn();
                        } else {
                            if (data.redirectURL) {
                                function goToCabinet() {
                                    window.location.href = data.redirectURL;
                                };
                                setTimeout(goToCabinet, 3000);
                            }
                        }
                    } else {
                        $('form.profile-edit').append('<div class="erros-message-block">' + data.responseText + '</div>');
                    }
                },
                error: function (data) {
                    $('form.profile-edit button').html('Отправить');
                    $('form.profile-edit button').removeClass('loading');
                    // ERROR
                }
            };
            $(form).ajaxSubmit(options);

        }
    });

// PHONE FORM VALIDATION //

    $('form.sms-chesk').validate({
        rules: {
            code: {
                required: true,
            },
        },

        messages: {
            code: {
                required: 'Необходимо заполнить поле!',
            },
        },
        submitHandler: function (form) {
            // $('form.send-instructions button').html('');
            $('form.sms-chesk button').addClass('loading');
            $('form.sms-chesk button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>')
            $("#msg-sms-response").html('');
            var options = {
                success: function (data) {
                  if (data.status === false) {
                      $('form.sms-chesk button').removeClass('loading');
                      $('form.sms-chesk button').html('Отправить');
                  }
                  if(data.responseText != ''){
                      $("#msg-sms-response").append(data.responseText);
                  }
                  if (data.redirectURL) {
                      function goToCabinet() {
                          window.location.href = data.redirectURL;
                      };
                      setTimeout(goToCabinet, 3000);
                  }
                },
                error: function (data) {
                    $('form.sms-chesk button').html('Отправить');
                    $('form.sms-chesk button').removeClass('loading');
                    // ERROR
                }
            };
            $(form).ajaxSubmit(options);
        }
    });

// PASSWORD RECOWERY FORM //

$('form.password-recovery-form').validate({
    rules: {
      emailRecovery: {
        required: true,
        email: true,
      },
    },

    messages: {
      emailRecovery: {
        required: 'Необходимо заполнить поле!',
        email: 'Введите корректный E-mail',
      },
    },   
    submitHandler: function(form) {
      $('form.password-recovery-form button').addClass('loading');
      $('form.password-recovery-form button').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
      $(".recovery-message-text").html('');
      var options = {
        success: function(data){
          if(data.status) {}
          if(data.responseText != ''){
            $('.profile-error-wrapper #js-profile-error').html(data.responseText);
            $('.profile-error-wrapper').fadeIn();
            $('.password-recovery-holder').fadeOut();
          }
          $('form.password-recovery-form button').html('Отправить');
          $('form.password-recovery-form button').removeClass('loading');
        },

        error: function (data) {
          $('form.password-recovery-form button').html('Отправить');
          $('form.password-recovery-form button').removeClass('loading');
        }
      };
      $(form).ajaxSubmit(options);
    }
  });

    function parseHash() {
        var hash = window.location.hash;
        if (hash == '#promo') {
            $('.second-code').fadeIn();
        }
    }

    parseHash();

    function parseHash() {
        var hash = window.location.hash;
        if (hash == '#message') {
            $('.profile-error-wrapper').fadeIn();
        }
    }

    parseHash();

    // AAANIMATION! //

    $(function () {
        $('.slider').fotorama({
            transition: 'crossfade',
            width: '100%',
            height: 475,
            autoplay: true,
        });
    });

    $(function () {
        $('.mosaic-holder').fotorama({
            transition: 'crossfade',
            width: '100%',
            height: '100%',
            autoplay: true,
        });
    });

    $('.cropper-wrapper .popup-close-cross, .cropper-wrapper .save').click(function () {
        $('.cropper-wrapper').fadeOut();
    });

    $('.cropper-wrapper .save').click(function () {
        $('.avatar-hack .hidden-avatar-input').val($('.cropper > img').cropper('getCroppedCanvas').toDataURL());
        $('.profile-info .avatar').css('background-image', 'url(' + $('.hidden-avatar-input').val($('.cropper > img').cropper('getCroppedCanvas').toDataURL()) + ');');
        $('.cropper-wrapper').fadeOut();
    });

    $('.cropper-wrapper .close').click(function () {
        $('.cropper-wrapper').fadeOut();
    });

    $('.js-cropper-image').on('change', function () {
        var input = $(this);
        var form = input.parents('form');
        var formType = form.attr('data-type');
        file = input[0].files[0];
        fr = new FileReader();
        fr.onload = function (e) {
            $('.js-image-test').remove();
            var image_str = '<img src="' + e.target.result + '">';
            var image_test = '<img class="js-image-test" style="position: fixed; left: -9999px;" src="' + e.target.result + '">';
            $('html').append(image_test);
            var img_width = $('.js-image-test').width();
            var img_height = $('.js-image-test').height();
            $('.cropper').html(image_str);
            $('.cropper > img').on('load', function () {
                $('.cropper-wrapper').fadeIn();
                $('.cropper > img').cropper({
                    aspectRatio: 1 / 1,
                    autoCropArea: 0.65,
                    strict: false,
                    guides: false,
                    highlight: false,
                    dragCrop: false
                });
                input.val('');
            });
        }
        fr.readAsDataURL(file);
    });

    $(".js-select-certificates").click(function(event){
        event.preventDefault();
        $(".select-gain").fadeIn();
    });
});