'use strict';

let login = 'cir2';
let currentTitle = 'All comments';

async function displayComments(photoId, user = null) {
    document.getElementById("comments-add").style.display = "block";
    document.getElementById("comments").style.display = "block";
    document.getElementById("comment-title").innerHTML = `<h3>${currentTitle}</h3>`;


    let response;
    if (user) {
        response = await fetch('php/request.php/comments/' + photoId + '/' + user);
    }
    else {
        response = await fetch('php/request.php/comments/' + photoId);
    }
    if (response.ok) {
        const text = await response.text();  // lire le corps brut
        if (text.trim() === "") {
            document.getElementById("comments").innerHTML = `<h5>Pas de commentaire disponible</h5>`;
            return;
        }

        const comments = JSON.parse(text);  // maintenant on sait que c'est du JSON valide
        document.getElementById("comment-title").innerHTML = `<h3>${currentTitle}</h3>`;
        document.getElementById("comments").innerHTML = '';

        for (let i = 0; i < comments.length; ++i) {
            document.getElementById("comments").innerHTML += `
                <div class="row">
                    <div class="col-1" style="color: blue; padding: 0;">${comments[i]["userLogin"]}</div>
                    <div class="col-7" style="text-align: left;">${comments[i]["comment"]}</div>
                </div>
            `;
        }
    } else {
        document.getElementById("comments").innerHTML = `<h5>Pas de commentaire disponible</h5>`;
    }

}

async function addComment(photoId, login, text) {
    

    const response = await fetch('php/request.php/' + photoId + "/addcomment", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user: login,
            text: text
        })
    });
    const result = await response.json();
    return result;
}

async function modifyComment(photoId, login, text, commentId) {

}

async function deleteComment(photoId, login, commentId) {

}
