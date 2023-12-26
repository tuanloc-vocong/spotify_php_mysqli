<?php
include("./dbConnection.php");
include("../auth/auth.php");

if(isset($_GET['songId'])){
    $songId = $_GET['songId'];
    $addToFavQuery = "INSERT INTO favourites VALUES ($uid, $songId);";

    if(mysqli_query($conn, $addToFavQuery)){
        echo json_encode($songId);
    }else{
        echo json_encode("Error");
    }
}
?>