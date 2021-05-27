$(document).ready(function(){
  var int_number = int_number1 = "";
  /*$("#left-side-menu li").removeClass();
  $("#left-side-menu li").addClass('nav-item');
  $('#left-side-menu li a').addClass('nav-link');
  $('#left-side-menu li:has(ul)').addClass('dropdown');
  $('#left-side-menu li ul').removeClass();
  $('#left-side-menu li ul').addClass('dropdown-menu');
  $('#left-side-menu li ul li').removeClass();
  $('.dropdown').find('a').addClass('dropdown-toggle');
  $('.dropdown').find('a').attr('data-toggle','dropdown');
  $('.dropdown').find('a').attr('role','button');
  $('.dropdown').find('a').attr('aria-haspopup','true');
  $('.dropdown').find('a').attr('aria-expanded','false');
  $('#left-side-menu li ul li a').removeClass();
  $('#left-side-menu li ul li a').addClass('dropdown-item');
  $('#left-side-menu li ul li a').removeAttr();*/
  
  // $("#left-side-menu li.menu-item-has-children").addClass("dropdown");
  $("#left-side-menu li.menu-item-has-children a").addClass("dropdown-toggle");
  $("#left-side-menu li.menu-item-has-children a").attr("data-toggle","dropdown");
  $("#left-side-menu li.menu-item-has-children a").attr("role","button");
  $("#left-side-menu li.menu-item-has-children a").attr("aria-haspopup","true");
  $("#left-side-menu li.menu-item-has-children a").attr("aria-expanded","false");
  $("#left-side-menu li.menu-item-has-children ul").removeClass();
  $("#left-side-menu li.menu-item-has-children ul").addClass("dropdown-menu");
  $("#left-side-menu li.menu-item-has-children ul li a").removeClass();
  $("#left-side-menu li.menu-item-has-children ul li a").addClass("dropdown-item");
  $("a.dropdown-item").removeAttr("data-toggle");
  $("a.dropdown-item").removeAttr("role");
  $("a.dropdown-item").removeAttr("aria-haspopup");
  $("a.dropdown-item").removeAttr("aria-expanded");


  if ($(window).width() < 700){
    $(".user-loggedin a.link_profile").click(function (e) {
      e.preventDefault();
      $(".user-sub-action-wrapper").toggleClass("active");
    });
  } else {
    $(".user-loggedin a.link_profile").click(function (e) {
      e.preventDefault();
    });
    $(".user-loggedin").hover(function () {
      $(".user-sub-action-wrapper").show();
    }, function () {
      $(".user-sub-action-wrapper").hide();
    });
  }

  if($('.international-number').length > 0) {
    var international_number_input = document.querySelector(".international-number");
    var jsURL = window.location.protocol + "//" + window.location.host +"/wp-content/themes/thriive/assets/js/utils.js";
    int_number = intlTelInput(international_number_input, {
      initialCountry: "IN",
      separateDialCode: true,
        // utilsScript: "https://intl-tel-input.com/node_modules/intl-tel-input/build/js/utils.js",
        utilsScript: jsURL,
      });
  }
  if(jQuery('.international-number1').length > 0) {
    var international_number_input = document.querySelector(".international-number1");
    var jsURL = window.location.protocol + "//" + window.location.host +"/wp-content/themes/thriive/assets/js/utils.js";
    int_number1 = intlTelInput(international_number_input, {
      initialCountry: "IN",
      separateDialCode: true,
        // utilsScript: "https://intl-tel-input.com/node_modules/intl-tel-input/build/js/utils.js",
        utilsScript: jsURL,
      });
  }

  $(".consult_online_link_oc").unbind('click').click(function(e){
    e.preventDefault();
    var slug = $(this).data('slug');
    var therapy = $(this).data('therapy');
    var from_status = $(this).data('from_status');
    if (from_status == 0) {
      gtag('event', 'click', {
        event_category: 'button',
        event_label: 'call now clicked - desktop',
      });
      $(".modal-body #reg_hide #lead_source").val(therapy);
      $(".modal-body #reg_unhide #slug").val(slug);
      $(".modal-body #reg_unhide #action").val('call');
      $(".modal-body #reg_unhide #therapy").val(therapy);
      $(".modal-body #reg_unhide #payment").val(1);
      $("#call_with_healer").modal('show');
      return false;
    }
    var id = $(this).attr('id').split("_");
    var therapist_email = $("#therapist_"+id[2]).val();
    var seeker_email = $("#seeker_"+id[2]).val();
    var payment_type = $(".payment_type").val();
    var pt;
    if(payment_type){
      pt = "&pt="+payment_type;
    } else {
      pt = "";
    }
    if(therapist_email == "" || seeker_email == ""){
      alert("Please try again later!");
      return false;
    }
    window.location.replace(window.location.protocol + "//" + window.location.host + "/payment-details?q="+slug+"&a=call&t="+therapy+pt);   
  });

  $(".start_chat_oc").unbind('click').click(function(e){
      var eventby = $('#chat_event').val();
      var from_status = $(this).data('from_status');
      var to_status = $(this).data('to_status');
      var to_user_id = $(this).data('touserid');
      var from_role = $(this).attr('data-role');
      var to_user_name = $(this).data('tousername');
      var from_user_id = $(this).data('fromuserid');
      var slug = $(this).data('slug');
      var therapy = $(this).data('therapy');

      if (from_status == 0) {
          gtag('event', 'click', {
              event_category: 'button',
              event_label: 'online - chat now clicked - '+eventby,
          });
          $(".modal-body #reg_hide #lead_source").val(therapy);
          $(".modal-body #reg_unhide #slug").val(slug);
          $(".modal-body #reg_unhide #action").val('chat');
          $(".modal-body #reg_unhide #therapy").val(therapy);
          $(".modal-body #reg_unhide #payment").val(1);
          $("#call_with_healer").modal('show');
          return false;
      }
      if (from_status != 0) {
          gtag('event', 'click', {
              event_category: 'button',
              event_label: 'online - chat now initiated - '+eventby,
          });
      }
      var payment_type = $(".payment_type").val();
      var pt;
      if(payment_type){
        pt = "&pt="+payment_type;
      } else {
        pt = "";
      }
      window.location.replace(window.location.protocol + "//" + window.location.host + "/payment-details?q="+slug+"&a=chat&t="+therapy+pt);
  });

  $(".book_now_link_oc").unbind('click').click(function(e){
    e.preventDefault();
    var slug = $(this).data('slug');
    var therapy = $(this).data('therapy');
    var from_status = $(this).data('from_status');
    if (from_status == 0) {
      gtag('event', 'click', {
        event_category: 'button',
        event_label: 'online - book later clicked - mobile',
      });
      $(".modal-body #reg_hide #lead_source").val(therapy);
      $(".modal-body #reg_unhide #slug").val(slug);
      $(".modal-body #reg_unhide #action").val('book_later');
      $(".modal-body #reg_unhide #therapy").val(therapy);
      $(".modal-body #reg_unhide #payment").val(1);
      $("#call_with_healer").modal('show');
      return false;
    }
    var id = $(this).attr('id').split("_");
    var therapist_email = $("#therapist_"+id[2]).val();
    var seeker_email = $("#seeker_"+id[2]).val();
    var payment_type = $(".payment_type").val();
    var pt;
    if(payment_type){
      pt = "&pt="+payment_type;
    } else {
      pt = "";
    }
    if(therapist_email == "" || seeker_email == ""){
      alert("Please try again later!");
      return false;
    }
    window.location.replace(window.location.protocol + "//" + window.location.host + "/booking-date-and-time?q="+slug+"&a=book_later&t="+therapy+pt); 
  });

  $(".video_call_now_link_oc").unbind('click').click(function(e){
    e.preventDefault();
    var slug = $(this).data('slug');
    var therapy = $(this).data('therapy');
    var from_status = $(this).data('from_status');
    if (from_status == 0) {
      gtag('event', 'click', {
        event_category: 'button',
        event_label: 'online - video call clicked - mobile',
      });
      $(".modal-body #reg_hide #lead_source").val(therapy);
      $(".modal-body #reg_unhide #slug").val(slug);
      $(".modal-body #reg_unhide #action").val('video_call');
      $(".modal-body #reg_unhide #therapy").val(therapy);
      $(".modal-body #reg_unhide #payment").val(1);
      $("#call_with_healer").modal('show');
      return false;
    }
    var id = $(this).attr('id').split("_");
    var therapist_email = $("#therapist_"+id[2]).val();
    var seeker_email = $("#seeker_"+id[2]).val();
    var payment_type = $(".payment_type").val();
    var pt;
    if(payment_type){
      pt = "&pt="+payment_type;
    } else {
      pt = "";
    }
    if(therapist_email == "" || seeker_email == ""){
      alert("Please try again later!");
      return false;
    }
    window.location.replace(window.location.protocol + "//" + window.location.host + "/booking-date-and-time?q="+slug+"&a=video_call&t="+therapy+pt); 
  });

  $('#oc_call_seeker_signup_btn').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();
    if ($(this).parsley().validate()) {
      $("#mobile_error_msg").html("");
      var country_code = $('.iti__selected-flag .iti__selected-dial-code').text();
      var trimStr = $("#mobile-number").val();
      if(trimStr){
        if(!int_number.isValidNumber()){
          $("#mobile_error_msg").html("Invalid Number");
          return false;
          }
      } 
      var trimStr = trimStr.replace(/\s/g, '');
      var mobileNumber = trimStr.replace('+' + country_code, '');
      var email = $('#email-id').val();
      var lead_source = $('#lead_source').val();
      $.ajax({
        url: myajax.ajax_url,
        type: 'POST',
        data: {'action': 'oc_call_seeker_signup', 'mobile_number': mobileNumber, 'country_code': country_code, 'email': email, 'lead_source': lead_source},
        success: function (data) {
          try {
            var jsonObj = JSON.parse(data);
            if(jsonObj.success == 'error'){
              $("#error_msg").text(jsonObj.resMessage);
            } else {
              if(jsonObj.resStatus=='success'){
                gtag('event', 'click', {
                  event_category: 'button',
                  event_label: 'oc call signup started',
                });
                var str = jsonObj.resData;
                var res = str.split("-");
                $("#error_msg").text("");
                $("#error_msg").text(jsonObj.resMessage);
                $("#otp_msg").text(jsonObj.resMessage);
                $("#user_id").val(res[0]);
                $("#mobile_number").val(res[2]);
                $("#country_code").val(res[1]);
                $("#event_name").val("oc call signup completed");
                $("#reg_hide").css("display", "none");
                $("#reg_unhide").css("display", "block");
              } else {
                $("#error_msg").text(jsonObj.resMessage);  
              }
            }
          } catch (err) {
            console.log('err. :  ' + err);
          }
        }
      });
    }
  });

  $('#verify_mobile_oc').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    if ($(this).parsley().validate()) {
      var event_name = $("#event_name").val();
      var payment = $("#payment").val();
      var payment_type = $(".payment_type").val();
      var slug = $("#slug").val();
      var action = $("#action").val();
      var therapy = $("#therapy").val();
      var otp = $('#mobile-otp').val();
      var user_id = $('#user_id').val(); 
      var pt;
      if(payment_type){
        pt = "&pt="+payment_type;
      } else {
        pt = "";
      }
      $.ajax({
        url: myajax.ajax_url,
        type: 'POST',
        data: {'action': 'oc_verify_user', 'otp': otp, 'user_id': user_id},
        success: function (data) {
          try {
            var jsonObj = JSON.parse(data);
            if(jsonObj.resStatus == 'error'){
              $("#error_msg_otp").text(jsonObj.resMessage);
            } else {
              if(jsonObj.resStatus=='success'){
                gtag('event', 'click', {
                  event_category: 'button',
                  event_label: event_name,
                });
                $("#error_msg_otp").text("");
                $("#error_msg_otp").text(jsonObj.resMessage);
                if(payment == 1 && (action == 'video_call' || action == 'book_later')){
                  window.location.replace(window.location.protocol + "//" + window.location.host + "/booking-date-and-time?q="+slug+"&a="+action+"&t="+therapy+pt); 
                } else if(payment == 1 && (action != 'video_call' || action != 'book_later')){
                  window.location.replace(window.location.protocol + "//" + window.location.host + "/payment-details?q="+slug+"&a="+action+"&t="+therapy+pt); 
                } else {
                  location.reload(true);
                }
              } else {
                $("#error_msg_otp").text(jsonObj.resMessage);  
              }
            }
          } catch (err) {
            console.log('err. :  ' + err);
          }
        }
      });
    }
  });
  $('#opencouponform').click(function(){
    $("#coupon_code").prop('required',true);
    $('#coupon_form').show();
  });
  $('#apply_coupon').click(function(){
    var slug = $("#slug").val();
    var action = $("#action").val();
    var therapy = $("#therapy").val();
    var code = $("#coupon_code").val();
    if(code){
      $.ajax({
           url: myajax.ajax_url,
           type: 'POST',
           data: {'action': 'apply_coupon_code', 'code': code},
           success: function (data) {
              var jsonObj = JSON.parse(data);
              if(jsonObj.resStatus=='error'){
                $('#coupon_msg').text(jsonObj.resMessage);
              }
              if(jsonObj.resStatus=='success'){
                window.location.replace(window.location.protocol + "//" + window.location.host + "/coupon-payment-page?q="+slug+"&a="+action+"&t="+therapy);
              }
           },
        complete: function (data) {
          console.log('complete. :  ' + data);
        },
        error: function (err) {
          console.log('error. :  ' + err);
        }
      });
    } else {
      $('#coupon_msg').text("Please enter coupon code to apply.");
    }
  });
  var ewAccordion = $(".ewHorizontalAccordian");
  var bw = $("body").innerWidth();
  if(ewAccordion.length > 0){
    if(ewAccordion.width() > bw){
      ewAccordion.find("ul").css("width",($("body").innerWidth() - 20));
    } else {
      ewAccordion.css("justify-content","center");
    }
  }
  
  $(document).on("click",".ewHorizontalAccordian ul li",function(){
    var el = $(this);
    
    if(el.hasClass("opening") || el.hasClass("closing")){
      return false;
    }

    var sibs =  el.siblings(".open")
    sibs.addClass("closing");
    el.addClass("opening");
    setTimeout(()=>{
      sibs.removeClass("closing").removeClass("open");
      el.addClass("open").removeClass("opening");
    },300);
  });
  
  $(document).on("click",".ew_accordion ul li",function(e){
    // e.preventDefault();
    var el = $(this);
    
    if(el.hasClass("opening") || el.hasClass("closing")){
      return false;
    }

    var sibs =  el.siblings(".open")
    sibs.addClass("closing");
    el.addClass("opening");
    setTimeout(()=>{
      sibs.removeClass("closing").removeClass("open");
      el.addClass("open").removeClass("opening");
    },300);
  });
  
  $(document).on("click",".ewHorizontalAccordian ul li a",function(e){
    e.preventDefault();
    var el = $(this);
    var link = el.attr("href")
    if(el.parent().hasClass("open")){
      window.location.href = link;
    } 
  });
  
  $(document).on("click",".ew_accordion ul li a",function(e){
    e.preventDefault();
    // e.stopImmediatePropagation()
    // e.stopPropagation();
    var el = $(this);
    var link = el.attr("href")
    if(el.parent().hasClass("open")){
      window.location.href = link;
    }
  });

  $('.testimonialSlider .owl-carousel').owlCarousel({
      loop:true,
      margin:10,
      nav:true,
      items:1,
      dots:false,
      navText : ["<img src='/wp-content/themes/thriive/assets/images/newsoulimg/icon-prev.svg'>","<img src='/wp-content/themes/thriive/assets/images/newsoulimg/icon-next.svg'>"]
  });

  try{
    $(".collapse").on('show.bs.collapse', function(){
        $(this).prev(".card-header").addClass("open");
    }).on('hide.bs.collapse', function(){
        $(this).prev(".card-header").removeClass("open");
    });
  } catch (e){
    console.log(e);
  }
  
  //Ajax Search
  var currentRequest = null; //variable to check ajax request is pending or not
  $(".seachform-section input").on('keyup', function () {
    $(".autocomplete-result").html("<li>Loading...</li>").css({'border':'1px solid #c5c5c5'});
    var lengthCount = $(this).val().length;
    var searchValue = $(this).val();
    //ajaxSearch(lengthCount,searchValue);  //Create function if need to use multiple times
    if (lengthCount >= 3) 
    {
      currentRequest = $.ajax({
        url: myajax.ajax_url,
        type: 'POST',
        data: {
          'action': 'getAilmentByAjaxNew',
          'issue': searchValue
        },
        beforeSend: function () {
          //stop previous ajax request
          if (currentRequest != null) {
            currentRequest.abort();
          }
        },
        success: function (data) {
          var html = "";
          $($.parseJSON(data)).map(function (key, val) {
            var select = 'selectAilment("'+val.slug+'")';
            html += "<li onClick='"+select+"'>"+val.name+"</li>";
          });
          $(".autocomplete-result").html(html).css({'border':'1px solid #c5c5c5'});
        },
        error: function (err) {
          console.log('Error: '.err);
        }
      });
    } 
    else 
    {
      $(".autocomplete-result").html("").css({'border':'none'});
      
    }
  });

  $('#otp_login_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();
    if ($(this).parsley().validate()) {
      $("#otpmob_error_msg1").html("");
      var country_code = $('.iti__selected-flag .iti__selected-dial-code').text();
      var trimStr = $("#mobile-num").val();
      if(trimStr){
        if(!int_number1.isValidNumber()){
          $("#otpmob_error_msg1").html("Invalid Number");
          return false;
          }
      } 
      var trimStr = trimStr.replace(/\s/g, '');
      var mobileNumber = trimStr.replace('+' + country_code, '');
      $.ajax({
        url: myajax.ajax_url,
        type: 'POST',
        data: {'action': 'otp_login', 'mobile_number': mobileNumber, 'country_code': country_code},
        success: function (data) {
          try {
            var jsonObj = JSON.parse(data);
            if(jsonObj.success == 'error'){
              $("#error_msg1").text(jsonObj.resMessage);
            } else {
              if(jsonObj.resStatus=='success'){
                var str = jsonObj.resData;
                var res = str.split("-");
                $("#error_msg1").text("");
                $("#error_msg1").text(jsonObj.resMessage);
                $("#otp_msg1").text(jsonObj.resMessage);
                $("#otp_user_id").val(res[0]);
                $("#otp_mobile_number").val(res[2]);
                $("#otp_country_code").val(res[1]);
                $("#otp_reg_hide").css("display", "none");
                $("#otp_reg_unhide").css("display", "block");
              } else {
                $("#error_msg1").text(jsonObj.resMessage);  
              }
            }
          } catch (err) {
            console.log('err. :  ' + err);
          }
        }
      });
    }
  });

  $('#otp_login_verify').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    if ($(this).parsley().validate()) {
      var otp = $('#mob-otp').val();
      var user_id = $('#otp_user_id').val(); 
      $.ajax({
        url: myajax.ajax_url,
        type: 'POST',
        data: {'action': 'otp_login_verify', 'otp': otp, 'user_id': user_id},
        success: function (data) {
          try {
            var jsonObj = JSON.parse(data);
            if(jsonObj.resStatus == 'error'){
              $("#error_msg_otp1").text(jsonObj.resMessage);
            } else {
              if(jsonObj.resStatus=='success'){
              $("#error_msg_otp1").text("");
            $("#error_msg_otp1").text(jsonObj.resMessage);
            location.reload(true);
              } else {
              $("#error_msg_otp1").text(jsonObj.resMessage);  
              }
            }
          } catch (err) {
            console.log('err. :  ' + err);
          }
        }
      });
    }
  });

  $("#resend_otp").click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    var mobile_number = $('#mobile_number').val();
    var country_code = $('#country_code').val();
    var user_id = $('#user_id').val();
    $.ajax({
         url: myajax.ajax_url,
         type: 'POST',
         data: {'action': 'callresendOTP', 'mobile_number': mobile_number, 'country_code': country_code, 'user_id': user_id},
         success: function (data) {
          var jsonObj = JSON.parse(data);
          if(jsonObj.resStatus=='success'){
             $("#error_msg_otp").text("");
             $("#otp_msg").text("");
               $("#otp_msg").text("OTP resent on "+mobile_number);
            } else {
            $("#error_msg_otp").text(jsonObj.resMessage);
            }   
      },
      complete: function (data) {
        console.log('complete. :  ' + data);
      },
      error: function (err) {
        console.log('error. :  ' + err);
      }
    });
  });

  $("#resend_otp1").click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    var mobile_number = $('#otp_mobile_number').val();
    var country_code = $('#otp_country_code').val();
    var user_id = $('#otp_user_id').val();
    $.ajax({
         url: myajax.ajax_url,
         type: 'POST',
         data: {'action': 'callresendOTP', 'mobile_number': mobile_number, 'country_code': country_code, 'user_id': user_id},
         success: function (data) {
          var jsonObj = JSON.parse(data);
          if(jsonObj.resStatus=='success'){
             $("#error_msg_otp").text("");
             $("#otp_msg").text("");
               $("#otp_msg").text("OTP resent on "+mobile_number);
            } else {
            $("#error_msg_otp").text(jsonObj.resMessage);
            }   
      },
      complete: function (data) {
        console.log('complete. :  ' + data);
      },
      error: function (err) {
        console.log('error. :  ' + err);
      }
    });
  });

  $('.excerpt-content-rm a.eclip-more-link').click(function(e) {
    e.preventDefault();
    $(this).closest('.abt-more').find('.readmore-content-wrapper').show();
    $('.excerpt-content-rm').hide()
    return false;
  });

  $('.readmore-content-wrapper a.eclip-more-link').click(function(e) {
    e.preventDefault();
    $(this).closest('.abt-more').find('.excerpt-content-rm').show();
    $('.readmore-content-wrapper').hide()
    return false;
  });

  $(document).on('focusin', '#dob', function () {
    $(this).datepicker({
      changeMonth: true,
      changeYear: true,
      yearRange: "-100:+0",
    });
  });

  $(document).on("click","#closemainmenu, #menu_overlay",function(){
    $('.offcanvas-collapse').toggleClass('open');
    $('#menuMain').toggleClass('open');
    $('#menu_overlay').toggleClass('menu_bg');
  });

  $('[data-toggle="offcanvas"]').on('click', function () {
    $('.offcanvas-collapse').toggleClass('open');
    $('#menuMain').toggleClass('open');
    $('#menu_overlay').toggleClass('menu_bg');
  });

  $(".nozomink").on('click',function(){
    var position = $(this).attr('data-position');
    $("#error_"+position).text('The link will be made available once the event starts.');
  });
  
  $('.event_login').on('click',function(){
    var position = $(this).attr('data-position');
    var uid = $('.uid').val();
    $.ajax({
        url: myajax.ajax_url,
        type: 'POST',
        data: {'action': 'register_for_event', 'position': position, 'uid': uid},
        success: function (data) {
          $('#event_page').load(' #event_page');
        }
    });
  });

  var $block = $('.searchformwrapper .no-results');
  $(document).on("click",".seachform-section",function(){
    $(".all_ailments").removeClass("d-none");
  });

  $("#closesearch, #closesearch1").on("click",function(){
    $('.all_ailments').addClass('d-none');
    $('.no-results').hide();
  });
  
  $(document).on("keyup",".search_issues",function(){ 
    // Search text
    var text = $(this).val().toLowerCase();
    var isMatch = false;
    // Hide all content class element
    $('.issue_box').find('.therapy_issues').hide();
    // Search 
    $('.issue_box').find('.therapy_issues .label_issues').each(function(){
      if ($(this).text().toLowerCase().startsWith(""+text+"")){
      // if($(this).text().toLowerCase().indexOf(""+text+"") != -1 ){
        isMatch = true;
        // $('.all_ailments').removeClass('d-none');
        $(this).closest('.therapy_issues').show();
      }
    });
    $block.toggle(!isMatch);
  });

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showLocation);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }

  function showLocation(position){
    var latitude = position.coords.latitude
    var longitude = position.coords.longitude;
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
          'action': 'userLocation',
          'latitude': latitude,
          'longitude': longitude
        },
        success: function (data) {
        },
        complete: function (data) {},
        error: function (err) {
          console.log("Error\n" + err);
        }
      });
  }
});

function selectAilment(val) {
  window.location.href = "/ailment/"+val;
}