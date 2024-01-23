<?php
    session_start();
    if(!isset($_SESSION['username'])){
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../db_connect.php');
    require_once('../models/TabletList.php');
    require_once('../models/PropertyList.php');
    require_once('../models/VendorList.php');
    $pl=new PropertyList($conn);
    $pl->getAllFromDatabase();
    $vl=new VendorList($conn);
    $vl->getAllFromDatabase();
    $bl=new TabletList($conn);
    if($_SERVER['REQUEST_METHOD']=="GET"&&!isset($_GET['id'])){
        if(!isset($_GET['search'])){
            $bl->getAllFromDatabase();
        } else{
            $bl->getAllFromDatabaseBySearchCriteria($_GET['search']);
        }
        echo $bl->convertToJSON();
    }
    if($_SERVER['REQUEST_METHOD']=="GET"&&isset($_GET['id'])){
        $record=$bl->getFromDatabaseById($_GET['id']);
        $record['properties']=$bl->getTabletPropertiesById($_GET['id']);
        echo json_encode($record,JSON_UNESCAPED_UNICODE);
    }
    
    if(isset($_POST['name'])){
        $tabletId=$bl->insertIntoDatabase($_POST['vendor'],$_POST['name'],$_POST['price'],$_POST['category']);       
        $propsArray=$pl->getDataAsArray();
        echo json_encode($propsArray);
        for($i=0;$i<count($propsArray);$i++){
            $bl->addTabletProperty($tabletId,$propsArray[$i]['id'],$_POST['prop_'.$propsArray[$i]['id']]);
        }
    }

    if($_SERVER['REQUEST_METHOD']=="DELETE"){
        $bl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD']=="PUT"){
        $data = json_decode( file_get_contents('php://input') );
        $bl->updateDatabaseById($data->id,$data->vendor,$data->name,$data->price,$data->category);
        $propsArray=$pl->getDataAsArray();
        for ($i=0;$i<count($propsArray);$i++){
            $bl->refreshTabletProperty($data->id,$propsArray[$i]['id'],$data->{'prop_'.$propsArray[$i]['id']});
        }
        echo json_encode($propsArray);
        //echo '{"status":"success"}';
    }
    
?>