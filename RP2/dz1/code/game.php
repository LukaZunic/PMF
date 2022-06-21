
<?php
    session_start();

    if(isset( $_POST['name'])) {
        if(preg_match( '/^[a-zA-Z]{3,20}$/', $_POST['name'])) {
            $_SESSION['name'] = $_POST['name'];
        } else {
            header('Location: index.php');
            exit;
        }
    }
    
    if(!isset($_SESSION['name']) || !isset($_SESSION['diff'])) {
        header('Location: index.php');
        exit;
    }

    $username  = $_SESSION['name'];
    $numOfAttempts = $_SESSION['numOfAttempts'];
    $difficulty = $_SESSION['diff'];

    $error = false;
    $errorMessage = '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Wordle-DZ 1</title>
</head>
<body>
    <h1>
        Wordle!
    </h1>    

    <p>Igrač: <?php echo $_POST["name"]; ?>

</body>
</html>
