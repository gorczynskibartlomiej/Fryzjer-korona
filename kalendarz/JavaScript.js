function powiadomienie(typ, czas=5)
{
    if(typ==undefined)
    {
        if(getComputedStyle(document.getElementById("komunikat")).visibility=='visible')
        {
            typ="komunikat";
        }
        
        if(getComputedStyle(document.getElementById("informacja")).visibility=='visible')
        {
            typ="informacja";
        }
    }

    odliczanie(typ, czas);
}
function odliczanie(typ, czas=5)
{
    if(czas>0) 
    {
        document.getElementById("p").innerHTML="Komunikat zniknie automatycznie za "+czas;
        czas--;
        setTimeout(odliczanie, 1000, typ, czas);
    }
    else 
    {
        zamknij(typ);
    }
}
function zamknij(typ)
{
    document.getElementById(typ).style.visibility = 'hidden';
}
function okno()
{
    document.getElementById("komunikat").style.visibility = 'visible';
}
function przenies()
{
    document.location.href="edytujUslugi.php";
}
function usunUsluge(id, nazwa)
{
    div = document.createElement('div');
    div.setAttribute("id", "komunikat");
    div.setAttribute("class", "potwierdz");
    div.style.visibility='visible';
    div.innerHTML="<h2 id='pytanie'>Czy chcesz usunać usługę "+nazwa+"? </h2><p><a href='' id='link'>Tak</a><a onclick='zamknijOkno(\"komunikat\")'>Nie</a></p>"
    
    if(document.getElementById("komunikat")!=null)
    {
        document.getElementById("komunikat").remove();
        document.body.appendChild(div);
    }
    else
    {
        document.body.appendChild(div);
        
    }
    document.getElementById('link').href = 'usunUsluge.php/?usun='+id;
    
}
function usunUslugePowiadomienie()
{
    div = document.createElement('div');
    div.setAttribute("id", "informacja");
    div.setAttribute("class", "informacja");
    div.setAttribute("onclick", "zamknijOkno(\"informacja\")");
    div.innerHTML="<h2>Usługa została usunięta</h2> "+
    "<p id='p'>Komunikat zniknie automatycznie za 5</p>";

    document.body.appendChild(div);

    odliczanie("informacja");
}
function rezerwacja(podstrona=false)
{
    div = document.createElement('div');
    div.setAttribute("id", "komunikat");
    div.setAttribute("class", "potwierdz");
    div.style.visibility='visible';

    if(!podstrona)
    {
        div.innerHTML="<h2 id='pytanie'>Dla kogo chcesz zarezerować? </h2><p><a href='klienci.php' id='link'>Dla klienta</a><a href='unsetDla.php' id='link'>Dla siebie</a><a onclick='zamknijOkno(\"komunikat\")'>Anuluj</a></p>";
    }
    else
    {
        div.innerHTML="<h2 id='pytanie'>Dla kogo chcesz zarezerować? </h2><p><a href='../klienci.php' id='link'>Dla klienta</a><a href='../unsetDla.php' id='link'>Dla siebie</a><a onclick='zamknijOkno(\"komunikat\")'>Anuluj</a></p>";
    }
    if(document.getElementById("komunikat")!=null)
    {
        document.getElementById("komunikat").remove();
        document.body.appendChild(div);
    }
    else
    {
        document.body.appendChild(div);
        
    }
}
function zamknijOkno(typ)
{
    document.getElementById(typ).remove();
}
function potwierdzenie(d, m, r, g, fid, f, uid, u, c)
{
    document.getElementById("komunikat").style.visibility = 'visible';
    document.getElementById("pytanie").innerHTML = "Czy chcesz zarezerwować "+u+" (koszt "+c+" zł) w dniu "+d+'.'+m+'.'+r+" na godzinę "+g+" do "+f+"?";
    document.getElementById("link").href = "../sendToDatabase.php/?godzina="+g+"&fryzjer="+fid+"&data="+r+"-"+m+"-"+d+"&usluga="+uid;
}     
function usunUzytkownika(i, n, id)
{
    document.getElementById('komunikat').style.visibility = 'visible';
    document.getElementById('pytanie').innerHTML = 'Czy chcesz usunąć użytkownika '+i+' '+n+'?';
    document.getElementById('link').href = 'usunPracownika.php/?usun='+id;
}
function zresetujHaslo(i, n, id)
{
    document.getElementById('komunikat').style.visibility = 'visible';
    document.getElementById('pytanie').innerHTML = 'Czy chcesz zresetować hasło użytkownika '+i+' '+n+'?';
    document.getElementById('link').href = 'resetujHaslo.php/?id='+id+'&u=1';
}
function zresetujHasloDlaKlienta(i, n, id)
{
    document.getElementById('komunikat').style.visibility = 'visible';
    document.getElementById('pytanie').innerHTML = 'Czy chcesz zresetować hasło użytkownika '+i+' '+n+'?';
    document.getElementById('link').href = 'resetujHaslo.php/?id='+id+'&u=2';
}
function zarezerwujDla(i, n, id)
{
    document.getElementById('komunikat').style.visibility = 'visible';
    document.getElementById('pytanie').innerHTML = 'Czy chcesz zarezerwować wizytę '+i+' '+n+'?';
    document.getElementById('link').href = 'klienci.php/?zarezerwujDla='+id;
}