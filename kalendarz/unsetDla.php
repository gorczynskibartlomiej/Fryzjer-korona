<?php
session_start();
$zarezerwujDla=$_SESSION["zarezerwujDla"]??null;
if($zarezerwujDla != null)
{
    unset($_SESSION["zarezerwujDla"]);
    header("Location: uslugi.php");
}
else
{
    header("Location: uslugi.php");
}
exit();
?>