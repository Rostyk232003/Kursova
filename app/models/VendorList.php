<?php
require_once('BaseList.php');
class VendorList extends BaseList
{
	public function getAllFromDatabase()
	{
		$sql = "SELECT * FROM `vendor` WHERE 1";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				$nc = new Vendor($row['id'], $row['vendor']);
				array_push($this->dataArray, $nc);
			}
		} else {
			echo "0 results";
		}
	}

	public function getAllFromDatabaseBySearchCriteria($search){
		$sql = "SELECT * FROM `vendor` WHERE LOWER(vendor) LIKE LOWER('%".$search."%')";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
		// output data of each row
			while($row = $result->fetch_assoc()) {
				$nc=new Vendor($row['id'],$row['vendor']);
				array_push($this->dataArray,$nc);
			}
		} else {
		//echo "0 results";
		}
	}

	public function getFromDatabaseById($id)
	{
		$sql = "SELECT * FROM `vendor` WHERE id=" . $id;
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
	public function updateDatabaseById($id, $vendor)
	{
		$stmt = $this->conn->prepare("UPDATE `vendor` SET `vendor`=? WHERE `id`=?;");
		$stmt->bind_param("ss", $vendor, $id);
		$stmt->execute();
	}
	/*public function deleteFromDatabase($id)
	{
		$stmt = $this->conn->prepare("DELETE FROM `vendor` WHERE id=?;");
		$stmt->bind_param("s", $id);
		$stmt->execute();
	}*/

	public function deleteFromDatabase($id)
	{
		// Перевірте, чи використовується категорія в інших записах
		$checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM `tablet` WHERE vendor_id=?;");
		$checkStmt->bind_param("s", $id);
		$checkStmt->execute();
		$checkResult = $checkStmt->get_result();
		$count = $checkResult->fetch_array(MYSQLI_NUM)[0];
	
		if ($count > 0) {
			// Категорія використовується в інших записах
			return json_encode(["status" => "error", "message" => "Виробник присутній у записі, не можна видаляти"]);
		}
	
		// Видаліть категорію
		$stmt = $this->conn->prepare("DELETE FROM `vendor` WHERE id=?;");
		$stmt->bind_param("s", $id);
	
		if ($stmt->execute()) {
			// Видалення успішне
			return json_encode(["status" => "success"]);
		} else {
			// Видалення не вдалося
			return json_encode(["status" => "error", "message" => "Видалення не вдалося"]);
		}
	}

	/*public function insertIntoDatabase($vendor)
	{
		$stmt = $this->conn->prepare("INSERT INTO `vendor` VALUES(DEFAULT,?);");
		$stmt->bind_param("s", $vendor);
		$stmt->execute();
		$last_id = $this->conn->insert_id;
		return $last_id;
	}*/

	public function insertIntoDatabase($vendor)
	{
		// Перевірка, чи існує категорія
		$stmt = $this->conn->prepare("SELECT * FROM `vendor` WHERE vendor = ?;");
		$stmt->bind_param("s", $vendor);
		$stmt->execute();
		
		$stmt->store_result();
		
		if($stmt->num_rows > 0){
			// Якщо категорія вже існує
			return "exists";
		} else {
			// Якщо категорія не існує, додати нову
			$stmt = $this->conn->prepare("INSERT INTO `vendor` VALUES(DEFAULT,?);");
			$stmt->bind_param("s", $vendor);
			$stmt->execute();
			$last_id = $this->conn->insert_id;
			//return $last_id;
			return "success";
		}
	}

	public function getDataAsSelect()
	{
		$result = '<select name="vendor">';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= $this->dataArray[$i]->getDataAsOption();
		}
		$result .= '</select>';
		return $result;
	}

	public function getDataAsSelectWithSelectedOption($id)
	{
		$result = '<select name="vendor">';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($id == $this->dataArray[$i]->getId()) {
				$result .= $this->dataArray[$i]->getDataAsSelectedOption();
			} else {
				$result .= $this->dataArray[$i]->getDataAsOption();
			}
		}
		$result .= '</select>';
		return $result;
	}
	public function add($vendor)
	{
		$id = ++$this->index;
		$nc = new Vendor($id, $vendor);
		array_push($this->dataArray, $nc);
		return $id;
	}
	public function edit($id, $vendor)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				$this->dataArray[$i]->edit($vendor);
				break;
			}
		}
	}
}
class Vendor
{
	private $id;
	private $vendor;
	public function __construct($id, $vendor)
	{
		$this->id = $id;
		$this->vendor = $vendor;
	}
	public function getId()
	{
		return $this->id;
	}
	public function getDataAsSelectedOption()
	{
		return "<option value='" . $this->id . "' selected>" . $this->vendor . "</option>";
	}
	public function getDataAsOption()
	{
		return "<option value='" . $this->id . "'>" . $this->vendor . "</option>";
	}
	public function getDataAsTableRow()
	{
		return "
				<tr>
					<td>" . $this->id . "</td>
					<td>" . $this->vendor . "</td>
					<td>
					<a href='vendor.php?id=" . $this->id . "'>Редагувати</a>
					<form method='POST'>
						<input type='hidden' name='action' value='delete'/>
						<input type='hidden' name='id' value='" . $this->id . "'/>
						<button type='submit'>Видалити</button>	
					</form></td>
				</tr>
			";
	}
	public function displayInfo()
	{
		return $this->id . ". " . $this->vendor . "</br>";
	}
	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}
}
