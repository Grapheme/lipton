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

  $.mask.definitions['c'] = "[A-Za-z0-9]";
  $('form.promo-code input, form.promo-code-2 input').mask('ccc ccc ccc ccc');

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
    var redirectUrl = $('').attr('data-redirect-authorization');
    // var redirectUrlCabinet = $('').attr('data-redirect-cabinet');
    
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
        $('.promo-code button').addClass('loading');
        var options = {
          success: function(data){
            $('.promo-code button').removeClass('loading');
              console.log(data);
              if(data.status) {
                $('.second-code').fadeIn();

                } else {
                  console.log(data.responseTextFail); // PROMOCODE OLLREADY REGISTERED
                };
          },
          error: function(data) {
            $('.promo-code button').removeClass('loading');
            // ERROR
          }
        };
        $(form).ajaxSubmit(options);

      } else { // UNAUTORIZED USER
        $('.promo-code button').addClass('loading');
        var firstCodeCookie = $(".promo-code .promoCode1").val();
        $.cookie('firstCodeCookie', firstCodeCookie);
        // var cookiePromo = $.cookie('firstCodeCookie'); // wright cookie cookie into var
        // console.log('Кука: ' + cookiePromo);
          function goToAuthorization () {
            window.location.href = redirectUrl;
          };
          setTimeout(goToAuthorization, 3000);
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
      $('.promo-code-2 button').addClass('loading');
      var options = {
        success: function(data){
          console.log(data.responseTextSuccsess);
            function goToCabinet () {
              window.location.href = data.redirect;
            };
            setTimeout(goToCabinet, 3000);
        },
        error: function(data) {
          $('.promo-code-2 button').removeClass('loading');
          // ERROR
        }
      };
      $(form).ajaxSubmit(options);
    }
  });

// LOGIN FORM VALIDATION //

  $('form.registration-form').validate({
    rules: {
      login: {
        required: true,
      },
      password: {
        required: true,
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
      $('.promo-code-2 button').addClass('loading');
      var options = {
        success: function(data){
            function goToCabinet () {
              window.location.href = data.redirect;
            };
            setTimeout(goToCabinet, 3000);
        },
        error: function(data) {
          $('.promo-code-2 button').removeClass('loading');
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
      $('form.full-registration button').addClass('loading');
      var options = {
        success: function(data){
            function goToCabinet () {
              window.location.href = data.redirect;
            };
            setTimeout(goToCabinet, 3000);
        },
        error: function(data) {
          $('form.full-registration button').removeClass('loading');
          // ERROR
        }
      };
      $(form).ajaxSubmit(options);
    }
  });
});