const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm-password');
const confirmPasswordErrorSpan = document.getElementById('confirm-password-error');
const usernameErrorSpan = document.getElementById('username-error');
const passwordErrorSpan = document.getElementById('password-error');
const loginErrorSpan = document.getElementById('login-error');
const sigupErrorSpan = document.getElementById('signup-error');
const notExistErrorSpan = document.getElementById('not-exist-error');
const doesExistErrorSpan = document.getElementById('does-exist-error');

console.log(confirmPasswordErrorSpan, confirmPasswordInput);

function validateUsername() 
{
    if(notExistErrorSpan) {notExistErrorSpan.style.display = 'none';}
    if(doesExistErrorSpan) {doesExistErrorSpan.style.display = 'none';}
        
    const username = usernameInput.value.trim();
    if(username == "")
    {
        usernameErrorSpan.innerHTML = 'Tên đăng nhập không thể rổng';
        return false;
    }
    if(username.includes(" "))
    {
        usernameErrorSpan.innerHTML = 'Không được chứa khoảng trắng';
        return false;
    }
    usernameErrorSpan.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validatePassword() {
    if(notExistErrorSpan) {notExistErrorSpan.style.display = 'none';}
    if(doesExistErrorSpan) {doesExistErrorSpan.style.display = 'none';}
    const password = passwordInput.value.trim();
    if(password == "")
    {
        passwordErrorSpan.innerHTML = 'Không thể trống';
        return false;
    }
    if(password.length < 8)
        {
            passwordErrorSpan.innerHTML = 'Ít nhất 8 kí tự';
            return false;
        }
    if(password.includes(" "))
    {
        passwordErrorSpan.innerHTML = 'Khôn được chứa khoảng trắng';
        return false;
    }
    if(!password.match(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/))
        {
            passwordErrorSpan.innerHTML = 'Phải có chữ và số';
            return false;
        }
    passwordErrorSpan.innerHTML = '<i class="fa-solid fa-check"></i>';
    return true;
}

function validateConfirmPassword() {
    const confirmPassword = confirmPasswordInput.value.trim();
    const password = passwordInput.value.trim();
    if(confirmPassword != password) 
    {
        confirmPasswordErrorSpan.innerHTML = "Không khớp";
        return false;
    }
    else 
    {
        confirmPasswordErrorSpan.innerHTML = '<i class="fa-solid fa-check"></i>';
        return true;
    }
}

function validateLogin(e) {
    if(!validateUsername() || !validatePassword()) 
    {
        e.preventDefault();
        loginErrorSpan.innerHTML = 'Vui lòng sửa những lỗi ở trên';
        setTimeout(() => {loginErrorSpan.innerHTML = ''}, 2000)
        return false;
    }
    return true;
}

function validateSignup(e) {
    
    if(!validateUsername() || !validatePassword() || !validateConfirmPassword()) 
        {
            e.preventDefault();
            sigupErrorSpan.innerHTML = 'Vui lòng sửa những lỗi ở trên';
            setTimeout(() => {sigupErrorSpan.innerHTML = ''}, 2000)
            return false;
        }
        return true;
}

/* function checkAccount(e) {
    
    if(validateLogin()) 
    {
        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();
        const user = accounts.find(acount => acount.username === username && acount.password === password);
        if(user)
        {
            alert("Login Success");
            window.location.href = "index.html";
        }
        else
        {
            alert("account does not exist");
        }
    }
    else
    {
        e.preventDefault();
    }
} */



/* function addAccount(e) 
{
    e.preventDefault();
    if(validateSignup()) 
    {
        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();
        const account = new Account(username, password);
        
        accounts.push(account);
        localStorage.setItem('accounts', JSON.stringify(accounts));

        
    }
    const bien = JSON.parse(localStorage.getItem('accounts'));
        console.log(bien);
} */

//Test login

/* class Account 
{
    username;
    password;
    constructor(username, password) {
        this.username = username;
        this.password = password;
    }
}

const accounts = JSON.parse(localStorage.getItem('accounts')) || []; */