<?php
header('Content-Type: text/plain');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');

// Récupérer la méthode de la requête
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Vérifier si PATH_INFO est défini
if (!isset($_SERVER['PATH_INFO'])) {
    http_response_code(400);
    echo 'Erreur : Ressource non spécifiée.';
    exit;
}

// Récupérer la ressource demandée en supprimant le premier slash
$pathInfo = ltrim($_SERVER['PATH_INFO'], '/');

// Découper la chaîne en segments
$pathParts = explode('/', $pathInfo);

// Vérifier si la ressource demandée est "photos"
if ($pathParts[0] === 'photos') {
    // Créer la chaîne de test
    $response = $requestMethod . ' photos';
    // Envoyer la réponse au client
    echo $response;
    exit;
} else {
    http_response_code(404);
    echo 'Erreur : Ressource non trouvée.';
    exit;
}