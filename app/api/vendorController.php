<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    session_start();
    require_once('../db_connect.php');
    require_once('../models/VendorList.php');
    $vl=new VendorList($conn);
	
    if($_SERVER['REQUEST_METHOD']=="GET"&&!isset($_GET['id'])){
        
        if(!isset($_GET['search'])){
            $vl->getAllFromDatabase();
        } else{
            $vl->getAllFromDatabaseBySearchCriteria($_GET['search']);
        }
        echo $vl->convertToJSON();
    }
    if($_SERVER['REQUEST_METHOD']=="GET"&&isset($_GET['id'])){
        
        $record=$vl->getFromDatabaseById($_GET['id']);
        echo json_encode($record,JSON_UNESCAPED_UNICODE);
    }
    /*if(isset ($_POST['vendor'])){
        $vl->insertIntoDatabase($_POST['vendor']);
        echo '{"status":"success"}';
    }*/ 

    if(isset ($_POST['vendor'])){
        $result = $vl->insertIntoDatabase($_POST['vendor']);
        if($result == "exists"){
            echo '{"status":"error", "message":"Виробник вже існує"}';
        } else {
            echo '{"status":"success"}';
        }
    }

    /*if($_SERVER['REQUEST_METHOD']=="DELETE"){
        $vl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    } */

    if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        $response = $vl->deleteFromDatabase($_REQUEST['id']);
        echo $response;
    }

    
    if($_SERVER['REQUEST_METHOD']=="PUT"){
        $data = json_decode( file_get_contents('php://input') );
        $vl->updateDatabaseById($data->id,$data->vendor);
        echo '{"status":"success"}';
    }
    
?>