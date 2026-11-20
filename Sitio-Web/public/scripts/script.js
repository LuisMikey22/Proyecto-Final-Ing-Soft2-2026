const searchBarCont = document.getElementById("search-bar-container");
const searchBarForm = document.getElementById("search-bar-form");

const searchBarButton = document.getElementById("search-bar-button");
const searchBarInput = document.getElementById("search-bar-input");
const closeButton = document.getElementById("close-button");

const searchButton = document.getElementById("search-button");

const menuToggle = document.getElementById("menu-toggle");
const menuButtonImage = document.getElementById("menu-button-image");

//por cada contenedor de tarjetas
document.querySelectorAll('.item-container').forEach(container => {
    let currentIndex = 0;

    const carousel = container.parentElement;
    const carouselCards = container.querySelectorAll('.item');
    const totalCarouselCards = carouselCards.length;

    const nextElButton = carousel.querySelector('.next-element-button');
    const previousElButton = carousel.querySelector('.previous-element-button');

    if(nextElButton) { //si existe botón en la página
        nextElButton.addEventListener('click', () => {
        currentIndex++;
        if (currentIndex >= totalCarouselCards) {
            currentIndex = 0;
        }

        updateTransform(carouselCards, currentIndex);
        });
    }

    if(previousElButton) { //si existe botón en la página
        previousElButton.addEventListener('click', () => {
            currentIndex--;

            if (currentIndex < 0) {
                currentIndex = totalCarouselCards - 1;
            }

            updateTransform(carouselCards, currentIndex);
        });
    }
});

function updateTransform(cards, currentIndex) {
    cards.forEach((card) => {
        const offset = -currentIndex * card.offsetWidth;
        card.style.transform = `translateX(${offset}px)`;
    });
}


searchButton.addEventListener('click', function() {
    searchBarCont.classList.replace("search-bar-container-hidden", "search-bar-container-visible");
    menuToggle.checked = false;
    menuButtonImage.src = `${ASSETS_PATH}/images/menuIcon.svg`;

    searchBarInput.focus();
});


searchBarButton.addEventListener('click', function(event) {
    event.preventDefault(); // Evita el envío normal
    redirect();
});

closeButton.addEventListener('click', function() {
    searchBarCont.classList.replace("search-bar-container-visible", "search-bar-container-hidden");
});

menuToggle.addEventListener('change', function() {
    if(this.checked){
        menuButtonImage.src = `${ASSETS_PATH}/images/closeIconDark.svg`;
    }else {
        menuButtonImage.src = `${ASSETS_PATH}/images/menuIcon.svg`;
    }
});

searchBarForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío normal
    redirect();
});


searchBarInput.addEventListener('keydown', function(event) {
    // Corrección: el parámetro se llama event (antes usaba "e", que no existía
    // y provocaba un ReferenceError al teclear en la barra de búsqueda)
    if(event.key === 'Enter') {
        event.preventDefault(); // Evita el envío normal
        redirect();
    }
});

function redirect() {
    const q = searchBarInput.value.trim();

    if(q!=="") {
        // Redirige con la URL deseada
        window.location.href = `${BASE_PATH}/search/${encodeURIComponent(q)}`;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById('chatbot-toggle-btn');
    const closeBtn = document.getElementById('chatbot-close-btn');
    const chatbotWindow = document.getElementById('chatbot-window');

    // El chatbot solo existe en la página de inicio: en el resto de las
    // páginas estos elementos no existen y no debe tronar el script.
    if (!toggleBtn || !closeBtn || !chatbotWindow) {
        return;
    }

    // Función para alternar la visibilidad
    function toggleChat() {
        chatbotWindow.classList.toggle('hidden');
    }

    // Eventos de clic
    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);
});

/*
window.addEventListener('load', () => {
    new Glider(document.querySelector('.new-art-item-container'), {
        slidesToScroll: 5,
        slidesToShow: 10,
        draggable: false,
        dots: '.carousel-indicator',
        arrows: {
            prev: '.previous-element-button',
            next: '.next-element-button'
        },
        responsive: [
            {
            // screens greater than >= 775px
            breakpoint: 480,
                settings: {
                    // Set to `auto` and provide item width to adjust to viewport
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },{
            // screens greater than >= 1024px
            breakpoint: 1024,
                settings: {
                    slidesToShow: 10,
                    slidesToScroll: 5,
                }
            }
        ]  
    });
});
*/