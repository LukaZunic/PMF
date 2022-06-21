<?php
    $db = DB::getConnection();

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
    else  
        $url = "http://";   

    $url.= $_SERVER['HTTP_HOST'];   
    $url.= $_SERVER['REQUEST_URI'];
    $url_components = parse_url($url);

    if(isset($url_components['query'])) parse_str($url_components['query'], $params);

    if(isset($_POST['apply'])) {


        $assignedProjects = $db->prepare('SELECT * FROM dz2_members WHERE id_user=:id_user');

        $assignedProjects->execute(array('id_user' => $_SESSION['id_user']));

        $ap = $assignedProjects->fetchAll();

        $applyAvailable = true;

        // check if user id is already assigned to a project
        foreach($ap as $a) {
            if($a['id_project'] == $params['id']) {
                echo '<p style="color: red;">Već ste prijavljeni na ovaj projekt!</p>';
                $applyAvailable = false;
                break;
            }
        }

        if($applyAvailable) {
            $st = $db->prepare('INSERT INTO dz2_members (id_project, id_user, member_type) VALUES (:id_project, :id_user, :member_type)');
            $st->execute(array('id_project' => $params['id'], 'id_user' => $_SESSION['id_user'], 'member_type' => 'member'));
    
            $st = $db->prepare('UPDATE dz2_projects SET number_of_members = number_of_members + 1 WHERE id = :id');
            $st->execute(array('id' => $params['id']));
    
            print_r('Uspješno ste se prijavili na projekt!');
        }       
    }

?>