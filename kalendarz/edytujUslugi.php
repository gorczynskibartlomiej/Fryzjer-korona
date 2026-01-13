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

    $wyslijDoBazy = $_POST["wyslijDoBazy"]??0;
    $querryType = $_POST["querryType"]??null;
    $idUslugi = $_POST["idUslugi"]??null;
    $nazwa = $_POST["nazwa"]??null;
    $czasTrwania = $_POST["czasTrwania"]??null;
    $koszt = $_POST["koszt"]??null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zakład fryzjerski</title>
    <link rel="stylesheet" href="style.css">
</head>
<body <?php if($_SESSION["usunietoUsluge"]??false) {echo "onload=\"usunUslugePowiadomienie()\""; unset($_SESSION["usunietoUsluge"]); }?> >
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
            <div class="edytujGodzContent">
                <h2>Edytuj dostępne usługi</h2>
                <?php
                    $query = mysqli_query($conn,"SELECT ID, NazwaUslugi, (LEFT(CzasTrwania,  2)*60+LEFT(RIGHT(CzasTrwania, 5), 2)), Koszt FROM Uslugi;");
                    while($row=mysqli_fetch_row($query))
                    {
                        echo "<div class='edytujUsluge'>
                                <form action='edytujUslugi.php' method='post'>
                                    <input type='hidden' name='querryType' value='UPDATE'>
                                    <input type='hidden' name='wyslijDoBazy' value='true'>
                                    <input type='hidden' name='idUslugi' value='$row[0]'>
                                    <input type='text' name='nazwa' value='$row[1]'>
                                    <input type='number' name='czasTrwania' value='$row[2]' required> minut
                                    <input type='number' name='koszt' value='".round($row[3],0)."' required> zł
                                    <input type='button' value='Usuń' onclick='usunUsluge($row[0], \"$row[1]\")'>
                                    <input type='submit' value='Zapisz'>
                                </form>";
                                if (!empty($nazwa) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($nazwa)) && $row[0]==$idUslugi)
                                {
                                    echo "Błędne dane";
                                    $wyslijDoBazy = false;
                                }
                                elseif(($czasTrwania<10 || $koszt<0) && $row[0]==$idUslugi)
                                {
                                    echo "Błędne dane";
                                    $wyslijDoBazy = false;
                                }
                            echo "</div>";
                    }
                ?>
                <div class='edytujUsluge'>
                    <form action='edytujUslugi.php' method='post'>
                        <input type='hidden' name='querryType' value='INSERT'>
                        <input type='hidden' name='wyslijDoBazy' value='true'>
                        <input type='hidden' name='idUslugi' value=''>
                        <input type='text' name='nazwa' value=''>
                        <input type='number' name='czasTrwania' value='' required> minut
                        <input type='number' name='koszt' value='' required> zł
                        <input type='submit' value='Dodaj'>
                    </form>
                    <?php
                        if (!empty($nazwa) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($nazwa)) && $querryType=="INSERT")
                        {
                            echo "Błędne dane";
                            $wyslijDoBazy = false;
                        }
                        elseif(($czasTrwania<10 || $koszt<0) && $querryType=="INSERT")
                        {
                            echo "Błędne dane";
                            $wyslijDoBazy = false;
                        }
                    ?>
                </div>
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
    $czasTrwania = round(($czasTrwania/20),0)*20;
    $czasTrwaniaBaza = floor(($czasTrwania/60)).":".($czasTrwania%60).":00";

    if($wyslijDoBazy && $idUslugi!=null && $nazwa!=null && $czasTrwania!=null && $koszt!=null && $querryType=="UPDATE")
    {
        mysqli_query($conn,"UPDATE Uslugi SET NazwaUslugi='$nazwa', CzasTrwania=addtime('$czasTrwaniaBaza', '0:0:0'), Koszt=$koszt WHERE ID=$idUslugi");
        $_SESSION["edycjaUslug"] = true;
    }
    if($wyslijDoBazy && $idUslugi==null && $nazwa!=null && $czasTrwania!=null && $koszt!=null && $querryType=="INSERT")
    {
        mysqli_query($conn,"INSERT INTO Uslugi (NazwaUslugi, CzasTrwania, Koszt) VALUES ('$nazwa', addtime('$czasTrwaniaBaza', '0:0:0'), $koszt)");
        $_SESSION["edycjaUslug"] = true;
    }
    if($wyslijDoBazy && $idUslugi!=null && $nazwa==null && $querryType=="UPDATE")
    {
        mysqli_query($conn,"DELETE FROM Uslugi WHERE ID=$idUslugi");
        $_SESSION["edycjaUslug"] = true;
    }

    mysqli_close($conn);

    if(isset($_SESSION["edycjaUslug"]))
    {
        if($_SESSION["edycjaUslug"])
        {
            echo "<div class='informacja' id='komunikat'  onclick='przenies()'>
                <h2>Lista usług została zmodyfikowana</h2>
                <p id='p'>Kliknij aby zamknąć</p>
            </div>";
        }
        unset($_SESSION["edycjaUslug"]);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>