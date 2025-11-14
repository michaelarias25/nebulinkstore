document.addEventListener('DOMContentLoaded', () => {

    /**
     * Menú Responsive (Hamburguesa)
     * Muestra u oculta la navegación en pantallas pequeñas.
     */
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('nav-visible');
        });
    }

    /**
     * Chatbot con Lógica de Seguimiento
     */
    const chatToggle = document.getElementById('chat-toggle');
    const chatContainer = document.getElementById('chat-container');
    const chatClose = document.getElementById('chat-close');
    const chatSend = document.getElementById('chat-send');
    const chatInput = document.getElementById('chat-input');
    const chatBody = document.getElementById('chat-body');

    // Muestra las opciones de bienvenida
    function showWelcomeOptions() {
        chatBody.innerHTML = `
            <div class="chat-message bot">
                <p>¡Hola! 👋 Soy tu asistente virtual. ¿Cómo puedo ayudarte?</p>
            </div>
            <div class="chat-options">
                <button class="chat-option-btn" data-message="Información de envíos">🚚 Envíos</button>
                <button class="chat-option-btn" data-message="Métodos de pago">💳 Métodos de pago</button>
                <button class="chat-option-btn" data-message="Garantía de productos">🛡️ Garantía</button>
            </div>
        `;
        // Asigna eventos a los botones de opción
        document.querySelectorAll('.chat-option-btn').forEach(button => {
            button.addEventListener('click', () => {
                handleUserMessage(button.getAttribute('data-message'));
            });
        });
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Pregunta al usuario si necesita algo más
    function askForAnotherQuestion() {
        // Espera 2 segundos antes de preguntar
        setTimeout(() => {
            addMessage("¿Puedo ayudarte con algo más?", 'bot');
            chatBody.innerHTML += `
                <div class="chat-options">
                    <button class="chat-option-btn" id="chat-yes">Sí, por favor</button>
                    <button class="chat-option-btn" id="chat-no">No, gracias</button>
                </div>
            `;
            // Asigna eventos a los botones de Sí/No
            document.getElementById('chat-yes').addEventListener('click', showWelcomeOptions);
            document.getElementById('chat-no').addEventListener('click', () => {
                addMessage("De acuerdo. ¡Que tengas un excelente día! 👋", 'bot');
                // Opcional: Oculta los botones de Sí/No después de la selección
                const options = chatBody.querySelector('.chat-options');
                if(options) options.remove();
            });
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 2000);
    }

    // Abrir y cerrar el chat
    chatToggle.addEventListener('click', () => {
        chatContainer.style.display = 'flex';
        chatToggle.style.display = 'none';
        showWelcomeOptions();
    });

    chatClose.addEventListener('click', () => {
        chatContainer.style.display = 'none';
        chatToggle.style.display = 'flex';
    });
    
    // Procesa el mensaje del usuario (ya sea por clic, opción o input) y obtiene respuesta
    function handleUserMessage(text) {
        const userText = text.trim();
        if (userText === '') return;

        addMessage(userText, 'user');
        chatInput.value = '';
        setTimeout(() => getBotResponse(userText.toLowerCase()), 1000);
    }

    // Añade un mensaje al cuerpo del chat
    function addMessage(text, sender) {
        // Elimina las opciones anteriores antes de añadir un nuevo mensaje
        const options = chatBody.querySelector('.chat-options');
        if (options) {
            options.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}`;
        messageDiv.innerHTML = `<p>${text}</p>`;
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    // Lógica de envío de mensajes
    chatSend.addEventListener('click', () => handleUserMessage(chatInput.value));
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleUserMessage(chatInput.value);
        }
    });

    // Lógica de respuestas del bot
    function getBotResponse(userText) {
        let botResponse = "No entendí tu pregunta. Por favor, elige una de las opciones.";
        if (userText.includes('envío')) {
            botResponse = "Hacemos envíos a todo el país. Tarda de 2 a 5 días hábiles.";
        } else if (userText.includes('pago')) {
            botResponse = "Aceptamos tarjetas de crédito/débito, PSE y pagos en efectivo vía Efecty.";
        } else if (userText.includes('garantía')) {
            botResponse = "Todos nuestros productos tienen 12 meses de garantía directamente con el fabricante.";
        }
        addMessage(botResponse, 'bot');
        
        // Llama a la función para preguntar si hay más dudas
        askForAnotherQuestion();
    }
});