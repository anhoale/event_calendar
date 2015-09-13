<?php 
class Database{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;
	
	private $dbh;
	private $error;
	private $stmt;
	
	public function __construct(){
		// Set DSN
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		// Set options
		$options = array(
				PDO::ATTR_PERSISTENT    => true,
				PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
		);
		// Create a new PDO instanace
		try{
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}
		// Catch any errors
		catch(PDOException $e){
			$this->error = $e->getMessage();
		}
	}
	
	//The prepare function allows you to bind values into your SQL statements.
	public function query($query){
		$this->stmt = $this->dbh->prepare($query);
	}

	/*
	 * bind the inputs with the placeholders we put in place.(Preven SQL Injection)
	 * Param is the placeholder value that we will be using in our SQL statement, example :name.
	 * Value is the actual value that we want to bind to the placeholder, example “John Smith”
	 * Type is the datatype of the parameter, example string.
	 * 
	 */
	public function bind($param, $value, $type = null){
		//use a switch statement to set the datatype of the parameter:
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		
		//run bindValue
		$this->stmt->bindValue($param, $value, $type);
	
	}
	
	//execute the prepared statement
	public function execute(){
		return $this->stmt->execute();
	}
	
	//The Result Set function returns an array of the result set rows
	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	//Very similar to the previous method, the Single method simply returns a single record from the database.
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}
	
	public function rowCount(){
		return $this->stmt->rowCount();
	}
	
	public function lastInsertId(){
		return $this->dbh->lastInsertId();
	}
	
	public function beginTransaction(){
		return $this->dbh->beginTransaction();
	}
	
	public function endTransaction(){
		return $this->dbh->commit();
	}
	
	public function cancelTransaction(){
		return $this->dbh->rollBack();
	}
	
	public function debugDumpParams(){
		return $this->stmt->debugDumpParams();
	}
}
?>