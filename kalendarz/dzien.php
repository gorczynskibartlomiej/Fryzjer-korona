<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;

    $dzien = $_GET["dzien"];
    $miesiac = $_GET["miesiac"];
    $rok = $_GET["rok"];

    if($dzien<10) $dzien="0".$dzien;
    if($miesiac<10) $miesiac="0".$miesiac;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zakład fryzjerski</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        if($status)
        {
            echo "<div class='potwierdz' id='komunikat'>
                    <h2 id='pytanie'></h2>
                    <p><a href='' id='link'>Tak</a><a onclick='zamknij".'("komunikat")'."'>Nie</a></p>
                </div>";
        }
        else
        {
            echo "<div class='blad' id='komunikat' onclick='zamknij".'("komunikat")'."'>
                    <h2>Aby zarezerwować wizytę musisz być zalogowany!</h2>
                    <p><a href='../log.php'>Kliknij tutaj aby sie zalogować lub założyć konto</a></p>
                </div>";
        }
    ?>
    <div class="strona">
        <div class="banner">
            <div class="banner-logo">
            <a href="../index.php"><img src="../logo.png" alt="logo"/></a>
            </div>
            <section>
                <div class="banner-profile">
                    <?php if($status):?>                    
                        <div class="log">
                            <a href="../logout.php">Wyloguj</a>
                        </div>
                        <div class="profil-email">                                
                            <a href="../profil.php"><?php echo $email?></a>
                        </div>                    
                    <?php else:?>
                        <div class="log">  
                            <a href="../log.php">Zaloguj</a>
                        </div>
                    <?php endif;?>
                </div>
            </section>
        </div>        
        <div class="nav">
            <ul>
                <a href="../index.php"><li>Strona główna</li></a>
                <a <?php if($rola!=2){ echo "onclick=\"rezerwacja(true)\"";} else { echo "href=\"../uslugi.php\"";} ?> ><li>Rezerwacja wizyty</li></a>
                <a href="../wizyty.php"><li>Umówione wizyty &nbsp
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
                <a href="../kontakt.php"><li>Kontakt</li></a>
            </ul>
        </div>
        <?php 
            if($rola==0)
            {
                echo "<div class='nav'>
                <ul>
                <a href='../zarzadzanie.php'><li>Zarządzanie pracownikami</li></a>
                <a href='../edytujUslugi.php'><li>Dodaj/Edytuj usługę</li></a>
                <a href='../godzPracy.php'><li>Ustaw godziny pracy</li></a>
                <a href='../edytujGodzPracyKalendarz.php'><li>Zmień godziny pracy</li></a>
                </ul>
                </div>";
            }
        ?>
        <div class="content">
            <div id="dzien-data">
            <div id="dzien-data-w">
                <?php
                    // if($rola==0)
                    // {
                    //     echo "<form action='../edytujGodzPracy.php' method='post'>
                    //             <input type='hidden' name='dzien' value='$dzien'>
                    //             <input type='hidden' name='miesiac' value='$miesiac'>
                    //             <input type='hidden' name='rok' value='$rok'>
                    //             <input type='submit' value='Edytuj godziny' class='zmienGodzPracy'>
                    //         </form>";
                    // }
                
                    echo "<h2>$dzien.$miesiac.$rok</h2>";
                    
                    $query = mysqli_query($conn,"SELECT TIME_FORMAT(MIN(Od),'%H'), TIME_FORMAT(MIN(Od),'%i'), TIME_FORMAT(MAX(Do),'%H'), TIME_FORMAT(MAX(Do),'%i') FROM godzinypracy WHERE Data='$rok-$miesiac-$dzien'");
                    $row=mysqli_fetch_row($query);
                    echo "<p>Zakład otwarty od $row[0]:$row[1] do $row[2]:$row[3]</p>";

                    $odGodz = $row[0]+0;
                    $odMin = $row[1]/10;
                    $doGodz = $row[2]+0;
                    $doMin = $row[3]/10;
                ?>    
                </div>
            </div>
            <div class="dzien-info">
                <div class="dzien-info-ogolne">Godzina</div>
                <?php
                    $query = mysqli_query($conn,"SELECT Imie, Nazwisko, Email FROM godzinypracy gp, uzytkownicy u WHERE Data='$rok-$miesiac-$dzien' AND gp.IDPracownika = u.ID ORDER BY u.ID");
                    while($row=mysqli_fetch_row($query))
                    {
                        if($row[2] == $email && !isset($_SESSION["zarezerwujDla"]))
                        {
                            continue;
                        }
                        echo "<div class='dzien-info-ogolne'>$row[0] $row[1]</div>";
                    }
                ?>
            </div>

            <?php
                $query = mysqli_query($conn,"SELECT count(u.ID) FROM uzytkownicy u, godzinypracy gp WHERE gp.IDPracownika=u.ID AND gp.Data='$rok-$miesiac-$dzien' AND Rola=1");
                @$pracownicy[mysqli_fetch_row($query)[0]];

                @$zakonczenieZmiany[mysqli_fetch_row($query)[0]];

                $pracownicyDane = array();
                $pracownicyEmail = array();
                $licznikPracownikow = 0;
                
                //sprawdzanie końca zmiany i zapisanie danych fryzjerów
                $query = mysqli_query($conn,"SELECT u.ID, concat(u.Imie, ' ', u.Nazwisko), u.Email FROM uzytkownicy u, godzinypracy gp 
                WHERE gp.IDPracownika=u.ID AND Rola=1 AND gp.Data='$rok-$miesiac-$dzien' ORDER BY ID");
                while($row=mysqli_fetch_row($query))
                {
                    @$pracownicy[$licznikPracownikow] = $row[0];
                    $pracownicyDane[$row[0]] = $row[1];
                    $pracownicyEmail[$row[0]] = $row[2];

                    @$zakonczenieZmiany[$licznikPracownikow]=mysqli_fetch_row(mysqli_query($conn,"SELECT Do FROM godzinypracy 
                    WHERE Data='$rok-$miesiac-$dzien' AND IDPracownika='$pracownicy[$licznikPracownikow]'"))[0];

                    $licznikPracownikow++;
                }

                $usluga= $_GET['usluga'];
                $query = mysqli_query($conn,"SELECT CzasTrwania, NazwaUslugi, Koszt FROM uslugi WHERE ID=$usluga");
                $row = mysqli_fetch_row($query);
                $czasUslugi = $row[0];
                $nazwaUslugi = $row[1];                
                $cenaUslugi = round($row[2],0);
                
                $h = $odGodz; $m = $odMin;
                while($h<$doGodz || $m<$doMin) //wypisanie godzin 
                {
                    echo "<div class='dzien-info'>";
                        echo "<div class='dzien-info-ogolne'>";
                            if($h < 10) echo "0$h:";
                            else echo "$h:";
                            echo $m."0<br>";
                            
                        echo "</div>";
                        
                        $godz = $h.":".$m."0";

                        for($i=0; $i<count($pracownicy); $i++)
                        {
                            $p = $pracownicy[$i];
                            if($pracownicyEmail[$p] == $email && !isset($_SESSION["zarezerwujDla"]))
                            {
                                continue;
                            }
                            
                            $query = mysqli_query($conn,"SELECT COUNT(godzinypracy.ID) FROM godzinypracy WHERE Data='$rok-$miesiac-$dzien' AND IDPracownika=$p and Od<='$godz' AND Do>'$godz'");
                            $row=mysqli_fetch_row($query)[0];

                            if($row != 1)
                            {
                                echo "<div class='dzien-info-niedostepny'></div>";
                                continue;
                            }
                            
                            $query = mysqli_query($conn, "SELECT count(r.ID) 
                            FROM rezerwacja r, uslugi u 
                            WHERE r.Usluga=u.ID AND Data='$rok-$miesiac-$dzien' AND Pracownik=$p AND addtime(Godzina, addtime('-$czasUslugi', '00:20:00')) <= '$godz' AND addtime(r.Godzina, u.CzasTrwania) > '$godz' or addtime('$zakonczenieZmiany[$i]', '-$czasUslugi') < addtime('$godz','0:0:0')");

                            $row=mysqli_fetch_row($query)[0];

                            if($row == 0 && $status) echo "<div class='dzien-info-wolne' onclick='potwierdzenie($dzien, $miesiac, $rok, \"$godz\", $p, \"$pracownicyDane[$p]\", $usluga, \"$nazwaUslugi\", $cenaUslugi)'>Wolne</div>";
                            elseif($row == 0 && !$status) echo "<div class='dzien-info-wolne' onclick='okno()'>Wolne</div>";
                            else echo "<div class='dzien-info-zajete'>Niedostępne</div>";
                        }
                        
                        $m+=2;
                        if($m == 6)
                        {
                            $m = 0;
                            $h++;
                        }
                    echo "</div>";
                }
             ?>
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
    <script src="../JavaScript.js"></script>
</body>
</html>
<?php
    mysqli_close($conn);
?>