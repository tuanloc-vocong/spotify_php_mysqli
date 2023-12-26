<?php
include("../utils/dbConnection.php");

session_start();
if(isset($_SESSION['id'])){
    header("Location: ..index.php");
}

$userName = $password = $re_password = "";
$errors = array('userName' => '', 'password' => '', 're_password' => '', 'matchPassword' => '', 'existUser' => '');

if(isset($_POST['submit'])){
    function cleanData($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $userName = cleanData($_POST['userName']);
    $password = cleanData($_POST['password']);
    $re_password = cleanData($_POST['re_password']);

    if(empty($userName)){
        $errors['userName'] = 'Username can not be empty!';
    }

    if(empty($password)){
        $errors['password'] = 'Password can not be empty!';
    }

    if(empty($re_password)){
        $errors['re_password'] = 'Confirm password can not be empty!';
    }

    if($password !== $re_password){
        $errors['matchPassword'] = 'The confirmation password does not match!';
    }

    $sql = "SELECT * FROM users WHERE userName = '$userName'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $errors['existUser'] = "User is already exist!";
    }

    if(array_filter($errors)){
        echo "Have errors";
    }else{
        $password = md5($password);
        $sql2 = "INSERT INTO users(userName, password, groupId) VALUE('$userName', '$password', 2)";
        $result2 = mysqli_query($conn, $sql2);

        if($result2){
            header("Location: login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IR-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <form action="signup.php" method="post">
        <h2>Sign Up</h2>
        <p class="error-container"><?php echo $errors['matchPassword']; ?></p>
        <p class="error-container"><?php echo $errors['existUser']; ?></p>

        <label>Username</label>
        <input class="<?php if($errors['userName'] != ''){
            echo 'error1';
        } ?>" type="text" name="userName" placeholder="Username" value="<?php echo $userName; ?>">
        <p class="error-container"><?php echo $errors['userName'] ?></p>

        <label>Password</label>
        <input class="<?php if($errors['password'] != ''){
            echo 'error1';
        } ?>" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
        <p class="error-container"><?php echo $errors['password'] ?></p>

        <label>Confirm Password</label>
        <input class="<?php if($errors['re_password'] != ''){
            echo 'error1';
        } ?>" type="password" name="re_password" placeholder="Confirm Password">
        <p class="error-container"><?php echo $errors['re_password'] ?></p>

        <button type="submit" name="submit">Sign Up</button>
        <a href="login.php" class="ca">Already have an account?</a>
    </form>
</body>
</html>