<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include database connections
    include(BASEURL.CFG.'/database.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

    $db = new Database();
    $db->connect();

    // Get the users id.
    if(isset($_GET['uid'])) {
        $uid = $_GET['uid'];
    }

    // If from edit users then the ID will be posted.
    if(isset($_POST['frmuid'])) {
        $uid = $_POST['frmuid'];
    }

    // Get the action.
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
    }

    if(isset($_GET['from'])) {
        $from = $_GET['from'];
    }

    // Check to see which action we want to perform.
    if($action == "delete"){
        delete($uid,$db);
    } elseif ($action == "edit"){
        edit($uid,$db);
    } elseif ($action == "create"){

        $uname = $_POST['frmuname'];
        $ufname = $_POST['frmufirstname'];
        $ulname = $_POST['frmulastname'];
        $uphone = $_POST['frmuphone'];
        $utitle = $_POST['frmutitle'];
        $utype = $_POST['frmutype'];
        $upass = $_POST['frmupassword'];

        create($uname, $ufname, $ulname, $upass, $utype, $utitle, $uphone, $db);
    }

    // The delete function
    function delete($id,$db){
        $db->delete('users','userid='.$id);
        header('Location: '.HTTP.HTTPURL.VIEW.'/users.php?action=userdel');
    }

    // The edit function.
    function edit($id,$db){
        if($_GET['from'] == "viewusers"){

            //If from view/users.php then header to view/edituser.php?action=edit&uid=$id
            header('Location: '.HTTP.HTTPURL.VIEW.'/edituser.php?action=edit&uid='.$id);

        } elseif($_GET['from'] == "viewedituser") {

            //If from view/edituser.php then perform SQL stuff and header to view/users.php.
            $username = $_POST['frmuname'];
            $firstname = $_POST['frmufirstname'];
            $lastname = $_POST['frmulastname'];
            $phone = $_POST['frmuphone'];
            $title = $_POST['frmutitle'];
            $user_type = $_POST['frmutype'];
            $password = $_POST['frmupassword'];

            $db->update('users',array('username'=>$username,'firstname'=>$firstname,'lastname'=>$lastname,'phone'=>$phone,'title'=>$title,'user_type'=>$user_type,'password'=>$password),'userid = '.$id);
            $res = $db->getResult();
            if($res){
                header('Location: '.HTTP.HTTPURL.VIEW.'/users.php?action=esuccess');
            } else {
                echo 'There was an error!';
            }
        }
   
    }

    // This function will be used to create users in the future.
    function create($uname, $ufname, $ulname, $upass, $utype, $utitle, $uphone, $db){

        $db->insert('users',array('username'=>$uname,'firstname'=>$ufname,'lastname'=>$ulname,'phone'=>$uphone,'title'=>$utitle,'user_type'=>$utype,'password'=>$upass));
        $res = $db->getResult();
        if($res){
            header('Location: '.HTTP.HTTPURL.VIEW.'/users.php?action=csuccess');
        } else {
            echo 'There was an error!';
        }

    }





?>