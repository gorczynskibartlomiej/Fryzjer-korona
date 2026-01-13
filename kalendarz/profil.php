<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");
    
    $dodajDoBazy = true;

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
    if(!$status)
    {
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    }

    $form=$_POST["form"]??null;

    $pass1=$_POST["pass1"]??null;
    $pass2=$_POST["pass2"]??null;
    $pass3=$_POST["pass3"]??null;

    $query = mysqli_query($conn,"SELECT * FROM uzytkownicy WHERE Email = '$email'");
    $row=mysqli_fetch_row($query);

    $id=$row[0];
    $emailForm=$_POST["email"] ?? $row[3];
    $imie=$_POST["imie"] ?? $row[1];
    $nazwisko=$_POST["nazwisko"] ?? $row[2];
    $tel=$_POST["tel"] ?? $row[5];
    $pesel=$_POST["pesel"] ?? $row[9];
    $adres=$_POST["adres"] ?? $row[6];
    $kodPocztowy=$_POST["kodPocztowy"] ?? $row[7];
    $miejscowosc=$_POST["miejscowosc"] ?? $row[8];

    $query = mysqli_query($conn,"SELECT Haslo from uzytkownicy WHERE ID=$id");
    $stareHaslo=mysqli_fetch_row($query)[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zakład fryzjerski</title>
    <link rel="stylesheet" href="style.css">
</head>
<body onload='powiadomienie("komunikat")'>
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
            <div class="profil-main">
                <div class="edycjaProfilu">
                    <form action="profil.php" method="post">
                        <h2>Zmień hasło</h2>
                        <input type="hidden" name="form" value="haslo">
                        <p class="logpanel"><input type="password" name="pass1" placeholder="Stare hasło" required ></p>
                        <p class="logpanel"><input type="password" name="pass2" placeholder="Nowe hasło" required ></p>
                        <p class="logpanel"><input type="password" name="pass3" placeholder="Powtórz nowe hasło" required ></p>
                        <p class="bledne">
                            <?php
                                if($form=="haslo")
                                {
                                    if($stareHaslo != $pass1 || empty($pass1) || empty($pass2) || empty($pass3) || !preg_match("/^[a-zA-Z0-9!@' ]*$/", test_input($pass1)) || !preg_match("/^[a-zA-Z0-9!@' ]*$/", test_input($pass2)) || !preg_match("/^[a-zA-Z0-9!@' ]*$/", test_input($pass3)))
                                    {
                                        echo "Błędne dane";
                                        $dodajDoBazy = false;
                                    }
                                    elseif($pass2 != $pass3)
                                    {
                                        echo "Hasła nie są identyczne";
                                        $dodajDoBazy = false;
                                    }
                                    else echo "";
                                }                                
                            ?>
                        </p>
                        <p class="logpanel"><input type="submit" value="Zmień hasło"></p>
                    </form>
                </div>
                <div class="edycjaProfilu">
                    <form action="profil.php" method="post">
                        <h2>Edytowanie danych osobowych</h2>
                        <input type="hidden" name="form" value="dane">
                        <p class="logpanel"><div class="logpanel-co">Imie</div><div class="logpanel-input"><input type="text" name="imie" placeholder="Imie" value="<?php echo $imie;?>" required></div></p>
                        <p class="bledne">
                            <?php
                                if ($form=="dane" && !empty($imie) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($imie)))
                                {
                                    echo "Niepoprawne dane";
                                    $dodajDoBazy = false;
                                }
                            ?>
                        </p>
                        <p class="logpanel"><div class="logpanel-co">Nazwisko</div><div class="logpanel-input"><input type="text" name="nazwisko" placeholder="Nazwisko" value="<?php echo $nazwisko;?>" required></div></p>
                        <p class="bledne">
                            <?php
                                if ($form=="dane" && !empty($nazwisko) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($nazwisko)))
                                {
                                    echo "Niepoprawne dane";
                                    $dodajDoBazy = false;
                                }
                            ?>
                        </p>
                        <p class="logpanel"><div class="logpanel-co">Telefon</div><div class="logpanel-input"><input type="tel" name="tel" placeholder="Telefon" value="<?php echo $tel;?>" required></div></p>
                        <p class="bledne">
                            <?php
                                if ($form=="dane" && !empty($tel) && !preg_match("/^(\+[0-9][0-9])?[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", test_input($tel)))
                                {
                                    echo "Niepoprawne dane";
                                    $dodajDoBazy = false;
                                }
                            ?>
                        </p>
                        <p class="logpanel"><div class="logpanel-co">Email</div><div class="logpanel-input"><input type="text" name="email" placeholder="Email" value="<?php echo $emailForm;?>" required></div></p>
                        <p class="bledne">
                            <?php
                                if($form=="dane")
                                {
                                    if($emailForm != null)
                                    {
                                        if(filter_var($emailForm, FILTER_VALIDATE_EMAIL))
                                        {
                                            $query = mysqli_query($conn,"SELECT COUNT(Email) FROM uzytkownicy WHERE Email='$emailForm' AND ID!=$id");
                                            $row=mysqli_fetch_row($query)[0];
                                    
                                            if($row!=0)
                                            {
                                                echo "Podany adres email jest już zajęty";
                                                $dodajDoBazy = false;
                                            }
                                        }
                                        else
                                        {
                                            echo "Email jest niepoprawny";
                                            $dodajDoBazy = false;
                                        }
                                    }
                                    else
                                    {
                                        $dodajDoBazy = false;
                                    }
                                }
                            ?>
                        </p>
                        <?php if($rola!=2):?>
                            <p class="logpanel"><div class="logpanel-co">Pesel</div><div class="logpanel-input"><input type="text" name="pesel" placeholder="Pesel" value="<?php echo $pesel;?>" required></div></p>
                            <p class="bledne">
                                <?php
                                    if($form=="dane")
                                    {

                                        if($pesel != null)
                                        {
                                            if (!empty($pesel) && !preg_match("/^[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", test_input($pesel)))
                                            {
                                                echo "Niepoprawne dane";
                                                $dodajDoBazy = false;
                                            }
                                            else
                                            {
                                                $query = mysqli_query($conn,"SELECT COUNT(Pesel) FROM uzytkownicy Where Pesel='$pesel AND ID!=$id'");
                                                $row=mysqli_fetch_row($query)[0];
                                    
                                                if($row!=0)
                                                {
                                                    echo "Pracownik o takim peselu jest już w bazie";
                                                    $dodajDoBazy = false;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $dodajDoBazy = false;
                                        }
                                    }
                                ?>
                            </p>
                            <p class="logpanel"><div class="logpanel-co">Adres</div><div class="logpanel-input"><input type="text" name="adres" placeholder="Adres" value="<?php echo $adres;?>" required></div></p>
                            <p class="bledne">
                                <?php
                                    if ($form=="dane" && !empty($adres) && !preg_match("/^[a-zA-Z0-9.ąęłśćźżóńĄĘŁŚĆŻŹÓŃ\/' ]*$/", test_input($adres)))
                                    {
                                        echo "Niepoprawne dane";
                                        $dodajDoBazy = false;
                                    }
                                ?>
                            </p>
                            <p class="logpanel"><div class="logpanel-co">Miejscowość</div><div class="logpanel-input"><input type="text" name="miejscowosc" placeholder="Miejscowosc" value="<?php echo $miejscowosc;?>" required></div></p>
                            <p class="bledne">
                                <?php
                                    if ($form=="dane" && !empty($miejscowosc) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($miejscowosc)))
                                    {
                                        echo "Niepoprawne dane";
                                        $dodajDoBazy = false;
                                    }
                                ?>
                            </p>
                            <p class="logpanel"><div class="logpanel-co">Kod pocztowy</div><div class="logpanel-input"><input type="text" name="kodPocztowy" placeholder="Kod pocztowy XX-XXX" value="<?php echo $kodPocztowy;?>" required></div></p>
                            <p class="bledne">
                                <?php
                                    if ($form=="dane" && !empty($kodPocztowy) && !preg_match("/^[0-9][0-9]\-[0-9][0-9][0-9]$/", test_input($kodPocztowy)))
                                    {
                                        echo "Niepoprawne dane";
                                        $dodajDoBazy = false;
                                    }
                                ?>
                            </p>
                        <?php endif;?>
                        <p class="logpanel"><input type="submit" value="Zapisz"></p>
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
    <script src="JavaScript.js"></script>   
</body>
</html>
<?php
    if($form=="haslo")
    {
        mysqli_query($conn,"UPDATE uzytkownicy SET Imie='$imie', Nazwisko='$nazwisko', Telefon='$tel', Email='$emailForm', Pesel='$pesel', Adres='$adres', Miejscowosc='$miejscowosc', kodPocztowy='$kodPocztowy' WHERE ID=$id");
        if($dodajDoBazy && $stareHaslo == $pass1 && $pass2==$pass3 && $pass1 != null && $pass2 != null && $pass3 != null)
        {
            mysqli_query($conn,"UPDATE uzytkownicy SET Haslo='$pass2' WHERE ID=$id");
            $_SESSION['zmienionoProfil'] = 1;
        }
    }
    if($form=="dane")
    {
        if($dodajDoBazy && $emailForm!=null && $imie!=null && $nazwisko!=null && $tel!=null)
        {
            if($rola!=2 && $pesel!=null && $adres!=null && $miejscowosc!=null && $kodPocztowy!=null)
            {
                mysqli_query($conn,"UPDATE uzytkownicy SET Imie='$imie', Nazwisko='$nazwisko', Telefon='$tel', Email='$emailForm', Pesel='$pesel', Adres='$adres', Miejscowosc='$miejscowosc', kodPocztowy='$kodPocztowy' WHERE ID=$id");
                $_SESSION['zmienionoProfil'] = 2;
            }
            else
            {
                mysqli_query($conn,"UPDATE uzytkownicy SET Imie='$imie', Nazwisko='$nazwisko', Telefon='$tel', Email='$emailForm' WHERE ID=$id");
                $_SESSION['zmienionoProfil'] = 2;
            }
        }
        if($email != $emailForm) $_SESSION['email'] = $emailForm;
    }

    if(isset($_SESSION['zmienionoProfil']))
    {
        if($_SESSION['zmienionoProfil'] == 1)
        {
            echo "<div class='informacja' id='komunikat' onclick='zamknij".'("komunikat")'."'>
                <h2>Twoje hasło zostało zmienione</h2>
                <p id='p'>Komunikat zniknie automatycznie za 5</p>
            </div>";
        }
        else
        {
            echo "<div class='informacja' id='komunikat' onclick='zamknij".'("komunikat")'."' >
                <h2>Twoje dane osobowe zostały zapisane</h2>
                <p id='p'>Komunikat zniknie automatycznie za 5</p>
            </div>";
        }
        unset($_SESSION['zmienionoProfil']);
    }
    mysqli_close($conn);

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>