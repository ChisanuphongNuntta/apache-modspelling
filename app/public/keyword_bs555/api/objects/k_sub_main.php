<?php

class K_sub_main
{

	private $conn;

	private $tbl_k_sub_main = "k_sub_main";
	private $tbl_k_main = "k_main";

	public $ksid;
	public $kid;
	public $count_in_gk;
	public $keyword;
	public $data_stat;
	public $date_create;

	public function __construct($db)
	{
		$this->conn = $db;
	}//end constructor
//////////////////////////////////////////////////////////////////////////////////

	function get_all_k_sub_main()
	{

		$sql = "SELECT * FROM " . $this->tbl_k_sub_main . " WHERE kid = :kid";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':kid', $this->kid);
		$stmt->execute();

		return $stmt;
	}//end function get all Events status Code
//////////////////////////////////////////////////////////////////////////////////

	function update_k_sub_main()
	{


		$sql = "UPDATE " . $this->tbl_k_main . "
				SET
					`count_in_gk` = :count_in_gk
				WHERE `kid` = :kid;
				";
		// echo print_r($this, true);
		$lastId = null;

		try {
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':kid', $this->kid);
			$stmt->bindParam(':count_in_gk', $this->count_in_gk);

			$stmt->execute();
			// $stmt->debugDumpParams();

			$lastId = $this->conn->lastInsertId();

			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			echo 'Exception: ' . $e->getMessage();
		}

		return $lastId;
	}//end function get all Events status Code
//////////////////////////////////////////////////////////////////////////////////
	function insert_k_sub_main()
	{


		$sql = "INSERT INTO ". $this->tbl_k_sub_main ."
					(`kid`,
					`keyword`,
					`date_create`)
				VALUES
					(:kid,
					:keyword,
					NOW());";
		// echo print_r($this, true);
		$lastId = null;

		try {
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':kid', $this->kid);
			$stmt->bindParam(':keyword', $this->keyword);

			$stmt->execute();
			// $stmt->debugDumpParams();

			$lastId = $this->conn->lastInsertId();

			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			echo 'Exception: ' . $e->getMessage();
		}

		return $lastId;
	}//end function get all Events status Code
//////////////////////////////////////////////////////////////////////////////////

}//end class
?>