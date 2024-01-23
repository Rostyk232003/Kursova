<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
    }
    require_once('../app/db_connect.php');
    require_once('../app/models/VendorList.php');
    $vl=new VendorList($conn);
    if(!isset($_GET['search'])){
        $vl->getAllFromDatabase();
    } else{
        $vl->getAllFromDatabaseBySearchCriteria($_GET['search']);
    }
	
    if(isset($_POST['action']) && $_POST['action']=='delete'){
        $vl->deleteFromDatabase($_POST['id']);
        $vl=new VendorList($conn);
	    $vl->getAllFromDatabase();
    }
    if(isset($_POST['name'])){
        $vl->insertIntoDatabase($_POST['name']);
        $vl=new VendorList($conn);
	    $vl->getAllFromDatabase();
    }
?>
<html>
    <head>
        <title>Vendors List</title>
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
                    <li><a href="vendors.php">Виробники</a></li>
                    <li><a href="categories.php">Категорії</a></li>
                    <li><a href="properties.php">Властивості</a></li>
                    <li><a href="logout.php">Вийти</a></li>
                </ul>
            </div>
            <div class='table-content'>
                    <h1>Виробники</h1>
                    <table>
                        <thead>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Дії</th>
                        </thead>
                        <tbody>
                            <?php echo $vl->getTable();?>
                        </tbody>
                    </table>
            </div>
            <div class='form-content'>
                <form method="POST">
                    <p><input type="text" placeholder="Назва" name="vendor" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
        </div>
    </body>
</html>