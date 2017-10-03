<?php
namespace Kernel;
use PDO;

class Database
{
    private static $_pdo;

    public function __construct($id = null)
    {
        $class = get_called_class();
        if (is_null($id)) {
            $vars = $class::getColumns($class::_getTable());
            foreach ($vars as $v) {
                $key = $v['Field'];
                $this->$key = $v['Default'];
            }
        }
        else {
            $query = 'SELECT * FROM `' . $class::_getTable() . '` WHERE id' . ' = ?';
            $stmt = self::_getPdo()->prepare($query);
            $stmt->execute([$id]);
            $attrs = $stmt->fetchAll(\PDO::FETCH_OBJ);
            if (isset($attrs[0])) {
                foreach ($attrs[0] as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Set and get PDO connection
     * @return PDO
     */
    private static function _getPdo(){
        if (!is_null(self::$_pdo)) return self::$_pdo;
        try
        {
            $db = Config::getDatabase();
            $pdo = new PDO('mysql:dbname='. $db['db'] .';host='. $db['host'], $db['user'], $db['pw']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(\Exception $e)
        {
            die('Error : '.$e->getMessage());
        }
        self::$_pdo = $pdo;
        self::$_pdo->exec('SET NAMES \'utf8\'');
        self::$_pdo->query('SET NAMES \'utf8\'');
        self::$_pdo->prepare('SET NAMES \'utf8\'');
        return self::$_pdo;
    }

    /**
     * Get table which called.
     * @return string
     */
    public static function _getTable() {
        $class = get_called_class();
        if (isset($class::$_table)) {
            return $class::$_table;
        } else {
            return strtolower(explode('\\', $class)[1]);
        }
    }

    /**
     * Get fields by table name
     * @param null $table
     * @return array
     */
    public static function getColumns($table = null) {
        if (is_null($table)) {
            $table = self::_getTable();
        }
        $query = 'show columns from `' . $table . '`';
        try {
            return Connection::query($query)->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            print($query);
            exit($e->getMessage());
        }
    }

    /**
     * Get with SELECT query and clause WHERE
     * @param $where
     * @param array $params
     * @return array
     */
    public static function where($where, $params = []) {
        $query = 'SELECT * FROM `' . self::_getTable() . '` WHERE ' . $where;
        return self::query($query, $params);
    }

    /**
     * Get first value with SELECT query and clause WHERE
     * @param $where
     * @param $params
     * @return array || null
     */
    public static function whereFirst($where, $params) {
        $res = self::where($where, $params);
        return isset($res[0]) ? $res[0] : null;
    }

    /**
     * Get by values
     * @param $params
     * @return array
     */
    public static function find($params) {
        $where = [];
        $p = [];
        foreach ($params as $k => $v) {
            $where[] = '`' . $k . '`=?';
            $p[] = $v;
        }
        return self::where(implode(' and ', $where), $p);
    }

    /**
     * Insert new values
     * @return int
     */
    public function insert()
    {
        $keys = [];
        $values = [];
        foreach ($this as $k => $v){
            $keys[] = '`' . $k . '`';
            $values[] = $v;
        }
        $keys = implode(',', $keys);

        return self::exec(
            'INSERT INTO '. self::_getTable() .'(' . $keys . ') VALUES (?'. str_repeat(', ?', count($values) - 1) .')',
            $values
        );
    }

    /**
     * Update row
     * Values and key with $this
     * @return int
     */
    public function update()
    {
        $keys = [];
        $values = [];
        foreach ($this as $k => $v){
            $keys[] = '`' . $k . '`';
            $values[] = $v;
        }
        $values[] = $this->id;

        var_dump($values);

        return self::exec(
            'UPDATE '. self::_getTable() .' SET '. implode(' = ?, ', $keys) . ' = ?' . ' WHERE id = ?',
            $values
        );
    }

    /**
     * Delete row from database
     * @return mixed
     */
    public function delete() {
        $res = $this->exec('DELETE FROM ' . self::_getTable() . ' WHERE `id`' . ' = ?', [$this->id]);
        unset($this);
        return $res;
    }

    /**
     * Get first value with by values
     * @param $params
     * @return array || null
     */
    public static function findOne($params) {
        $array = self::find($params);
        return isset($array[0]) ? $array[0] : null;
    }

    /**
     * @return int
     */
    public static function getLastId()
    {
        return self::_getPdo()->lastInsertId();
    }

    /**
     * @param $statement string
     * @param $params array
     * @return array
     */
    public static function query($statement, $params = null){
        $q = self::_getPdo()->prepare($statement);
        $q->execute($params);
        $res = $q->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }

    /**
     * @param $statement string
     * @param $params array
     * @return int
     */
    public static function exec($statement,$params){
        $q = self::_getPdo()->prepare($statement);
        return $q->execute($params);
    }
}