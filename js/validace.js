(function(global) {
    // Declare variables for form elements
    let nameEl = null;
    let emailEl = null;
    let passEl = null;
    let passAgainEl = null;
    let formEl = null;

    function init() {
        // Assign the form and form elements to the declared variables
        formEl = document.querySelector('form');
        nameEl = document.querySelector('#name');
        emailEl = document.querySelector('#email');
        passEl = document.querySelector('#password');
        passAgainEl = document.querySelector('#password_confirm');
        // Add a submit event listener to the form that calls the validate function
        formEl.addEventListener('submit', validate);
    }

    function validate(e) {
        // Call the individual validation functions
        const nameResult = validateName();
        const emailResult = validateEmail();
        const passResult = validatePassword();
        const passAgainResult = validatePasswordAgain();

        if (nameResult && emailResult && passResult && passAgainResult) {
        } else {
            // Prevent the form from submitting if any of the validation functions returned false
            e.preventDefault();
        }
    }

    function validateName() {
        // Check if the name field has at least 5 characters
        if (nameEl.value.length >= 5) {
	    if (nameEl.parentElement.querySelector('error')) {
        	nameEl.parentElement.removeChild(nameEl.parentElement.querySelector('error'));
	    }
            return true;
        } else {
            if ( nameEl.parentElement.querySelector('span') == null) {
        	const errorDiv = document.createElement('span');
        	errorDiv.classList.add('error');
        	nameEl.parentElement.append(errorDiv);
	    }
            return false;
        }
    }

    function validateEmail() {
        // Check if the email field has at least 5 characters
        if (emailEl.value.length >= 5) {
            // Remove the error class if the validation passes
            emailEl.parentElement.classList.remove('error');
            return true;
        } else {
            // Add the error class if the validation fails
            emailEl.parentElement.classList.add('error');
            return false;
        }
    }

    function validatePassword() {
        // Check if the password field has at least 8 characters
        if (passEl.value.length >= 8) {
            // Remove the error class if the validation passes
            passEl.parentElement.classList.remove('error');
            return true;
        } else {
            // Add the error class if the validation fails
            passEl.parentElement.classList.add('error');
            return false;
        }
    }

    function validatePasswordAgain() {
        // Check if the password confirmation field matches the password field
        if (passAgainEl.value == passEl.value) {
            // Remove the error class if the validation passes
            passAgainEl.parentElement.classList.remove('error');
            return true;
        } else {
            // Add the error class if the validation fails
            passAgainEl.parentElement.classList.add('error');
            return false;
        }
    }

    global.init = init;
})(window)