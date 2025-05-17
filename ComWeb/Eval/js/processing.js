/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 03-May-2022 - 17:52:00
 * \\Last Modified: 30-Mar-2025 - 23:06:54
 */

'use strict';

// strlen callback.
document.getElementById('strlen').addEventListener('click', async () =>
{
  const response = await fetch('php/request.php/ex3/?value=' +
    document.getElementById('strlen-value').value);
  if (!response.ok)
    displayErrors(response.status);
  else
    document.getElementById('strlen-result').value = await response.json();
});

// substr callback.
document.getElementById('substr').addEventListener('click', async () =>
{
  const response = await fetch('php/request.php/ex3/', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'value1=' + document.getElementById('substr-value1').value +
      '&value2=' + document.getElementById('substr-value2').value});
  if (!response.ok)
    displayErrors(response.status);
  else
    document.getElementById('substr-result').value = await response.json();
});

// str_replace callback.
document.getElementById('str_replace').addEventListener('click', async () =>
{
  const response = await fetch('php/request.php/ex3/?value1=' +
    document.getElementById('str_replace-value1').value +
    '&value2=' + document.getElementById('str_replace-value2').value +
    '&value3=' + document.getElementById('str_replace-value3').value,
    {method: 'DELETE'});
  if (!response.ok)
    displayErrors(response.status);
  else
    document.getElementById('str_replace-result').value = await response.json();
});
