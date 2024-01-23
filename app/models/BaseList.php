<?php
abstract class BaseList{
		protected $dataArray;
		protected $index;
		protected $conn;
		public function __construct($conn){
			$this->dataArray=[];
			$this->index=0;
			$this->conn=$conn;
		}
		public function convertToJSON(){
			header("Content-type: application/json");
			$jsonArray=[];
			for ($i=0; $i<count($this->dataArray);$i++){
				array_push($jsonArray,$this->dataArray[$i]->getAsJSONObject());
			}
			return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
		}
		public function getTable(){
			$tableContent='';
			for ($i=0; $i<count($this->dataArray);$i++){
				$tableContent.=$this->dataArray[$i]->getDataAsTableRow();
			}
			return $tableContent;
		}
	}