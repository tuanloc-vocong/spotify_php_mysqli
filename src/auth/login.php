<?php
include("../utils/dbConnection.php");

session_start();
if(isset($_SESSION['id'])){
    header("Location: ../index.php");
}
$userName = $password = "";
$errors = array('userName' => '', 'passwpord' => '');

if(isset($_POST['submit'])){
    function cleanData($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $userName = cleanData($_POST['userName']);
    $password = cleanData($_POST['password']);

    if(empty($userName)){
        $errors['userName']
    }
}
?>