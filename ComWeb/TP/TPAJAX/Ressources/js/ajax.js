'use strict';

async function requestTimestamp() //Bien utiliser 'async' pour utiliser 'await'
{
    const date = await fetch('php/timestamp.php');
    if (date.ok)
        displayTimestamp(await date.text()); //Faire un return est risqué car éffectué avant d'avoir reçu la réponse. Il ne s'affiche alors qu'une promesse
    else
        console.log('HTTP error:' + date.status);
}

function displayTimestamp(timestampElement)
{
    document.getElementById('timestamp').innerHTML = timestampElement;
}

async function requestError()
{
    const error = await fetch('php/errors.php');
    if (!error.ok)
        displayError(error);
}

function displayError(error)
{
    let messages =
    {
        400 : 'Bad Request',
        401 : 'Unauthorized',
        403 : 'Forbidden',
        404 : 'Not Found',
        500 : 'Internal Server Error',
        503 : 'Service Unavailable'
    }
    document.getElementById('errors').style.display = 'block'; //Pour afficher le block invisible
    document.getElementById('errors').innerHTML = '<i class="fa-solid fa-circle-xmark"></i>' + " Error " + error.status + " : " + messages[error.status];
}

function main()
{
    setInterval(requestTimestamp, 1000); //Fait un appel régulier de cette fonction toutes les 1000 ms
    setInterval(requestError, 5000);
}

main();

// .innerHTML -> Pour écrire dans la balise choisie
// .then() -> Pour effectuer qqc après le prog
// settimeout(funcsansparenthèses, tempsenMS) -> Pour faire un appel de fonction dans à un temps précis