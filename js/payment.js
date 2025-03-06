
let totalPriceValue = 0;

// products list

class Card {
    constructor(id, name, quanlity, price, image) {
        this.id = id;
        this.name = name;
        this.quanlity = quanlity;
        this.price = price;
        this.image = image;
    }
}

const cards = [
    new Card(1, "Áo thun", 2, 20, 'images/product1.png'),
    new Card(2, "Quần jean", 1, 35, 'images/product2.png'),
    new Card(3, "Giày thể thao", 3, 50, 'images/product3.png')
];

// lấy element

const productPaymentList = document.querySelector('.product-payment-list'); // lấy danh sach xac nhan
const productList = document.querySelector('.product-list'); // lay danh sach hien thi
const validateTotalPrice = document.getElementById('validate-total-price'); // lay xac nhan tong gia
clear();
cards.forEach((card) => {
    addToList(card);
}); 

// xoa card khoi danh sach
function clear() {
    while(productList.firstChild) {
        productList.removeChild(productList.firstChild);
    }
    while(productPaymentList.firstChild) {
        productPaymentList.removeChild(productPaymentList.firstChild);
    }
}


// them card tu danh sach
function addToList(card) {
    const cardElement = document.createElement('div');
    cardElement.classList.add('card');
    const imgElement = document.createElement('img');
    imgElement.src = card.image;
    imgElement.alt = card.name;
    const quanlityELement = document.createElement('p');
    quanlityELement.innerHTML = 'So luong :' + card.quanlity;
    const costElement = document.createElement('p');
    costElement.innerHTML = 'Gia : ' + card.price + ' $';

    cardElement.append(imgElement, quanlityELement, costElement);
    productList.appendChild(cardElement);

    // add trong xác nhận
    const validateProductElement = document.createElement('p');
    validateProductElement.innerHTML = `<span class="validate-quantity"> ${card.quanlity} </span> ${card.name}   ${card.price} $`;
    productPaymentList.appendChild(validateProductElement);
    totalPriceValue += card.price;

}

// phần thanh toán
class user {
    #wallet;
    #address;

    constructor(wallet, address) {
        this.#wallet = wallet;
        this.#address = address;
    }

    getWallet() {
        return this.#wallet;
    }

    setWallet(para) {
        this.#wallet = para;
    }

    getAddress() {
        return this.#address;
    }

    
}
const user1 = new user(1000, "Hong ngu dong thap");




const userWallet = document.querySelector('.user-wallet');
const totalPrice = document.querySelector('.total-price');
const address = document.getElementById('address');
const addressError = document.getElementById('address-error');
const paymentError = document.querySelector('.payment-error');
const paymentValidate = document.querySelector(".vohinh");
const paymentButton = document.querySelector(".payment-btn");
const validateAddress = document.getElementById('validate-address');
const validatePayment = document.querySelector('.validate-payment');

userWallet.innerHTML = 'Tiền trong ví: ' + user1.getWallet() + " $"; // hien thi tien trong vi


totalPrice.innerHTML = 'Tổng: ' + totalPriceValue + ' $'; // hien thi tong tien can thanh toan

validateTotalPrice.innerHTML = totalPriceValue + ' $';


paymentButton.addEventListener('click', () => {
    const checkedPayment = document.querySelector('input[name="payment-method"]:checked'); 
    if(checkedPayment.value == 'wallet') {
        validateAddress.innerHTML = "Địa chỉ: " + user1.getAddress();
    }
    else {
        if(address.value.trim() == '') {
            addressError.innerHTML = 'Can not empty';
            addressError.style.display = 'block';
            address.style.borderBottom = '1px solid red';
            setTimeout(() => {
                addressError.style.display = 'none';
                address.style.borderBottom = '1px solid #333';
            }, 2000);
            
            return;
            
        }
        validateAddress.innerHTML = "Địa chỉ: " + address.value;
    }
    hienHinh();
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

// Xác nhận thanh toán
console.log(user1.getWallet());
console.log(totalPriceValue);
function Payment() {
    if(Number(user1.getWallet()) > Number(totalPriceValue)) {
        user1.setWallet(user1.getWallet() - totalPriceValue);
        userWallet.innerHTML = user1.getWallet();
        hienHinh();
        alert("Thanh toan thanh cong");
    } else {
        paymentError.style.display = 'block';
        paymentError.innerHTML = "Ví của bạn chỉ còn " + user1.getWallet() + " $";
        setTimeout(() => {paymentError.style.display = 'none';}, 2000);
    }  
}




    
    



