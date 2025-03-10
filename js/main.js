const moreElement = document.querySelector('.more');//lay element more
const main = document.querySelector('main');
const footer = document.querySelector('footer');
const navContainer = document.querySelector('.nav-container');
const userInfo = document.querySelector('.user-info');

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