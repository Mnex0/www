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
        document.getElementById("comments-add").style.display = "block";
        document.getElementById("comments").style.display = "block";
        let response = await fetch('php/request.php/comments/'+photo["id"]);
        if (response.ok)
        {
            const comments = await response.json();
            document.getElementById("comments").innerHTML = '';
            for (let i = 0; i < comments.length; ++i)
            {
                document.getElementById("comments").innerHTML += `
                <div class="row">
                    <div class="col-1" style="color: blue; padding: 0;">${comments[i]["userLogin"]}</div>
                    <div class="col-7" style="text-align: left;">${comments[i]["comment"]}</div>
                </div>
                `;
                console.log(comments[i]["comment"]);
            }
        }
        else
        {
            document.getElementById("comments").innerHTML += `<h4>Pas de commentaire disponible</h4>`;
        }
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


let login = 'cir2';
let currentTitle = 'Comments';

// Display all comments.
ajaxRequest('GET', 'php/request.php/comments/', displayComments);
document.getElementById('all-button').addEventListener('click', () => 
{
  currentTitle = 'Comments';
  ajaxRequest('GET', 'php/request.php/comments/', displayComments);
});

// Display my comments.
document.getElementById('my-button').addEventListener('click', () => 
{
  currentTitle = 'My comments';
  ajaxRequest('GET', 'php/request.php/comments/?login=' + login, displayComments);
});

// Add comment.
document.getElementById('comments-add').addEventListener('submit', (event) => 
{
  event.preventDefault();
  let value = document.getElementById('comment').value;
  ajaxRequest('POST', 'php/request.php/comments/', () =>
  {
    ajaxRequest('GET', 'php/request.php/comments/', displayComments);
  }, 'login=' + login + '&text=' + value);
  document.getElementById('comment').value = '';
});

//------------------------------------------------------------------------------
//--- ModifyComments -------------------------------------------------------------
//------------------------------------------------------------------------------
// Modify comments.
function modifyComments()
{
  const modifyButtons = document.querySelectorAll('.mod');
  modifyButtons.forEach(e => e.addEventListener('click', (event) =>
  {
    let value = event.target.closest('.mod').getAttribute('value');
    ajaxRequest('PUT', 'php/request.php/comments/' + value, () =>
    {
      ajaxRequest('GET', 'php/request.php/comments/', displayComments);
    }, 'login=' + login + '&text=' + prompt('Nouveau comment :'));
  }));
}

//------------------------------------------------------------------------------
//--- DeleteComments -------------------------------------------------------------
//------------------------------------------------------------------------------
// Delete comments.
function deleteComments()
{
  const deleteButtons = document.querySelectorAll('.del');
  deleteButtons.forEach(e => e.addEventListener('click', (event) =>
  {
    let value = event.target.closest('.del').getAttribute('value');
    ajaxRequest('DELETE', 'php/request.php/comments/' + value + '?login=' +
      login, () =>
    {
      ajaxRequest('GET', 'php/request.php/comments/', displayComments);
    });
  }));
}

//------------------------------------------------------------------------------
//--- displayComments ------------------------------------------------------------
//------------------------------------------------------------------------------
// Display comments.
// \param comments The comments data received via the Ajax request.
function displayComments(comments)
{
  // Fill comments.
  document.getElementById('comments').innerHTML = '<h3>' + currentTitle + '</h3>';
  for (let comment of comments)
    document.getElementById('comments').innerHTML += '<div class="card">' +
      '<div class="card-body">' + comment.login + ' : ' + comment.text +
      '<div class="btn-group float-end" role="group">' +
      '<button type="button" class="btn btn-light float-end mod"' +
      ' value="' + comment.id + '"><i class="fa fa-edit"></i></button>' +
      '<button type="button" class="btn btn-light float-end del"' +
      ' value="' + comment.id + '"><i class="fa fa-trash"></i></button>' +
      '<div></div></div>';
  modifyComments();
  deleteComments();
}


function main()
{
    requestThumbnails();
}
main();