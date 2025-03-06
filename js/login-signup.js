/* const nameError = document.getElementById('name-error');
const phoneError = document.getElementById('phone-error');
const emailError = document.getElementById('email-error');
const addressError = document.getElementById('address-error');
const submitError = document.getElementById('submit-error');

function validateName() {
    const name = document.getElementById('contact-name').value;


    if(name.length == 0) {
        nameError.innerHTML = 'Name is required';
        return false;
    }
    if(!name.match(/^[A-Za-z]*\s{1}[A-Za-z]+$/)) {
        nameError.innerHTML = 'Write full name';
        return false;
    }
    nameError.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validatePhone() {
    const phone = document.getElementById('contact-phone').value;

    if(phone.length == 0) {
        phoneError.innerHTML = 'Phone is required';
        return false;
    }
    if(phone.length != 10) {
        phoneError.innerHTML = 'Phone number should be 10 digits';
        return false;
    }
    if(!phone.match(/^[0-9]{10}$/)) {
        phoneError.innerHTML = 'Only digits please';
        return false;
    }
    phoneError.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validateEmail() {
    const email = document.getElementById('contact-email').value;

    if(email.length == 0) {
        emailError.innerHTML = 'Email is required';
        return false;
    }
    if(!email.match(/^[A-Za-z\._\-[0-9]*[@][A-Za-z]*[\.][a-z]{3}$/))
    {
        emailError.innerHTML = 'Email invalid';
        return false;
    }
    emailError.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validateAddress() {
    const address = document.getElementById('address').value;
    const required = 10;
    let left = required - address.length;

    if(address.length < required) {
        addressError.innerHTML = `${left} more characters required`;
        return false;
    }
    addressError.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validateForm() {
    if(!validateName() || !validatePhone() || !validateEmail() || !validateAddress()) {
        submitError.style.display = 'block';
        submitError.innerHTML = 'Please fix errors to submit';
        setTimeout(() => {submitError.style.display = 'none';}, 3000);
        return false;
    }
} */


const emailError = document.getElementById("email-error");
const passwordError = document.getElementById("password-error");
const loginError = document.getElementById("login-error");

function validateEmail() {
    const email = document.getElementById('contact-email').value;
    if(email.length == 0) {
        emailError.innerHTML = 'Email is required';
        return false;
    }
    if(!email.match(/^[A-Za-z\._\-[0-9]*[@][A-Za-z]*[\.][a-z]{3}$/))
    {
        emailError.innerHTML = 'Email invalid';
        return false;
    }
    emailError.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validatePassword() {
    const password = document.getElementById("password").value;
    if(password.length == 0) {
        passwordError.innerHTML = "Password is required";
        return false;
    }
    if(!password.match(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/)) {
        passwordError.innerHTML = "Password Invalid";
        return false;
    }
    passwordError.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}
