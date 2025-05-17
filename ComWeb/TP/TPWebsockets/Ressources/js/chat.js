'use strict'

let websocket;
let login = 'nexo';

function createWebSocket(websocket, login) {
    websocket = new WebSocket('ws://localhost:8080');
    websocket.onopen = (event) => {
        console.log('Connexionétablie.');
        websocket.send('Mon premier message.');
    }
    websocket.onmessage = (event) => {
        console.log('Message reçu : ' + event.data);
        textArea.value += "chat";
        textArea.scrollTop = textArea.scrollHeight;
    }
    websocket.onclose = (event) => {
        console.log('Communication terminée.');
    }
}

createWebSocket(websocket, login);