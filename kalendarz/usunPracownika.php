<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer");

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;

    $id=$_GET['usun']??null;

    if($id==null || !$status || $rola != 0)
    {
        mysqli_close($conn);
        header("Location: ../index.php");
        exit();
    }

    $query = mysqli_query($conn,"SELECT COUNT(ID) FROM uzytkownicy WHERE ID=$id");
    $row=mysqli_fetch_row($query)[0];

    if($row==1)
    {
        mysqli_query($conn,"DELETE FROM uzytkownicy WHERE ID=$id");
        $_SESSION["usunietoPracownika"] = true;
    }
    
    mysqli_close($conn);
    header("Location: ../zarzadzanie.php");
    exit();

?>