<?php
namespace Project\Config;
use \PDO;

class Database
{
    private static $_pdo;

    private function __construct() {}

    private static function get(){
        if (!is_null(self::$_pdo)) return self::$_pdo;
        try
        {
            $pdo = new PDO('mysql:dbname='.DB.';host='.HOST, USER, PW);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }
        self::$_pdo = $pdo;

        self::$_pdo->exec('SET NAMES \'utf8\'');
        self::$_pdo->query('SET NAMES \'utf8\'');
        self::$_pdo->prepare('SET NAMES \'utf8\'');

        return self::$_pdo;
    }
    /**
     * @param $statement string
     * @param $params array
     * @return array
     */
    public static function query($statement,$params = null){
        $q = self::get()->prepare($statement);
        $q->execute($params);
        $res = $q->fetchAll(PDO::FETCH_OBJ);

        return $res;
    }
    /**
     * @param $statement string
     * @param $params array
     * @return int
     */
    public static function exec($statement,$params){
        $q = self::get()->prepare($statement);
        return $q->execute($params);
    }
    /**
     * @param $id int
     * @param $table string
     * @return array
     */
    public static function getById($id,$table)
    {
        $q = self::get()->prepare('SELECT * FROM '. $table .' WHERE id = :id');
        $q->execute([':id' => $id]);
        $res = $q->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }
    /**
     * @return int
     */
    public static function lastId()
    {
        return self::$_pdo->lastInsertId();
    }
}