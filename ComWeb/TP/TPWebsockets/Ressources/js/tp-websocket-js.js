// Fichier: js/chat.js
// Script pour gérer les communications WebSocket du chat

// Variables globales
let websocket; // Canal de communication
const login = "MonPseudo"; // Votre pseudo ici

// Fonction pour créer la connexion WebSocket
function createWebSocket() {
    // Création du socket WebSocket
    // Remplacez l'adresse et le port par ceux de votre serveur
    websocket = new WebSocket('ws://localhost:8080');
    
    // Événement déclenché lorsque la connexion est établie
    websocket.onopen = function(event) {
        addSystemMessage("Connexion établie avec le serveur");
    };
    
    // Événement déclenché à la réception d'un message
    websocket.onmessage = function(event) {
        const textArea = document.getElementById('chat-room');
        
        textArea.value += event.data + "\n";
        
        // Faire défiler automatiquement vers le bas
        textArea.scrollTop = textArea.scrollHeight;
    };
    
    // Événement déclenché lorsque la connexion est fermée
    websocket.onclose = function(event) {
        addSystemMessage("Connexion fermée avec le serveur");
        
        // Tentative de reconnexion après 3 secondes
        setTimeout(createWebSocket, 3000);
    };
    
    // Événement déclenché en cas d'erreur
    websocket.onerror = function(error) {
        addSystemMessage("Erreur de connexion avec le serveur");
        console.error('Erreur WebSocket:', error);
    };
}

// Fonction pour ajouter un message système
function addSystemMessage(message) {
    const textArea = document.getElementById('chat-room');
    textArea.value += "[Système] " + message + "\n";
    textArea.scrollTop = textArea.scrollHeight;
}

// Fonction pour envoyer un message
function sendMessage(event) {
    // Stopper la propagation normale du formulaire
    event.preventDefault();
    
    // Récupérer le message à envoyer
    const messageInput = document.getElementById('chat-message');
    const message = messageInput.value.trim();
    
    // Vérifier que le message n'est pas vide et que la connexion est ouverte
    if (message && websocket && websocket.readyState === WebSocket.OPEN) {
        // Envoyer le message au format: login: message
        const fullMessage = login + ": " + message;
        websocket.send(fullMessage);
        
        // Vider le champ de saisie
        messageInput.value = '';
    }
}

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Associer la fonction d'envoi à la soumission du formulaire
    const chatForm = document.getElementById('chat-send');
    chatForm.addEventListener('submit', sendMessage);
    
    // Créer la connexion WebSocket au chargement de la page
    createWebSocket();
});
