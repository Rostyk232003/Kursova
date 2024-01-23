<?php
require_once('BaseList.php');
class TabletList extends BaseList
{
	public function add($vendor, $name, $category, $price, $properties)
	{
		$id = ++$this->index;
		$nb = new Tablet($id, $vendor, $name, $category, $price, $properties);
		array_push($this->dataArray, $nb);
		return $id;
	}
	public function getAllFromDatabase()
	{
		
		$sql = "SELECT `tablet`.id,`tablet`.name,`tablet`.price, `vendor`.vendor vendorname,`category`.name catname FROM `tablet` 
		INNER JOIN `category` ON `tablet`.category_id=`category`.id
		INNER JOIN `vendor` ON `tablet`.vendor_id=`vendor`.id  WHERE 1
		ORDER BY 1";
		
		/*"SELECT `tablet`.*,`vendor`.name vendorname,`category`.name catname FROM `tablet` 
            INNER JOIN `category` ON `tablet`.category_id=`category`.id  WHERE 1"; */

		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				$nc = new Tablet($row['id'], $row['vendorname'], $row['name'], $row['price'], $row['catname'], $this->getTabletPropertiesById($row['id']));
				array_push($this->dataArray, $nc);
			}
		} else {
			echo "0 results";
		}
	}

	public function getAllFromDatabaseBySearchCriteria($search){
		$sql = "SELECT `tablet`.id,`tablet`.name,`tablet`.price, `vendor`.vendor vendorname,`category`.name catname FROM `tablet` 
		INNER JOIN `category` ON `tablet`.category_id=`category`.id
		INNER JOIN `vendor` ON `tablet`.vendor_id=`vendor`.id  WHERE
		LOWER(`tablet`.name) LIKE LOWER('%".$search."%')
		OR LOWER(`vendor`.vendor) LIKE LOWER('%".$search."%')
		OR LOWER(`category`.name) LIKE LOWER('%".$search."%')";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
		// output data of each row
			while($row = $result->fetch_assoc()) {
				$nc=new Tablet($row['id'],$row['vendorname'],$row['name'],$row['price'],$row['catname'],$this->getTabletPropertiesById($row['id']));
				array_push($this->dataArray,$nc);
			}
		} else {
		echo "0 results";
		}
	}

	public function getFromDatabaseById($id)
	{
		$sql = "SELECT * FROM `tablet` WHERE id=" . $id;
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				return $row;
			}
		} else {
			echo "0 results";
		}
	}
	public function updateDatabaseById($id, $vendor, $name, $price, $category)
	{
		$stmt = $this->conn->prepare("UPDATE `tablet` SET `vendor_id`=?,`name`=?,`price`=?,`category_id`=? WHERE `id`=?;");
		$stmt->bind_param("sssss", $vendor, $name, $price, $category, $id);
		$stmt->execute();
	}
	public function deleteFromDatabase($id)
	{
		$stmt = $this->conn->prepare("DELETE FROM `graphictablet_property` WHERE tablet_id=?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt = $this->conn->prepare("DELETE FROM `tablet` WHERE id=?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
	}
	public function insertIntoDatabase($vendor, $name, $price, $category)
	{
		$stmt = $this->conn->prepare("INSERT INTO `tablet` VALUES(DEFAULT,?,?,?,?);");
		$stmt->bind_param("ssss", $vendor, $name, $price, $category);
		$stmt->execute();
		$last_id = $this->conn->insert_id;
		return $last_id;
	}
	public function addTabletProperty($tabletId, $propertyId, $value)
	{
		$stmt = $this->conn->prepare("INSERT INTO `graphictablet_property` VALUES(DEFAULT,?,?,?);");
		$stmt->bind_param("sss", $tabletId, $propertyId, $value);
		$stmt->execute();
	}
	public function refreshTabletProperty($tabletId, $propertyId, $value)
	{
		$stmtDelete = $this->conn->prepare("DELETE FROM `graphictablet_property` WHERE `property_id`=? AND `tablet_id`=? ");
		$stmtDelete->bind_param("ss", $propertyId, $tabletId);
		$stmtDelete->execute();
		$stmtAdd = $this->conn->prepare("INSERT INTO `graphictablet_property` VALUES(DEFAULT,?,?,?);");
		$stmtAdd->bind_param("sss", $tabletId, $propertyId, $value);
		$stmtAdd->execute();
	}

	public function getTabletPropertiesById($id)
	{
		$sql = "SELECT `graphictablet_property`.*, `property`.name, `property`.`units` 
            FROM graphictablet_property INNER JOIN `property` 
            ON `property`.`id`=`graphictablet_property`.`property_id` 
            WHERE `graphictablet_property`.`tablet_id`=" . $id;
		$result = $this->conn->query($sql);
		$propsArray = [];
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				array_push($propsArray, $row);
			}
		} else {
			//echo "0 results";
		}
		return $propsArray;
	}
}
class Tablet
{
	private $id;
	private $vendor;
	private $name;
	private $category;
	private $price;
	private $properties;
	public function __construct($id, $vendor, $name, $category, $price, $properties)
	{
		$this->id = $id;
		$this->vendor = $vendor;
		$this->name = $name;
		$this->category = $category;
		$this->price = $price;
		$this->properties = $properties;
	}
	public function getId()
	{
		return $this->id;
	}
	public function getDataAsTableRow()
	{
		return "
				<tr>
					<td>" . $this->id . "</td>
					<td>" . $this->vendor . "</td>
					<td>" . $this->name . "</td>
					<td>" . $this->category . "</td>
					<td>" . $this->price . "</td>
					<td>" . $this->displayProperties() . "</td>
					<td>
					<a href='tablet.php?id=" . $this->id . "'>Редагувати</a>
					<form method='POST'>
						<input type='hidden' name='action' value='delete'/>
						<input type='hidden' name='id' value='" . $this->id . "'/>
						<button type='submit'>Видалити</button>	
					</form></td>
				</tr>
			";
	}
	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}
	private function displayProperties()
	{
		$result = '<i>Характеристики:</i></br>';
		foreach ($this->properties as $property) {
			$result .=  $property['name'] . ": " . $property['value'] . " (" . $property['units'] . ")";
			$result .=  "<br>";
		}
		return $result;
	}
	public function displayInfo()
	{
		return $this->id . ". <b>" . $this->vendor . " " . $this->name . "</b></br>
			Ціна: " . $this->price . "<br>
			Категорія: " . $this->category . "<br>" . $this->displayProperties();
	}
}
