let idConversacion = null;
function toggleChat() {
    let body = document.getElementById("chat-body");
    body.style.display = (body.style.display === "flex") ? "none" : "flex";
}

function cargarContactos() {
    fetch("../php_action/DAO/listUsers.php")
    .then(res => res.json())
    .then(usuarios => {
            const select = document.getElementById("select-contact");
            select.innerHTML = '<option value="">— Selecciona contacto —</option>';
            usuarios.forEach(u => {
                const option = document.createElement("option");
                option.value = u.id;      // id del usuario
                option.textContent = u.username; // nombre del usuario
                select.appendChild(option);
            });
        })
        .catch(err => console.error("Error cargando usuarios:", err));
}

function abrirChat(idUsuarioDestino) {
    fetch("../php_action/DAO/GetConver.php?otro=" + idUsuarioDestino)
    .then(r => r.json())
    .then(data => {
            idConversacion = data.id_conversacion;
            cargarMensajes();
            setInterval(cargarMensajes, 1500);
            document.getElementById("chat-body").style.display = "flex";
        });
}

function cargarMensajes() {
    if (!idConversacion) return;

    fetch("../php_action/DAO/LoadConve.php?id=" + idConversacion)
    .then(r => r.json())
    .then(data => {
        let area = document.getElementById("chat-messages");
        area.innerHTML = "";

        data.forEach(m => {
                area.innerHTML += `<div class="msg ${m.mio ? 'me' : 'other'}">${m.texto}</div>`;
            });

        area.scrollTop = area.scrollHeight;
    });
}

function enviarMsg() {
    let texto = document.getElementById("chat-text").value;
    if (!texto.trim() || !idConversacion) return;
    let form = new FormData();
    form.append("id_conversacion", idConversacion);
    form.append("contenido", texto);
    console.log("Funciona2");
    fetch("../php_action/DAO/SetConver.php", { method: "POST", body: form })
    .then(() => {
        document.getElementById("chat-text").value = "";
        cargarMensajes();
    });
}

function iniciarChatSeleccion() {
    const select = document.getElementById("select-contact");
    const idUsuarioDestino = select.value;

    if (!idUsuarioDestino) {
        alert("Selecciona un contacto");
        return;
    }

    abrirChat(idUsuarioDestino);
}

// Cargar usuarios al iniciar
document.addEventListener("DOMContentLoaded", () => {
    cargarContactos();
    
    const select = document.getElementById("select-contact");
    const input = document.getElementById("chat-text");
    const btn = document.getElementById("btnEnviar");

    input.addEventListener("keydown", e => {
        if (e.key === "Enter") enviarMsg();
    });
    select.addEventListener("change", () => {
        const usuarioId = select.value;
        if (!usuarioId) return;

        fetch("../php_action/DAO/GetConver.php?otro=" + usuarioId)
            .then(r => r.json())
            .then(data => {
                idConversacion = data.id_conversacion;
                cargarMensajes();        
                console.log("Funciona");
                // Evitar duplicar intervalos

            });
    });
    btn.addEventListener("click", enviarMsg);
});