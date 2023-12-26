<?php
include("./dbConnection.php");
include("../auth/auth.php");

if(isset($_GET['songId'])){
    $songId = $_GET['songId'];
    $addToFavQuery = "DELETE FROM favourites WHERE favourites.uid = $uid and songId = $songId;";

    if(mysqli_query($conn, $addToFavQuery)){
        echo json_encode("Delete " . $songId);
    }else{
        echo json_encode("Error " . mysqli_error($conn));
    }
}
?>