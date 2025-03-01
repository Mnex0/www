'use strict';

async function requestPolls()
{
  const response = await fetch('php/request.php/polls');
  addEventListener(document.getElementById('polls-select').value, requestPoll(document.getElementById('polls-select').value));
  if (!response.ok)
  {
    displayError(response.status);
  }
  else
    displayPolls(await response.json());
}

function displayPolls(polls)
{
    for (let i = 0; i < 3; i++)
    {
      document.getElementById('polls-select').innerHTML += "<option value='"+i+"'>"+polls[i]["title"]+"</option>";
    }
}

async function requestPoll(id)
{
  const response = await fetch('php/request.php/polls/'.concat(id+1));
  if (!response.ok)
  {
    displayError(response.status);
  }
  else
    displayPoll(await response.json());
}

function displayPoll(poll)
{
  for (let i = 1; i < 4; i++)
  {
    let score = (100*parseFloat(poll['option'+i+'score'])/parseFloat(poll.participants)).toFixed(2);
    document.getElementById('poll-option'+i).innerHTML = '<div class="progress" role="progressbar" aria-label="'+poll["option"+i]+'" aria-valuenow="'+score+'" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar" style="width: '+score+'"></div>';
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

requestPolls();