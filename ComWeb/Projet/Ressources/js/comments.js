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
        const text = await response.text();  // Read raw text
        if (text.trim() === "") { // If empty then print no comments
            document.getElementById("comments").innerHTML = `<h5>Pas de commentaire disponible</h5>`;
            return; // Getting out of the prog
        }

        const comments = JSON.parse(text);  // Now we know that the text is not empty, we can take the json format back
        document.getElementById("comment-title").innerHTML = `<h3>${currentTitle}</h3>`;
        document.getElementById("comments").innerHTML = '';

        for (let i = 0; i < comments.length; ++i) { // Dynamically creating the comment cards -> no action given yet to the buttons
            document.getElementById("comments").innerHTML += `
            <div class="card">
                <div class="card-body" style="text-align: left;">
                    <span id="${comments[i]["id"]} "style="color: blue; padding: 0; margin: 2%;">${comments[i]["userLogin"]}</span>
                    ${comments[i]["comment"]}
                    <button type="button" id="del-${comments[i]["id"]}" class="btn btn-light float-end del" value="${comments[i]["id"]}"><i class="fa fa-trash"></i></button>
                    <button type="button" id="mod-${comments[i]["id"]}" class="btn btn-light float-end mod" value="${comments[i]["id"]}"><i class="fa fa-edit"></i></button>
                </div>
            </div>`;
        }
        attachDeleteListeners(); // We bind these new buttons to their respective actions
        attachModifyListeners();
    } else {
        document.getElementById("comments").innerHTML = `<h5>Pas de commentaire disponible</h5>`;
    }
}

async function addComment(photoId, login, text) {
    const response = await fetch("php/request.php/comments/" + photoId, { // Precising the method and the arguments taken in the url
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user: login,
            text: text
        })
    });
    return response.ok;
}

async function modifyComment(photoId, commentId, login, text) {
    const forLog = await fetch("php/request.php/comment/" + commentId);
    if (forLog.ok) {
        const comm = await forLog.json(); // Checking if the logins are the same

        if (login == comm["userLogin"]) {
            const mod = await fetch("php/request.php/comments/" + photoId, {
                method: "PUT",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idCom: commentId,
                    text: text
                })
            });
            return mod.ok;
        }
        else { // If not, than deny the modification
            alert("Impossible : Vous n'êtes pas le propriétaire de ce commentaire. Vous êtes : " + login + " et le propriétaire est : " + comm["userLogin"]);
            return false;
        }
    }
}

async function deleteComment(login, photoId, commentId) {
    const forLog = await fetch("php/request.php/comment/" + commentId);
    if (forLog.ok) {
        const comm = await forLog.json(); // Same than above

        if (login == comm["userLogin"]) {
            const del = await fetch("php/request.php/comments/" + photoId, {
                method: "DELETE",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idCom: commentId
                })
            });
            return del.ok;
        }
        else {
            alert("Impossible : Vous n'êtes pas le propriétaire de ce commentaire. Vous êtes : " + login + " et le propriétaire est : " + comm["userLogin"]);
            return false;
        }
    }
}