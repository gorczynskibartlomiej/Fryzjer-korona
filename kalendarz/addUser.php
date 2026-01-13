<?php
    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
    if(!$status || $rola==2)
    {
        header("Location: index.php");
        exit();
    }

    if(isset($_POST["rolaUzytkownika"])) 
    {
        $rolaUzytkownika = $_POST["rolaUzytkownika"];
    }
    else
    {
        $rolaUzytkownika = $_GET["u"]??null;
        if($rolaUzytkownika != null)
        {
            $_SESSION["rolaUzytkownika"] = $_GET["u"];
            header("Location: ../addUser.php");
            exit();
        }
        if(isset($_SESSION["rolaUzytkownika"]))
        {
            $rolaUzytkownika = $_SESSION["rolaUzytkownika"];
            unset($_SESSION["rolaUzytkownika"]);
        }
        else $rolaUzytkownika = 1;
    }

    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    $emailPracownik=$_POST["email"] ?? null;
    $imie=$_POST["imie"] ?? null;
    $nazwisko=$_POST["nazwisko"] ?? null;
    $tel=$_POST["tel"] ?? null;
    $pesel=$_POST["pesel"] ?? null;
    $adres=$_POST["adres"] ?? null;
    $kodPocztowy=$_POST["kodPocztowy"] ?? null;
    $miejscowosc=$_POST["miejscowosc"] ?? null;
    
    $dodajDoBazy = true;
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
            <div class="logowanie">
                <form action="addUser.php" method="post">
                    <h2>Dodawanie <?php if($rolaUzytkownika==1) echo "pracownika"; else echo "klienta"; ?></h2>
                    <input type="hidden" name="rolaUzytkownika" value="<?php echo $rolaUzytkownika; ?>">
                    <p class="logpanel"><input type="text" name="imie" placeholder="Imie" value="<?php echo $imie;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($imie) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($imie)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="text" name="nazwisko" placeholder="Nazwisko" value="<?php echo $nazwisko;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($nazwisko) && !preg_match("/^[a-zA-Z-ąęłśćźżóń' ]*$/", test_input($nazwisko)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="tel" name="tel" placeholder="Telefon" value="<?php echo $tel;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($tel) && !preg_match("/^(\+[0-9][0-9])?[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", test_input($tel)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="text" name="email" placeholder="Email" value="<?php echo $emailPracownik;?>"></p>
                    <p class="bledne">
                        <?php
                            if($emailPracownik != null)
                            {
                                if(filter_var($emailPracownik, FILTER_VALIDATE_EMAIL))
                                {
                                    $query = mysqli_query($conn,"SELECT COUNT(Email) FROM uzytkownicy WHERE Email='$emailPracownik'");
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
                            elseif($emailPracownik==null && $rolaUzytkownika!=2)
                            {
                                echo "Email jest wymagany dla pracownika";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel">Hasło: <?php if($rolaUzytkownika==1) echo "Fryzjer123!@"; else echo "Klient123!@"; ?></p>
                    <?php if($rolaUzytkownika==1):?>
                    <p class="logpanel"><input type="text" name="pesel" placeholder="Pesel" value="<?php echo $pesel;?>" required></p>
                    <p class="bledne">
                        <?php
                            
                            if($pesel != null)
                            {
                                if (!empty($pesel) && !preg_match("/^[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", test_input($pesel)))
                                {
                                    echo "Niepoprawne dane";
                                    $dodajDoBazy = false;
                                }
                                else
                                {
                                    $query = mysqli_query($conn,"SELECT COUNT(Pesel) FROM uzytkownicy Where Pesel='$pesel'");
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
                        ?>
                    </p>
                    <p class="logpanel"><input type="text" name="adres" placeholder="Adres" value="<?php echo $adres;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($adres) && !preg_match("/^[a-zA-Z0-9.ąęłśćźżóńĄĘŁŚĆŻŹÓŃ\/' ]*$/", test_input($adres)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="text" name="miejscowosc" placeholder="Miejscowosc" value="<?php echo $miejscowosc;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($miejscowosc) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($miejscowosc)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="text" name="kodPocztowy" placeholder="Kod pocztowy XX-XXX" value="<?php echo $kodPocztowy;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($kodPocztowy) && !preg_match("/^[0-9][0-9]\-[0-9][0-9][0-9]$/", test_input($kodPocztowy)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <?php endif;?>
                    <p class="logpanel"><input type="submit" value="Dodaj"></p>
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
    <script src="JavaScript.js">        
    </script>
</body>
</html>
<?php
    if($dodajDoBazy && $emailPracownik!=null && $imie!=null && $nazwisko!=null && $tel!=null && $rolaUzytkownika==1 && $pesel!=null && $adres!=null && $miejscowosc!=null && $kodPocztowy!=null)
    {
        mysqli_query($conn,"INSERT INTO uzytkownicy (Imie, Nazwisko, Email, Haslo, Telefon, Rola, Pesel, Adres, Miejscowosc, KodPocztowy) VALUES ( '$imie', '$nazwisko', '$emailPracownik', 'Fryzjer123!@', '$tel', $rolaUzytkownika, '$pesel', '$adres', '$miejscowosc', '$kodPocztowy')");
        $_SESSION["pracownik"] = true;
    }
    if($dodajDoBazy && $emailPracownik==null && $imie!=null && $nazwisko!=null && $tel!=null && $rolaUzytkownika==2)
    {
        mysqli_query($conn,"INSERT INTO uzytkownicy (Imie, Nazwisko, Email, Haslo, Telefon, Rola, Pesel, Adres, Miejscowosc, KodPocztowy) VALUES ( '$imie', '$nazwisko', '$emailPracownik', 'Klient123!@', '$tel', $rolaUzytkownika, '$pesel', '$adres', '$miejscowosc', '$kodPocztowy')");
        
        $_SESSION["nowyKlient"] = $imie." ".$nazwisko;
        mysqli_close($conn);
        header("Location: klienci.php");
        exit();
    }
    if(isset($_SESSION["pracownik"]))
    {
        echo "<div class='informacja' id='informacja' onclick='zamknij".'("informacja")'."' >
            <h2>Pracownik został dodany pomyślnie</h2>
            <p id='p'>Komunikat zniknie automatycznie za 5</p>
        </div>";
        unset($_SESSION["pracownik"]);
    }
    
    mysqli_close($conn);

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>