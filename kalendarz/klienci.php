<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
    if(!$status || $rola==2)
    {
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    }

    $wyszukaj=$_POST["wyszukaj"]??"";
    
    $zarezerwujDla=$_GET["zarezerwujDla"]??null;
    if($zarezerwujDla != null)
    {
        $_SESSION["zarezerwujDla"]=$zarezerwujDla;
        mysqli_close($conn);
        header("Location: ../uslugi.php");
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
<body onload='powiadomienie("informacja")'>
<?php
    if(isset($_SESSION["hasloUzytkownika"]))
    {
        $zrestartowaneHaslo = $_SESSION['hasloUzytkownika'];
        echo "<div class='informacja' id='informacja' onclick='zamknij".'("informacja")'."'>
                <h2>Hasło użytkownika $zrestartowaneHaslo zostało zmienione na \"Klient123!@\"</h2>
                <p id='p'>Komunikat zniknie automatycznie za 5</p>
            </div>";                
        unset($_SESSION["hasloUzytkownika"]);
    }
    if(isset($_SESSION["nowyKlient"]))
    {
        $nowyKlient = $_SESSION["nowyKlient"];
        echo "<div class='informacja' id='informacja' onclick='zamknij".'("informacja")'."'>
            <h2>Uzytkownik $nowyKlient został dodany pomyślnie</h2>
            <p id='p'>Komunikat zniknie automatycznie za 5</p>
        </div>";
        unset($_SESSION["nowyKlient"]);
    }
?>
    <div class='potwierdz' id='komunikat'>
        <h2 id='pytanie'>Czy chcesz usunac uzytkownika</h2>
        <p><a href='' id='link'>Tak</a><a onclick='zamknij("komunikat")'>Nie</a></p>
    </div>
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
            <div class="wizytaDlaKogos">
                <div class="wyszukaj">
                    <form action="klienci.php" method="post">
                        <input type="text" name="wyszukaj" placeholder="Wyszukaj imię, nazwisko, adres email lub numer telefonu użytkownika">
                        <input type="submit" value="Szukaj">
                    <a href="addUser.php/?u=2" style="border: 0.1em solid rgb(118, 118, 118); padding: 0.3em 0.3em; font-size: large;">Dodaj</a>
                    </form>
                </div>
            </div>
            <div class='klienci'>
                <?php
                    $query = mysqli_query($conn,"SELECT ID, Imie, Nazwisko, Email, Telefon FROM uzytkownicy WHERE Rola=2 AND (Imie LIKE '%$wyszukaj%' OR Nazwisko LIKE '%$wyszukaj%' OR Email LIKE '%$wyszukaj%' OR Telefon LIKE '%$wyszukaj%') LIMIT 5");
                    while($row=mysqli_fetch_row($query))
                    {
                        echo    "<div class='klient'>
                                    <div class='klient-info'>
                                        <div class='klient-nazwa'>$row[1] $row[2]</div>
                                        <div class='klient-dane'>"; if($row[3]!="") echo "email: $row[3]<br>"; echo "tel: $row[4]</div>
                                    </div>
                                    <div class='klient-przyciski'>
                                        <div class='klient-przycisk' onclick='zarezerwujDla(\"$row[1]\", \"$row[2]\", $row[0])'>Zarezerwuj wizytę</div>";
                                        if($row[3]!="")
                                            echo "<div class='klient-przycisk' onclick='zresetujHasloDlaKlienta(\"$row[1]\", \"$row[2]\", $row[0])'>Resetuj hasło</div>";
                                        
                        echo    "</div></div>";
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