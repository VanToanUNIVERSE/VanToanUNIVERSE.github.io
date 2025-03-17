const fullName = document.getElementById("full-name").value.trim();
const email = document.getElementById("email").value.trim();
const phone = document.getElementById("phone").value.trim();
const address =document.getElementById("address").value.trim();
const submitError = document.getElementById("submit-error");

const form = document.getElementById('submit-form');

function Close(e) {
    e.preventDefault();
    form.style.display = 'none';
}

