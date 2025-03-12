const moreElement = document.querySelector('.more');//lay element more
const moreContent = document.querySelector('.more-content');//lay E morecontent
const main = document.querySelector('main');//lay main
const footer = document.querySelector('footer');//lay footer
const navContainer = document.querySelector('.nav-container');//lay thanh fixed
const userInfo = document.querySelector('.user-info');// lay thong tin user-info

function showAndClose(parentClass) {
    const parentElement = document.querySelector('.' + parentClass);
    if(parentElement.style.display == 'none' ||  parentElement.style.display == '') {
        
        parentElement.style.display = 'flex';
        
        parentElement.style.animation =  'slideIn 0.4s ease-in-out forwards';
       
    } else {
        parentElement.style.animation = 'slideOut 0.4s ease-in-out forwards';
        setTimeout(() => {parentElement.style.display = 'none';}, 400);
    }
    main.classList.toggle('blur');
    footer.classList.toggle('blur');
    
    userInfo.classList.toggle('blur');
    navContainer.classList.toggle('blur');
}

moreElement.addEventListener('click', () => {
    showAndClose('more-content');
    
});

document.addEventListener('click', (event) => {
    if (!moreContent.contains(event.target) && event.target !== moreElement) {
        if(moreContent.style.display == 'flex') {
            showAndClose('more-content');
        }
       
    }
});