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

    $dzien=$_GET['dzien']??null;
    $miesiac=$_GET['miesiac']??null;
    $rok=$_GET['rok']??null;
    $querryType=$_POST['querryType']??null;
    $wyslij=$_POST['wyslij']??false;
    $wolny=$_POST['wolny']??false;
    $id=$_POST['id']??null;
    $od=$_POST['od']??null;
    $do=$_POST['do']??null;

    if(strlen($od)==5) $od.=":00";
    if(strlen($do)==5) $do.=":00";

    $data = "$dzien-$miesiac-$rok";
    $dataBaza = "$rok-$miesiac-$dzien";

    $query = mysqli_query($conn,"SELECT COUNT(ID) FROM uzytkownicy WHERE Rola=1");
    @$pracownicy[mysqli_fetch_row($query)[0]];
    $licznikPracownikow = 0;

    $query = mysqli_query($conn,"SELECT ID FROM uzytkownicy WHERE Rola=1");
    while($row=mysqli_fetch_row($query))
    {
        @$pracownicy[$licznikPracownikow] = $row[0];
        $licznikPracownikow++;
    }

    if($od>$do)
    {
        $wyslij = false;
    }
    if($wyslij && $wolny)
    {
        mysqli_query($conn,"DELETE FROM rezerwacja WHERE Data='$dataBaza'");
        mysqli_query($conn,"DELETE FROM godzinypracy WHERE Data='$dataBaza'");
        $_SESSION["edycjaGodzinPracy"] = true;
    }
    if($wyslij && $od!=null && $do!=null && $id!=null)
    {
        if($querryType=="UPDATE")
        {
            mysqli_query($conn,"DELETE FROM rezerwacja WHERE Godzina<'$od' AND Data='$dataBaza' AND Pracownik=$id");
            mysqli_query($conn,"DELETE FROM rezerwacja WHERE Godzina>'$do' AND Data='$dataBaza' AND Pracownik=$id");
            mysqli_query($conn,"UPDATE godzinypracy SET Od='$od', Do='$do' WHERE Data='$dataBaza' AND IDPracownika=$id");
            $_SESSION["edycjaGodzinPracy"] = true;
        }
        if($querryType=="INSERT")
        {
            mysqli_query($conn,"INSERT INTO godzinypracy (Data, Od, Do, IDPracownika) VALUES ('$dataBaza', '$od', '$do', $id)");
            $_SESSION["edycjaGodzinPracy"] = true;
        }
    }
    if($wyslij && $od == $do && $id!=null)
    {
        mysqli_query($conn,"DELETE FROM rezerwacja WHERE Data='$dataBaza' AND Pracownik=$id");
        mysqli_query($conn,"DELETE FROM godzinypracy WHERE Data='$dataBaza' AND IDPracownika=$id");
        $_SESSION["edycjaGodzinPracy"] = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zakład fryzjerski</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body onload='powiadomienie("komunikat")'>
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
                <a href='edytujGodzPracyKalendarz.php'><li>Zmień godziny pracy</li></a>
                </ul>
                </div>";
            }
        ?>
        <div class="content">
            <div class="edytujGodzContent">
                <h2>Ustaw godziny pracy</h2>
                <p><?php echo $data; ?></p>
                <?php
                    foreach($pracownicy as $p)
                    {
                        $query = mysqli_query($conn,"SELECT COUNT(u.ID) FROM godzinypracy gp, uzytkownicy u WHERE u.ID=gp.IDPracownika AND Data='$dataBaza' AND IDPracownika=$p");
                        $row=mysqli_fetch_row($query);

                        if($row[0]==1)
                        {
                            $query = mysqli_query($conn,"SELECT u.ID, Imie, Nazwisko, Od, Do FROM godzinypracy gp, uzytkownicy u WHERE u.ID=gp.IDPracownika AND Data='$dataBaza' AND IDPracownika=$p");
                            while($row=mysqli_fetch_row($query))
                            {
                                echo "
                                    <div class='edytujGodz'>
                                        <form action='../edytujGodzPracy.php/?dzien=$dzien&miesiac=$miesiac&rok=$rok' method='post'>
                                            <input type='hidden' name='querryType' value='UPDATE'>
                                            <input type='hidden' name='wyslij' value='true'>
                                            <input type='hidden' name='id' value='$row[0]'>
                                            <div class='edytujGodz-pracownik'>$row[1] $row[2]</div>
                                            <div class='edytujGodz-godziny'><input type='time' name='od' value='$row[3]'> - <input type='time' name='do' value='$row[4]'></div>
                                            <div class='edytujGodz-przycisk'><input type='submit' value='Zapisz'></div>
                                        </form>
                                    </div>";
                            }
                        }
                        else
                        {
                            $query = mysqli_query($conn,"SELECT ID, Imie, Nazwisko FROM uzytkownicy WHERE ID=$p");
                            while($row=mysqli_fetch_row($query))
                            {
                                echo "
                                    <div class='edytujGodz'>
                                        <form action='../edytujGodzPracy.php/?dzien=$dzien&miesiac=$miesiac&rok=$rok' method='post'>
                                            <input type='hidden' name='querryType' value='INSERT'>
                                            <input type='hidden' name='wyslij' value='true'>
                                            <input type='hidden' name='id' value='$row[0]'>
                                            <div class='edytujGodz-pracownik'>$row[1] $row[2]</div>
                                            <div class='edytujGodz-godziny'><input type='time' name='od' value=''> - <input type='time' name='do' value=''></div>
                                            <div class='edytujGodz-przycisk'><input type='submit' value='Zapisz'></div>
                                        </form>
                                    </div>";
                            }
                        }
                        
                    }
                ?>
                <div class='edytujGodz'>
                    <form action='../edytujGodzPracy.php/<?php echo "?dzien=$dzien&miesiac=$miesiac&rok=$rok";?>' method='post'>
                        <?php
                        echo "  <input type='hidden' name='dzien' value='$dzien'>
                                <input type='hidden' name='miesiac' value='$miesiac'>
                                <input type='hidden' name='rok' value='$rok'>";
                        ?>
                        <input type='hidden' name='wolny' value='true'>
                        <input type='hidden' name='wyslij' value='true'>
                        <div class='edytujGodz-wolny'><input type='submit' value='Ustaw dzień jako wolny'></div>
                    </form>
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
    <script src="../JavaScript.js"></script>
</body>
</html>
<?php
    

    mysqli_close($conn);

    if(isset($_SESSION["edycjaGodzinPracy"]))
    {
        if($_SESSION["edycjaGodzinPracy"])
        {
            echo "<div class='informacja' id='komunikat' onclick='zamknij".'("komunikat")'."'>
                <h2>Godziny pracy zostały zmodyfikowane</h2>
                <p id='p'>Komunikat zniknie automatycznie za 5</p>
            </div>";
        }
        unset($_SESSION["edycjaGodzinPracy"]);
    }
?>