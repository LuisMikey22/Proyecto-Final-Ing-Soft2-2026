<section class="bg-base-100 rounded-3xl" style="max-width: 800px; margin: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">
    <div style="background-color: var(--color-dark-blue); padding: 1.5rem; text-align: center; color: white;">
        <h2 class="text-2xl font-bold">Asistente Virtual</h2>
        <p class="text-sm opacity-80">Respuestas rápidas a tus dudas frecuentes</p>
    </div>

    <div id="chat-window" style="padding: 2rem; height: 500px; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem; background-color: #f8fafc;">
        </div>

    <div id="chat-controls" style="padding: 1.5rem; background-color: white; border-top: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;">
        <p class="text-teal-700 w-full text-center text-sm mb-2" id="controls-prompt">Cargando opciones...</p>
        <div id="buttons-container" style="display: grid; width: 100%; gap: 0.5rem; justify-content: center;">
            </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const chatWindow = document.getElementById("chat-window");
    const buttonsContainer = document.getElementById("buttons-container");
    const controlsPrompt = document.getElementById("controls-prompt");

    let currentOptionId = null;

    // Función para agregar burbujas de texto
    function appendMessage(text, sender = 'bot') {
        const msgDiv = document.createElement("div");
        msgDiv.style.maxWidth = "80%";
        msgDiv.style.padding = "1rem";
        msgDiv.style.borderRadius = "1rem";
        msgDiv.style.fontSize = "1rem";
        msgDiv.style.lineHeight = "1.5";
        
        if (sender === 'bot') {
            msgDiv.style.backgroundColor = "white";
            msgDiv.style.color = "var(--color-dark-blue)";
            msgDiv.style.alignSelf = "flex-start";
            msgDiv.style.borderBottomLeftRadius = "0";
            msgDiv.style.boxShadow = "0 2px 4px rgba(0,0,0,0.05)";
        } else {
            msgDiv.style.backgroundColor = "var(--color-dark-blue)";
            msgDiv.style.color = "white";
            msgDiv.style.alignSelf = "flex-end";
            msgDiv.style.borderBottomRightRadius = "0";
        }
        
        msgDiv.innerText = text;
        chatWindow.appendChild(msgDiv);
        chatWindow.scrollTop = chatWindow.scrollHeight; // Auto-scroll
    }

    // Iniciar Chat
    function initChat() {
        chatWindow.innerHTML = "";
        appendMessage("¡Hola! Soy el asistente virtual de la tienda. Elige una de las siguientes opciones para poder ayudarte rápido:", 'bot');
        controlsPrompt.innerText = "Selecciona un tema:";
        
        fetch("<?= BASE_PATH ?>/api/chatbot/main")
            .then(res => res.json())
            .then(data => {
                buttonsContainer.innerHTML = "";
                data.forEach(opt => {
                    const btn = document.createElement("button");
                    btn.className = "action-button"; 
                    btn.style.width = "auto";
                    btn.innerText = opt.button_text;
                    btn.onclick = () => handleMainOptionClick(opt);
                    buttonsContainer.appendChild(btn);
                });
            })
            .catch(err => console.error("Error cargando opciones:", err));
    }

    // Al hacer clic en un tema principal
    function handleMainOptionClick(opt) {
        currentOptionId = opt.id_chatbot_option;
        appendMessage(opt.button_text, 'user');
        buttonsContainer.innerHTML = "<p class='text-teal-700'>Escribiendo...</p>";
        
        // Registrar clic (Métrica)
        fetch("<?= BASE_PATH ?>/api/chatbot/log", {
            method: 'POST',
            body: JSON.stringify({ id_option: currentOptionId })
        });

        setTimeout(() => {
            appendMessage(opt.bot_response, 'bot');
            
            // Buscar sub-opciones
            fetch(`<?= BASE_PATH ?>/api/chatbot/sub/${currentOptionId}`)
                .then(res => res.json())
                .then(data => {
                    buttonsContainer.innerHTML = "";
                    if(data.length > 0) {
                        controlsPrompt.innerText = data[0].bot_prompt;
                        data.forEach(subOpt => {
                            const btn = document.createElement("button");
                            btn.className = "edit-button"; 
                            btn.innerText = subOpt.button_label;
                            btn.onclick = () => handleSubOptionClick(subOpt);
                            buttonsContainer.appendChild(btn);
                        });
                    }
                    addRestartButton();
                });
        }, 800);
    }

    // Al hacer clic en una sub-opción
    function handleSubOptionClick(subOpt) {
        appendMessage(subOpt.button_label, 'user');
        buttonsContainer.innerHTML = "";
        controlsPrompt.innerText = "";
        
        // Registrar sub-clic
        fetch("<?= BASE_PATH ?>/api/chatbot/log", {
            method: 'POST',
            body: JSON.stringify({ id_option: currentOptionId, id_extra: subOpt.id_chatbot_extra_question })
        });

        setTimeout(() => {
            appendMessage(subOpt.final_response, 'bot');
            addRestartButton();
        }, 800);
    }

    function addRestartButton() {
        const btn = document.createElement("button");
        btn.className = "action-button";
        btn.style.width = "auto";
        btn.style.backgroundColor = "transparent";
        btn.style.color = "var(--color-dark-blue)";
        btn.style.border = "1px solid var(--color-dark-blue)";
        btn.innerText = "↻ Hacer otra consulta";
        btn.onclick = initChat;
        buttonsContainer.appendChild(btn);
    }

    // Arrancar al cargar la página
    initChat();
});
</script>