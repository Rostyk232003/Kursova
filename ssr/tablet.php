<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
    }
    require_once('../app/db_connect.php');
    require_once('../app/models/TabletList.php');
    require_once('../app/models/CategoryList.php');
    require_once('../app/models/PropertyList.php');
    require_once('../app/models/VendorList.php');
    $bl=new TabletList($conn);
	$tablet=$bl->getFromDatabaseById($_GET['id']);
    $pl=new PropertyList($conn);
	$pl->getAllFromDatabase();
    $cl=new CategoryList($conn);
	$cl->getAllFromDatabase();
    $vl=new VendorList($conn);
	$vl->getAllFromDatabase();
    $tabletProps=$bl->getTabletPropertiesById($_GET['id']);
    if(isset($_POST['name'])){
        $bl->updateDatabaseById($_POST['id'],$_POST['vendor'],$_POST['name'],$_POST['price'],$_POST['category']);
        $propsArray=$pl->getDataAsArray();
        for ($i=0;$i<count($propsArray);$i++){
            $bl->refreshTabletProperty($_POST['id'],$propsArray[$i]['id'],$_POST['prop_'.$propsArray[$i]['id']]);
        }
        header('Location:tablets.php');
    }
?>
<html>
    <head>
        <title>Tablets List</title>
        <link href="../assets/style.css" rel="stylesheet" />
    </head>
    <body>
        <div class='container'>
            <div class='navigation'>
                <ul>
                    <li><a href="tablets.php">Графічні планшети</a></li>
                    <li><a href="vendors.php">Виробник</a></li>
                    <li><a href="categories.php">Категорії</a></li>
                    <li><a href="properties.php">Властивості</a></li>
                    <li><a href="logout.php">Вийти</a></li>
                </ul>
            </div>
            
            <div class='form-content'>
                <form method="POST">
                <p><?php echo $vl->getDataAsSelectWithSelectedOption($tablet['vendor_id']); ?></p>
                    <p><input value="<?php echo $tablet['name'];?>" type="text" placeholder="Модель" name="name" required/></p>
                    <p><?php echo $cl->getDataAsSelectWithSelectedOption($tablet['category_id']); ?></p>
                    <p><input value="<?php echo $tablet['price'];?>" type="number" placeholder="Ціна" name="price" required/></p>
                    <?php echo $pl->getDataAsInputBlockWithValues($tabletProps); ?>
                    <p><input value="<?php echo $tablet['id'];?>" type="hidden" name="id" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
            <div></div>
        </div>
    </body>
</html>