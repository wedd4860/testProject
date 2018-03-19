<?php
if (!defined('_KIMILJUNG_')) exit;
class Database{
    private $dbname;
    private $host = 'localhost';
	private $port = '3306';
    private $user = '';
    private $pass = '';
    private $stmt;
    private $dbh;
    private $error;

    public function __construct($DB_NAME = null, $HOST = null, $PORT = null, $USER = null, $PASS = null){
    	if(!is_null($DB_NAME)){
			$this->dbname = $DB_NAME;
    	}
    	if(!is_null($HOST)){
			$this->host = $HOST;
    	}
    	if(!is_null($PORT)){
			$this->port = $PORT;
    	}
    	if(!is_null($USER)){
			$this->user = $USER;
    	}
    	if(!is_null($PASS)){
			$this->pass = $PASS;
    	}

		// Set DSN
		$dsn='mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbname;

		// Set options
		$options = array(
			/*임시 : 지속적 연결 해제
			PDO::ATTR_PERSISTENT    => true,
			*/
			PDO::ATTR_PERSISTENT    => false,
			PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
		);

		// Create a new PDO instanace
		$this->dbh=new PDO($dsn,$this->user,$this->pass,$options);
		$this->prepare("set names 'utf8'");
    }

    public function prepare($query){
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
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
        $this->stmt->bindValue($param, $value, $type);
    }

//
    public function execute(){
		try{
	        return $this->stmt->execute();
		}catch (PDOException $e){
			$this->errorReport($e);
		}
    }
//전부다
    public function dataAllFetch(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//싱글
    public function dataFetch(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

	private function errorReport($e){
		global $debugMode;
		if($debugMode){					// 화면 출력
			print_r('<pre>');
			print_r("SQL error (" . $e->getMessage() . "): <br />" . $this->stmt->queryString . " <br />\n\n");
			print_r('</pre>');
		}

		$tempErrorLogFile = null;
		$serverNameF = substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.'));
		$tempErrorLogFile = _DOCUMENT_ROOT. "/logs/" . $serverNameF ."_". _SERVICE_NAME . "_error.log";

		$postData = '';
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			while (list ($key, $val) = each ($_POST)) {
				if ($key != 'submit' && $key != 'button' && $key != 'fm') {
					if (is_array($val)) {
						$postData .= '   '. $key . ': ';
						while (list ($key2, $val2) = each ($val)) {
							$postData .= $val2 . ' | ';
						}
						$postData .= "\n";
					} else {
						$postData .= '   '. $key . ': ' . $val . "\n";
					}
				}
			}
		}

		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $postData .= 'HTTP_ACCEPT_LANGUAGE => ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "\n";
		if (isset($_SERVER['HTTP_UA_CPU'])) $postData .= 'HTTP_UA_CPU => ' . $_SERVER['HTTP_UA_CPU'] . "\n";
		if (isset($_SERVER['HTTP_USER_AGENT'])) $postData .= 'HTTP_USER_AGENT => ' . $_SERVER['HTTP_USER_AGENT'] . "\n";
		if (isset($_SERVER['HTTP_COOKIE'])) $postData .= 'HTTP_COOKIE => ' . $_SERVER['HTTP_COOKIE'] . "\n";
		if (isset($_SERVER['REMOTE_HOST'])) $postData .= 'REMOTE_HOST => ' . $_SERVER['REMOTE_HOST'] . "\n";
		if (isset($_SERVER['REMOTE_ADDR'])) $postData .= 'REMOTE_ADDR => ' . $_SERVER['REMOTE_ADDR'] . "\n";
		if (isset($_SERVER['REQUEST_METHOD'])) $postData .= 'REQUEST_METHOD => ' . $_SERVER['REQUEST_METHOD'] . "\n";

		error_log("REQUEST: ". $_SERVER['REQUEST_URI'] ."  \nTIME: ". date("Y.m.d H:i:s") ." \n", 3, $tempErrorLogFile);
		if (!empty($postData))		error_log($postData, 3, $tempErrorLogFile);

		error_log("SQL error (" . $e->getMessage() . "): \n   " . $this->stmt->queryString . " \n\n", 3, $tempErrorLogFile);

		exit();
	}
}
/**
 * MySQL database control (for PDO)
 * 사용법

echo "<pre>";

//db connection
$database = new Database();

//single row
$row = array();
$sql = "
	select *
	from custom.GOODS cg
	where cg.STATUS = :status
";
$database->prepare($sql);
$database->bind(':status', 1);
$row = $database->dataFetch();
print_r($row);

//multi rows
$string = '%Customize %';
$rows = array();
$sql = "
	select *
	from custom.GOODS cg
	where cg.G_TITLE_EN like :g_title_en
";
$database->prepare($sql);
$database->bind(':g_title_en', $string);
$rows = $database->dataAllFetch();
print_r($rows);

echo "</pre>";

//db connection close
$database = null;

 *
 */
?>