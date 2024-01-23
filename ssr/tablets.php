<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}
require_once('../app/db_connect.php');
require_once('../app/models/TabletList.php');
require_once('../app/models/CategoryList.php');
require_once('../app/models/PropertyList.php');
require_once('../app/models/VendorList.php');
$bl=new TabletList($conn);
    if(!isset($_GET['search'])){
        $bl->getAllFromDatabase();
    } else{
        $bl->getAllFromDatabaseBySearchCriteria($_GET['search']);
    }
    $pl=new PropertyList($conn);
	$pl->getAllFromDatabase();
    $cl=new CategoryList($conn);
	$cl->getAllFromDatabase();
    $vl=new VendorList($conn);
	$vl->getAllFromDatabase();
    if(isset($_POST['action']) && $_POST['action']=='delete'){
        $bl->deleteFromDatabase($_POST['id']);
        $bl=new TabletList($conn);
	    $bl->getAllFromDatabase();
    }
    
    if(isset($_POST['name'])){
        $tabletId=$bl->insertIntoDatabase($_POST['vendor'],$_POST['name'],$_POST['price'],$_POST['category']);       
        $propsArray=$pl->getDataAsArray();
        for($i=0;$i<count($propsArray);$i++){
            $bl->addTabletProperty($tabletId,$propsArray[$i]['id'],$_POST['prop_'.$propsArray[$i]['id']]);
        }
        $bl=new TabletList($conn);
	    $bl->getAllFromDatabase();
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
        <form>
            <input type="text" name="search" id="searchInput" required/>
            <button type="submit">Пошук</button>
        </form>
            <ul>
                <li><a href="tablets.php">Графічні планшети</a></li>
                <li><a href="vendors.php">Виробник</a></li>
                <li><a href="categories.php">Категорії</a></li>
                <li><a href="properties.php">Властивості</a></li>
                <li><a href="logout.php">Вийти</a></li>
            </ul>
        </div>
        <div class='table-content'>
            <h1>Графічні планшети</h1>
            <table>
                <thead>
                    <th>ID</th>
                    <th>Виробник</th>
                    <th>Модель</th>
                    <th>Ціна</th>
                    <th>Категорія</th>
                    <th>Характеристики</th>
                    <th>Дії</th>
                </thead>
                <tbody>
                    <?php echo $bl->getTable(); ?>
                </tbody>
            </table>
        </div>
        <div class='form-content'>
            <form method="POST">
                <p><?php echo $vl->getDataAsSelect(); ?></p>
                <p><input type="text" placeholder="Модель" name="name" required /></p>
                <p><?php echo $cl->getDataAsSelect(); ?></p>
                <p><input type="number" placeholder="Ціна" name="price" required /></p>
                <?php echo $pl->getDataAsInputBlock(); ?>
                <p><button type="submit">Зберегти</button></p>
            </form>
        </div>
    </div>
</body>

</html>