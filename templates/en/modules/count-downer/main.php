<section class="sidebox_topvoters topvoter sidebox">
    <h4 class="sidebox_title border_box">
        <i>GRB STARTS IN</i>
    </h4>
    <div class="sidebox_body border_box">
        <h1 id="countdown"></h1>
    </div>
</section>

<script>
    // Set the date we're counting down to
    var countDownDate = new Date("Sep 5, 2024 15:37:25").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {
        // Get the current date and time
        var now = new Date().getTime();

        // Calculate the distance between now and the count down date
        var distance = countDownDate - now;

        // Calculate days, hours, minutes, and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Format the output
        var countdownString = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        // Output the result
        document.getElementById("countdown").innerHTML = countdownString;

        // If the count down is over, display "EXPIRED"
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>
