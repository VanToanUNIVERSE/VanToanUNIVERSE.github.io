
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
    new Card(1, "Áo thun", 2, 20, 'images/AK1.png'),
    new Card(2, "Quần jean", 1, 35, 'images/AK2.png'),
    new Card(3, "Giày thể thao", 3, 50, 'images/AK3.png'),
    new Card(1, "Áo thun", 2, 20, 'images/AK1.png'),
    new Card(3, "Giày thể thao", 3, 50, 'images/AK3.png'),
    new Card(1, "Áo thun", 2, 20, 'images/AK1.png')
    
    
];

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

const user1 = new user(1000, "Hong ngu dong thap"); //tao user

// lấy element

const productPaymentList = document.querySelector('.product-payment-list'); // lấy danh sach xac nhan
const productList = document.querySelector('.product-list'); // lay danh sach hien thi
const validateTotalPrice = document.getElementById('validate-total-price'); // lay xac nhan tong gia

const userWallet = document.querySelector('.user-wallet'); // lay E so tien trong vi
const totalPrice = document.querySelector('.total-price');  // lay E tong tien
const address = document.getElementById('address'); //lay E diachi
const addressError = document.getElementById('address-error'); //lay E loi dia chi
const paymentError = document.querySelector('.payment-error'); //lay E loi thanh toan
const paymentValidate = document.querySelector(".display-none"); // lay E container vo hinh
const paymentButton = document.querySelector(".payment-btn");// lay btn thanh toan
const validateAddress = document.getElementById('validate-address');//lay E xac nhan dia chi
const validatePayment = document.querySelector('.validate-payment');//lay E xac nhan thanh toan


const deliveryContainer = document.querySelector('.delivery-container');
const deliveryHead = document.querySelector('.delivery-head');
let deliveryItems = document.querySelectorAll('.delivery-item');
const deleleDelivery = document.querySelector('.fa-trash');


const scrollContainer = document.querySelector(".product-list");//lay E de xuly overflow
const beforeElement = document.querySelector('.before');//lay E cua nut tiep
const afterElement = document.querySelector('.after');//lay E cua nut lui
//lay E chua thong tin nhưng product can validate


// xoa card khoi danh sach
function clear(name) {
    while(name.firstChild) {
        name.removeChild(name.firstChild);
    }
}


// them card tu danh sach
function addToList(card, container) {
    //tao card can dc tach rieng
    const cardElement = document.createElement('div');
    cardElement.classList.add('card');

    const nameElement = document.createElement('p');
    nameElement.innerHTML = card.name;

    const imgElement = document.createElement('img');
    imgElement.src = card.image;
    imgElement.alt = card.name;

    const quanlityELement = document.createElement('p');
    quanlityELement.innerHTML = 'So luong :' + card.quanlity;

    const costElement = document.createElement('p');
    costElement.innerHTML = 'Gia : ' + card.price + ' $';

    cardElement.append(imgElement, nameElement, quanlityELement, costElement);
    container.appendChild(cardElement);

    // add trong xác nhận
    const validateProductElement = document.createElement('p');
    validateProductElement.innerHTML = `<span class="validate-quantity"> ${card.quanlity} </span> ${card.name}   ${card.price} $`;
    productPaymentList.appendChild(validateProductElement);
    totalPriceValue += card.price * card.quanlity;


    //add vào Payment

}


function displayInfomation() {
    userWallet.innerHTML = 'Tiền trong ví: ' + user1.getWallet() + " $"; // hien thi tien trong vi
    totalPrice.innerHTML = 'Tổng: ' + totalPriceValue + ' $'; // hien thi tong tien can thanh toan
    validateTotalPrice.innerHTML = totalPriceValue + ' $';
}


function addEven() {
    //nut thanh toan
    
    paymentButton.addEventListener('click', () => {
        const address = document.getElementById("address");
            if(address.value.trim() == '') {
                    addressError.innerHTML = 'Can not empty';
                    addressError.style.display = 'block';
                    address.style.borderBottom = '1px solid red';
                setTimeout(() => {
                    addressError.style.display = 'none';
                    address.style.borderBottom = '1px solid #333';
                }, 2000);

            }
            else
            {
                const checkedPayment = document.querySelector('input[name="payment-method"]:checked');
                console.log(checkedPayment.value);
                hienHinh();
                if(checkedPayment.value == 'wallet')
                {
                    document.getElementById("validate-payment-method").innerHTML = "Phương thức thanh toán: Wallet";
                }
                else
                {
                    document.getElementById("validate-payment-method").innerHTML = "Phương thức thanh toán: Thanh toán khi nhận hàng";
                }
                
                validateAddress.innerHTML = "Địa chỉ nhận hàng: " +  address.value;
                
            }  
            
           
    });

   
    //thay doi phuong thuc thanh toan
    /* document.querySelectorAll('input[name="payment-method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if(this.value == "cod") {
                address.style.display = 'block';
                document.getElementById("address-wallet").style.display = "none";
            } else {
                address.style.display = 'none';
                document.getElementById("address-wallet").style.display = "block";
            }
        });
    }); */
}


function hienHinh() {
    if(paymentValidate.style.display == 'none' || paymentValidate.style.display == '') {
        paymentValidate.style.display = 'block';
    } else {
        paymentValidate.style.display = 'none';
    }
    
}

/* function Payment() {
    const checkedPayment = document.querySelector('input[name="payment-method"]:checked');
    console.log(checkedPayment.value);
    if(checkedPayment.value == 'wallet') {
        if(Number(user1.getWallet()) > Number(totalPriceValue)) {
            user1.setWallet(user1.getWallet() - totalPriceValue);
            displayInfomation();
            hienHinh();
            alert("Thanh toan thanh cong");
            createDelivery();
        } else {
            paymentError.style.display = 'block';
            paymentError.innerHTML = "Ví của bạn chỉ còn " + user1.getWallet() + " $";
            setTimeout(() => {paymentError.style.display = 'none';}, 2000);
        } 
    } else {
        alert("Đặt hàng thành công");
        createDelivery();
        hienHinh();
    }
     
} */

//delivery

class Delivery {
    constructor(deliveryID, deliveryStatus, deliveryPrice) {
        this.deliveryID = deliveryID;
        this.deliveryStatus = deliveryStatus;
        this.deliveryPrice = deliveryPrice;
    }
}



function createDelivery() {
    const checkedPayment = document.querySelector('input[name="payment-method"]:checked');
    let delivery = new Delivery();
    let ad;
    if(checkedPayment.value == 'wallet') {
        delivery = new Delivery('1', 'Chờ xác nhận', 'Đã thanh toán');
        ad = user1.getAddress();
        
    }
    else {
        delivery = new Delivery('1', 'Chờ xác nhận', totalPriceValue);
        ad = address.value;
    }

    //create payment detail
    
    const payment = new PaymentClass(delivery, ad, checkedPayment.value, cards);
    paymentDetails.innerHTML = `
        <p>${payment.delivery.deliveryID}</p>
            <p>${payment.delivery.deliveryStatus}</p>
            <p>${payment.delivery.deliveryPrice}</p>
            <p>${payment.address}</p>
            <p>${payment.paymentMethod}</p>
            <div class="product-list" id="payment-product-list">
                
            </div>

            <button class="close-payment-detail-btn btn" onclick="closePMD()">X</button>`;
    const paymentProductList = document.getElementById('payment-product-list');
    paymentProductList.addEventListener('wheel', (e) => {
        e.preventDefault();
        paymentProductList.scrollLeft += e.deltaY;
        
    });
    clear(productPaymentList);
    payment.cards.forEach(card => {
        addToList(card, paymentProductList);
    });   
    
    // create delivery
    const deliveryItemElement = document.createElement('div');
    deliveryItemElement.classList.add('delivery-item');
    deliveryItemElement.innerHTML = `<p class="delivery-id">${delivery.deliveryID}</p>
    <p class="delivery-status">${delivery.deliveryStatus}</p>
    <p class="delivery-price">${delivery.deliveryPrice} $</p>
    <div class="delivery-icons">
        <button onclick='closePMD()'><i class="fa-solid fa-eye" title="Xem chi tiết đơn"></i></button>
        <i class="fa-solid fa-trash" title="Hủy đơn"></i>
    </div>;`
    deliveryContainer.appendChild(deliveryItemElement);

    deliveryItems = document.querySelectorAll('.delivery-item');// update lai danh sach delivery
}

function displayDeliveryContainer() {
    if(!deliveryContainer.querySelector('.delivery-item')) {
        deliveryContainer.style.display = 'none';
    } else {
        deliveryContainer.style.display = 'flex';
    }

    //sau khi hien thị addeven
    deliveryItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if(e.target.classList.contains('fa-trash')) {
                e.target.parentElement.parentElement.remove();
            }
            
            displayDeliveryContainer();
        })
    });
}




//payment detail
const paymentDetails = document.querySelector('.payment-detail');


class PaymentClass {
    constructor(delivery, address, paymentMethod, cards) {
        this.delivery = delivery instanceof Delivery ? delivery : new Delivery(); 
        this.address = address;
        this.paymentMethod = paymentMethod;
        this.cards = Array.isArray(cards) ? cards : [];
    }
}


function closePMD() {
    if(paymentDetails.style.display == 'none' || paymentDetails.style.display == '') {
        paymentDetails.style.display = 'flex';
        
    } else {
        paymentDetails.style.display = 'none';
    }
    
}



function createOverflowForProductList() {
    const maxScrollLeft = scrollContainer.scrollWidth - scrollContainer.clientWidth;
    function updateButtonState(clickedButton) {
        if(clickedButton == 'before') {
            afterElement.style.color = '#333';
            beforeElement.style.color = scrollContainer.scrollLeft <= scrollContainer.offsetWidth/3 ? '#3333331e' : '#333';
        }
        if(clickedButton == 'after') {
            beforeElement.style.color = '#333';
            afterElement.style.color = scrollContainer.scrollLeft >= maxScrollLeft - scrollContainer.offsetWidth/3 ? '#3333331e' : '#333';
        }
    }

    afterElement.addEventListener('click', () => {
        scrollContainer.scrollLeft += scrollContainer.offsetWidth/3;  
        updateButtonState('after');

        /* afterElement.classList.add('animate');
        setTimeout(() => {afterElement.classList.remove('animate');}, 500); */
    });

    beforeElement.addEventListener('click', () => {
        scrollContainer.scrollLeft -= scrollContainer.offsetWidth/3;
        updateButtonState('before');

        /* beforeElement.classList.add('animate');
        setTimeout(() => {beforeElement.classList.remove('animate');}, 500); */
    });
    
    scrollContainer.addEventListener('wheel', (e) => {
        e.preventDefault();
        scrollContainer.scrollLeft += e.deltaY;
        beforeElement.style.color = scrollContainer.scrollLeft <= 0 ? "#3333331e" : "#333";
        afterElement.style.color = scrollContainer.scrollLeft >= maxScrollLeft - 10 ? "#3333331e" : "#333";
    });

    //validate
    
}



function LoadPage() {
    
    
    /* clear(productList);// clear
    clear(productPaymentList);
    
    //tao sp
    //them san pham va hien thi
    cards.forEach((card) => {
        addToList(card, productList);
    }); */

    createOverflowForProductList();

    //add even
    addEven();

    //hien thi tien va so du
    /* displayInfomation(); */
}

    
LoadPage();


//lam mau
const beforeAndAfter = document.querySelectorAll('.effect-btn');

beforeAndAfter.forEach(bt => {
    bt.addEventListener('click', () => {
        bt.classList.add('effect');
        setTimeout(() => {bt.classList.remove('effect');}, 500);
    });
});

//lien quan backend
function deleteCard(cartKey) //ham xoa AJAX
{
    const card = document.getElementById(cartKey);
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../payment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4 && xhr.status === 200)
        {
            const response = xhr.responseText;
            console.log(response);
            card.style.display = 'none';
            document.querySelector(".total-price").setAttribute("data-total-price", response);
            console.log(document.getElementById(cartKey));
        }
        
    }
    xhr.send("action=deleteCard&cartKey=" + cartKey);
}

function deleteCardUI(cartKey)
{
    const card = document.getElementById(cartKey);
    const productPrice = document.getElementById(cartKey + "-price").getAttribute("data-price");
    const productQuantity = document.getElementById(cartKey + "-quantity").getAttribute("data-quantity");
    const cardPrice = productQuantity * productPrice;
    const totalPrice = document.querySelector(".total-price");
    let totalPriceValue = totalPrice.getAttribute("data-total-price");
    totalPriceValue -= parseFloat(cardPrice);
    totalPrice.setAttribute("data-total-price", totalPriceValue);
    totalPrice.innerHTML = totalPriceValue.toLocaleString("vi-VN") + " VNĐ";
    validateTotalPrice.innerHTML = totalPriceValue.toLocaleString("vi-VN") + " VNĐ";
    if(totalPriceValue == 0)
    {
        document.querySelector(".product-list").innerHTML = "<h3>Chưa có sản phẩm nào</h3>";
        document.querySelector(".payment-btn").style.display = "none";
    }
}

if(!productList.querySelector(".card")) // neu chua co san pham khong hien thi nut thanh toan
{
    paymentButton.style.display = "none";
}

function Payment(wallet) //ham thanh toan AJAX
{   let status = "Chưa thanh toán";
    const checkedPayment = document.querySelector('input[name="payment-method"]:checked').value;
    let orderPrice = document.querySelector(".total-price").getAttribute("data-total-price");
    console.log(orderPrice);
    if(checkedPayment == "wallet")
    {
        status = "Đã thanh toán";
    }
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../payment.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4 && xhr.status === 200)
        {    
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            
            if(response.message == "1")
            {
                paymentError.innerHTML = "Không đủ tiền trong ví";
                console.log(response.message);
                paymentError.style.display = "block";
                setTimeout(() => {paymentError.style.display = "none";}, 2000);
                return;
            }
            
                const walletElement = document.querySelector(".user-wallet");
                walletElement.innerHTML = "Tiền trong ví: " + parseFloat(response.wallet).toLocaleString("vi-VN") + " VNĐ";
                const totalPriceElement = document.querySelector(".total-price");
                totalPriceElement.innerHTML = "Tổng " + parseFloat(response.orderPrice).toLocaleString("vi-VN") + " VNĐ";;
                clear(productList);
                clear(productPaymentList);
                validatePayment.style.display = "none";
                document.querySelector(".payment-success").style.display = "block";
                setTimeout(() => {document.querySelector(".payment-success").style.display = "none";}, 2000);
                paymentButton.style.display = "none";

                
                const div = document.createElement("div");
                div.classList.add("delivery-item");
                div.setAttribute("id", response.orderID);
                div.innerHTML = response.delivery;
                deliveryContainer.appendChild(div);
                deliveryContainer.style.display = "flex";
              
        }
    }
    xhr.send("wallet=" + wallet +"&orderPrice=" + orderPrice + "&address=" + address.value + "&method=" + checkedPayment + "&status=" + status);
}


function showOrderDetails(orderID)
{
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../payment.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4 && xhr.status === 200)
        {
            const orderDetails = document.querySelector(".payment-detail");
            orderDetails.style.display = "flex";
            const respone = xhr.responseText;
            console.log(respone);
            orderDetails.innerHTML = respone;
        }
        
    }
    xhr.send("action=show&orderID=" + orderID);
}

function cancelOrder(orderID)
{
    const deliveryStatus = document.getElementById(orderID + "-status");
    const delivery = document.getElementById(orderID);
    let remove = 0;
    if(deliveryStatus.innerHTML == "Đã hủy") 
    {
        remove = 1;
    }
    
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../payment.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4 && xhr.status === 200)
        {
            
            if(deliveryStatus.innerHTML == "Đã hủy") //neu da cancel thi xoa
            {
                deliveryContainer.removeChild(delivery);
                if(!deliveryContainer.querySelector(".delivery-item"))
                {
                    deliveryContainer.style.display = "none";
                }
            }
            else
            {
                const cancelButton = document.getElementById("cancel-btn");
                cancelButton.setAttribute("title", "Xóa đơn");
                deliveryStatus.innerHTML = xhr.responseText;
            }      
        }
        
    }
    xhr.send("action=cancel&orderID=" + orderID + "&remove=" + remove);
}

function beforePayment()
{
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../payment.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200)
        {
            productPaymentList.innerHTML = xhr.responseText;
        }
    }
    xhr.send("beforePayment=1");
}



