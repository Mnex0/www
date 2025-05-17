'use strict'

let websocket;
let login = 'nexo';

function createWebSocket() {
    websocket = new WebSocket('ws://localhost:8080');
    websocket.onopen = () => {
        console.log('Connexion établie.');
    };
    websocket.onmessage = (event) => {
        addMess(event.data);
    };
    websocket.onclose = () => {
        console.log('Communication terminée.');
        setTimeout(createWebSocket, 5000);
    };
}

function addMess(message) {
    const textArea = document.getElementById("chat-room");
    textArea.value += message + "\n";
    textArea.scrollTop = textArea.scrollHeight;
}

function sendMess(event) {
    const messageInput = document.getElementById('chat-message');
    const mess = messageInput.value.trim();
    console.log(mess);

    if (mess && /*websocket &&*/ websocket.readyState === WebSocket.OPEN) {
        // Envoyer le message au format: login: message
        const fullMessage = login + " : " + mess;
        websocket.send(fullMessage);

        // Vider le champ de saisie
        messageInput.value = '';
    }
}
document.addEventListener('DOMContentLoaded', function () {
    // Associer la fonction d'envoi à la soumission du formulaire
    const chatForm = document.getElementById('chat-send');
    chatForm.addEventListener('submit', sendMess);

    // Créer la connexion WebSocket au chargement de la page
    createWebSocket();
});