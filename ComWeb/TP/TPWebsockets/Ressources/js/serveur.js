// Serveur WebSocket Node.js
const WebSocket = require('ws');

// Création du serveur WebSocket sur le port 8080
const wss = new WebSocket.Server({ port: 8080 });

console.log('Serveur WebSocket démarré sur le port 8080');

// Tableau pour stocker les connexions clients
const clients = new Set();

// Événement lors d'une nouvelle connexion
wss.on('connection', (ws) => {
    // Ajouter le client à la liste
    clients.add(ws);
    console.log(`Nouveau client connecté. Total: ${clients.size}`);

    // Envoyer un message de bienvenue
    ws.send(JSON.stringify({
        username: 'Serveur',
        text: 'Bienvenue sur le chat!'
    }));

    // Événement lors de la réception d'un message
    ws.on('message', (message) => {
        console.log(`Message reçu: ${message}`);

        try {
            // Diffuser le message à tous les clients connectés
            clients.forEach((client) => {
                if (client !== ws && client.readyState === WebSocket.OPEN) {
                    client.send(message.toString());
                }
            });
        } catch (error) {
            console.error('Erreur lors de la diffusion du message:', error);
        }
    });

    // Événement lors de la fermeture d'une connexion
    ws.on('close', () => {
        // Retirer le client de la liste
        clients.delete(ws);
        console.log(`Client déconnecté. Total: ${clients.size}`);
    });

    // Événement en cas d'erreur
    ws.on('error', (error) => {
        console.error('Erreur de connexion:', error);
        clients.delete(ws);
    });
});