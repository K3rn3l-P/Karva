// Barrapw.js

// Funzione per controllare la robustezza della password
function checkPasswordStrength(password) {
    var password_strength = document.getElementById("password-strength");

    // TextBox left blank.
    if (password.length == 0) {
        password_strength.innerHTML = "";
        return;
    }

    // Regular Expressions.
    var regex = [
        /[A-Z]/, // Uppercase Alphabet.
        /[a-z]/, // Lowercase Alphabet.
        /[0-9]/, // Digit.
        /[$@$!%*#?&]/ // Special Character.
    ];

    var passed = 0;

    // Validate for each Regular Expression.
    for (var i = 0; i < regex.length; i++) {
        if (regex[i].test(password)) {
            passed++;
        }
    }

    // Additional checks for password length and disallowed characters.
    if (password.length < 8 || password.length > 20) {
        password_strength.innerHTML = "Password must be between 8 and 20 characters long.";
        password_strength.className = "progress-bar bg-danger";
        return;
    }

    if (/[^A-Za-z0-9$@$!%*#?&]/.test(password)) {
        password_strength.innerHTML = "Password contains disallowed characters.";
        password_strength.className = "progress-bar bg-danger";
        return;
    }

    // Display status.
    var strength = "";
    var progressBarClass = "";
    switch (passed) {
        case 0:
        case 1:
        case 2:
            strength = "Weak";
            progressBarClass = "bg-danger";
            break;
        case 3:
            strength = "Medium";
            progressBarClass = "bg-warning";
            break;
        case 4:
            strength = "Strong";
            progressBarClass = "bg-success";
            break;
    }
    password_strength.innerHTML = strength;
    password_strength.className = "progress-bar " + progressBarClass;
}

// Funzione per controllare se le password corrispondono
function checkPasswordMatch() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("password_confirm").value;
    var passwordMatchDiv = document.getElementById("password-match");

    if (password === confirmPassword) {
        passwordMatchDiv.innerHTML = "";
        passwordMatchDiv.className = "";
    } else {
        passwordMatchDiv.innerHTML = "Passwords do not match.";
        passwordMatchDiv.className = "error";
    }
}

// Funzione per generare una password casuale con una robustezza accettabile
function generatePassword() {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz$@$!%*#?&()_+";
    var passwordLength = 12;
    var password = "";

    // Genera una password casuale fino a quando non soddisfa i criteri di robustezza desiderati
    do {
        password = ""; // Resetta la password ad ogni iterazione

        // Genera la password
        for (var i = 0; i < passwordLength; i++) {
            var randomNumber = Math.floor(Math.random() * chars.length);
            password += chars.substring(randomNumber, randomNumber + 1);
        }

        // Controlla la robustezza della password generata
        var hasUpperCase = /[A-Z]/.test(password);
        var hasLowerCase = /[a-z]/.test(password);
        var hasNumber = /[0-9]/.test(password);
        var hasSpecialChar = /[$@$!%*#?&]/.test(password);

    } while (!(hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar)); // Ripeti finché la password non soddisfa i criteri di robustezza

    document.getElementById("password").value = password;
    document.getElementById("password_confirm").value = password;

    checkPasswordStrength(password);
}



// Funzione per mostrare/nascondere la password
function togglePasswordVisibility() {
    var passwordField = document.getElementById("password");
    var confirmPasswordField = document.getElementById("password_confirm");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        confirmPasswordField.type = "text";
    } else {
        passwordField.type = "password";
        confirmPasswordField.type = "password";
    }
}
