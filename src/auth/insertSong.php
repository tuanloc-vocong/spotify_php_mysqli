<?php
include('./auth.php');
include("../utils/dbConnection.php");

if(!$authenticated){
    header("Location: ./login.php");
}else{
    if(!$admin){
        header("Location: ./unauth.php");
    }
}

$getSingers = "SELECT * FROM singers";
$result = mysqli_query($conn, $getSingers);
$singers = mysqli_fetch_all($result, MYSQLI_ASSOC);
$titleUpdate = $singerIDfff = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql2 = "SELECT * FROM songs WHERE id = '$id'";
    $res2 = mysqli_query($conn, $sql2);
    $data = mysqli_fetch_array($res2);
    $titleUpdate = $data["title"];
    $singerIDfff = $data["singerID"];
}

$errors = array('title' => '', 'mp3' => '', 'img' => '');
$title = $mp3 = $img = $singerID = '';

function saveFile($fileInfo){
    $fileName = $fileInfo['name'];
    $type = $fileInfo['type'];
    $folder = (strpos($type, "image") !== false) ? 'images/' : 'music/';
    $tmpPath = $fileInfo['tmp_name'];
    $destinationPath = $folder . $fileName;

    if(move_uploaded_file($tmpPath, '../' . $destinationPath)){
        echo "Successfully uploaded!";
    }else{
        echo "Upload fail!";
    }

    return $destinationPath;
}

if(isset($_POST['submit'])){
    if(empty($_POST['title'])){
        $errors['title'] = "Title cannot be empty!";
    }else{
        $title = $_POST['title'];
    }

    $singerID = $_POST['singer'];

    if(empty($_FILES['mp3']['name'])){
        $errors['mp3'] = "Music File cannot be empty!";
    }else{
        $mp3 = $_FILES['mp3'];
    }

    if(empty($_FILES["images"]["name"])){
        $errors['image'] = "Image file cannot be empty!";
    }else{
        $img = $_FILES['images'];
    }

    if(array_filter($errors)){
        echo 'Form not valid!';
    }else{
        $mp3Path = saveFile($mp3);
        $imgPath = saveFile($img);

        if(isset($_GET['id'])){
            $updateSong = "UPDATE songs SET title = '$title', filePath = '$mp3Path', imgPath = '$imgPath', singerID = '$singerID' WHERE id = $id";
            $res3 = mysqli_query($conn, $updateSong);
            header("Location: editSong.php");
        }else{
            $insertSong = "INSERT INTO songs(title, filePath, imgPath, singerId) VALUES('$title', '$mp3Path', '$imgPath', $singerID)";
            if(!mysqli_query($conn, $insertSong)){
                echo "Error: " . "<br>" . mysqli_error($conn) . '!';
            }else{
                header("Location: editSong.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Singer</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="add-info">
        <h3 class="notice">Upload Songs</h3>
        <form method="post" class="form-insert" enctype="multipart/form-data">
            <?php foreach($errors as $error) : ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endforeach; ?>
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo $titleUpdate; ?>">
            <label>Singer</label>
            <select name="singer">
                <?php foreach($singers as $singer) : ?>
                    <option value='<?php echo $singer['id'] ?>' <?php if($singer['id'] == $singerIDfff) : ?> selected="selected" <?php endif; ?>>
                        <?php echo $singer['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>MP3 File</label>
            <input type="file" name="mp3" accept="audio/*">
            <label>Images</label>
            <input type="file" name="image" accept="image/*"><br>
            <a href="editSong.php" class="ca">Back</a>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>