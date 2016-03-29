<?php
/**
 * Interface for DML functions
 */
interface dmlFunctions {
    public function createData($data);
    public function fetchDataById($id);
    public function searchData($criteria);
}


/**
 * PDO class
 */
class PDODataAccess implements dmlFunctions {
	
	private $conn;
	private $table;
	
	//funcion for database connection	
	public function __construct(){
		$hostname = "localhost";
		$username = "root";
		$password = "";
		$dbname = "test";
		$this->conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
		$this->table = "datasource";
	}
	
	public function __destruct() {
		$this->conn = null;
	}
	
    public function createData($data){
		try {
			if(!empty($data)) {
				$stmt = $this->conn->prepare("INSERT INTO ".$this->table." (id, name, description, active, created, updated) 
				VALUES (:id, :name, :description, :active, :created, :updated)"); // prepare sql and bind parameters
				$stmt->bindParam(':id', $data['id']);
				$stmt->bindParam(':name', $data['name']);
				$stmt->bindParam(':description', $data['description']);
				$stmt->bindParam(':active', $data['active']);
				$stmt->bindParam(':created', $data['created']);
				$stmt->bindParam(':updated', $data['updated']);
				$stmt->execute();
				echo "PDO - createData - New record created successfully";
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
    }
	
    public function fetchDataById($id){
		try {
			$stmt = $this->conn->query("SELECT id, name, description, active, created, updated FROM ".$this->table." WHERE id = " . $id); // prepare sql and bind parameters
			$row = $stmt->fetchObject();
			if(!empty($row)) {
				echo "PDO - fetchDataById - Output:<br>Id: ".$row->id."<br>Name: ".$row->name."<br>Description: ".$row->description."<br>Active: ".$row->active."<br>Created: ".$row->created."<br>Updated: ".$row->updated;
			} else {
				echo "Data not found.";
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}	
    }
	
    public function searchData($criteria){
		try {
			$key = key($criteria);
			$value = array_values($criteria);
			$stmt = $this->conn->query("SELECT id, name, description, active, created, updated FROM ".$this->table." WHERE ".$key." like '%" . $value[0] . "%'"); // prepare sql and bind parameters
			$row = $stmt->fetchObject();
			if(!empty($row)) {
				echo "PDO - searchData - Output:<br>Id: ".$row->id."<br>Name: ".$row->name."<br>Description: ".$row->description."<br>Active: ".$row->active."<br>Created: ".$row->created."<br>Updated: ".$row->updated;
			} else {
				echo "Data not found.";
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}	
    }
}


/**
 * REST class
 */
class RESTDataAccess implements dmlFunctions {
	
	private $table;
	private $restURL;
	
	public function __construct(){
		$this->table = "datasource";
		$this->restURL = "http://localhost/projects/giftcards/exercise2/rest.php/";
	}
	
    public function createData($data){
		if(!empty($data)) {
			$service_url = $this->restURL.$this->table.'/';
			$curl = curl_init($service_url);
			$curl_post_data = $data;
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
			$curl_response = curl_exec($curl);
			if ($curl_response === false) {
				$info = curl_getinfo($curl);
				curl_close($curl);
				die('error occured during curl exec. Additioanl info: ' . var_export($info));
			}
			curl_close($curl);
			$decoded = json_decode($curl_response);
			if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
				die('error occured: ' . $decoded->response->errormessage);
			}
			echo "REST - createData - New record created successfully";
		}
    }
	
    public function fetchDataById($id){		
		$service_url = $this->restURL.$this->table.'/'.$id;
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		if(!empty($decoded)) {
			echo "REST - fetchDataById - Output:<br>Id: ".$decoded->id."<br>Name: ".$decoded->name."<br>Description: ".$decoded->description."<br>Active: ".$decoded->active."<br>Created: ".$decoded->created."<br>Updated: ".$decoded->updated;
		} else {
				echo "Data not found.";
		}
    }
	
    public function searchData($criteria){
		$key = key($criteria);
		$value = array_values($criteria);
		echo $service_url = $this->restURL.$this->table.'/'.$key.'/'.$value[0];
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		 
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		if(!empty($decoded)) {
			echo "REST - fetchDataById - Output:<br>Id: ".$decoded->id."<br>Name: ".$decoded->name."<br>Description: ".$decoded->description."<br>Active: ".$decoded->active."<br>Created: ".$decoded->created."<br>Updated: ".$decoded->updated;
		} else {
				echo "Data not found.";
		}
    }
}

 
/**
 * factory class
 */
class DataAccessObjFactory {
    public static function getDataAccessObj($dataAccessType) {
		$dbAccessObj = null;
		if ('REST'==$dataAccessType) {
			$dbAccessObj = new RESTDataAccess();
		} else if ('PDO'==$dataAccessType) {
			$dbAccessObj = new PDODataAccess();
		}
		return $dbAccessObj;
	}
}

/**
 * data access controller class
 */
class DataAccessController {
	
	private $dataAcccessObj;
	public function __construct($dataAccessType){
		$this->dataAcccessObj = DataAccessObjFactory::getDataAccessObj($dataAccessType);
	}

	public function createData($data){
		return $this->dataAcccessObj->createData($data);
	}

	public function fetchDataById($id){
		return $this->dataAcccessObj->fetchDataById($id);
	}
	
	public function searchData($criteria){    
		return $this->dataAcccessObj->searchData($criteria);
	}
}

//Test data
$data = array("id" => 4, "name" => "EFG", "description" => "test data 4", "active" => 1, "created" => "2016-03-01", "updated" => "2016-03-06");
$id = 2;
$criteria = array("name" => "abc");

// Test PDO
$myDataAccessClass = new DataAccessController('PDO');
$myDataAccessClass->createData($data);
//$myDataAccessClass->fetchDataById($id);
//$myDataAccessClass->searchData($criteria);

// Test REST
//$myDataAccessClass1 = new DataAccessController('REST');
//$myDataAccessClass1->createData($data);
//$myDataAccessClass1->fetchDataById($id);
//$myDataAccessClass1->searchData($criteria);
?>