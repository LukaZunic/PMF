<?php
require_once 'db.php';
require_once 'header.php';

$db = DB::getConnection();

$q = $db->prepare('SELECT * FROM dz2_users WHERE username=:username');
$q->execute(array('username' => $_SESSION['username']));

$user = $q->fetch();

?>

    <div>
            <?php
                echo '<button style="margin-top: 10px;"> <a href="homepage.php">Natrag</a> </button>';
            ?>
            
            <h1>Projekti gdje ste vlasnik</h1>

            <div class="grid">
            
                <?php

                    $myProjects = $db->prepare('SELECT * FROM dz2_projects WHERE id_user=:id_user');

                    $myProjects->execute(array('id_user' => $user['id']));

                    $userDetails = $db->prepare('SELECT * FROM dz2_users WHERE id=:id');
                    $userDetails->execute(array('id' => $_SESSION['id_user']));
                    $userDetails = $userDetails->fetch();


                    while($row = $myProjects->fetch()) {

                        echo '<div class="project_selection">';
                            echo '<p style="font-size: 12px;"> Author: ' . ucwords($userDetails['username']) . ' (status: '.  $row['status'] . ')' . '</p>';
                            echo '<h4>' . $row['title'] . '</h4>';
                            echo '<p> Broj članova: ' . $row['number_of_members'] . '</p>';
                        echo '</div>';
                    }

                ?>
            </div>

    </div>


    <div>
            
            <h1>Projekti na koje ste prijavljeni</h1>


            <div class="grid">
            
                <?php
                    
                    $assignedProjects = $db->prepare('SELECT * FROM dz2_members WHERE id_user=:id_user');

                    $assignedProjects->execute(array('id_user' => $user['id']));
                    $ap = $assignedProjects->fetchAll();

                    foreach($ap as $row) {

                        $project = $db->prepare('SELECT * FROM dz2_projects WHERE id=:id');
                        $project->execute(array('id' => $row['id_project']));
                        $project = $project->fetch();

                        $userDetails = $db->prepare('SELECT * FROM dz2_users WHERE id=:id');
                        $userDetails->execute(array('id' => $project['id_user']));
                        $userDetails = $userDetails->fetch();

                        echo '<div class="project_selection">';
                            echo '<p style="font-size: 12px;"> Author: ' . ucwords($userDetails['username']) . ' (status: '.  $project['status'] . ')' . '</p>';
                            echo '<h4>' . $project['title'] . '</h4>';
                            echo '<p> Broj članova: ' . $project['number_of_members'] . '</p>';
                        echo '</div>';
                    }

                    echo '<button style="margin-top: 10px;"> <a href="homepage.php">Natrag</a> </button>';

                ?>
            </div>



    </div>

</div>