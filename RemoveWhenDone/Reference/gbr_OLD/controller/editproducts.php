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

        $pname = $_POST['frmpname'];
        $pmsn = $_POST['frmmsn'];
        $pscost = $_POST['frmpscost'];
        $prcost = $_POST['frmprcost'];
        $ptype = $_POST['frmptype'];
        $prtype = $_POST['frmprtype'];

        create($pname, $pmsn, $pscost, $prcost, $ptype, $prtype, $db);
    }

    // The delete function
    function delete($id,$db){
        $db->delete('modifications','userid='.$id);
        header('Location: '.HTTP.HTTPURL.VIEW.'/products.php?action=prodel');
    }

    // The edit function.
    function edit($id,$db){
        if($_GET['from'] == "viewproducts"){

            //If from view/users.php then header to view/edituser.php?action=edit&uid=$id
            header('Location: '.HTTP.HTTPURL.VIEW.'/editproducts.php?action=edit&uid='.$id);

        } elseif($_GET['from'] == "vieweditproducts") {

            //If from view/edituser.php then perform SQL stuff and header to view/users.php.
            $pname = $_POST['frmpname'];
            $pmsn = $_POST['frmmsn'];
            $pscost = $_POST['frmpscost'];
            $prcost = $_POST['frmprcost'];
            $ptype = $_POST['frmptype'];
            $prtype = $_POST['frmprtype'];

            $db->update('modifications',array('mod_name'=>$pname,'mod_short_name'=>$pmsn,'mod_cost'=>$pscost,'monthly'=>$prcost,'item_type'=>$ptype,'rental_type'=>$prtype),'mod_ID = '.$id);
            $res = $db->getResult();
            if($res){
                header('Location: '.HTTP.HTTPURL.VIEW.'/products.php?action=esuccess');
            } else {
                echo 'There was an error!';
            }
        }
   
    }

    // This function will be used to create users in the future.
    function create($pname, $pmsn, $pscost, $prcost, $ptype, $prtype, $db){

        $db->insert('modifications',array('mod_name'=>$pname,'mod_short_name'=>$pmsn,'mod_cost'=>$pscost,'monthly'=>$prcost,'item_type'=>$ptype,'rental_type'=>$prtype));
        $res = $db->getResult();
        if($res){
            header('Location: '.HTTP.HTTPURL.VIEW.'/products.php?action=csuccess');
        } else {
            echo 'There was an error!';
        }

    }

?>