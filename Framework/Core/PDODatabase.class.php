<?php
/**
* Database management class
* To Connect to the database and perform various common tasks
* Using PDO interface
*/
class PDODatabase
{
	/**
	* stores multiple Connections in an array
	* each Connection is an element of the array
	*/
    private $Connections;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* chose which Connection from multiple Connections to be used in a query
	*/
	private $ActiveConnection = 0;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* queries which have been executed and results stored in an array to be used later
	* useful within a template engine
	*/
    private $QueryCache = array();
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* data which have been stored in an array to be used later
	* useful within a template engine
	*/
    private $DataCache = array();
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* the last executed query
	* not in a prepared statement
	*/
    private $LastGeneralQuery;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* the last prepared query in a prepared statement
	*/
    private $LastPreparedQuery;
	/*-----------------------------------------------------------------------------------------------------------*/
	
	
	
	
	
	/**
	* class constructor
	*/
    public function __construct(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	
	
	
	
	/**
	* Connect to database
	* @param String $host database host server
	* @param String $username database username
	* @param String $password database password
	* @param String $dbname database name
	* @return int the id of the active Connection
	*/
    public function Connect($host,$username,$password,$dbname){
		try {
			$this->Connections[] = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
			$connectionId = count($this->Connections)-1;
			// set the PDO error mode to exception
    		$this->Connections[$connectionId]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->ActiveConnection = $connectionId;
			return $this->ActiveConnection;
    	}
		catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* closes active database Connection
	* @return void
	*/
    public function CloseConnection(){
		$this->Connections[$this->ActiveConnection] = NULL;
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	public function isConnected(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Change which database Connection is actively used for the next operation
	* @param int the new Connection id
	* @return void
	*/
	public function SetActiveConnection($new){
		$this->ActiveConnection = $new;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* store an executed query to cache array
	* @param String $queryStr the SQL string
	* @return int the id of the cached query
	*/
    public function CacheQuery($queryStr){
        try{
            $cachedQuery = $this->Connections[$this->ActiveConnection]->query($queryStr);
            $this->QueryCache[] = $cachedQuery;
            return count($this->QueryCache)-1;
        }
        catch(PDOException $e){
			echo "Error in caching query: " . $e->getMessage();
            return -1;
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @param int $cachePointer the index of the cached query in query cache array
	* @return int number of record returned by a cached query
	*/
    public function GetRowNumberFromCachedQuery($cachePointer){
        return $this->QueryCache[$cachePointer]->rowCount();
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @param int $cachePointer the index of the query cache array
	* @return array the record by a cached query
	*/
    public function GetResultsFromCachedQuery($cachePointer){
        return $this->QueryCache[$cachePointer]->fetch(PDO::FETCH_ASSOC);
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* store some data to data cache array to be used later
	* @param array $data the data to be stored
	* @return int id of the data that has been catched
	*/
    public function CacheData($data){
        $this->DataCache[] = $data;
        return count($this->DataCache)-1;
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @param int $cachePointer the index of the cached data in the data cache array
	* @return array the data from data cache array
	*/
    public function GetDataFromCache($cachePointer){
        return $this->DataCache[$cachePointer];
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* executes a query
	* @param String $queryStr the SQL string of a query
	* return void
	*/
    public function ExecuteQuery($queryStr){
		try{
		    $newGeneralQuery = $this->Connections[$this->ActiveConnection]->query($queryStr);
            $this->LastGeneralQuery = $newGeneralQuery;
		}
        catch(PDOException $e){
			echo "Error in executing query: " . $e->getMessage();
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
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
        catch(PDOException $e){
			echo "Error in preparing query: " . $e->getMessage();
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @return the last prepared query
	*/
	public function GetLastPreparedQuery(){
	    return $this->LastPreparedQuery;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @return int number of database record returned by the last executed general query
	*/
    public function GetRowNumber(){
		return $this->LastGeneralQuery->rowCount();
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @return int number of database record returned by the last executed prepared query
	*/
	public function GetRowCount(){
		return $this->LastPreparedQuery->rowCount();
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @return array result data by the last executed general query
	*/
    public function GetResultData(){
        return $this->LastGeneralQuery->fetch(PDO::FETCH_ASSOC);
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @return array result data by the last executed prepared query
	*/
	public function FetchResultData(){
        return $this->LastPreparedQuery->fetch(PDO::FETCH_ASSOC);
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	public function CreateTable($fields, $dataTypes, $dataLengths){
		//
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* FOR COMPLEX SELECT QUERY
	* Prepared statement to select a record in database
	* @param String $table the database table
	* @param $fields the names of the columns of the table
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
	public function SelectRecord($table, $fields, $condition=NULL, $params=NULL, $order=NULL, $limit=NULL){
		try{
			$condition = empty($condition) ? "" : " WHERE " . $condition;
			$limit = empty($limit) ? "" : " LIMIT " . $limit;
			$order = empty($order) ? "" : " ORDER BY " . $order;
			$queryString = "SELECT {$fields} FROM {$table} {$condition} {$order} {$limit}";
			$this->PrepareQuery($queryString);
			$this->LastPreparedQuery->execute($params);
		}
		catch(PDOException $e){
			echo "Error in fetching data: " . $e->getMessage();
    	}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* FOR SIMPLE SELECT QUERY
	* Prepared statement to select a record in database
	* @param String $table the database table
	* @param $fields the names of the columns of the table
	* @param array $conditionCols the condition columns
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
	public function Select($table, $fields, $conditionCols=NULL, $params=NULL, $order=NULL, $limit=NULL){
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
			$conditions = empty($conditions) ? "" : " WHERE " . $conditions;
			$limit = empty($limit) ? "" : " LIMIT " . $limit;
			$order = empty($order) ? "" : " ORDER BY " . $order;
			$queryString = "SELECT {$fields} FROM {$table} {$conditions} {$order} {$limit}";
			$this->PrepareQuery($queryString);
			$this->LastPreparedQuery->execute($params);
		}
		catch(PDOException $e){
			echo "Error in fetching data: " . $e->getMessage();
    	}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* prepared statement to insert a record in database
	* @param String $table the database table
	* @param array $fieldNames the name of the columns of the table
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
    public function InsertRecord($table, $fieldNames, $params){
		//INSERT INTO Table (column1,column2,column3) VALUES (?,?,?)
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
			$this->LastPreparedQuery->execute($params);
			return TRUE;
        }
        catch(PDOException $e){
			echo "Error in inserting data: " . $e->getMessage();
			return FALSE;
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* prepared statement to update a record in database
	* @param String $table the database table
	* @param array $changes the name of the columns of the table to be updated
	* @param array $conditions the conditional columns
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
    public function UpdateRecord($table, $updatingCols, $conditionCols, $params){
		//UPDATE Table SET column1=?,column2=? WHERE column=?
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
			$this->LastPreparedQuery->execute($params);
        	return TRUE;
		}
		catch(PDOException $e){
			echo "Error in updating data: " . $e->getMessage();
			return FALSE;
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* USE THIS FOR COMPLEX CONDITIONS SUCH AS MIXING 'AND', 'OR' LOGIC
	* Deletes a record from database using prepared statement
	* @param String $table the database table
	* @param String $condition the condition of selecting record in the table
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
	public function DeleteRecord($table, $condition, $params, $limit=NULL){
		//DELETE FROM Table WHERE column1=? LIMIT n
        try{
			$limit = empty($limit) ? "" : " LIMIT " . $limit;
			$queryString = "DELETE FROM {$table} WHERE {$condition} {$limit}";
			
            $this->PrepareQuery($queryString);
            $this->LastPreparedQuery->execute($params);
			return TRUE;
        }
        catch(PDOException $e){
			echo "Error in deleting data: " . $e->getMessage();
			return FALSE;
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* FOR SIMPLE DELETE QUERY
	* Deletes a record from database using prepared statement
	* @param String $table the database table
	* @param String $condition the condition of selecting record in the table
	* @param array $params the parameters against the placeholders
	* @return bool
	*/
	public function Delete($table, $conditionCols, $params, $limit=NULL){
		//DELETE FROM Table WHERE column1=? LIMIT n
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
			$queryString = "DELETE FROM {$table} WHERE {$conditions} {$limit}";
			
            $this->PrepareQuery($queryString);
            $this->LastPreparedQuery->execute($params);
			return TRUE;
        }
        catch(PDOException $e){
			echo "Error in deleting data: " . $e->getMessage();
			return FALSE;
    	}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
    public function __destruct(){
        
    }

}
?>