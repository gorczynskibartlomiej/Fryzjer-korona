<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");
    
    $dodajDoBazy = true;

    $id=$_GET['id']??null;

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;
    if(!$status || $rola!=0 || $id==null)
    {
        mysqli_close($conn);
        header("Location: ../index.php");
        exit();
    }

    $query = mysqli_query($conn,"SELECT * FROM uzytkownicy WHERE ID = $id");                
    $row=mysqli_fetch_row($query);

    $emailPracownik=$_POST["email"] ?? $row[3];
    $imie=$_POST["imie"] ?? $row[1];
    $nazwisko=$_POST["nazwisko"] ?? $row[2];
    $tel=$_POST["tel"] ?? $row[5];
    $pesel=$_POST["pesel"] ?? $row[9];
    $adres=$_POST["adres"] ?? $row[6];
    $kodPocztowy=$_POST["kodPocztowy"] ?? $row[7];
    $miejscowosc=$_POST["miejscowosc"] ?? $row[8];
    @$form=$_POST["form"]??false;
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
                <a href='../edytujGodzPracyKalendarz.php'><li>Zmień godziny pracy</li></a>
                </ul>
                </div>";
            }
        ?>
        <div class="content">
            <div class="logowanie">
                <form action="../editUser.php/?id=<?php echo $id;?>" method="post">
                    <h2>Edytowanie danych pracownika</h2>
                    <input type="hidden" name="form" value="true">
                    <p class="logpanel"><div class="logpanel-co">Imie</div><div class="logpanel-input"><input type="text" name="imie" placeholder="Imie" value="<?php echo $imie;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if (!empty($imie) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($imie)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Nazwisko</div><div class="logpanel-input"><input type="text" name="nazwisko" placeholder="Nazwisko" value="<?php echo $nazwisko;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if (!empty($nazwisko) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($nazwisko)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Telefon</div><div class="logpanel-input"><input type="tel" name="tel" placeholder="Telefon" value="<?php echo $tel;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if (!empty($tel) && !preg_match("/^(\+[0-9][0-9])?[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", test_input($tel)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Email</div><div class="logpanel-input"><input type="text" name="email" placeholder="Email" value="<?php echo $emailPracownik;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if($emailPracownik != null)
                            {
                                if(filter_var($emailPracownik, FILTER_VALIDATE_EMAIL))
                                {
                                    $query = mysqli_query($conn,"SELECT COUNT(Email) FROM uzytkownicy WHERE Email='$emailPracownik' AND ID!=$id");
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
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Pesel</div><div class="logpanel-input"><input type="text" name="pesel" placeholder="Pesel" value="<?php echo $pesel;?>" required></div></p>
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
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Adres</div><div class="logpanel-input"><input type="text" name="adres" placeholder="Adres" value="<?php echo $adres;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if (!empty($adres) && !preg_match("/^[a-zA-Z0-9.ąęłśćźżóńĄĘŁŚĆŻŹÓŃ\/' ]*$/", test_input($adres)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Miejscowość</div><div class="logpanel-input"><input type="text" name="miejscowosc" placeholder="Miejscowosc" value="<?php echo $miejscowosc;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if (!empty($miejscowosc) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($miejscowosc)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><div class="logpanel-co">Kod pocztowy</div><div class="logpanel-input"><input type="text" name="kodPocztowy" placeholder="Kod pocztowy XX-XXX" value="<?php echo $kodPocztowy;?>" required></div></p>
                    <p class="bledne">
                        <?php
                            if (!empty($kodPocztowy) && !preg_match("/^[0-9][0-9]\-[0-9][0-9][0-9]$/", test_input($kodPocztowy)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="submit" value="Zapisz"></p>
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
    <script src="../JavaScript.js"></script>
</body>
</html>
<?php
    if($dodajDoBazy && $form && $emailPracownik!=null && $imie!=null && $nazwisko!=null && $tel!=null && $pesel!=null && $adres!=null && $miejscowosc!=null && $kodPocztowy!=null)
    {
        mysqli_query($conn,"UPDATE uzytkownicy SET Imie='$imie', Nazwisko='$nazwisko', Telefon='$tel', Email='$emailPracownik', Pesel='$pesel', Adres='$adres', Miejscowosc='$miejscowosc', kodPocztowy='$kodPocztowy' WHERE ID=$id");
        
        $query = mysqli_query($conn,"SELECT concat(Imie, ' ', Nazwisko) FROM uzytkownicy WHERE ID=$id");
        $uzytkownik=mysqli_fetch_row($query)[0];
        $_SESSION["edytowanyPracownik"] = $uzytkownik;
    }
    
    mysqli_close($conn);

    if(isset($_SESSION["edytowanyPracownik"]))
    {
        $edytowanyPracownik = $_SESSION['edytowanyPracownik'];
        echo "<div class='informacja' id='komunikat' onclick='zamknij".'("komunikat")'."'>
                <h2>Dane użytkownika $edytowanyPracownik zostały zmienione</h2>
                <p id='p'>Komunikat zniknie automatycznie za 5</p>
            </div>";              
        unset($_SESSION["edytowanyPracownik"]);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>