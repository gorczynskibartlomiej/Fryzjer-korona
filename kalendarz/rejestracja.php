<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    $email=$_POST["email"] ?? null;
    $haslo=$_POST["pass"] ?? null;
    $haslo2=$_POST["pass2"] ?? null;
    $imie=$_POST["imie"] ?? null;
    $nazwisko=$_POST["nazwisko"] ?? null;
    $tel=$_POST["tel"] ?? null;
    
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
<body>
    <div class="strona">
        <div class="banner">
            <div class="banner-logo">
                <img src="logo.png" alt="logo"/>
            </div>
            <div class="banner-profile">
                <div class="log">
                    <a href="log.php">Zaloguj</a>
                </div>
            </div>
        </div>
        <div class="nav">
            <ul>
                <a href="index.php"><li>Strona główna</li></a>
                <a href="uslugi.php"><li>Rezerwacja wizyty</li></a>
                <a href="wizyty.php"><li>Umówione wizyty</li></a>
                <a href="kontakt.php"><li>Kontakt</li></a>
            </ul>
        </div>
        <div class="content">
            <div class="logowanie">
                <form action="rejestracja.php" method="post">
                    <h2>Rejestracja</h2>
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
                            if (!empty($nazwisko) && !preg_match("/^[a-zA-Z-ąęłśćźżóńĄĘŁŚĆŻŹÓŃ' ]*$/", test_input($nazwisko)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="tel" name="tel" placeholder="Telefon" value="<?php echo $tel;?>" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($tel) && !preg_match("/^(\+)?[0-9]*$/", test_input($tel)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="text" name="email" placeholder="Email" value="<?php echo $email;?>" required></p>
                    <p class="bledne">
                        <?php
                            if($email != null)
                            {
                                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                                {
                                    $query = mysqli_query($conn,"SELECT COUNT(Email) FROM uzytkownicy WHERE Email='$email'");
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
                    <p class="logpanel"><input type="password" name="pass" placeholder="Hasło" required></p>
                    <p class="bledne">
                        <?php
                            if (!empty($pass) && !preg_match("/^[a-zA-Z0-9!@' ]*$/", test_input($pass)))
                            {
                                echo "Niepoprawne dane";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="password" name="pass2" placeholder="Potwierdz hasło" required></p>
                    <p class="bledne">
                        <?php
                            if($haslo != $haslo2)
                            {
                                echo "Podane hasła nie są identyczne";
                                $dodajDoBazy = false;
                            }
                        ?>
                    </p>
                    <p class="logpanel"><input type="submit" value="Zarejestruj"></p>
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
</body>
</html>
<?php
    //dodwawanie do bazy
    if($dodajDoBazy && $email!=null && $haslo!=null && $imie!=null && $nazwisko!=null && $tel!=null)
    {
        mysqli_query($conn,"INSERT INTO uzytkownicy (Imie, Nazwisko, Email, Haslo, Telefon, Rola) VALUES ( '$imie', '$nazwisko', '$email', '$haslo', '$tel', 2)");

        $query = mysqli_query($conn,"SELECT COUNT(Email) FROM uzytkownicy WHERE Email='$email' AND Haslo='$haslo'");
        $row=mysqli_fetch_row($query)[0];

        if($row == 1)
        {
            $query = mysqli_query($conn,"SELECT Rola FROM uzytkownicy WHERE Email='$email' AND Haslo='$haslo'");
            $row=@mysqli_fetch_row($query)[0];

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['rola'] = $row;
            $_SESSION['status'] = true;

            header("Location: index.php");
            exit();
        }
    }
    mysqli_close($conn);

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>