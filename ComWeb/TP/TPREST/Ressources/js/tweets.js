/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Yncréa Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 29-Jan-2018 - 16:48:07
 * \\Last Modified: 05-Feb-2024 - 10:51:18
 */

'use strict';

let login = 'cir2';
let currentTitle = 'Liste des tweets';

// Display all tweets.
ajaxRequest('GET', 'php/request.php/tweets/', displayTweets);
document.getElementById('all-button').addEventListener('click', () => 
{
  currentTitle = 'Liste des tweets';
  ajaxRequest('GET', 'php/request.php/tweets/', displayTweets);
});

// Display my tweets.
document.getElementById('my-button').addEventListener('click', () => 
{
  currentTitle = 'Liste de mes tweets';
  ajaxRequest('GET', 'php/request.php/tweets/?login=' + login, displayTweets);
});

// Add tweet.
document.getElementById('tweet-add').addEventListener('submit', (event) => 
{
  event.preventDefault();
  let value = document.getElementById('tweet').value;
  ajaxRequest('POST', 'php/request.php/tweets/', () =>
  {
    ajaxRequest('GET', 'php/request.php/tweets/', displayTweets);
  }, 'login=' + login + '&text=' + value);
  document.getElementById('tweet').value = '';
});

//------------------------------------------------------------------------------
//--- ModifyTweets -------------------------------------------------------------
//------------------------------------------------------------------------------
// Modify tweets.
function modifyTweets()
{
  const modifyButtons = document.querySelectorAll('.mod');
  modifyButtons.forEach(e => e.addEventListener('click', (event) =>
  {
    let value = event.target.closest('.mod').getAttribute('value');
    ajaxRequest('PUT', 'php/request.php/tweets/' + value, () =>
    {
      ajaxRequest('GET', 'php/request.php/tweets/', displayTweets);
    }, 'login=' + login + '&text=' + prompt('Nouveau tweet :'));
  }));
}

//------------------------------------------------------------------------------
//--- DeleteTweets -------------------------------------------------------------
//------------------------------------------------------------------------------
// Delete tweets.
function deleteTweets()
{
  const deleteButtons = document.querySelectorAll('.del');
  deleteButtons.forEach(e => e.addEventListener('click', (event) =>
  {
    let value = event.target.closest('.del').getAttribute('value');
    ajaxRequest('DELETE', 'php/request.php/tweets/' + value + '?login=' +
      login, () =>
    {
      ajaxRequest('GET', 'php/request.php/tweets/', displayTweets);
    });
  }));
}

//------------------------------------------------------------------------------
//--- displayTweets ------------------------------------------------------------
//------------------------------------------------------------------------------
// Display tweets.
// \param tweets The tweets data received via the Ajax request.
function displayTweets(tweets)
{
  // Fill tweets.
  document.getElementById('tweets').innerHTML = '<h3>' + currentTitle + '</h3>';
  for (let tweet of tweets)
    document.getElementById('tweets').innerHTML += '<div class="card">' +
      '<div class="card-body">' + tweet.login + ' : ' + tweet.text +
      '<div class="btn-group float-end" role="group">' +
      '<button type="button" class="btn btn-light float-end mod"' +
      ' value="' + tweet.id + '"><i class="fa fa-edit"></i></button>' +
      '<button type="button" class="btn btn-light float-end del"' +
      ' value="' + tweet.id + '"><i class="fa fa-trash"></i></button>' +
      '<div></div></div>';
  modifyTweets();
  deleteTweets();
}
