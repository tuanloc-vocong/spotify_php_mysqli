<?php
include("../utils/dbConnection.php");

if(isset($_GET['uid'])){
    $userId = $_GET['uid'];
    $groupId = $_GET['admin'];
    
    $updateSql = "UPDATE users SET groupId = $groupId WHERE id = $userId";
    $result1 = mysqli_query($conn, $updateSql);
    if($result1){
        echo "Successful update user $userId!";
    }else{
        echo "Error when update user $userId!";
    }
}
?>