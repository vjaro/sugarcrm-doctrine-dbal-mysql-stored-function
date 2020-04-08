<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
require_once('include/entryPoint.php');

function getPDOConnection()
{
    global $sugar_config, $db;

    $config = new Doctrine\DBAL\Configuration();
    $params = array(
        'dbname' => $sugar_config['dbconfig']['db_name'],
        'user' => $sugar_config['dbconfig']['db_user_name'],
        'password' => $sugar_config['dbconfig']['db_password'],
        'host' => $sugar_config['dbconfig']['db_host_name'],
        'port' => $sugar_config['dbconfig']['db_port'],
        'driver' => 'pdo_mysql',
        'driverOption' => array(
            PDO::ATTR_EMULATE_PREPARES => true
        )
    );
    $conn = $db->getConnection();
    try {
        $pdoCon = Doctrine\DBAL\DriverManager::getConnection($params, $config);
        if ($pdoCon) {
            $driver = $pdoCon->getDriver();
            $conn = $pdoCon;
        }
    } catch (Exception $e) {
        $GLOBALS["log"]->fatal($e->getMessage());
    }

    return $conn;
}

function createStoredFunction()
{
    $conn = getPDOConnection();
    if($conn){
        $query = "getAge.sql";
        $contents = file_get_contents($query);
        $res = $conn->executeQuery($contents);
        if (!$res){
            return "failed to install stored function \n";
        }else{
            return "successfully installed stored function \n";
        }
    }

}

print_r(createStoredFunction());
