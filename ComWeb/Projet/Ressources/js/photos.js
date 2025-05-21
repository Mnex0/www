'use strict'

let currentPhoto = 0;

async function requestThumbnails() {
  const photos = await fetch('php/request.php/photos/');
  if (photos.ok)
    displayThumbnails(await photos.json());
  else
    displayError(photos.status);
}

function displayThumbnails(ThumbnailsElement) {
  for (let i = 0; i < Object.keys(ThumbnailsElement).length; ++i) {
    document.getElementById('photo').innerHTML += `
      <div class="col-xs-2 col-md-2">
      <img id="${i + 1}" id="photo-small" photoid="${i + 1}" src="${ThumbnailsElement[i]["small"]}"
      class="img-thumbnail" onclick="displayPhoto(this.id)">
      </div>
      `;
  }
}

async function displayPhoto(id) {
  currentPhoto = id;
  let response = await fetch('php/request.php/photos/' + id);
  if (response.ok)
    displayThumbnail(await response.json());
  else
    displayError(response.status);
}

async function displayThumbnail(photo) {
  if (photo) {
    document.getElementById("largephoto").innerHTML = `
        <h4>${photo["title"]}</h4>
        <img id="largethumbnail" id="photo-large" src="${photo["large"]}">
        `;
    document.getElementById("comments-add").style.display = 'block';
    document.getElementById("comments").style.display = 'block';
    document.getElementById("menu").style.display = 'block';
    document.getElementById("comment-title").innerHTML = `
      <h3>
      All comments
      </h3>
      `;
    displayComments(photo["id"]);
  }
}

function displayError(error) {
  let messages =
  {
    400: 'Bad Request',
    401: 'Unauthorized',
    403: 'Forbidden',
    404: 'Not Found',
    500: 'Internal Server Error',
    503: 'Service Unavailable'
  }
  document.getElementById('errors').style.display = 'block'; //Pour afficher le block invisible
  document.getElementById('errors').innerHTML = '<i class="fa-solid fa-circle-xmark"></i>' + " Error " + error.status + " : " + messages[error.status];
}


// Display all comments.
document.getElementById('all-button').addEventListener('click', () => {
  currentTitle = 'All comments';
  displayComments(currentPhoto);
  //ajaxRequest('GET', 'php/request.php/comments/', displayComments);
});

// Display my comments.
document.getElementById('my-button').addEventListener('click', () => {
  currentTitle = 'My comments';
  displayComments(currentPhoto, login);
  //ajaxRequest('GET', 'php/request.php/comments/?login=' + login, displayComments);
});


// Add comment.
document.querySelector('.btn-outline-success').addEventListener('click', () => {
  let value = document.getElementById('new-comment').value;
  console.log(value);
  if (value.trim() === '') {
    alert('Attention : le champ ne peut pas être vide !');
  }
  else {
    if (addComment(currentPhoto, login, value)) {
      let filtre = null;
      if (currentTitle == 'My comments') {
        filtre = login;
      }
      document.getElementById('new-comment').value = '';
    }
    else
      console.log("Problème");
  }
});

/*
//------------------------------------------------------------------------------
//--- ModifyComments -------------------------------------------------------------
//------------------------------------------------------------------------------
// Modify comments.
function modifyComments() {
  const modifyButtons = document.querySelectorAll('.mod');
  modifyButtons.forEach(e => e.addEventListener('click', (event) => {
    let value = event.target.closest('.mod').getAttribute('value');
    ajaxRequest('PUT', 'php/request.php/comments/' + value, () => {
      ajaxRequest('GET', 'php/request.php/comments/', displayComments);
    }, 'login=' + login + '&text=' + prompt('Nouveau comment :'));
  }));
}

//------------------------------------------------------------------------------
//--- DeleteComments -------------------------------------------------------------
//------------------------------------------------------------------------------
// Delete comments.
function deleteComments() {
  const deleteButtons = document.querySelectorAll('.del');
  deleteButtons.forEach(e => e.addEventListener('click', (event) => {
    let value = event.target.closest('.del').getAttribute('value');
    ajaxRequest('DELETE', 'php/request.php/comments/' + value + '?login=' +
      login, () => {
        ajaxRequest('GET', 'php/request.php/comments/', displayComments);
      });
  }));
}

//------------------------------------------------------------------------------
//--- displayComments ------------------------------------------------------------
//------------------------------------------------------------------------------
// Display comments.
// \param comments The comments data received via the Ajax request.
function displayComments(comments) {
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
*/

function main() {
  requestThumbnails();
}
main();