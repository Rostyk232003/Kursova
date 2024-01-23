<?php
require_once('BaseList.php');
class PropertyList extends BaseList
{
	public function add($name, $units)
	{
		$id = ++$this->index;
		$np = new Property($id, $name, $units);
		array_push($this->dataArray, $np);
		return $id;
	}
	public function getAllFromDatabase()
	{
		$sql = "SELECT * FROM `property` WHERE 1";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				$np = new Property($row['id'], $row['name'], $row['units']);
				array_push($this->dataArray, $np);
			}
		} else {
			echo "0 results";
		}
	}

	public function getAllFromDatabaseBySearchCriteria($search)
	{
		$sql = "SELECT * FROM `property` WHERE 
			LOWER(name) LIKE LOWER('%" . $search . "%')
			OR LOWER(units) LIKE LOWER('%" . $search . "%')";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				$np = new Property($row['id'], $row['name'], $row['units']);
				array_push($this->dataArray, $np);
			}
		} else {
			//echo "0 results";
		}
	}

	public function getFromDatabaseById($id)
	{
		$sql = "SELECT * FROM `property` WHERE id=" . $id;
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
	public function updateDatabaseById($id, $name, $units)
	{
		$stmt = $this->conn->prepare("UPDATE `property` SET `name`=?,`units`=? WHERE `id`=?;");
		$stmt->bind_param("sss", $name, $units, $id);
		$stmt->execute();
	}
	/*public function deleteFromDatabase($id){
			$stmt = $this->conn->prepare("DELETE FROM `graphictablet_property` WHERE property_id=?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();
			$stmt = $this->conn->prepare("DELETE FROM `property` WHERE id=?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();
			
		}*/

	public function deleteFromDatabase($id)
	{
		// Перевірте, чи використовується властивість в інших записах
		$checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM `graphictablet_property` WHERE property_id=?;");
		$checkStmt->bind_param("s", $id);
		$checkStmt->execute();
		$checkResult = $checkStmt->get_result();
		$count = $checkResult->fetch_array(MYSQLI_NUM)[0];

		if ($count > 0) {
			// Властивість використовується в інших записах
			return json_encode(["status" => "error", "message" => "Властивість присутня у записі, не можна видаляти"]);
		}

		// Видаліть властивість
		$stmt = $this->conn->prepare("DELETE FROM `property` WHERE id=?;");
		$stmt->bind_param("s", $id);

		if ($stmt->execute()) {
			// Видалення успішне
			return json_encode(["status" => "success"]);
		} else {
			// Видалення не вдалося
			return json_encode(["status" => "error", "message" => "Видалення не вдалося"]);
		}
	}

	public function getDataAsArray()
	{
		$propArray = [];
		for ($i = 0; $i < count($this->dataArray); $i++) {
			array_push($propArray, $this->dataArray[$i]->getAsJSONObject());
		}
		return $propArray;
	}
	public function getDataAsInputBlock()
	{
		$result = '<p>Характеристики</p>';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= $this->dataArray[$i]->getDataAsInputField();
		}
		return $result;
	}
	public function getDataAsInputBlockWithValues($tabletProps)
	{
		$result = '<p>Характеристики</p>';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$fieldHTML = $this->dataArray[$i]->getDataAsInputField();
			for ($j = 0; $j < count($tabletProps); $j++) {
				if ($tabletProps[$j]['property_id'] == $this->dataArray[$i]->getId()) {
					$fieldHTML = $this->dataArray[$i]->getDataAsInputFieldWithValue($tabletProps[$j]['value']);
					break;
				}
			}

			$result .= $fieldHTML;
		}

		return $result;
	}
	/* public function insertIntoDatabase($name,$units){
            $stmt = $this->conn->prepare("INSERT INTO `property` VALUES(DEFAULT,?,?);");
            $stmt->bind_param("ss", $name,$units);
            $stmt->execute();
            $last_id = $this->conn->insert_id;
			return $last_id; 
        }*/

	public function insertIntoDatabase($name, $units)
	{
		// Перевірка, чи існує властивість
		$stmt = $this->conn->prepare("SELECT * FROM `property` WHERE name = ?;");
		$stmt->bind_param("s", $name);
		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			// Якщо властивість вже існує
			return "exists";
		} else {
			// Якщо властивість не існує, додати нову
			$stmt = $this->conn->prepare("INSERT INTO `property` VALUES(DEFAULT,?,?);");
			$stmt->bind_param("ss", $name, $units);
			$stmt->execute();
			$last_id = $this->conn->insert_id;
			return "success";
		}
	}
}
class Property
{
	private $id;
	private $name;
	private $units;
	public function __construct($id, $name, $units)
	{
		$this->id = $id;
		$this->name = $name;
		$this->units = $units;
	}
	public function getId()
	{
		return $this->id;
	}
	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}
	public function getDataAsInputField()
	{
		return '<p><input type="text" placeholder="' . $this->name . ' (' . $this->units . ')" name="prop_' . $this->id . '" required/></p>';
	}
	public function getDataAsInputFieldWithValue($value)
	{
		return '<p><input type="text" value="' . $value . '" placeholder="' . $this->name . ' (' . $this->units . ')" name="prop_' . $this->id . '" required/></p>';
	}
	public function getDataAsTableRow()
	{
		return "
				<tr>
					<td>" . $this->id . "</td>
					<td>" . $this->name . "</td>
					<td>" . $this->units . "</td>
					<td>
					<a href='property.php?id=" . $this->id . "'>Редагувати</a>
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
		return $this->id . ". " . $this->name . " <i>(" . $this->units . ")</i></br>";
	}
}
