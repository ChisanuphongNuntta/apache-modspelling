<?php
	
class Database{
	private $host = "localhost";
	private $db_name = "keyword";
	private $username = "root";
	// private $passwd = "";
	private $passwd = "s3cur3!KBI";
	public $conn;
	
	public function getConnection(){
		$this->conn = null;
		
		try{
			$this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name,$this->username,$this->passwd);
			$this->conn->exec("SET NAMES UTF8");
		}catch(PDOException $ex){
			echo "Connection error : ".$ex->getMessage();
		}
		return $this->conn;
	}//end getConnection
}//end class database	C:\xampp\htdocs\keyword_bs555\api\config\database.php
?>