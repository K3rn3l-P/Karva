// Stripe field Setup

//TEST Publisable KEY
//   "pk_test_51IO6weDmWrwWiUwKnNexx2SHx1PSa0S78shf8b29vfpdMZgP2EzZ8AjeioOeiZxSlr4XaZ7o5aTohlbacdaaZrlU00uVq0gVW1"

//Live Duff Publisable KEY
//   "pk_live_51IO6weDmWrwWiUwK1FBqFAYZZdcNke8KYX8Fb0qrgotNPQTzpk9HJFRcchrLF8paBWp4eygri4eudy3AYOrRn0qQ00gbVLNAn6"

//Live GODDESS Publisable KEY
//   "pk_live_51IdEd4LxJfxGIDdlD5q3SEDNyDDvgjJmsjO3ila3YLwWrAAT5w0QNK4aAjusJtOq9tKCk0LIvIVxikSK0DmqDKQS00Jn0MyOn0"


//LIVE GODDESS Publisable KEY
 var stripe = Stripe(
   "pk_live_51KgOUuCR67cxdwpZmAzxRv56WYMiyJ7lGlot0KD1BVHKLjarAdRqWuO76MQ2JHwC10ypbfP9xxPnvrCBxtWmU9aZ00AJViazHW"
 );


var elements = stripe.elements();

var style = {
  base: {
    fontWeight: 400,
    fontFamily: "Roboto, Open Sans, Segoe UI, sans-serif",
    fontSize: "16px",
    width: "100%",

    color: "#000",
    backgroundColor: "transparent",
    "::placeholder": {
      color: "#888",
    },
  },
  invalid: {
    fontFamily: "Roboto, Open Sans, Segoe UI, sans-serif",
    color: "#fa755a",
    iconColor: "#fa755a",
  },
};

var card = elements.create("card", { style: style });
// Stripe injects an iframe into the DOM
card.mount("#card-element");

card.on("change", function (event) {
  // Disable the Pay button if there are no card details in the Element
  // document.querySelector("button").disabled = event.empty;
  document.querySelector("#card-error").textContent = event.error
    ? event.error.message
    : "";
});

var resultContainer = document.getElementById("paymentResponse");

function scroll_to_class(element_class, removed_height) {
  var scroll_to = $(element_class).offset().top - removed_height;
  if ($(window).scrollTop() != scroll_to) {
    $("html, body").stop().animate({ scrollTop: scroll_to }, 0);
  }
}

function bar_progress(progress_line_object, direction) {
  var number_of_steps = progress_line_object.data("number-of-steps");
  var now_value = progress_line_object.data("now-value");
  var new_value = 0;
  if (direction == "right") {
    new_value = now_value + 100 / number_of_steps;
  } else if (direction == "left") {
    new_value = now_value - 100 / number_of_steps;
  }

  progress_line_object
    .attr("style", "width: " + new_value + "%;")
    .data("now-value", new_value);
}

function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if (!regex.test(email)) {
    return false;
  } else {
    return true;
  }
}

jQuery(document).ready(function () {
  var isChecked = false;

  $('input[type=radio][name=payment-method]').change(function() {
    if (this.value == 'stripe') {
        
    }
    else if (this.value == 'paymentwall') {
        
    }
  });


  $("#termscheckbox").on("change", function () {
    var val = this.checked ? this.value : "off";

    if (val == "on") {
      isChecked = true;
      $("#alert-term").hide();
    } else {
      isChecked = false;

      //
    }
  });
  $("#btn-terms").on("click", function (e) {
    if (!isChecked) {
      $("#alert-term").show();
      return false;
    } else {
      $("#alert-term").hide();
    }

    var parent_fieldset = $("#termFiedlset");
    // Move to Final Step For Confirmation Payment Screen
    parent_fieldset.fadeOut(400, function () {
      // change icons
      $("#TermHeading")
        .removeClass("active")
        .addClass("activated")
        .next()
        .addClass("active");

      // show next step
      parent_fieldset.next().fadeIn();
      // scroll window to beginning of the form
      scroll_to_class($(".f1"), 20);

      // progress bar
      var progress_line = $(this).parents(".f1").find(".f1-progress-line");
      bar_progress(progress_line, "right");
    });
  });

  $('input[type=radio][name="paypal_price"]').change(function() {
    //alert($(this).val()); // or this.value
    var paypalUrl = $("input[name='paypal_price']:checked").val();
    $("#btn-paypal-href").attr("href", paypalUrl);
    $("#amount").val(paypalUrl);
    
  });



  $("#btn-payment-method").on("click", function (e) {

    var parent_fieldset = $("#paymentMethodFiedlset");
    // Move to Final Step For Confirmation Payment Screen
    parent_fieldset.fadeOut(400, function () {
      // change icons
      $("#TermPaymentMethod")
        .removeClass("active")
        .addClass("activated")
        .next()
        .addClass("active");

      // show next step
      parent_fieldset.next().fadeIn();
      // scroll window to beginning of the form
      scroll_to_class($(".f1"), 20);

      // progress bar
      var progress_line = $(this).parents(".f1").find(".f1-progress-line");
      bar_progress(progress_line, "right");
    });

    var paymentMethod = $('input[type=radio][name=payment-method]:checked').val();
    if(paymentMethod === 'stripe'){
      $("#paypalPackagesTable").hide();
      $("#stripePackagesTable").show();
      $("#btn-stripe-packages").show();
      $("#paymentwall-iframe").hide();
	  $("#gcash-iframe").hide();
      $("#ConfirmationHeading").show();
      $("#BIHeading").show();
      $("#btn-paypal-packages").hide();

    }
    else if (paymentMethod === 'paymentwall')
    {
      $("#paypalPackagesTable").hide();
      $("#stripePackagesTable").hide();
      $("#btn-stripe-packages").hide();
      $("#paymentwall-iframe").show();
	  $("#gcash-iframe").hide();
      $("#ConfirmationHeading").hide();
      $("#BIHeading").hide();
      $("#btn-paypal-packages").hide();
    }
    
	
	
	else if (paymentMethod === 'paypal')
    {
      $("#paypalPackagesTable").show();
      $("#stripePackagesTable").hide();
      $("#btn-stripe-packages").hide();
      $("#paymentwall-iframe").hide();
	  $("#gcash-iframe").hide();
      $("#ConfirmationHeading").hide();
      $("#BIHeading").hide();
      $("#btn-paypal-packages").show();
    }
	
	else if (paymentMethod === 'gcash')
    {
      $("#paypalPackagesTable").hide();
      $("#stripePackagesTable").hide();
      $("#btn-stripe-packages").hide();
      $("#paymentwall-iframe").hide();
	  $("#gcash-iframe").show();
      $("#ConfirmationHeading").hide();
      $("#BIHeading").hide();
      $("#btn-paypal-packages").hide();
    }
	
	
	
	
   // $('input[name=radioName]:checked').val()
   

  });
  //$.backstretch("assets/img/backgrounds/1.jpg");

  $("#top-navbar-1").on("shown.bs.collapse", function () {
    $.backstretch("resize");
  });
  $("#top-navbar-1").on("hidden.bs.collapse", function () {
    $.backstretch("resize");
  });

  /*
         Form
     */
  $(".f1 fieldset:first").fadeIn("slow");

  $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on(
    "focus",
    function () {
      $(this).removeClass("input-error");
    }
  );

  // next step

  var next_step = true;
  $(".f1 .btn-next").on("click", function () {
    var parent_fieldset = $(this).parents("fieldset");
    // navigation steps / progress steps
    var current_active_step = $(this).parents(".f1").find(".f1-step.active");
    var progress_line = $(this).parents(".f1").find(".f1-progress-line");

    // fields validation
    parent_fieldset
      .find('input[type="text"], input[type="password"], textarea')
      .each(function () {
        if ($(this).val() == "") {
          $(this).addClass("input-error");
          next_step = false;
        } else {
          $(this).removeClass("input-error");
        }
      });
    // fields validation

    if (next_step) {
      parent_fieldset.fadeOut(400, function () {
        // change icons
        current_active_step
          .removeClass("active")
          .addClass("activated")
          .next()
          .addClass("active");
        // progress bar
        bar_progress(progress_line, "right");
        // show next step
        $(this).next().fadeIn();
        // scroll window to beginning of the form
        scroll_to_class($(".f1"), 20);
      });
    }
  });

  // previous step
  $(".f1 .btn-previous").on("click", function () {
    // navigation steps / progress steps
    var current_active_step = $(this).parents(".f1").find(".f1-step.active");
    var progress_line = $(this).parents(".f1").find(".f1-progress-line");

    $(this)
      .parents("fieldset")
      .fadeOut(400, function () {
        // change icons
        current_active_step
          .removeClass("active")
          .prev()
          .removeClass("activated")
          .addClass("active");
        // progress bar
        bar_progress(progress_line, "left");
        // show previous step
        $(this).prev().fadeIn();
        // scroll window to beginning of the form
        scroll_to_class($(".f1"), 20);
      });
  });

  $("#btn-pay-amount").on("click", function (e) {
    $("#alert-contact").hide();

    var stripeTokenID;
    var errorMessage;
    var isValid = true;

    var selectedPackage = $("input[name='price']:checked").val();

    if ($("#f1-country").val() === "") {
      $("#alert-contact").html("Select your country");
      isValid = false;
    } else if ($("#f1-first-name").val() === "") {
      $("#alert-contact").html("Please enter your first name");
      isValid = false;
    } else if ($("#f1-last-name").val() === "") {
      $("#alert-contact").html("Please enter your last name");
      isValid = false;
    } else if ($("#f1-address").val() === "") {
      $("#alert-contact").html("Please enter your Address");
      isValid = false;
    } else if ($("#f1-sector").val() === "") {
      $("#alert-contact").html("Please enter your Address 2");
      isValid = false;
    } else if ($("#f1-postal-code").val() === "") {
      $("#alert-contact").html("Please enter Postal Code");
      isValid = false;
    } else if ($("#f1-city").val() === "") {
      $("#alert-contact").html("Please enter City Name");
      isValid = false;
    } else if ($("#f1-phone-type").val() === "") {
      $("#alert-contact").html("Select Phone Type");
      isValid = false;
    } else if ($("#f1-phone").val() === "") {
      $("#alert-contact").html("Please Enter Phone Number");
      isValid = false;
    } else if ($("#f1-email").val() === "") {
      $("#alert-contact").html("Please Enter your Email Address");
      isValid = false;
    } else if (IsEmail($("#f1-email").val()) == false) {
      $("#alert-contact").html("Please Enter Valid Email Address");
      isValid = false;
    } else {
      $("#alert-contact").html("");
      $("#alert-contact").hide();
      resultContainer.innerHTML = "";
    }

    if (!isValid) {
      $("#alert-contact").show();
      $("#btn-pay-amount").show();
      $("#btn-previous-step3").show();
      $("#loading").hide();
      return false;
    } else {
      $("#alert-contact").hide();
    }

    isValid = true;

    var mUserID = $("#uid").val();
    //var mUserID = 1;

    var data1 = {
      price: selectedPackage,
      userId: mUserID,
      country: $("#f1-country option:selected").text(),
      firstname: $("#f1-first-name").val(),
      lastname: $("#f1-last-name").val(),
      address: $("#f1-address").val(),
      sector: $("#f1-sector").val(),
      postalcode: $("#f1-postal-code").val(),
      city: $("#f1-city").val(),
      phonetype: $("#f1-phone-type").val(),
      phone: $("#f1-phone").val(),
      email: $("#f1-email").val(),
    };

    data1 = JSON.stringify(data1);
    //console.log("data%j", data1);

    loading(true);

    fetch("templates/en/pages/billing/stripe_intent.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: data1,
    })
      .then((response) => response.json())
      .then(function (result) {
        if (result.data == "true") {
          payWithCard(stripe, card, result.secret);
        } else {
          isValid = false;
          loading(false);

          $("#alert-contact").html(result.message);
          $("#alert-contact").show();
        }
      });

    //fetch("templates/en/pages/stripe/stripe_payment.php", {

    if (!isValid) {
      $("#alert-contact").html(errorMessage);
      $("#alert-contact").show();

      $("#btn-pay-amount").show();
      $("#btn-previous-step3").show();

      return false;
    }

    return false;
  });
});

function successReponse(message) {
  var parent_fieldset = $("#bIFiedlset");
  // Move to Final Step For Confirmation Payment Screen
  parent_fieldset.fadeOut(400, function () {
    // change icons
    $("#BIHeading")
      .removeClass("active")
      .addClass("activated")
      .next()
      .addClass("active");

    // show next step
    parent_fieldset.next().fadeIn();
    // scroll window to beginning of the form
    scroll_to_class($(".f1"), 20);

    // progress bar
    var progress_line = $(this).parents(".f1").find(".f1-progress-line");
    bar_progress(progress_line, "right");
  });

  // alert("Your payment has been successful. Thank you  ");
  var fullname = $("#f1-first-name").val() + " " + $("#f1-last-name").val();
  var selectedPackage = $("input[name='price']:checked").val();

  $("#f1-full-name").val(fullname);
  $("#f1-paid-amount").val("Amount Spend: " + selectedPackage + "€");

  $("#myalert").text(message);
}

var payWithCard = function (stripe, card, clientSecret) {
  stripe
    .confirmCardPayment(clientSecret, {
      payment_method: {
        card: card,
      },
    })
    .then(function (result) {
      if (result.error) {
        // Show error to your customer
        loading(false);
        $("#alert-contact").html(result.error.message);
        $("#alert-contact").show();
      } else {
        //alert(result.paymentIntent.id);

        // Call stripe_payment File to Save into DB

        var selectedPackage = $("input[name='price']:checked").val();

        var mUserID = $("#uid").val();
        //var mUserID = 1;

        data1 = {
          payment_intent_id: result.paymentIntent.id,
          price: selectedPackage,
          userId: mUserID,
          country: $("#f1-country option:selected").text(),
          firstname: $("#f1-first-name").val(),
          lastname: $("#f1-last-name").val(),
          address: $("#f1-address").val(),
          sector: $("#f1-sector").val(),
          postalcode: $("#f1-postal-code").val(),
          city: $("#f1-city").val(),
          phonetype: $("#f1-phone-type").val(),
          phone: $("#f1-phone").val(),
          email: $("#f1-email").val(),
        };

        data1 = JSON.stringify(data1);
        fetch("templates/en/pages/billing/stripe_payment.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: data1,
        })
          .then((response) => response.json())
          .then(function (result) {
            if (result.data == "true") {
              loading(false);
              successReponse(result.message);
            } else {
              loading(false);
              $("#alert-contact").html(result.message);
              $("#alert-contact").show();
            }
          });
      }
    });
};

var loading = function (isLoading) {
  if (isLoading) {
    // // Disable the button and show a spinner
    document.querySelector("#btn-pay-amount").disabled = true;
    document.querySelector("#btn-previous-step3").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#btn-pay-amount").classList.add("hidden");
    document.querySelector("#btn-previous-step3").classList.add("hidden");
  } else {
    document.querySelector("#btn-pay-amount").disabled = false;
    document.querySelector("#btn-previous-step3").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#btn-pay-amount").classList.remove("hidden");
    document.querySelector("#btn-previous-step3").classList.remove("hidden");
  }
};
