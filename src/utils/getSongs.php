<?php
include("./dbConnection.php");

if(isset($_GET['filter'])){
    $filterTexts = $_GET['filter'];
    $songsFilterQuery = "SELECT songs.id, songs.title title, songs.filePath audio, songsimgPath img, singers.name singerName, singers.id singerId
                        FROM songs LEFT JOIN singers ON singers.id = songs.singerId
                        WHERE title LIKE '%$filterTexts%' OR singers.name LIKE '%$filterTexts%'";
    $result = mysqli_query($conn, $songsFilterQuery);
    $songs = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($songs);
}
?>