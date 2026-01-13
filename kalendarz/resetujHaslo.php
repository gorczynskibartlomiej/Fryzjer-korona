<?php

    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;

    $id=$_GET['id']??null;
    $u=$_GET['u']??null;

    if($id==null || $u==null || !$status || $rola == 2) //sprawdzenie czy użytkownik nie jest klientem
    {
        mysqli_close($conn);
        header("Location: ../index.php");
        exit();
    }

    $query = mysqli_query($conn,"SELECT COUNT(ID) FROM uzytkownicy WHERE ID=$id");
    $row=mysqli_fetch_row($query)[0];

    if($u==1) $noweHaslo = "Fryzjer123!@";
    else $noweHaslo = "Klient123!@";

    if($row==1)
    {
        mysqli_query($conn,"UPDATE uzytkownicy SET Haslo='$noweHaslo' WHERE ID=$id");

        $query = mysqli_query($conn,"SELECT concat(Imie, ' ', Nazwisko) FROM uzytkownicy WHERE ID=$id");
        $uzytkownik=mysqli_fetch_row($query)[0];
        $_SESSION["hasloUzytkownika"] = $uzytkownik;
    }

    mysqli_close($conn);
    if($u==1)
    {
        header("Location: ../zarzadzanie.php");
        exit();
    }
    else
    {
        header("Location: ../klienci.php");
        exit();
    }
?>