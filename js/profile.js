

const form = document.getElementById('submit-form');

function Close(e) {
    e.preventDefault();
    form.style.display = 'none';
}

function validateForm(e)
{
    const fullName = document.getElementById("full-name").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const address =document.getElementById("address").value.trim();
    const image = document.getElementById("image").files;
    const submitError = document.getElementById("submit-error");
    if(fullName == "" && email == "" && phone == "" && address == "" && image.length == 0)
    {
        submitError.innerHTML = "Vui lòng điển ít nhất 1 thông tin";
        submitError.style.display = "block";
        setTimeout(() => {submitError.style.display = "none";}, 2000);
        e.preventDefault();
    }
}
