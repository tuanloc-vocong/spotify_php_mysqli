<?php
include("./dbConnection.php");
if(isset($_GET['singerId'])){
    $singerId = $_GET['singerId'];
    $singerFilterQuery = "SELECT * FROM singers WHERE id = $singerId";
    $result = mysqli_query($conn, $singerFilterQuery);
    $singer = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $songsQuery = "SELECT songs.id, songs.title title, songs.filePath audio, songs.imgPath img, singers.name singerName, singers.id singerId
                    FROM songs LEFT JOIN singers ON singers.id = songs.singerId
                    WHERE singers.id = $singerId ORDER BY songs.dateAdded DESC";
    $result2 = mysqli_query($conn, $songsQuery);
    $songs = mysqli_fetch_all($result2, MYSQLI_ASSOC);

    $singer['songs'] = $songs;
    echo json_decode($singer);
}
?>