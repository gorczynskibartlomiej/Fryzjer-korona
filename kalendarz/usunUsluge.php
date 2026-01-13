<?php
    //łączenie z bazą
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, "Fryzjer"); 

    session_start();
    $status = $_SESSION['status']??false;
    $email = $_SESSION['email']??null;
    $rola = $_SESSION['rola']??2;

    $id=$_GET['usun']??null;

    if($id==null || !$status || $rola != 0) //sprawdzenie czy zalogowany użytkownik jest adminem
    {
        mysqli_close($conn);
        header("Location: ../index.php");
        exit();
    }

    $query = mysqli_query($conn,"SELECT COUNT(ID) FROM Uslugi WHERE ID=$id"); //sprawdzenie czy istnieje taka usługa
    $row=mysqli_fetch_row($query)[0];
    if($row==1)
    {
        mysqli_query($conn,"DELETE FROM Uslugi WHERE ID=$id");
        $_SESSION["usunietoUsluge"] = true;
    }
    
    mysqli_close($conn);
    header("Location: ../edytujUslugi.php");
    exit();

?>