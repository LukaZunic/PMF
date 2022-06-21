<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUp</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reset.css">
</head>
<body>

    <div class="header">

        <div id="headerLogo"> 
            <h1>TeamUp</h1>
        </div>
        
        <div id="usernameHeader">
            <?php
                session_start();
                if(isset($_SESSION['username'])){
                    echo '<p>' . ucwords($_SESSION['username']) . '</p>';
                }


                echo '<button> <a href="logout.php">Odjava</a> </button>';
            ?>
        </div>

    </div>



