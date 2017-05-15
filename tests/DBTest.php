<?php

class DBTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $tables = [];

    // Only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // Only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    /**
     * Author Geert Berkers
     *
     * Get Connection with testDatabase
     *
     * @return null|PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO('mysql:host=192.168.1.8;port=3306;dbname=forestDBTest', 'forest', '12Forest12');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, 'forestDBTest');
        }

        return $this->conn;
    }

    /**
     * Author Geert Berkers
     *
     * Get Dataset from database
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        $tables = array();
        array_push($tables, 'games');
        array_push($tables, 'sessions');
        return $this->getConnection()->createDataSet($tables);
    }

    public function setup()
    {
        $this->getConnection();
    }

    public function testGameRowCount()
    {
        $rowCountGames = $this->getDataSet()->getTable('games')->getRowCount();
        self::assertEquals(5, $rowCountGames);
    }

    public function testSessionRowCount()
    {
        $rowCountSession = $this->getDataSet()->getTable('sessions')->getRowCount();
        self::assertEquals(5, $rowCountSession);
    }
}
