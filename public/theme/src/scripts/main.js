$( document ).ready(function() {


  $('.ico-burger').click(function (){
    $('.right-menu-holder').slideToggle( function() {
      $('.ico-burger').toggleClass('close-ico');
    });
  });

  $('.second-code .fields-holder a, a.popup-close-cross').click(function() {
      $('.second-code').fadeOut();
  });

  $('#sex').selectmenu();
  $('#accept').button();

  $('.accordion-item .accordion-head').click( function() {
    $(this).next().slideToggle();
    $(this).toggleClass('active');
    checkScroll();
  });

  $('.country').click(function(){
    var popupContent = $(this).find('.content').clone();
    $('.learn-more-popup').html(popupContent);
    $('.learn-more-popup').append('<a href="" class="popup-close-cross"></a>');
    $('.learn-more-popup-holder').fadeIn();
  });

  $('.learn-more-popup a.popup-close-cross').click(function() {
    $('.learn-more-popup-holder').fadeOut();
  });

// WONDERFULL MASKS //

  $.mask.definitions['c'] = "[A-Za-z0-9]";
  $('form.promo-code .promoCode1, form.promo-code-2 .promoCode2').mask('ccc ccc ccc ccc ccc');
  $('form.full-registration input[name="phone"]').mask('+7 (999) 999 99 99');
  $('form.full-registration input.dd, form.full-registration input.mm').mask('99');
  $('form.full-registration input.yyyy').mask('9999');

// SCROLL BLOCK //

  function checkScroll() {
    setTimeout(function(){
      // console.log($('.faq-block').scrollTop(), $('.js-scroll-cont').height() - $('.faq-block').height());
      if($('.faq-block').scrollTop() >= $('.js-scroll-cont').height() - $('.faq-block').height()) {
        $('.scroll-top').addClass('disabled');
      } else {
        $('.scroll-top').removeClass('disabled');
      }
      if($('.faq-block').scrollTop() == 0) {
        $('.scroll-bottom').addClass('disabled');
      } else {
        $('.scroll-bottom').removeClass('disabled');
      }
    }, 200);
  }

  checkScroll();
  $('.scroll-top').click(function (){
    $('.faq-block').animate({scrollTop: $('.faq-block').scrollTop() + 200}, 500)
    checkScroll();
  });

  $('.scroll-bottom').click(function (){
    $('.faq-block').animate({scrollTop: $('.faq-block').scrollTop() - 200}, 500)
    checkScroll();
  });

// PROMO-CODE FORM VALIDATION //

  var auth = $('#promo-code-form').attr('data-user-auth');
  var redirectUrl = $('#promo-code-form button').attr('data-redirect-authorization');
    
  $('form.promo-code').validate({
    rules: {
      promoCode1: {
        required: true,
      }
    },

  messages: {
    promoCode1: {
      required: "Необходимо заполнить поле",
    }
  },

  submitHandler: function(form) {
      if(auth == 'authorized') {
        $('.promo-code button').html();
        $('.promo-code button').addClass('loading');
        $('.promo-code button').html('<i class="fa fa-cog fa-spin"></i>');
        var options = {
          success: function(data){
            $('.promo-code button').removeClass('loading');
              if(data.status) {
                $('.second-code').fadeIn();

                } else {
                  console.log(data.responseTextFail); // PROMOCODE OLLREADY REGISTERED
                };
          },
          error: function(data) {
            $('.promo-code button').removeClass('loading');
            $('.promo-code button').html('Отправить');
            // ERROR
          }
        };
        $(form).ajaxSubmit(options);

      } else { // UNAUTORIZED USER
        $('.promo-code button').addClass('loading');
        $('.promo-code button').addClass('loading');
        $('.promo-code button').html('<i class="fa fa-cog fa-spin"></i>')
        var firstCodeCookie = $(".promo-code .promoCode1").val();
        $.cookie('firstCodeCookie', firstCodeCookie);
        // var cookiePromo = $.cookie('firstCodeCookie'); // wright cookie cookie into var
        // console.log('Кука: ' + cookiePromo);
        if(redirectUrl) {
          function goToAuthorization () {
            window.location.href = redirectUrl;
          };
          setTimeout(goToAuthorization, 3000);
        }
      }
    }
  });

// PROMO-CODE FORM 2 VALIDATION //

  $('form.promo-code-2').validate({
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

    submitHandler: function(form) {
      $('.promo-code-2 button').html();
      $('.promo-code-2 button').addClass('loading');
      $('.promo-code-2 button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = {
        success: function(data){
          console.log(data.responseTextSuccsess);
            if(data.redirect) {
              function goToCabinet () {
                window.location.href = data.redirect;
              };
              setTimeout(goToCabinet, 3000);
            }
        },
        error: function(data) {
          $('.promo-code-2 button').html('Отправить');
          $('.promo-code-2 button').removeClass('loading');
          // ERROR
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
        minlength: 4
      }
    },

    messages: {
      login: {
        required: 'Необходимо заполнить это поле!',
      },
      password: {
        required: 'Необходимо заполнить это поле!',
      }
    },   
    submitHandler: function(form) {
      $('form.registration button').html();
      $('form.registration button').addClass('loading');
      $('form.registration button').html('<i class="fa fa-cog fa-spin"></i>');
      var options = { 
        success: function(data){
          if(data.redirect) {
            function goToCabinet () {
              window.location.href = data.redirect;
            };
            setTimeout(goToCabinet, 3000);
          }
        },
        error: function(data) {
          $('form.registration button').html('Отправить');
          $('form.registration button').removeClass('loading');
          // ERROR
        }
      };
      $(form).ajaxSubmit(options);
    }
  });

// STORY FOTM VALIDATION //

  $('form.story').validate({
    rules: {
      message: {
        required: true,
      },
    },

    messages: {
      message: {
        required: 'Необходимо yfgbcfnm bcnjhb.!',
      },
    },   
    submitHandler: function(form) {
      $('form.story button').html();
      $('form.story button').addClass('loading');
      $('form.story button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = { 
        success: function(data){
          if(data.redirect) {
            function goToCabinet () {
              window.location.href = data.redirect;
            };
            setTimeout(goToCabinet, 3000);
          }
        },
        error: function(data) {
          $('form.story button').html('Отправить');
          $('form.story button').removeClass('loading');
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
        required: 'Необходимо заполнить это поле!',
      },
    },

    submitHandler: function(form) {
      $('form.full-registration button').html();
      $('form.full-registration button').addClass('loading');
      $('form.full-registration button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = {
        success: function(data){
          if(data.redirect) {
            function goToCabinet () {
              window.location.href = data.redirect;
            };
            setTimeout(goToCabinet, 3000);
          }
        },
        error: function(data) {
          $('form.full-registration button').html('Отправить');
          $('form.full-registration button').removeClass('loading');
          // ERROR
        }
      };
      $(form).ajaxSubmit(options);
    }
  });

// MOSAIC //

  function mosaicBlink() {
    $('.mosaic .mosaic-blured').fadeIn();
    var mosaicTotal = $('.mosaic').length;

      //в условие цикла цисло активных блоков

    for (randomArray = 0; randomArray < 10; randomArray++ ) {
      var randomMosaic = Math.floor(Math.random() * (mosaicTotal - 0 + 1)) + 0;
      $('.mosaic .mosaic-blured').eq(randomMosaic).fadeOut();
    }
      setTimeout(mosaicBlink, 5000);
  }

  function mosaicBuild() {

    var winnersWidth = $('.mosaic-holder').outerWidth();
    var winnersHeight = $('.mosaic-holder').outerHeight();
    var mosaicHorisontal = Math.ceil(winnersWidth / 100);
    var mosaicVertical = Math.ceil(winnersHeight / 100);
    var curentMosaic = 0;
    var mosaicRows = 0;

    do {
    var $row = $('<div class="mosaic-row"></div>').appendTo($('.mosaic-fuckup'));
    mosaicRows++;
    curentMosaic = 1;

      do {
        $row.append('<div class="mosaic"><div class="mosaic-blured" style="background-position: -'+ (curentMosaic-1) * 100 + 'px -' + (mosaicRows-1) * 100 +'px"></div></div>');
        curentMosaic++;
      }
      while (curentMosaic <= mosaicHorisontal);

    }
    while (mosaicRows <= mosaicVertical);

    mosaicBlink();
  }

  mosaicBuild();

  $(window).resize(function(){
    $('.mosaic-fuckup').html('');
    mosaicBuild();
  });

});