function toggleView(btn, postId) {
	var post = $("#" + postId).find(".post-bottom");
	if ($(post).css("display") == "block") {
		$(btn).find(".rm").show();
		$(btn).find(".rl").hide();
	} else {
		$(btn).find(".rm").hide();
		$(btn).find(".rl").show();
	}
	$(post).slideToggle(300);
}

function startTimer(time, divId, expiredLabel) {
  // Get todays date and time
	var end = new Date().getTime() + time * 1000;
	// Update the count down every 1 second
	var x = setInterval(function() {
	  // Get todays date and time
	  var now = new Date().getTime();

	  // Find the distance between now and the count down date
	  var distance = end - now;

	  // Time calculations for days, hours, minutes and seconds
	  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, "0");;
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, "0");;

	  // Display the result in the element with id=divId
	  var daysLabel = days ? days + "d " : "";
	  var hoursLabel = hours ? hours + ":" : "";
	  var targetElem = document.getElementById(divId);
	  if (targetElem) {
		  targetElem.innerHTML = daysLabel + hoursLabel + minutes + ":" + seconds;

		  // If the count down is finished, write some text 
		  if (distance < 0) {
			clearInterval(x);
			document.getElementById(divId).innerHTML = expiredLabel;
		  }
	  } else {
		  clearInterval(x);
	  }
	}, 1000);
}