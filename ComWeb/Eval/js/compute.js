'use strict'

async function opsRequest(id)
{
    const rep = await fetch('php/request.php/ex2/'+id+'?step=1');
    if (rep.ok)
        {
            displayOps(await rep.json());
        }
    else
        {
            console.log(rep.status);
        }
}

function displayOps(list)
{
    console.log(list);
    document.getElementById('step1').innerHTML = String(list['values'][0]); //On reset le contenu de la balise
    let result = list['values'][0];
    for (let i = 1; i < (list['operators']).length - 1; ++i)
    {
        document.getElementById('step1').innerHTML += String(list['operators'][i-1]) + String(list['values'][i]);
        let adding = 0;
        if (list['operators'][i-1] == '-')
        {
            adding = -list['values'][i];
        }
        else
        {
            adding = list['values'][i];
        }
        result += adding;
    }
    document.getElementById('step1').innerHTML += '=' + String(result);
    return result;
}

function step2(id)
{
    ajaxRequest('POST', 'php/request.php/ex2/', () =>
        {
          ajaxRequest('GET', 'php/request.php/ex2', opsRequest);
        }, 'ID=' + id + '&result=' + opsRequest(id));
}

function main()
{
    //let ans = 11686833;
    let ans = prompt("Entrez votre identifiant : ");
    opsRequest(ans);
}

main();