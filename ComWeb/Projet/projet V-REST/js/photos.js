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
document.getElementById('all-button').addEventListener('click', async () => {
  currentTitle = 'All comments';
  await displayComments(currentPhoto);
});

// Display my comments.
document.getElementById('my-button').addEventListener('click', async () => {
  currentTitle = 'My comments';
  await displayComments(currentPhoto, login);
});

// Add comment button
document.querySelector('.btn-outline-success').addEventListener('click', async (event) => {
  event.preventDefault();
  let value = document.getElementById('new-comment').value;
  let filter = null;
  if (value.trim() === '') {
    alert('Attention : le champ ne peut pas être vide !');
  }
  else {
    if (addComment(currentPhoto, login, value)) {
      if (currentTitle == 'My comments') {
          filter = login;
      }
      document.getElementById('new-comment').value = '';
    }
    else
      console.log("!Problème!");
  }
  await displayComments(currentPhoto, filter);
});

//Add comment input
document.getElementById('new-comment').addEventListener('keydown', async (event) => {
  if (event.key === 'Enter') {
    event.preventDefault(); // Empêche le comportement par défaut du form
    
    // Déclencher le même code que le bouton
    let value = document.getElementById('new-comment').value;
    let filter = null;
    if (value.trim() === '') {
      alert('Attention : le champ ne peut pas être vide !');
    } else {
      if (addComment(currentPhoto, login, value)) {
        if (currentTitle == 'My comments') {
          filter = login;
        }
        document.getElementById('new-comment').value = '';
      } else {
        console.log("!Problème!");
      }
      await displayComments(currentPhoto, filter);
    }
  }
});

//Delete comment : Because the buttons are created dynamically, this code must comes after
function attachDeleteListeners() {
  //console.log("Attaching delete buttons");
  const deleteButtons = document.querySelectorAll('[id^="del-"]');
  deleteButtons.forEach(button => {
    button.addEventListener('click', async (event) => {
      event.preventDefault(); // Just in case
      let filter = null;
      const idCom = parseInt(button.value);
      if (isNaN(idCom)) {
        console.error('ID de commentaire invalide:', button.value);
        return;
      }

      try {
        const response = await deleteComment(login, currentPhoto, idCom);
        if (response.ok)
          filter = login;
        await displayComments(currentPhoto, filter);
      } catch (error) {
        console.error('Erreur lors de la suppression:', error);
      }
    });
  });
  //console.log("Nombre de boutons trouvés:", document.querySelectorAll('[id^="del-"]').length);
}

//Old delete
/*
const deleteButtons = document.querySelectorAll('[id^="del-"]');
deleteButtons.forEach(button => {
  button.addEventListener('click', async (event) => {
    event.preventDefault(); // Just in case
    console.log("Clické");
    let filter = null;
    const idCom = parseInt(button.value);
    if (isNaN(idCom)) {
      console.error('ID de commentaire invalide:', button.value);
      return;
    }

    try {
      const response = await deleteComment(login, currentPhoto, idCom);
      if (response.ok)
        filter = currentTitle;
      await displayComments(currentPhoto, filter);
    } catch (error) {
      console.error('Erreur lors de la suppression:', error);
    }
  });
});
*/

//Modify comment
function attachModifyListeners() {
  //console.log("Attaching modify buttons");
  const modifyButtons = document.querySelectorAll('[id^="mod-"]');
  modifyButtons.forEach(button => {
    button.addEventListener('click', async (event) => {
      event.preventDefault(); // Just in case
      let filter = null;
      let text = prompt("Entrez le nouveau commentaire à écrire : ", "default");
      const idCom = parseInt(button.value);
      if (isNaN(idCom)) {
        console.error('ID de commentaire invalide:', button.value);
        return;
      }

      try {
        const response = await modifyComment(currentPhoto, idCom, login, text);
        if (response.ok)
          filter = login;
        await displayComments(currentPhoto, filter);
      } catch (error) {
        console.error('Erreur lors de la modification:', error);
      }
    });
  });
  //console.log("Nombre de boutons trouvés:", document.querySelectorAll('[id^="mod-"]').length);
}

function main() {
  requestThumbnails();
}

main();