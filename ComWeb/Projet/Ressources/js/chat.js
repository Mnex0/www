'use strict'

let websocket;

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
        const fullMessage = login + " : " + mess;
        websocket.send(fullMessage);
        messageInput.value = '';
    }
}
document.addEventListener('DOMContentLoaded', function () {
    // Binding the submission of the form
    const chatForm = document.getElementById('chat-send');
    chatForm.addEventListener('submit', sendMess);
    // Create WebSocket connection when loading the page
    createWebSocket();
});