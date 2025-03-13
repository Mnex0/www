'use strict'

async function requestThumbnails()
{
    const photos = await fetch('php/request.php/photos/');
    if (photos.ok)
        displayThumbnails(await photos.json());
    else
        displayError(photos.status);
}

function displayThumbnails(ThumbnailsElement)
{
    for (let i = 0; i < Object.keys(ThumbnailsElement).length; ++i)
    {
        document.getElementById('photo').innerHTML += `
        <div class="col-xs-2 col-md-2">
        <img id="${i+1}" id="photo-small" photoid="${i+1}" src="${ThumbnailsElement[i]["small"]}"
        class="img-thumbnail" onclick="displayPhoto(this.id)">
        </div>
        `;
    }
}

async function displayPhoto(id)
{
    let response = await fetch('php/request.php/photos/'+id);
    if (response.ok)
        displayThumbnail(await response.json());
    else
        displayError(response.status);
}

async function displayThumbnail(photo)
{
    if (photo)
    {
        document.getElementById("largephoto").innerHTML =`
        <h4>${photo["title"]}</h4>
        <img id="largethumbnail" id="photo-large" src="${photo["large"]}">
        `;
    }
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