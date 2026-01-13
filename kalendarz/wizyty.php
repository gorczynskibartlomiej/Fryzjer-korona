<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
    
    if(!$status)
    {
        mysqli_close($conn);
        header("Location: log.php");
        exit();
    }

    $rezerwacjaID = $_POST["rezerwacjaID"]??null;
    $operacja = $_POST["operacja"]??null;
    if($rezerwacjaID != null && $operacja != null)
    {
        switch($operacja)
        {
            case "potwierdz":
                mysqli_query($conn,"UPDATE rezerwacja SET Potwierdzona=true WHERE ID=$rezerwacjaID");
                $to = "koronaprojektzespolowy@gmail.com";//email odbiorcy
                $subject = "Potwierdzenie wizyty";//temat
                $txt = "Twoja wizyta zostala potwierdzona. \nEmail wygenerowany automatycznie prosze na niego nie odpowiadac.\n";//treść
                $headers = "From:koronaprojektzespolowy@gmail.com";//nasz email

                mail($to,$subject,$txt,$headers);//funkcja wysyłająca email

                break;
            case "usun":
                mysqli_query($conn,"DELETE FROM rezerwacja WHERE ID=$rezerwacjaID");
                break;
        }
        mysqli_close($conn);
        header("Location: wizyty.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zakład fryzjerski</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="strona">
        <div class="banner">
            <div class="banner-logo">
            <a href="index.php"><img src="logo.png" alt="logo"/></a>
            </div>
            <section>
                <div class="banner-profile">
                    <?php if($status):?>                    
                        <div class="log">
                            <a href="logout.php">Wyloguj</a>
                        </div>
                        <div class="profil-email">                                
                            <a href="profil.php"><?php echo $email?></a>
                        </div>                    
                    <?php else:?>
                        <div class="log">  
                            <a href="log.php">Zaloguj</a>
                        </div>
                    <?php endif;?>
                </div>
            </section>
        </div>
        <div class="nav">
            <ul>
                <a href="index.php"><li>Strona główna</li></a>
                <a <?php if($rola!=2){ echo "onclick=\"rezerwacja()\"";} else { echo "href=\"uslugi.php\"";} ?> ><li>Rezerwacja wizyty</li></a>
                <a href="wizyty.php"><li>Umówione wizyty &nbsp
                <?php 
                    if($rola==0)
                    {
                        $querry = mysqli_query($conn,"SELECT Count(r.ID)FROM uzytkownicy u, rezerwacja r WHERE u.ID=r.Pracownik AND potwierdzona=0");
                        $row=mysqli_fetch_row($querry)[0]; 
                        if($row>9)
                        {
                            echo "<span class='notifications'> &nbsp 9+ &nbsp</span>"; 
                        }
                        elseif($row>0 && $row<=9)
                        {
                            echo "<span class='notifications'> &nbsp $row  &nbsp</span>"; 
                        }
                    }
                    if($rola==1)
                    {
                        $querry = mysqli_query($conn,"SELECT Count(r.ID)FROM uzytkownicy u, rezerwacja r WHERE u.ID=r.Pracownik AND u.Email='$email' AND potwierdzona=0");
                        $row=mysqli_fetch_row($querry)[0]; 
                        if($row>9)
                        {
                            echo "<span class='notifications'> &nbsp 9+ &nbsp</span>"; 
                        }
                        elseif($row>0 && $row<=9)
                        {
                            echo "<span class='notifications'> &nbsp $row  &nbsp</span>"; 
                        }
                    }
                    
                    echo "</li></a>";
                ?>
                <a href="kontakt.php"><li>Kontakt</li></a>
            </ul>
        </div>
        <?php 
            if($rola==0)
            {
                echo "<div class='nav'>
                <ul>
                <a href='zarzadzanie.php'><li>Zarządzanie pracownikami</li></a>
                <a href='edytujUslugi.php'><li>Dodaj/Edytuj usługę</li></a>
                <a href='godzPracy.php'><li>Ustaw godziny pracy</li></a>
                <a href='edytujGodzPracyKalendarz.php'><li>Zmień godziny pracy</li></a>
                </ul>
                </div>";
            }
        ?>
        <div class="content">
            <div class="wizyty">
                <?php
                    $query = mysqli_query($conn,"SELECT ID FROM uzytkownicy WHERE email='$email'");
                    $row=mysqli_fetch_row($query)[0];
                    // wypisanie listy w zależności od roli użytkownika
                    switch($rola) 
                    {
                        case 0:
                            $query = mysqli_query($conn,"SELECT rezerwacja.ID, rezerwacja.Potwierdzona, uslugi.Nazwauslugi, Data, left(rezerwacja.Godzina, 5), uslugi.Koszt, left(CzasTrwania, 2)*60+right(left(CzasTrwania, 5), 2), uzytkownicy.Imie, uzytkownicy.Nazwisko
                            FROM rezerwacja, uslugi, uzytkownicy 
                            WHERE rezerwacja.Usluga=uslugi.ID AND rezerwacja.Pracownik=uzytkownicy.ID AND rezerwacja.Data>='CURRENT_DATE()' ORDER BY rezerwacja.Data, rezerwacja.Godzina");
                            break;
                        case 1:
                            $query = mysqli_query($conn,"SELECT rezerwacja.ID, rezerwacja.Potwierdzona, uslugi.Nazwauslugi, concat(DAY(Data), '.', MONTH(Data), '.', YEAR(Data)), left(rezerwacja.Godzina, 5), uslugi.Koszt, left(CzasTrwania, 2)*60+right(left(CzasTrwania, 5), 2), uzytkownicy.Imie, uzytkownicy.Nazwisko, uzytkownicy.Telefon
                            FROM rezerwacja, uslugi, uzytkownicy 
                            WHERE rezerwacja.Usluga=uslugi.ID AND rezerwacja.Klient=uzytkownicy.ID AND rezerwacja.Data>='CURRENT_DATE()' AND rezerwacja.Pracownik=$row ORDER BY rezerwacja.Data, rezerwacja.Godzina");
                            break;
                        case 2:
                            $query = mysqli_query($conn,"SELECT rezerwacja.ID, rezerwacja.Potwierdzona, uslugi.Nazwauslugi, concat(DAY(Data), '.', MONTH(Data), '.', YEAR(Data)), left(rezerwacja.Godzina, 5), uslugi.Koszt, left(CzasTrwania, 2)*60+right(left(CzasTrwania, 5), 2), uzytkownicy.Imie, uzytkownicy.Nazwisko
                            FROM rezerwacja, uslugi, uzytkownicy 
                            WHERE rezerwacja.Usluga=uslugi.ID AND rezerwacja.Pracownik=uzytkownicy.ID AND rezerwacja.Data>='CURRENT_DATE()' AND rezerwacja.Klient=$row ORDER BY rezerwacja.Data, rezerwacja.Godzina"); //4
                            break;
                    }
                    
                    while($row=mysqli_fetch_row($query))
                    {
                        if($row[1] || $rola==2)
                        {
                            echo    "<div class='wizyta'>
                                        <div class='wizyta-info'>".date('d.m.Y', strtotime($row[3]))." $row[4] $row[2]</div>
                                        <div class='wizyta-cena'>".round($row[5],0)." zł</div>
                                        <div class='wizyta-zatwierdz'>
                                            <form action='wizyty.php' method='post'>
                                                <input type='hidden' name='rezerwacjaID' value='$row[0]'>
                                                <input type='hidden' name='operacja' value='usun'>
                                                <input type='submit' value='Anuluj wizytę'>
                                            </form>
                                         </div>";
                        }
                        else
                        {
                            echo    "<div class='wizyta-potwierdz'>
                                        <div class='wizyta-info'>".date('d.m.Y', strtotime($row[3]))." $row[4] $row[2]</div>
                                        <div class='wizyta-cena'>".round($row[5],0)." zł</div>
                                        <div class='wizyta-zatwierdz'>
                                            <form action='wizyty.php' method='post'>
                                                <input type='hidden' name='rezerwacjaID' value='$row[0]'>
                                                <input type='hidden' name='operacja' value='potwierdz'>
                                                <input type='submit' value='Potwierdź wizytę'>
                                            </form>
                                         </div>";
                        }
                            
                            echo        "<div class='wizyta-czas'>Czas: $row[6] minut</div>
                                         <div class='wizyta-kto'>";
                                            switch($rola)
                                            {
                                                case 0: echo "Pracownik: $row[7] $row[8]"; break;
                                                case 1: echo "Klient: $row[7] $row[8] $row[9]"; break;
                                                case 2: echo "Fryzjer: $row[7] $row[8]"; break;
                                            }
                            echo        "</div>";

                        if(!$row[1] && $rola!=2)
                        {
                            echo        "<div class='wizyta-zatwierdz'>
                                            <form action='wizyty.php' method='post'>
                                                <input type='hidden' name='rezerwacjaID' value='$row[0]'>
                                                <input type='hidden' name='operacja' value='usun'>
                                                <input type='submit' value='Anuluj wizytę'>
                                            </form>
                                        </div>";
                        }
                        if(!$row[1] && $rola==2)
                        {
                            echo        "<div class='wizyta-niepotwierdzono'>
                                            <form action='wizyty.php' method='post'>
                                                <input type='hidden' name='rezerwacjaID' value='$row[0]'>
                                                <input type='hidden' name='operacja' value='usun'>
                                                <input type='submit' value='Niepotwierdzono' disabled>
                                            </form>
                                        </div>";
                        }
                        if($row[1])
                        {
                            echo        "<div class='wizyta-potwierdzono'>
                                            <form action='wizyty.php' method='post'>
                                                <input type='hidden' name='rezerwacjaID' value='$row[0]'>
                                                <input type='hidden' name='operacja' value='usun'>
                                                <input type='submit' value='Potwierdzono' disabled>
                                            </form>
                                        </div>";
                        }
                        echo      "</div>";                        
                    }
                ?>
            </div>

        </div>
        <div class="stopka">
            <p>Trudno wyobrazić sobie bardziej uroczyste i efektowne fryzury niż te zrobione przez naszych fryzjerów.
                <span> </span>
                <strong>Profesjonalne strzyżenie</strong>
                <span> </span>
                to absolutny must have w życiu!
                <span> </span>
                <span> </span> Wszyscy będą oczarowani twoją nową fryzurą. W
                <span> </span><strong>salonie Korona</strong><span> </span>
                znajdziesz
                <span> </span>
                <strong>profesjonalistów, którzy wykonują swoją pracę najlepiej jak mogą</strong>
                <span> </span>
                Nie wydasz fortuny, a przy okazji zmienisz swój wygląd.
            </p>
        </div>
    </div>
    <script src="JavaScript.js"></script>
</body>
</html>
<?php
    mysqli_close($conn);
?>