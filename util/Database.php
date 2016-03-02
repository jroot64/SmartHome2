<?php

/**
 * The database class handles the connection to the database, performs the querys
 * and wrapps the usage of the bound variables.
 */
class Database
{

    private static $instance = null;
    public $valueArray;
    public $paramToBindTo;
    protected $conn;


    /**
     * The function __construct initiates the connection to the database based on the
     * database settings.
     */
    private function __construct($installMode = false)
    {
        require_once(__DIR__ . "/../Settings.php");
//        require_once(__DIR__ . "/PhpArray.php");
        $settings = new Settings();
        $DBhost = $settings->getDBHost();
        $DBname = $settings->getDBname();
        $DBuser = $settings->getDBuser();
        $DBpassword = $settings->getDBpassword();
        if($installMode){
            echo "test";
            $conn = $this->connect($DBhost, $DBname, $DBuser, $DBpassword, true);
        }else{
            $conn = $this->connect($DBhost, $DBname, $DBuser, $DBpassword);
        }

        $this->conn = $conn;
        $this->preparePhpArray();
    }


    /**
     * The function connect connects to the database and sets the encoding to UTF8.
     *
     * @param $DBhost
     * @param $DBname
     * @param $DBuser
     * @param $DBpassword
     * @return PDO
     */
    private function connect($DBhost, $DBname, $DBuser, $DBpassword, $installMode = false)
    {
        if(!$installMode) {
            try {
                $db = new PDO('mysql:host=' . $DBhost . ';dbname=' . $DBname, $DBuser, $DBpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', PDO::ATTR_PERSISTENT => true));
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (PDOException $e) {
                echo $e;
                return false;
            }
        }else{
            $db = new PDO('mysql:host=' . $DBhost , $DBuser, $DBpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', PDO::ATTR_PERSISTENT => true));
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        }
    }


    /**
     * The function preparePhpArray clears the value and bound array and resets the
     * index position of the array to zero.
     */
    private function preparePhpArray()
    {
//        echo "test";
        $this->paramToBindTo = null;
        $this->valueArray = null;

        $this->paramToBindTo[0] = 0;
        $this->valueArray[0] = 0;
    }


    /**
     * The function getInstance adds the ability of an singleton class to the content class.
     * This makes it possible to reach public functions from all points of the code.
     */
    public static function getinstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return Database|null
     */
    public static function getinstanceInstall()
    {
        if (null === self::$instance) {
            self::$instance = new self(true);
        }
        return self::$instance;
    }


    /**
     * The function query executes the given query and bounds the params.
     *
     * @param $querry
     * @return array|bool|null
     */
    public function query($querry)
    {
        $bounds = $this->valueArray;
        $params = $this->paramToBindTo;
        $conn = $this->conn;

        $result = NULL;

        $stmt = $conn->prepare($querry);
        if ($bounds[0] == $params[0]) {
            for ($i = 0; $i < $bounds[0]; $i++) {
                $stmt->bindParam($params[$i + 1], $bounds[$i + 1]);
            }
        }
        try {
            $boolquery = $stmt->execute();
        } catch (PDOException $e) {
            $this->preparePhpArray();
            return false;
        }

        try {
            $result = $stmt->fetchAll();
            $this->preparePhpArray();
            return $result;
        } catch (PDOException $e) {
            if ($boolquery == true) {
                $this->preparePhpArray();
                return true;
            } else {
                $this->preparePhpArray();
                return false;
            }
        }
    }


    /**
     * The function addParam adds the given value to the paramToBindTo array and increments the index value.
     *
     * @param $val
     */

    public function addParam($val)
    {
        $new = $this->paramToBindTo[0] + 1;
        $this->paramToBindTo[$new] = $val;
        $this->paramToBindTo[0] = $new;

    }

    /**
     * The function addBound adds the given value to the valueArray array and increments the index value.
     *
     * @param $val
     */
    public function addBound($val)
    {
        $new = $this->valueArray[0] + 1;
        $this->valueArray[$new] = $val;
        $this->valueArray[0] = $new;

    }


    /**
     * The function testConnection tries to connect to the database and returns a bool whether the connection works
     * or not.
     *
     * @return bool
     */
    static public function testConnection(){
        require_once(__DIR__ . "/../Settings.php");
        $settings = new Settings();
        $DBhost = $settings->getDBHost();
        $DBname = $settings->getDBname();
        $DBuser = $settings->getDBuser();
        $DBpassword = $settings->getDBpassword();

        try {
            $db = new PDO('mysql:host=' . $DBhost . ';dbname=' . $DBname, $DBuser, $DBpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', PDO::ATTR_PERSISTENT => true));
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            $db->query("SELECT * FROM `user`");
//            var_dump($db);
            $db = null;
//            echo "true";
            return true;
        } catch (PDOException $e) {
//            echo $e;
//            echo "false";
//            exit();
            return false;
        }
    }
}