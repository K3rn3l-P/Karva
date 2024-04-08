// Stripe field Setup

//Stripe A
 var stripe = Stripe(
   "pk_test_51IO6weDmWrwWiUwKnNexx2SHx1PSa0S78shf8b29vfpdMZgP2EzZ8AjeioOeiZxSlr4XaZ7o5aTohlbacdaaZrlU00uVq0gVW1"
 );

//Stripe Haseeb Test Account
/*var stripe = Stripe(
  "pk_test_51IHhxTEx93rzbytl2PheXrbkrM8pxKuHiHzCJ3kzHWa4j56Mkr7G1FGosR63uEtdUJYq4MWbaAk5rlMS6Yyt57fV00k11aom9u"
);*/

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
    color: "#eb1c26",
  },
};

var cardElement = elements.create("cardNumber", {
  style: style,
});
cardElement.mount("#card_number");

var exp = elements.create("cardExpiry", {
  style: style,
});
exp.mount("#card_expiry");

var cvc = elements.create("cardCvc", {
  style: style,
});
cvc.mount("#card_cvc");

// Validate input of the card elements
var resultContainer = document.getElementById("paymentResponse");
cardElement.addEventListener("change", function (event) {
  if (event.error) {
    resultContainer.innerHTML = "<p>" + event.error.message + "</p>";
  } else {
    resultContainer.innerHTML = "";
  }
});

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

jQuery(document).ready(function () {
  /*
        Fullscreen background
    */
  $.backstretch("assets/img/backgrounds/1.jpg");

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

  $("#btn-close").on("click", function (e) {
      document.location.href="/";
  });

  $("#btn-pay-amount").on("click", function (e) {
    //alert("Hello");

    var stripeTokenID;
    var errorMessage;
    var isValid = true;

    //$("#btn-pay-amount").off("click");

    var selectedPackage = $("input[name='price']:checked").val();

    var cartNo = $("#card_number").val();
    var cartExpiry = $("#card_expiry").val();
    var cartCVC = $("#card_cvc").val();

    if ($("#f1-country").val() === "") {
      resultContainer.innerHTML = "<p>" + "Select your country" + "</p>";
      isValid = false;
    } else if ($("#f1-first-name").val() === "") {
      resultContainer.innerHTML =
        "<p>" + "Please enter your first name" + "</p>";
      isValid = false;
    } else if ($("#f1-last-name").val() === "") {
      resultContainer.innerHTML =
        "<p>" + "Please enter your last name" + "</p>";
      isValid = false;
    } else if ($("#f1-address").val() === "") {
      resultContainer.innerHTML = "<p>" + "Please enter your Address" + "</p>";
      isValid = false;
    } else if ($("#f1-sector").val() === "") {
      resultContainer.innerHTML = "<p>" + "Please enter your Address 2" + "</p>";
      isValid = false;
    } else if ($("#f1-postal-code").val() === "") {
      resultContainer.innerHTML = "<p>" + "Please enter Postal Code" + "</p>";
      isValid = false;
    } else if ($("#f1-city").val() === "") {
      resultContainer.innerHTML = "<p>" + "Please enter City Name" + "</p>";
      isValid = false;
    } else if ($("#f1-phone-type").val() === "") {
      resultContainer.innerHTML = "<p>" + "Select Phone Type" + "</p>";
      isValid = false;
    } else if ($("#f1-phone").val() === "") {
      resultContainer.innerHTML = "<p>" + "Please Enter Phone Number" + "</p>";
      isValid = false;
    } else if ($("#f1-email").val() === "") {
      resultContainer.innerHTML =
        "<p>" + "Please Enter your Email Address" + "</p>";
      isValid = false;
    } else {
      resultContainer.innerHTML = "";
    }

    if (!isValid) {
      return false;
    }

    stripe.createToken(cardElement).then(function (result) {
      if (result.error) {
        // Inform the user if there was an error
        errorMessage = result.error.message;
        resultContainer.innerHTML = "<p>" + errorMessage + "</p>";
        //$("#preloader").hide();

        isValid = false;
      } else {
        // Send the token to your server
        stripeTokenID = result.token.id;
        isValid = true;

        //var mUserID = $("#uid").val();
        var mUserID = 1;

        var data1 = {
          stripeTokenID: stripeTokenID,
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

        console.log("data%j", data1);

        if (!isValid) {
          return false;
        }

//fetch("templates/en/pages/stripe1/stripe_payment.php", {
alert("here");

fetch("stripe_payment.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/json"
          },
          body: data1
      }) .then(response => response.json())
      .then(function(result) {
          $("#preloader").hide();
           $("btn-pay-amount").on('click');
          if(result.data == "true") {
              alert("Your payment has been successful. Thank you  " );
              //window.location.replace("http://95.111.242.21/?p=ucp");
          } else {
              alert("Sorry, your payment has failed. Please try again.  ");
             //window.location.replace("http://95.111.242.21/?p=ucp");
          }
      });
        /*fetch("stripe_payment.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: data1,
        })
          .then((response) => response.json())
          .then(function (result) {

            alert("here inside");
            if (result.data == "true") {
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
                var progress_line = $(this)
                  .parents(".f1")
                  .find(".f1-progress-line");
                bar_progress(progress_line, "right");
              });

              // alert("Your payment has been successful. Thank you  ");
              var fullname =
                $("#f1-first-name").val() + " " + $("#f1-last-name").val();

              $("#f1-full-name").val(fullname);
              $("#f1-paid-amount").val("We've credited <number purchased> Shaiya Points to your account!");

              // window.location.replace("http://95.111.242.21/?p=ucp");
            } else {
              alert("Sorry, your payment has failed. Please try again.  ");
              //window.location.replace("http://95.111.242.21/?p=ucp");
            }
          });*/
      }
    });

    if (!isValid) {
      resultContainer.innerHTML = "<p>" + errorMessage + "</p>";
      $("#btn-pay-amount").on("click");
      return false;
    }

    return false;
  });
  // submit
  //$(".f1").on("#btn-pay-amount", function (e) {

  // fields validation
  // $(this)
  //   .find('input[type="text"], input[type="password"], textarea')
  //   .each(function () {
  //     if ($(this).val() == "") {
  //       e.preventDefault();
  //       $(this).addClass("input-error");
  //     } else {
  //       $(this).removeClass("input-error");
  //     }
  //   });
  // fields validation
  //  });
});
