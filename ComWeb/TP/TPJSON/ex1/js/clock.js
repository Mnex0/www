/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Yncréa Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 29-Jan-2018 - 16:48:07
 * \\Last Modified: 23-Aug-2024 - 14:11:10
 */

'use strict';

setInterval(requestTime, 1000);

//------------------------------------------------------------------------------
//--- requestTime --------------------------------------------------------------
//------------------------------------------------------------------------------
// Request time.
async function requestTime()
{
  const response = await fetch('php/time.php');
  if (!response.ok)
    displayErrors(response.status);
  else
    displayClock(await response.json());
}

//------------------------------------------------------------------------------
//--- displayClock -------------------------------------------------------------
//------------------------------------------------------------------------------
// Display a clock.
// \param time The time data received via the Ajax request.
function displayClock(time)
{
  let canvas;
  let context;
  let radius;

  // Display text.
  document.getElementById('title').innerHTML = time[0];
  
  // Define context.
  canvas = document.getElementById('clock');
  context = canvas.getContext('2d');
  radius = canvas.height/2;
  context.translate(radius, radius);
  radius *= 0.9;

  // Draw clock background, numbers and hands.
  drawBackground(context, radius);
  drawNumbers(context, radius);
  drawHands(context, radius, time[1]);
  context.setTransform(1, 0, 0, 1, 0, 0);
}

//------------------------------------------------------------------------------
//--- drawBackground -----------------------------------------------------------
//------------------------------------------------------------------------------
// Draw clock background.
// \param context The drawing context.
// \param radius The clock radius.
function drawBackground(context, radius)
{
  let gradient;

  // White background.
  context.beginPath();
  context.arc(0, 0, radius, 0, 2*Math.PI);
  context.fillStyle = 'white';
  context.fill();

  // Gradient contour.
  gradient = context.createRadialGradient(0, 0, radius*0.95, 0, 0, radius*1.05);
  gradient.addColorStop(0, '#333');
  gradient.addColorStop(0.5, 'white');
  gradient.addColorStop(1, '#333');
  context.strokeStyle = gradient;
  context.lineWidth = radius*0.1;
  context.stroke();

  // Clock center.
  context.beginPath();
  context.arc(0, 0, radius*0.1, 0, 2*Math.PI);
  context.fillStyle = '#333';
  context.fill();
}

//------------------------------------------------------------------------------
//--- drawNumbers --------------------------------------------------------------
//------------------------------------------------------------------------------
// Draw clock numbers.
// \param context The drawing context.
// \param radius The clock radius.
function drawNumbers(context, radius)
{
  let angle;
  
  context.font = radius*0.15 + 'px arial';
  context.textBaseline = 'middle';
  context.textAlign = 'center';
  for (let number = 1; number <= 12; number++)
  {
    angle = number*Math.PI/6;
    context.rotate(angle);
    context.translate(0, -radius*0.85);
    context.rotate(-angle);
    context.fillText(number.toString(), 0, 0);
    context.rotate(angle);
    context.translate(0, radius*0.85);
    context.rotate(-angle);
  }
}

//------------------------------------------------------------------------------
//--- drawHands ----------------------------------------------------------------
//------------------------------------------------------------------------------
// Draw clock hands.
// \param context The drawing context.
// \param radius The clock radius
// \param time The time
function drawHands(context, radius, time)
{
  let hours;
  let minutes;
  let seconds;

  // Get data.
  hours = time['hours'];
  minutes = time['minutes'];
  seconds = time['seconds'];

  // Hours.
  hours %= 12;
  hours = (hours*Math.PI/6) + (minutes*Math.PI/(6*60)) +
    (seconds*Math.PI/(360*60));
  drawHand(context, radius*0.5, radius*0.07, hours);
  
  // Minutes.
  minutes = (minutes*Math.PI/30) + (seconds* Math.PI/(30*60));
  drawHand(context, radius*0.8, radius*0.07, minutes);

  // Seconds.
  seconds = (seconds*Math.PI/30);
  drawHand(context, radius*0.9, radius*0.02, seconds);
}

//------------------------------------------------------------------------------
//--- drawHand -----------------------------------------------------------------
//------------------------------------------------------------------------------
// Draw clock hand.
// \param context The drawing context.
// \param length The hand length.
// \param width The hand width.
// \param value The hand value.
function drawHand(context, length, width, value)
{
  context.beginPath();
  context.lineWidth = width;
  context.lineCap = 'round';
  context.moveTo(0, 0);
  context.rotate(value);
  context.lineTo(0, -length);
  context.stroke();
  context.rotate(-value);
}

//------------------------------------------------------------------------------
//--- displayErrors ------------------------------------------------------------
//------------------------------------------------------------------------------
// Display an error message accordingly to an error code.
// \param errorCode The error code (HTTP status for example).
function displayErrors(errorCode)
{
  let messages = {
    400: 'Requête incorrecte',
    401: 'Authentifiez-vous',
    403: 'Accès refusé',
    404: 'Page non trouvée',
    500: 'Erreur interne du serveur',
    503: 'Service indisponible'
  };

  // Display error.
  if (errorCode in messages)
  {
    document.getElementById('errors').innerHTML = '<i class="fa-solid ' +
      'fa-circle-exclamation"></i> <strong>' + messages[errorCode] +
      '</strong>';
    document.getElementById('errors').style.display = 'block';
  }
}
