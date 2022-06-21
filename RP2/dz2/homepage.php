<?php

require_once 'db.php';
require_once 'header.php';
require_once 'apply.php';

$db = DB::getConnection();

$st = $db->prepare('SELECT * FROM dz2_projects');
$st->execute();

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
    $url = "https://";   
else  
    $url = "http://";   

$url.= $_SERVER['HTTP_HOST'];   
$url.= $_SERVER['REQUEST_URI'];
$url_components = parse_url($url);

if(isset($url_components['query'])) parse_str($url_components['query'], $params);


$q = $db->prepare('SELECT * FROM dz2_users WHERE username=:username');

$q->execute(array('username' => $_SESSION['username']));

$user = $q->fetch();
$myProjects = $db->prepare('SELECT * FROM dz2_projects WHERE id_user=:id_user');

$myProjects->execute(array('id_user' => $user['id']));

$_SESSION['id_user'] = $user['id'];


?>
    <button>
        <a href="myProjects.php">Moji Projekti</a>
    </button>

    <button>
        <a href="newProject.php">Objavi Projekt</a>
    </button>


    <div class="grid">

        <?php

            if(isset($params['id'])) {
                $st = $db->prepare('SELECT * FROM dz2_projects WHERE id=:id');
                $st->execute(array('id' => $params['id']));
                $row = $st->fetch();

                $userDetails = $db->prepare('SELECT * FROM dz2_users WHERE id=:id');
                $userDetails->execute(array('id' => $params['id']));
                $userDetails = $userDetails->fetch();
                
                echo '<div class="project_highlighted">';
                    echo '<p style="font-size: 12px;"> Autor: ' . ucwords($userDetails['username']) . ' (status: '.  $row['status'] . ')' . '</p>';
                    echo '<p style="font-size: 22px; font-weight: bold;">' . $row['title'] . '</p>';
                    echo '<p style="font-size: 10px; font-weight: bold;"> Autor ID: ' . $row['id_user'] . '</p>';
                    echo '<p>' . $row['abstract'] . '</p>';
                    echo '<p style="font-weight: bold;"> Broj članova: ' . $row['number_of_members'] . '</p>';
                echo '</div>';

                echo '<button style="margin-top: 10px; height:50px;"> <a href="homepage.php">Natrag na sve projekte</a> </button>';
                
                echo '<form method="post">';
                    echo '<button style="margin-top: 10px; height:50px;" name="apply">Prijavi se</button>';
                echo '</form>';
                
            }

            while($row = $st->fetch()){

                $userDetails = $db->prepare('SELECT * FROM dz2_users WHERE id=:id');
                $userDetails->execute(array('id' => $row['id_user']));
                $userDetails = $userDetails->fetch();
        
                echo '<div class="project_selection">';
                    echo '<p style="font-size: 12px;"> Autor: ' . ucwords($userDetails['username']) . ' (status: '.  $row['status'] . ')' . '</p>';
                    echo '<h4>' . $row['title'] . '</h4>';
                    echo '<p style="font-size: 10px; font-weight: bold;"> Autor ID: ' . $row['id_user'] . '</p>';
                    echo '<p> Broj članova: ' . $row['number_of_members'] . '</p>';
                    echo '<button style="margin-top: 10px;"> <a href="homepage.php?id=' . $row['id'] . '">Detalji</a> </button>';
                echo '</div>';
            };

        ?>
    
   


</body>
</html>

<?php
   if(isset($_POST['apply'])) {
        print_r($_POST['apply']);
    }
?>