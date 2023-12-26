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
        $errors['userName'] = "Username can't be empty!";
    }else{
        $password = md5($password);
        $sql = "SELECT * FROM users WHERE userName = '$userName' AND password = '$password'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            if($row['userName'] == $userName && $row['password'] == $password){
                $_SESSION['userName'] = $row['userName'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['admin'] = ($row['groupId'] == 1) ? true : false;

                header("Location: ../index.php");
                exit();
            }else{
                $errors['password'] = "Incorrect username or password!";
            }
        }else{
            $errors['userName'] = "Incorrect username or password!";
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
    <title>Login</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <label>Username</label>
        <input class="<?php if($errors['userName'] != ''){
            echo 'error1';
        } ?>" type="text" name="userName" placeholder="Username" value="<?php echo $userName; ?>">
        <p class="error-container"><?php echo $errors['userName']; ?></p>

        <label>Password</label>
        <input class="<?php if($errors['password'] != ''){
            echo 'error1';
        } ?>" type="password" name="password" placeholder="Password">
        <p class="error-container"><?php echo $errors['password']; ?></p>

        <button type="submit" name="submit">Login</button>
        <a href="signup.php" class="ca">Create an account</a>
    </form>
</body>
</html>