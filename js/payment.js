let wallet = 100000;


const address = document.getElementById('address');
const paymentValidate = document.querySelector(".vohinh");
const paymentButton = document.querySelector(".payment-btn");
const validateAddress = document.getElementById('validate-address');
const validatePayment = document.querySelector('.validate-payment');


paymentButton.addEventListener('click', () => {
    if(address.value.trim() == '') {
        let walletElement = document.getElementById('wallet')
        if(!walletElement) {
            walletElement = document.createElement('p');
            walletElement.id = 'wallet';
            
            validatePayment.appendChild(walletElement);
        }
        walletElement.innerHTML = 'WALLET :' + wallet;
    }
    else {
        validateAddress.innerHTML = "Địa chỉ: " + address.value;
    }
});


document.querySelectorAll('input[name="payment-method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if(this.value == "cod") {
            address.style.display = 'block';
        } else {
            address.style.display = 'none';
        }
    });
});

function hienHinh() {
    if(paymentValidate.style.display == 'none' || paymentValidate.style.display == '') {
        paymentValidate.style.display = 'block';
    } else {
        paymentValidate.style.display = 'none';
    }
    
}