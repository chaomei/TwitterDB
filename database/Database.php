<?php

class Database {
    private $db_name;
    private $connection;

    public function __construct($dbname) {
        $this->db_name = $dbname;
        
        define('MYSQL_USER', getenv('MYSQL_USER'));
        define('MYSQL_PASS', getenv('MYSQL_PASS'));

        $dsn = 'mysql:host=localhost;dbname=' . $this->db_name;
        try {

            $this->connection = new PDO($dsn, MYSQL_USER, MYSQL_PASS);
            echo 'Successfully connected to database ' . $this->db_name . '!<br>';
        } catch (PDOException $e) {
            echo 'Exception encountered: ' . $e->getMessage() . '<br>';
            $check = strpos($e->getMessage(), 'Unknown database');
            if ($check != false) {
                $this->createDatabase($dbname);
            }  // end of check       
        }   // end of try ... catch
    }

    public function createDatabase($dbname) {
        echo 'Creating database ' . $dbname . ' ......<br>';

        try {
            $this->connection = new PDO('mysql:host=localhost', MYSQL_USER, MYSQL_PASS);
            $this->db_name = $dbname;
            $this->connection->exec("CREATE DATABASE " . $this->db_name);

            echo 'Database ' . $this->db_name . ' has been created successfully!<br>';
        } catch (PDOException $e) {
            echo 'Exception encountered: ' . $e->getMessage() . '<br>';
        }  // end of try ... catch          
    }

    public function createTable($table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (               
                id VARCHAR(30) NOT NULL,               
                date DateTime,               
                text VARCHAR(140),               
                screen_name VARCHAR(30),            
                PRIMARY KEY (id)               
                )";

        try {
            $this->connection->exec($sql);
            echo 'Table ' . $table_name . ' has been created successfully!<br>';
        } catch (PDOException $e) {
            echo 'Exception encountered: ' . $e->getMessage() . '<br>';
        }
    }

    public function insertTweets($table_name, $array_tweets) {
        $sql = "INSERT INTO " . $table_name . " "
                . "(id, date, text, screen_name) "
                . "VALUES (:id, :date, :text, :screen_name)";
        try {
            $statement = $this->connection->prepare($sql);
            foreach ($array_tweets as $tweet) {
                $parameters = array(
                    ':id' => $tweet->id,
                    ':date' => date('Y-m-d H:i:s', strtotime($tweet->created_at)),
                    ':text' => $tweet->text,
                    ':screen_name' => $tweet->user->screen_name
                );
                $statement->execute($parameters);
            }
        } catch (Exception $ex) {
            echo 'Exception encountered: ' . $ex->getMessage() . '<br>';
        }
    }

    public function retrieveTweets() {
        try {
            $sql = "SELECT text FROM " . $table_name;
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $tweets = array();
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                foreach ($row as $key => $value) {
                    $tweets[] = $value;
                }
            }
            return $tweets;
        } catch (Exception $ex) {
            return 'Something wrong: ' . $ex->getMessage() . '<br>';
        }
    }

}
