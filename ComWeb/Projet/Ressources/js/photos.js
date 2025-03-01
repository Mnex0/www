'use strict'

async function requestThumbnails()
{
    const photos = await fetch('php/request.php/photos/');
    if (photos.ok)
        displayThumbnails(await photos.text());
    else
        console.log('HTTP error:' + photos.status);
}

function displayThumbnails(ThumbnailsElement)
{
    document.getElementById('photo').innerHTML = ThumbnailsElement;
}

async function requestError()
{
    const error = await fetch('php/request.php/photos/');
    if (error.ok)
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
    requestThumbnails();
}

main();