'use strict'

async function timeRequest(id)
{
    const rep = await fetch('php/request.php/ex1/'+id)
    if (rep.ok)
    {
        displayTime(await rep.json());
    }
    else
    {
        console.log(rep.status);
    }
}

function displayTime(list)
{
    document.getElementById('name').innerHTML = list[0];
    document.getElementById('time').innerHTML = list[1];
}

function main()
{
    //let ans = 11686833;
    let ans = prompt("Entrez votre identifiant");
    timeRequest(ans);
}

main();