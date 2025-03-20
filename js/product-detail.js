const proudctImageContainer = document.querySelector('.product-image-container');
const beforeButton = document.querySelector('.before');
const afterButton = document.querySelector('.after');

const maxScrollLeft = proudctImageContainer.scrollWidth - proudctImageContainer.clientWidth;
const width = proudctImageContainer.offsetWidth * 1.1;

proudctImageContainer.addEventListener('wheel', (e) => {
    e.preventDefault();
    proudctImageContainer.scrollLeft += e.deltaY;
    updateButtonState();
});

beforeButton.addEventListener('click', () => {
    proudctImageContainer.scrollLeft -= width;
    updateButtonState();
});

afterButton.addEventListener('click', () => {
    proudctImageContainer.scrollLeft += width;
    updateButtonState();
});

function updateButtonState() {
    setTimeout(() => {
        if(proudctImageContainer.scrollLeft <= 0) {
            beforeButton.style.opacity = 0.1;
        }
        else {
            beforeButton.style.opacity = 1;
        }
        if(proudctImageContainer.scrollLeft >= maxScrollLeft - width) {
            afterButton.style.opacity = 0.1;
        }
        else {
            afterButton.style.opacity = 1;
        }
        console.log(maxScrollLeft);
        console.log(proudctImageContainer.scrollLeft);
    }, 100);
}



// AJAX
function addToCart(productID, productName, productQuantity, productPrice, productImage)
{
    const productSize = document.getElementById('size').value;
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../product-detail.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4 && xhr.status === 200)
        {
            
            document.getElementById("submit-message").style.display = "block"; 
        }
        setTimeout(() => {document.getElementById("submit-message").style.display = "none"; }, 2000);
    }
    xhr.send("productID=" + productID +"&productName=" + productName + "&productQuantity=" + productQuantity + "&productPrice=" + productPrice + "&productSize=" + productSize + "&productImage=" + productImage);
}