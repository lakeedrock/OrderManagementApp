<?php
  /**
   * PDO Database class
   * Connect to Database
   *Create prepared statements
   * Bind values
   * Return rows and results
   */
  class Database{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $statement;
    private $error;

    public function __construct(){
      // Set DSN
      $dsn = 'mysql:host='. $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );

      //Create PDO instance
      try {
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      } catch (PDOException $e) {
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }

    //Prepare Statement With Query
    public function query($sql){
      $this->statement = $this->dbh->prepare($sql);
    }

    //Bind Values
    public function bind($param, $value, $dataType = null){
      if (is_null($dataType)) {
        switch (true) {
          case is_int($value):
            $dataType = PDO::PARAM_INT;
            break;
          case is_bool($value):
            $dataType = PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $dataType = PDO::PARAM_NULL;
            break;
          default:
            $dataType = PDO::PARAM_STR;
            break;
        }
      }

      $this->statement->bindValue($param,$value,$dataType);
    }

    //Execute the prepared statement
    public function execute(){
      return $this->statement->execute();
    }

    //Get result set as array of Objects
    public function resultSet(){
      $this->execute();
      return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }


    // Get single record as Object
    public function singleResult(){
      $this->execute();
      return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    //Get row count
    public function rowCount(){
      return $this->statement->rowCount();
    }

  }
