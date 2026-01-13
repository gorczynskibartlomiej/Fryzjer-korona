<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
    if(!$status || $rola!=0)
    {
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    }

    @$dzien1 = date_create($_POST['dzien1'])??null;
    @$dzien2 = date_create($_POST['dzien2'])??null;
    $godzina1 = $_POST['godzina1']??"08:00";
    $godzina2 = $_POST['godzina2']??"16:00";
    $pracownicy = $_POST['pracownik']??null;

    if($dzien1 != null && $dzien2 != null && $pracownicy != null)
    {
        for($i = $dzien1; $i <= $dzien2; date_add($i, date_interval_create_from_date_string("1 day")))
        {
            if(date_format($i, "w") == 0)
            {
                continue;
            }
            
            $data = date_format($i, "Y-m-d");
            
            foreach($pracownicy as $p)
            {
                $query = mysqli_query($conn,"SELECT COUNT(Data) FROM godzinypracy WHERE IDPracownika = $p AND Data='$data'");                
                $row=mysqli_fetch_row($query)[0];

                if($row == 0)
                {
                    mysqli_query($conn,"INSERT INTO godzinypracy (Data, Od, Do, IDPracownika) VALUES ('$data', '$godzina1', '$godzina2', $p)");
                }
                else
                {
                    mysqli_query($conn,"UPDATE godzinypracy SET Od='$godzina1', Do='$godzina2' WHERE IDPracownika=$p AND Data='$data'");
                }
            }
        }
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
            <div class="godzPracy">
                <form action="godzPracy.php" method="post">
                    <h2>Ustaw godziny pracy</h2>
                    <input type="date" name="dzien1" id=""> - <input type="date" name="dzien2" id="">
                    <input type="time" name="godzina1" value="08:00"> - <input type="time" name="godzina2" value="16:00">
                    <?php
                        $query = mysqli_query($conn,"SELECT * FROM uzytkownicy WHERE Rola=1");
                        while($row=mysqli_fetch_row($query))
                        {
                            echo "<label><input type='checkbox' name='pracownik[]' value='$row[0]'> $row[1] $row[2]</label>";
                        }
                    ?>
                    <input type="submit" value="Ustaw godziny pracy">
                </form>
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