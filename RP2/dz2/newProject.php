<?php
require_once 'db.php';
require_once 'header.php';

$db = DB::getConnection();

$q = $db->prepare('SELECT * FROM dz2_users WHERE username=:username');
$q->execute(array('username' => $_SESSION['username']));

$user = $q->fetch();

?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <label for="title">Naslov projekta:</label>		
    <input type="text" name="title" id="title" value="" />

    <br />

    <label for="description">Opis projekta:</label>		
    <input type="text" name="description" id="description" value="" />

    <br />

    <label for="membersNum">Broj članova:</label>		
    <input name="membersNum" id="membersNum" value="" />

    <br />

    <button type="submit" name="projectSubmit">Prijavi projekt!</button>
</form>


<?php

    if(isset($_POST['projectSubmit'])){

        $title = $_POST['title'];
        $description = $_POST['description'];
        $membersNum = $_POST['membersNum'];
        $status = 'open';

        $q = $db->prepare('INSERT INTO dz2_projects (id_user, title, abstract, number_of_members, status) VALUES (:id_user, :title, :abstract, :number_of_members, :status)');
        $q->execute(array('id_user' => $user['id'], 'title' => $title, 'abstract' => $description, 'number_of_members' => $membersNum, 'status' => $status));


        $q = $db->prepare('SELECT * FROM dz2_projects WHERE id_user=:id_user AND title=:title AND abstract=:abstract AND number_of_members=:number_of_members AND status=:status');
        $q->execute(array('id_user' => $user['id'], 'title' => $title, 'abstract' => $description, 'number_of_members' => $membersNum, 'status' => $status));
        
        $res = $q->fetch();

        $st = $db->prepare('INSERT INTO dz2_members (id_project, id_user, member_type) VALUES (:id_project, :id_user, :member_type)');
        $st->execute(array('id_project' => $res['id'], 'id_user' => $_SESSION['id_user'], 'member_type' => 'member'));

        echo '<p>Uspješno ste prijavili projekt!</p>';

        echo '<button style="margin-top: 10px;"> <a href="homepage.php">Natrag na naslovnicu</a> </button>';
        exit;
    }

?>


