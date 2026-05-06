document.addEventListener('DOMContentLoaded', () => {
    const regForm = document.querySelector('form[action="register.php"]');
    
    if (regForm) {
        regForm.addEventListener('submit', (e) => {
            const passInput = document.querySelector('input[name="password"]');
            const password = passInput.value;
            
            // Lógica: 8 caracteres + 2 números
            const hasMinLength = password.length >= 8;
            const hasTwoNumbers = (password.match(/\d/g) || []).length >= 2;

            if (!hasMinLength || !hasTwoNumbers) {
                e.preventDefault(); // Detiene el envío
                alert("🎮 ¡ERROR! Tu contraseña debe tener al menos 8 caracteres y 2 números.");
            }
        });
    }
});

// Funciones para la integración de IA 
function detectYearIA() {
    const title = document.getElementById('game_title').value;
    if(!title) return alert("Escribe el nombre del juego primero.");
    alert("🤖 Buscando año de lanzamiento para: " + title + "...");
    // Aquí el Alumno 3 conectará con la API de Gemini
}

function getDescIA() {
    const title = document.getElementById('game_title').value;
    if(!title) return alert("Escribe el nombre del juego primero.");
    alert("✨ Generando descripción épica con IA...");
}

function getImageIA() {
    alert("🔍 Buscando portadas en alta resolución...");
}