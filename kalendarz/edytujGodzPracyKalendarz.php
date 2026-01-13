<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
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
            <div class="calendar">
                <?php
                    $miesiace=["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
                    $querry = mysqli_query($conn,"SELECT YEAR(CURRENT_DATE()), MONTH(CURRENT_DATE()), DAY(CURRENT_DATE())");
                    $row=mysqli_fetch_row($querry);
                    $aktualnyRok = $row[0];     
                    $aktualnyMiesiac = $row[1];
                    $akutalnyDzien = $row[2];
                    $rok = $_GET["rok"] ?? $aktualnyRok;
                    $miesiac = $_GET["miesiac"] ?? $aktualnyMiesiac;
                    

                    $querry = mysqli_query($conn,"SELECT (DAYOFWEEK('$rok-$miesiac-1')+5)%7+1");
                    $dzientyg = mysqli_fetch_row($querry)[0];
                    $querry = mysqli_query($conn,"select DAYOFMONTH(LAST_DAY('$rok-$miesiac-1'))");
                    $dniwmiesiacu = mysqli_fetch_row($querry)[0];

                    echo "<table>
                        <tr><th><a href='?miesiac=".(($miesiac-1)==0 ? $miesiac+11 : $miesiac-1)."&rok=".(($miesiac-1)==0 ? $rok-1 : $rok)."'>&#129144;</a></th> 
                        <th id='calendar-month' colspan='5'><h2>".$miesiace[$miesiac-1]." $rok</h2></th> 
                        <th><a href='?miesiac=".(($miesiac+1)==13 ? $miesiac-11 : $miesiac+1)."&rok=".(($miesiac+1)==13 ? $rok+1 : $rok)."'>&#129146;</a></th></tr>";
                    
                    for($i = 1; $i<=$dniwmiesiacu;$i++)
                    {

                        if($dzientyg==1)
                        {
                            echo "<tr>";
                        }

                        if($i == 1)
                        {
                            if($dzientyg!=1) echo "<td colspan='".($dzientyg-1)."'></td>";
                        }

                        echo "<td ";

                        $querry = mysqli_query($conn,"SELECT COUNT(ID) FROM godzinypracy WHERE Data='$rok-$miesiac-$i'");
                        $czyjest = mysqli_fetch_row($querry)[0];

                        if($dzientyg!=7 && ( ($rok>$aktualnyRok) || ($rok==$aktualnyRok && $miesiac>$aktualnyMiesiac) || ($rok==$aktualnyRok && $miesiac==$aktualnyMiesiac && $i>=$akutalnyDzien)) && $czyjest!=0)
                        {
                            echo "class='calendar-day'><a href='edytujGodzPracy.php/?dzien=$i&miesiac=$miesiac&rok=$rok'>$i</a></td>";
                        }
                        else
                        {
                            echo " class='calendar-day-closed'>$i</td>";
                        }

                        if($dzientyg==7)
                        {
                            echo "</tr>";
                        }

                        $dzientyg=($dzientyg)%7+1;
                    }
                    echo "<tr> <th colspan='7'> <p class='calendar-legends'> <span class='unavailable'> ■ </span> - dzień niedostępny <span class='available'> ■ </span> - dzień dostępny </p> </th></tr>";
                    echo"</table>";

                    mysqli_close($conn);
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