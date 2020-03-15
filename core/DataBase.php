<?php
/**
* this is db.php database application.
*/
namespace wilroy;
use \PDO;
/**
 * This is db class to insert and update
 */
class DataBase
{
	private function connect()
	{
		try
		{
			//$settings = parse_ini_file(realpath("../config/config.ini"), true);
   			$settings = parse_ini_file(__DIR__ . "/../config/config.ini", true);
			$servername = $settings['db']['host'];
			$username = $settings['db']['username'];
			$password = $settings['db']['password'];
			$dbname = $settings['db']['dbname'];
			//print_r($settings);
			try{
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			}
			catch(\Exception $e){
				if($settings['debug']['debug'] == 1)
				{
					echo 'Error: ',  $e->getMessage(), "\n";
					die();
				}
				else{
					echo "error in connecting database please contact Administrator";
					die();
				}

			}
				// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "Connected successfully";
			return $conn;
		}
		catch(PDOException $e)
		{
			//echo "Connection failed: " . $e->getMessage();
			return $conn = false;
		}
	}

/********************************insert***********************************************/

	function insert($query , $para)
	{
		$insertdb = $this-> connect();
		if($insertdb === false)
		{
			echo "Database connect error";
		}
		else
		{
			//echo $query . "<br/>" ;
			//print_r($para);
			//echo "<br/>";
			try {
			$stmt = $insertdb->prepare($query);
			foreach ($para as $k => &$v) {
			//echo "$k => $v.<br/>";
			//$k = ":" . $k;
			$stmt->bindParam($k, $v);
			}
			$stmt->execute();
			$lastid = $insertdb->lastInsertId();
			return $lastid;
			} catch(PDOExecption $e) {
			//print "Error!: " . $e->getMessage() . "</br>";
			echo "Error!: Please contact Administrator";
			}
		}
	}

/*
To call insert function use
$insertDb_test = (new db)->insert($query , $para);
the return result will be , last inserted id of the row.

*/
/********************************delete***********************************************/
	function distroy($query , $para)
	{
		$deletedb = $this -> connect();
		if($deletedb === false)
		{
			echo "Database connect error";
		}
		else
		{
			try {
			$stmt = $deletedb->prepare($query);
			foreach ($para as $k => &$v) {
			//echo "$k => $v.<br/>";
			//$k = ":" . $k;
			$stmt->bindParam($k, $v);
			}
			$stmt->execute();
			echo "Record / Records Deleted!";
			}catch(PDOExecption $e) {
			//print "Error!: " . $e->getMessage() . "</br>";
			echo "Error!: Please contact Administrator";
			}
		}
	}
/*
To call delete function use
$deleteDb_test = (new db)->delete($query , $para);
the return result will be , echo message "Record / Records Deleted!".
*/
/********************************select***********************************************/
	function select($query , $para)
	{
		$selectdb = $this -> connect();
		if($selectdb === false)
		{
			echo "Database connect error";
		}
		else
		{
			try {
			//echo $query. "<br/>";
			$stmt = $selectdb->prepare($query);
			foreach ($para as $k => &$v) {
			//echo "$k => $v.<br/>";
			//$k = ":" . $k;
			$stmt->bindParam($k, $v);
			}
			$stmt->execute();
			//$selectdb->execute($query);
			//echo "Records Fetched!";
			$result["num_rows"] = $stmt -> rowCount();
			$result["result"] = $stmt;
			return $result;
			}catch(PDOExecption $e) {
			//print "Error!: " . $e->getMessage() . "</br>";
			echo "Error!: Please contact Administrator";
			}
		}
	}
/*
To call select function use
$selectDb_test = (new db)->select($query , $para);
the return result will be array -> $result["num_rows"] i.e number of rows AND $result["result"] i.e. all selected records.
*/
/********************************update***********************************************/
	function update($query , $para)
	{
		$updatedb = $this -> connect();
		if($updatedb === false)
		{
			echo "Database connect error";
		}
		else
		{
			try {
			//echo $query. "<br/>";
			$stmt = $updatedb->prepare($query);
			foreach ($para as $k => &$v) {
			//echo "$k => $v.<br/>";
			//$k = ":" . $k;
			$stmt->bindParam($k, $v);
			}
			$stmt->execute();
			//$selectdb->execute($query);
			//echo "Records Fetched!";
			$result["num_rows"] = $stmt -> rowCount();
			return $result;
			}catch(PDOExecption $e) {
			//print "Error!: " . $e->getMessage() . "</br>";
			echo "Error!: Please contact Administrator";
			}
		}
	}
/********************************create***********************************************/
	function create($query)
	{
		try
		{
			$createtabledb = $this -> connect();
			if($createtabledb === false)
			{
				echo "Database connect error";
			}
			else
			{
				$createtabledb -> exec($query);
				//$selectdb->execute($query);
				echo "table Created!";
			}
		}
		catch(PDOException $e)
		{
			//echo $query . "<br>" . $e->getMessage();
			echo "Error please contact Administrator!";
		}
	}
/**********************************test***********************************************/
	function testconnect()
	{
		try
		{
			$testconnection = $this -> connect();
			if($testconnection === false)
			{
				//echo "Not Connected";
				return 0;
			}
			else
			{
				//echo "Connected Successfully";
				return 1;
			}
		}
		catch(PDOException $e)
		{
			return "There is error in connection to database contact Administrator";
		}
	$testconnection = null;

	}
/***********************************get column name**************************************/
	function getcolumnname($tablename)
	{
		$sql = "select column_name from information_schema.columns where table_name = :test AND column_default is null and extra !=  'auto_increment'";
		//$sql = "select column_name from information_schema.columns where table_name = :test AND extra !=  'auto_increment'";
		//$sql = "select column_name from information_schema.columns where table_name = :test";
		$dbConn = $this -> connect();
		if($dbConn === false)
			{
				echo "Database connect error";
			}
			else
			{
				$s = $tablename;
				$stmt = $dbConn->prepare($sql);

				$stmt->bindParam(":test" , $s);
				$stmt->execute();
				$raw_column_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//print_r($raw_column_data);
				//var_dump($stmt);
				echo "<br/>";
				foreach ($raw_column_data as $k => $v) {
				foreach ($v as $value) {
				//echo "column {$k} value is => {$value}<br />";
				$resultnames[$k] = $value;
				}
				}
				return $resultnames;
			}
	}

/***********************************show tables**************************************/
	function gettablename()
	{
		$sql = "show tables";
		$dbConn = $this -> connect();
		if($dbConn === false)
			{
				echo "Database connect error";
			}
			else
			{
				$stmt = $dbConn->prepare($sql);
				$stmt->execute();
				$raw_column_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//print_r($raw_column_data);
				//var_dump($stmt);
				//echo "<br/>";
				foreach ($raw_column_data as $k => $v) {
				foreach ($v as $value) {
				//echo "column {$k} value is => {$value}<br />";
				$resultnames[$k] = $value;
				}
				}
				return $resultnames;
			}
	}

}
 ?>
