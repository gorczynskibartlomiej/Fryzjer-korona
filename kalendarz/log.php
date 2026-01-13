<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    $email=$_POST["email"] ?? null;
    $haslo=$_POST["pass"] ?? null;
    $rola=2;

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
            <div class="banner-profile">
                <div class="log">
                    <a href="log.php">Zaloguj</a>
                </div>
            </div>
        </div>
        <div class="nav">
            <ul>
                <a href="index.php"><li>Strona główna</li></a>
                <a <?php if($rola!=2){ echo "onclick=\"rezerwacja()\"";} else { echo "href=\"uslugi.php\"";} ?> ><li>Rezerwacja wizyty</li></a>
                <a href="wizyty.php"><li>Umówione wizyty</li></a>
                <a href="kontakt.php"><li>Kontakt</li></a>
            </ul>
        </div>
        <div class="content">
            <div class="logowanie">
                <form action="log.php" method="post">
                    <h2>Zaloguj</h2>
                    <p class="logpanel"><input type="text" name="email" placeholder="Email" required></p>
                    <p class="logpanel"><input type="password" name="pass" placeholder="Hasło" required ></p>
                    <p class="bledne">
                        <?php //walidacja emailu i hasła
                            if($email != null || $haslo != null || filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z0-9!@' ]*$/", test_input($haslo))) echo "Nieprawidłowy email lub hasło";
                            else echo "";
                        ?>
                    </p>
                    <p class="logpanel"><input type="submit" value="Zaloguj"></p>
                    <p class="logpanel"><a href="rejestracja.php">Nie masz konta? Zarejestruj sie</a></p>
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

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>