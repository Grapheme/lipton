$( document ).ready(function() {


  $('.ico-burger').click(function (){
    $('.right-menu-holder').slideToggle( function() {
      $('.ico-burger').toggleClass('close-ico');
      return false;
    });
  });

  $('.second-code .fields-holder a, a.popup-close-cross').click(function() {
      $('.second-code').fadeOut();
      return false;
  });

  $('.gained-prizes .prize a').click(function() {
      $('.send-instructions').fadeIn();
  });

  $('.profile-head .request a').click(function(){
    $('.story').fadeIn();
  });

  $('.send-instructions .fields-holder a').click(function() {
      $('.send-instructions').fadeOut();
  });

  $('#sex').selectmenu();
  $('#accept').button();
  $('#gain-list').selectmenu();

  $('.accordion-item .accordion-head').click( function() {
    $(this).next().slideToggle();
    $(this).toggleClass('active');
    checkScroll();
  });

  if($(window).width() > 320) {
      $('.country').click(function(){
      var popupContent = $(this).find('.content').clone();
      $('.learn-more-popup').html(popupContent);
      $('.learn-more-popup').append('<a href="#" class="popup-close-cross"></a>');
      $('.learn-more-popup-holder').fadeIn();
    });
  } else {
    $('.country').click(function(){
      var popupContent = $(this).find('.content').slideToggle();
    });
  }

  $('.story a.popup-close-cross').click(function() {
    $('.story').fadeOut();
    return false;
  });

  $(document).on('click', '.learn-more-popup a.popup-close-cross', function(e) {
    e.preventDefault();
    $('.learn-more-popup-holder').fadeOut();
  });

// WONDERFULL MASKS //

  $.mask.definitions['c'] = "[A-Za-z0-9]";
  $('form.promo-code .promoCode1, form.promo-code-2 .promoCode2').mask('cccccccc');
  $('form.full-registration input[name="phone"]').mask('+7 (999) 999 99 99');
  $('form.full-registration input.dd, form.full-registration input.mm').mask('99');
  $('form.full-registration input.yyyy').mask('9999');

  $('form.profile-edit input[name="phone"]').mask('+7 (999) 999 99 99');
  $('form.profile-edit input.dd, form.profile-edit input.mm').mask('99');
  $('form.profile-edit input.yyyy').mask('9999');

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
  var redirectURL = $('#promo-code-form button').attr('data-redirect-authorization');
    
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

  submitHandler: function(form) {
      if(auth == 'authorized') {
        $('#promo-code-form button').html();
        $('#promo-code-form button').addClass('loading');
        $('#promo-code-form button').html('<i class="fa fa-cog fa-spin"></i>');
        var options = {
          success: function(data){
            $('#promo-code-form button').removeClass('loading');
              if(data.next_code) {
                $('.second-code').fadeIn();

                } else { // PROMOCODE OLLREADY REGISTERED
                  $('form.promo-code-2').html('');
                  $('.second-code').html('<div class="error-block"><h3>Ошибка</h3><p>' + data.responseText + '</p> <a href="#">Закрыть</a></div>');
                  $('.second-code').fadeIn();
                };
          },
          error: function(data) {
            $('#promo-code-form button').removeClass('loading');
            $('#promo-code-form button').html('Отправить');
            // ERROR
          }
        };
        $(form).ajaxSubmit(options);

      } else { // UNAUTORIZED USER
        $('#promo-code-form button').addClass('loading');
        $('#promo-code-form button').html('<i class="fa fa-cog fa-spin"></i>')
        var firstCodeCookie = $(".promo-code .promoCode1").val();
        $.cookie('firstCodeCookie', firstCodeCookie);
        // var cookiePromo = $.cookie('firstCodeCookie'); // wright cookie cookie into var
        // console.log('Кука: ' + cookiePromo);
        if(redirectURL) {
          function goToAuthorization () {
            window.location.href = redirectURL;
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
          $('form.promo-code-2').html('');
          $('.second-code').html('<div class="error-block"><h3>' + data.responseText + '</h3>');
          $('.second-code').fadeIn();
            if(data.redirectURL) {
              function goToCabinet () {
                window.location.href = data.redirectURL;
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

// PROFILE-FORM CODE VALIDATION //

  $('form.profile-promo-code').validate({
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
      $('form.profile-promo-code button').html();
      $('form.profile-promo-code button').addClass('loading');
      $('form.profile-promo-code button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = {
        success: function(data){
          console.log(data.responseTextSuccsess);
            if(data.redirectURL) {
              function goToCabinet () {
                window.location.href = data.redirectURL;
              };
              setTimeout(goToCabinet, 3000);
            }
        },
        error: function(data) {
          $('form.profile-promo-code button').html('Отправить');
          $('form.profile-promo-code button').removeClass('loading');
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
          if(data.redirectURL) {
            function goToCabinet () {
              window.location.href = data.redirectURL;
            };
            setTimeout(goToCabinet, 3000);
          } else {
            $('form.registration').append('<div class="erros-message-block">' + data.responseText + '</div>');
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

// STORY FORM VALIDATION //

  $('form.story').validate({
    rules: {
      message: {
        required: true,
      },
    },

    messages: {
      message: {
        required: 'Необходимо написать историю!',
      },
    },   
    submitHandler: function(form) {
      $('form.story button').html();
      $('form.story button').addClass('loading');
      $('form.story button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = { 
        success: function(data){
          if(data.redirectURL) {
            function goToCabinet () {
              window.location.href = data.redirectURL;
            };
            setTimeout(goToCabinet, 3000);
          } else {
            $('form.story').append('<div class="erros-message-block">' + data.responseText + '</div>');
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
    },   
    submitHandler: function(form) {
      $('form.feedback button').html();
      $('form.feedback button').addClass('loading');
      $('form.feedback button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = { 
        success: function(data){
          if(data.status) {
            $('form.feedback').html('');
            $('form.feedback').append('<div class="erros-message-block">' + data.responseText + '</div>');
          } else {
            $('form.feedback').append('<div class="erros-message-block">' + data.responseText + '</div>');
          }
        },
        error: function(data) {
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
    submitHandler: function(form) {
      $('form.send-instructions button').html();
      $('form.send-instructions button').addClass('loading');
      $('form.send-instructions button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = { 
        success: function(data){
          if(data.redirectURL) {
            function instructionsSended () {
              window.location.href = data.redirectURL;
            };
            setTimeout(goToCabinet, 3000);
          }
        },
        error: function(data) {
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
          if(data.redirectURL) {
            function goToCabinet () {
              window.location.href = data.redirectURL;
            };
            setTimeout(goToCabinet, 3000);
          } else {
            $('form.full-registration').append('<div class="erros-message-block">' + data.responseText + '</div>');
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

// PROFILE EDITING FORM VALIDATION //

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
      $('form.profile-edit button').html();
      $('form.profile-edit button').addClass('loading');
      $('form.profile-edit button').html('<i class="fa fa-cog fa-spin"></i>')
      var options = {
        success: function(data){
          if(data.redirectURL) {
            function goToCabinet () {
              window.location.href = data.redirectURL;
            };
            setTimeout(goToCabinet, 3000);
          } else {
            $('form.profile-edit').append('<div class="erros-message-block">' + data.responseText + '</div>');
          }
        },
        error: function(data) {
          $('form.profile-edit button').html('Отправить');
          $('form.profile-edit button').removeClass('loading');
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
        $row.append('<div class="mosaic"><div class="mosaic-blured" style="background-position: -'+ (curentMosaic) * 75 + 'px -' + (mosaicRows-1) * 75 +'px"></div></div>');
        curentMosaic++;
      }
      while (curentMosaic <= mosaicHorisontal);

    }
    while (mosaicRows <= mosaicVertical);

    mosaicBlink();
  }

  $(window).resize(function(){
    $('.mosaic-fuckup').html('');
    mosaicBuild();
  });

  mosaicBuild();
/////////
  function parseHash(){
    var hash = window.location.hash;
    if (hash == '#promo') {
      $('.second-code').fadeIn();
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

  // PRELOADER //

  // var preloaderAnimation = function() {
  //   var anim_time = 150;
  //   var anim_timeout = [];
  //   var options = {
  //     "preloader .preloader-plain": {
  //       steps: 7
  //     }
  //   };
  //   var Animation = function(step, elem, direction) {
  //     var this_steps = options[elem].steps;
  //     var this_elem = $(elem);
  //     if(direction == 'hover') {
  //       step++;
  //     } else {
  //       step--;
  //     }
  //     var new_pos = step * this_elem.width() * (-1);
  //     this_elem
  //       .css('background-position',  new_pos + 'px 0')
  //       .attr('data-active-step', step);
  //     anim_timeout[elem] = setTimeout(function(){
  //       if(step < this_steps - 1 && step > 0) {
  //         Animation(step, elem, direction);
  //       }
  //     }, anim_time/this_steps);
  //   }
  //   $.each(options, function(index, value){
  //     if(!$(index).hasClass('active')) {
  //       $(index).css('background-size', 100*value.steps + '% 100%');
  //       $(index).on('mouseenter', function(){
  //         clearTimeout(anim_timeout[index]);
  //         var start_step = 0;
  //         if($(this).attr('data-active-step')) {
  //           start_step = $(this).attr('data-active-step');
  //         }
  //         Animation(start_step, index, 'hover');
  //       }).on('mouseleave', function(){
  //         clearTimeout(anim_timeout[index]);
  //         Animation($(this).attr('data-active-step'), index, 'hoverout');
  //       });
  //     }
  //   });
  // }

  // preloaderAnimation();

   // get a collection of all elements with a BG image
    // var bgImages = $('*').filter(function() {
    //   console.log(13)
    //   return ($(this).css('background-image') !== '');
    //   console.log(100);
    // });
    // var bg = 0
    //     // var bgImages = $('*').length;

    //     var Bg = function() {
    //       if ('*').css('background-image' !== '') {
    //         bg++
    //       }
    //       console.log(bg);
    //     }

    var backgroundImages = $('*').filter(function() { return $(this).css('background-image') != 'none' && !$(this).attr('style') });
    var bgCounter = backgroundImages.length;
    var oneImgPersent = Math.round(100 / bgCounter);
    console.log(oneImgPersent);

    $('*').filter(function() { return $(this).css('background-image') != 'none' && !$(this).attr('style') }).each(
      function loading() {
        var imgHack = $(this).css('background-image');
        $('body').append('<img src="' + imgHack + '">')
      });

    loading().setTimeout(10000);
   // // get a collection of new images, assigning the sources from the original collection
   // }).map(function() {
   //     return $("<img />").attr("src", $(this).css('background-image').slice(5, -2));
   // });

   // var len = bgImages.length;
   // var loadCounter = 0;

   // // use an onload counter to keep track of which ones have loaded
   // bgImages.load(function() {
   //    loadCounter++;
   //    if(loadCounter == len) {

   //       // we have all loaded
   //       // fade out curtain
   //    }
   // }).each(function() {

   //    // if we have been pulled up from cache, manually trigger onload
   //    if (this.complete) {
   //      console.log(bgImages + map);
   //    }
   // });

});