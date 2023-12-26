<?php
include("../utils/dbConnection.php");

$sql = "SELECT * FROM singers";
$result = mysqli_query($conn, $sql);
$singers = mysqli_fetch_all($result, MYSQLI_ASSOC);

$start = ($_REQUEST['page'] - 1) * 5;
$end = ($start + 5);
$listSinger = array();

foreach($singers as $index => $singer){
    if($start <= $index && $index < $end){
        array_push($listSinger, $singer);
    }
}

echo json_encode($listSinger, JSON_UNESCAPED_UNICODE);
?>