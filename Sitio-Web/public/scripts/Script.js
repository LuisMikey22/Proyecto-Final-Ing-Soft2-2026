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
    const visibleCards = 5; //muestra cantidad de tarjetas simultáneas

    const carousel = container.parentElement;
    const cards = Array.from(container.querySelectorAll('.item'));
    const total = cards.length;

    const nextBtn = carousel.querySelector('.next-element-button');
    const prevBtn = carousel.querySelector('.previous-element-button');

    //calcular máximo índice desplazable (cuando queda menor a 5 ya no avanza)
    const maxIndex = Math.max(0, total - visibleCards);

    //estado inicial: desactivar botones si no hay suficiente contenido
    updateButtons();

    //preguntar si existe el botón para evitar null 
    nextBtn?.addEventListener('click', () => {
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateTransform(cards, currentIndex);
            updateButtons();
        }
    });

    //preguntar si existe el botón para evitar null 
    prevBtn?.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateTransform(cards, currentIndex);
            updateButtons();
        }
    });

    // ocultar o desactivar botones según estado
    function updateButtons() {
        if (!nextBtn || !prevBtn) return;

        if (maxIndex === 0) {
            // no hay más desplazamiento
            nextBtn.disabled = true;
            prevBtn.disabled = true;
            nextBtn.style.opacity = '0.5';
            prevBtn.style.opacity = '0.5';

            return;
        }

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === maxIndex;
        prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
        nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
    }
});

function updateTransform(cards, currentIndex) {
    // mover cada tarjeta independientemente
    cards.forEach(card => {
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
    let pressedKey = e.key;
    e.preventDefault();

    if(pressedKey === 'Enter') {
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

    // Función para alternar la visibilidad
    function toggleChat() {
        chatbotWindow.classList.toggle('hidden');
    }

    // Eventos de clic
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleChat);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', toggleChat);
    }
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