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