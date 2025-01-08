<?php

class K_main
{

	private $conn;

	private $tbl_k_main = "k_main";

	public $kid;
	public $group_keyword;
	public $count_in_gk;

	public $remark;
	public $tag_color;
	public $date_create;

	public function __construct($db)
	{
		$this->conn = $db;
	}//end constructor
//////////////////////////////////////////////////////////////////////////////////

	function get_all_k_main()
	{

		$sql = "SELECT * FROM " . $this->tbl_k_main;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		return $stmt;
	}
//////////////////////////////////////////////////////////////////////////////////

	function update_k_main()
	{


		$sql = "UPDATE " . $this->tbl_k_main . "
				SET
					`group_keyword` = :group_keyword,
					`remark` = :remark,
					`tag_color` = :tag_color
				WHERE `kid` = :kid;
				";
		// echo print_r($this, true);
		$lastId = null;

		try {
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':kid', $this->kid);
			$stmt->bindParam(':group_keyword', $this->group_keyword);
			// $stmt->bindParam(':count_in_gk', $this->count_in_gk);
			$stmt->bindParam(':remark', $this->remark);
			$stmt->bindParam(':tag_color', $this->tag_color);

			$stmt->execute();
			// $stmt->debugDumpParams();

			$lastId = $this->conn->lastInsertId();

			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			echo 'Exception: ' . $e->getMessage();
		}

		return $lastId;
	}
//////////////////////////////////////////////////////////////////////////////////
	function insert_k_main()
	{


		$sql = "INSERT INTO " . $this->tbl_k_main .
			" (`group_keyword`, `remark`, `tag_color`, `date_create`) 
        VALUES (:group_keyword, :remark, :tag_color, NOW())";
		// echo print_r($this, true);
		$lastId = null;

		try {
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':group_keyword', $this->group_keyword);
			// $stmt->bindParam(':count_in_gk', $this->count_in_gk);
			$stmt->bindParam(':remark', $this->remark);
			$stmt->bindParam(':tag_color', $this->tag_color);

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