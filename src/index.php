<?php
include("./utils/getUrl.php");
include("./utils/dbConnection.php");
include("./auth/auth.php");

function redirect($url){
    echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
}

$getAllSongsQuery = "SELECT songs.id, songs.title title, songs.filePath audio. songs.imgPath img, singers.name singerName, singers.id singerID
                    FROM songs LEFT JOIN singers ON singers.id = songs.singerID
                    ORDER BY songs.dateAdded DESC";

$result = mysqli_query($conn, $getAllSongsQuery);
$songs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>