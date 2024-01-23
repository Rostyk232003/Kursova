<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
    }
    require_once('../app/db_connect.php');
    require_once('../app/models/VendorList.php');
    $vl=new VendorList($conn);
	$vendor=$vl->getFromDatabaseById($_GET['id']);
    
    if(isset($_POST['vendor'])){
        $vl->updateDatabaseById($_POST['id'],$_POST['vendor']);
        header('Location:vendors.php');
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
                <ul>
                    <li><a href="tablets.php">Графічні планшети</a></li>
                    <li><a href="vendors.php">Виробники</a></li>
                    <li><a href="categories.php">Категорії</a></li>
                    <li><a href="properties.php">Властивості</a></li>
                    <li><a href="logout.php">Вийти</a></li>
                </ul>
            </div>
            <div class='form-content'>
                <form method="POST">
                    <p><input value='<?php echo $vendor['vendor'];?>' type="text" placeholder="Назва" name="vendor" required/></p>
                    <p><input value='<?php echo $vendor['id'];?>' type="hidden" name="id" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                    <p id="vendorErrorText"></p>
                    <p id="vendorDeleteErrorText"></p>
                </form>
            </div>
            <div></div>
        </div>
    </body>
</html>