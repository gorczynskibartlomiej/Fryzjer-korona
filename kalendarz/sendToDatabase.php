<?php
	session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;

    if(!$status)
    {
        header("Location: ../log.php");
        exit();
    }
    
    $usluga = $_GET["usluga"];
    $data = $_GET["data"];
    $godzina = $_GET["godzina"];
    $fryzjer = $_GET["fryzjer"];

    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    $query = mysqli_query($conn,"SELECT ID FROM uzytkownicy WHERE email='$email'");
    //sprawdzenie czy pracownik rezerwuje dla klienta
    if(isset($_SESSION["zarezerwujDla"]))
    {
        $klient = $_SESSION["zarezerwujDla"];
        unset($_SESSION["zarezerwujDla"]);
    }
    else
    {
        $klient = mysqli_fetch_row($query)[0];
    }

    

    $query = mysqli_query($conn,"SELECT CzasTrwania FROM uslugi WHERE ID='$usluga'");
    $czasUslugi = mysqli_fetch_row($query)[0];

    $query = mysqli_query($conn, "SELECT count(r.ID) FROM rezerwacja r, uslugi u 
    WHERE r.Usluga=u.ID AND Data='$data' AND Pracownik=$fryzjer AND addtime(Godzina, addtime('-$czasUslugi', '00:20:00')) <= '$godzina' AND addtime(r.Godzina, u.CzasTrwania) > '$godzina'");
    $row = mysqli_fetch_row($query)[0];

    //sprawdzenie czy dana godzina jest jeszcze dostępna
    if($row==0)
    {
        mysqli_query($conn,"INSERT INTO rezerwacja (Pracownik, Data, Godzina, Klient, Usluga, Potwierdzona) VALUES ($fryzjer, '$data', addtime('$godzina', '0:0:0'), $klient, $usluga, false)");
        $_SESSION['sukces'] = true;
    }
    else
    {
        $_SESSION['sukces'] = false;
    }

    mysqli_close($conn);
    
    header("Location: ../uslugi.php");
    exit();
?>