<?php
/**
* Database management class
* To connect to the database and perform various common tasks
* Using MySQLi interface
*/
class MySQLiDatabase
{
	/**
	* Stores multiple connections in an array
	* each connection is an element of the array
	*/
    private $Connections;
	
	/**
	* Chose which connection from multiple connections to be used in a query
	*/
	private $ActiveConnection = 0;
	
	/**
	* Queries which have been executed and results stored in an array to be used later
	* Useful within a template engine
	*/
    private $QueryCache = array();
	
	/**
	* Data which have been stored in an array to be used later
	* Useful within a template engine
	*/
    private $DataCache = array();
	
	/**
	* The last executed query
	* Not in a prepared statement
	*/
    private $LastGeneralQuery;
	
	/**
	* The last prepared query in a prepared statement
	*/
    private $LastPreparedQuery;
	
	/**
	* Class constructor
	*/
    public function __construct(){
    }
	
	/**
	* Connect to database
	* @param String $host database host server
	* @param String $username database username
	* @param String $password database password
	* @param String $dbname database name
	* @return int the id of the active connection
	*/
    public function Connect($host,$username,$password,$dbname){
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		try {
			$this->Connections[] = new mysqli($host, $username, $password, $dbname);
			$connectionId = count($this->Connections)-1;
			$this->ActiveConnection = $connectionId;
			return $this->ActiveConnection;
    	}
		catch(Exception $e){
			echo "Could not connect to database: " . $e->getMessage();
    	}
    }
	
	/**
	* Closes active database connection
	* @return void
	*/
    public function CloseConnection(){
        $this->connection->close();
    }
	
	public function IsConnected(){}
	
	/**
	* Store an executed query to cache array
	* @param String $queryStr the SQL string
	* @return int the id of the cached query
	*/
    public function CacheQuery($queryStr){
        try{
            $cachedQuery = $this->Connections[$this->ActiveConnection]->query($queryStr);
            $this->QueryCache[] = $cachedQuery;
            return count($this->QueryCache)-1;
        }
        catch(Exception $e){
			echo "Error in caching query: " . $e->getMessage();
            return -1;
    	}
    }
	
	/**
	* @param int $cachePointer the index of the cached query in query cache array
	* @return int number of record returned by a cached query
	*/
    public function GetRowNumberFromCachedQuery($cachePointer){
        return $this->QueryCache[$cachePointer]->num_rows;
    }
	
	/**
	* @param int $cachePointer the index of the query cache array
	* @return array the record by a cached query
	*/
    public function GetResultsFromCachedQuery($cachePointer){
        return $this->QueryCache[$cachePointer]->fetch_array(MYSQLI_ASSOC);
    }
	
	/**
	* store some data to data cache array to be used later
	* @param array $data the data to be stored
	* @return int id of the data that has been catched
	*/
    public function CacheData($data){
        $this->DataCache[] = $data;
        return count($this->DataCache)-1;
    }
	
	/**
	* @param int $cachePointer the index of the cached data in the data cache array
	* @return array the data from data cache array
	*/
    public function GetDataFromCache($cachePointer){
        return $this->DataCache[$cachePointer];
    }
	
	/**
	* executes a non-prepared query
	* @param String $queryStr the SQL string of a query
	* return void
	*/
    public function ExecuteQuery($queryStr){
		try{
		    $newGeneralQuery = $this->Connections[$this->ActiveConnection]->query($queryStr);
            $this->LastGeneralQuery = $newGeneralQuery;
		}
        catch(Exception $e){
			echo "Error in executing query: " . $e->getMessage();
    	}
    }
	
	/**
	* prepared statement
	* prepare a query
	* @param String $queryStr the SQL string of a query
	* @return void
	*/
    public function PrepareQuery($queryStr){
        try{
            $newPreparedQuery = $this->Connections[$this->ActiveConnection]->prepare($queryStr);
            $this->LastPreparedQuery = $newPreparedQuery;
        }
        catch(Exception $e){
			echo "Error in preparing query: " . $e->getMessage();
    	}
    }
	
	/**
	* @return the last prepared query
	*/
	public function GetLastPreparedQuery(){
	    return $this->LastPreparedQuery;
	}
	
	/**
	* @return int number of database record returned by the last executed general query
	*/
    public function GetRowNumber(){
        return $this->LastGeneralQuery->num_rows;
    }
	
	/**
	* @return int number of database record returned by the last executed prepared query
	*/
	public function GetRowCount(){
        return $this->LastPreparedQuery->num_rows;
    }
	
	/**
	* @return array result data by the last executed general query
	*/
    public function GetResultData(){
        return $this->LastGeneralQuery->fetch_array(MYSQLI_ASSOC);
    }
	
	/**
	* @return array result data by the last executed prepared query
	*/
	public function FetchResultData(){
        return $this->LastPreparedQuery->fetch_array(MYSQLI_ASSOC);
    }
	
	/**
	* Bind parameters to prepared query
	* @var string parameTypes the parameter types separated with comma
	* @var array params the parameters
	* @return string the bound parameters with types
	*/
	public function BindParam($params = array()){
		$types = "";
		$values = "";
		$bound = "";
		foreach($params as $param){
			if(gettype($param) === "integer"){
        		$types .= "i";
    		}
    		elseif(gettype($param) === "string"){
        		$types .= "s";
    		}
			$values .= "$param\,";
		}
		$values = substr($values, 0, -1);
		$bound = "\"{$types}\"\, {$values}";
		return $bound;
	}
	
	/**
	* prepared statement to select record(s) from database
	* @param string $table the database table
	*/
	public function SelectRecord($table, $fields, $condition=NULL, $params=NULL, $order=NULL, $limit=NULL){
		try{
			$condition = empty($condition) ? "" : " WHERE " . $condition;
			$limit = empty($limit) ? "" : " LIMIT " . $limit;
			$order = empty($order) ? "" : " ORDER BY " . $order;
			$queryString = "SELECT {$fields} FROM {$table} {$condition} {$order} {$limit}";
			$this->PrepareQuery($queryString);
			if(!empty($params)){
				$this->LastPreparedQuery->bind_param($this->BindParam($params));
			}
			$this->LastPreparedQuery->execute();
		}
		catch(Exception $e){
			echo "Error in fetching data: " . $e->getMessage();
    	}
	}
	
	/**
	* prepared statement to insert a record into a database table
	* @param String $table the database table
	* @param array $fieldNames the name of the columns of the table
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
    public function InsertRecord($table, $fieldNames = array(), $params = array()){
		// INSERT INTO Table (column1,column2,column3) VALUES (?,?,?)
        try{
            $fields = "";
            $placeHolders = "";
            foreach($fieldNames as $f){
            	$fields .= "$f,";
            	$placeHolders .= "?,";
            }
			//remove trailing comma(,) of field,value and placeholder
			$fields = substr($fields, 0, -1);
			$placeHolders = substr($placeHolders, 0, -1);
			$queryString = "INSERT INTO {$table} ({$fields}) VALUES ({$placeHolders})";
			$this->PrepareQuery($queryString);
			$this->LastPreparedQuery->bind_param($this->BindParam($params));
			$this->LastPreparedQuery->execute();
			$this->LastPreparedQuery->close();
			return TRUE;
        }
        catch(Exception $e){
			echo "Error in inserting data: " . $e->getMessage();
			return FALSE;
    	}
    }
	
	/**
	* UPDATE METHOD
	* prepared statement to update a record in database
	* @param String $table the database table
	* @param array $changes the name of the columns of the table to be updated
	* @param array $conditions the conditional columns
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
    public function UpdateRecord($table, $updatingCols = array(), $conditionCols = array(), $params = array()){
		// UPDATE Table SET column1=?,column2=? WHERE column=?
		try{
			$updates = "";
            $conditions = "";
            foreach($updatingCols as $c){
            	$updates .= "{$c}=?,";
            }
			//remove trailing comma
			$updates = substr($updates, 0, -1);
			foreach($conditionCols as $con){
				if(count($conditionCols) === 1){
            		$conditions = "{$con}=?";
				}
				elseif(count($conditionCols) > 1){
					$conditions .= "{$con}=? AND ";
				}
            }
			//remove trailing " AND "
			if(count($conditionCols) > 1){
				$conditions = substr($conditions, 0, -5);
			}
			$queryString = "UPDATE {$table} SET {$updates} WHERE {$conditions}";
			$this->PrepareQuery($queryString);
			$this->LastPreparedQuery->bind_param($this->BindParam($params));
			$this->LastPreparedQuery->execute();
			$this->LastPreparedQuery->close();
        	return TRUE;
		}
		catch(Exception $e){
			echo "Error in updating data: " . $e->getMessage();
			return FALSE;
    	}
    }
	
	/**
	* DELETE METHOD
	* deletes a record from database using prepared statement
	* @param String $table the database table
	* @param String $condition the condition of selecting record in the table
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
	public function DeleteRecord($table,$conditionCols,$params,$limit=NULL){
		// DELETE FROM Table WHERE column1=? LIMIT n
        try{
            $conditions = "";
			foreach($conditionCols as $con){
				if(count($conditionCols) === 1){
            		$conditions = "{$con}=?";
				}
				elseif(count($conditionCols) > 1){
					$conditions .= "{$con}=? AND ";
				}
            }
			//remove trailing " AND "
			if(count($conditionCols) > 1){
				$conditions = substr($conditions, 0, -5);
			}
			$limit = empty($limit) ? "" : " LIMIT " . $limit;
			$queryString = "DELETE FROM {$table} WHERE {$condition} {$limit}";
			
            $this->PrepareQuery($queryString);
            $this->LastPreparedQuery->bind_param($this->BindParam($params));
			$this->LastPreparedQuery->execute();
			$this->LastPreparedQuery->close();
			return TRUE;
        }
        catch(Exception $e){
			echo "Error in deleting data: " . $e->getMessage();
			return FALSE;
    	}
    }
    public function __destruct(){       
    }

}
?>