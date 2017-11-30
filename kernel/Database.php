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
            $vars = $class::getColumns($class::getTable());
            foreach ($vars as $v) {
                $key = $v['Field'];
                $this->$key = $v['Default'];
            }
        }
        else {
            $query = 'SELECT * FROM `' . $class::getTable() . '` WHERE id' . ' = ?';
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
     * Set and get PDO
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
    public static function getTable() {
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
            $table = self::getTable();
        }
        $statement = 'SHOW COLUMNS FROM `' . $table . '`';
        try {
            $q = self::_getPdo()->prepare($statement);
            $q->execute();
            return $q->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            print($statement);
            exit($e->getMessage());
        }
    }

    /**
     * Get value of another table with the name of the 'class_id'
     * @param $name : class
     * @return object
     */
    public function __get($name) {
        if ($name == 'id') { return null; }

        $fullName = explode('\\', $name);
        $field = end($fullName) . '_id';
        $fullName = array_map(function ($v){ return ucfirst($v); }, $fullName);
        $class = ucfirst(implode('\\', $fullName));
        $class = 'Models\\' .$class;

        return new $class($this->$field);
    }

    /**
     * Get with SELECT query and clause WHERE
     * @param $where
     * @param array $params
     * @param null $order
     * @param null $limit
     * @return array
     */
    public static function where($where, $params = [], $order = null, $limit = null) {
        $query = 'SELECT * FROM `' . self::getTable() . '` WHERE ' . $where . self::order($order) . self::limit($limit);
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
     * Get last value with by values
     * @param $where
     * @param $params
     * @return array | null
     */
    public static function whereLast($where, $params) {
        $array = self::where($where, $params);
        $lastKey = count($array) - 1;
        return isset($array[$lastKey]) ? $array[$lastKey] : null;
    }

    /**
     * Get by values
     * @param $params
     * @param null $order
     * @param null $limit
     * @return array
     */
    public static function find($params, $order = null, $limit = null) {
        $where = [];
        $p = [];
        foreach ($params as $k => $v) {
            $where[] = '`' . $k . '`=?';
            $p[] = $v;
        }
        return self::where(implode(' and ', $where), $p, $order, $limit);
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
     * Get last value with by values
     * @param $params
     * @return array || null
     */
    public static function findLast($params) {
        $array = self::find($params);
        $lastKey = count($array) - 1;
        return isset($array[$lastKey]) ? $array[$lastKey] : null;
    }

    /**
     * Get first value by id
     * @param $id
     * @return array
     */
    public static function getById($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * Get all datas from table
     * @param null $order
     * @param null $limit
     * @return array
     */
    public static function getAll($order = null, $limit = null)
    {
        return self::query('SELECT * FROM '. self::getTable() . self::order($order) . self::limit($limit));
    }

    /**
     * Set string ORDER BY...
     * @param $order
     * @return string
     */
    public static function order($order) {
        return (null !== $order) ? (' ORDER BY ' . $order) : '';
    }

    /**
     * Set string LIMIT...
     * @param $limit
     * @return string
     */
    public static function limit($limit) {
        return (null !== $limit) ? (' LIMIT ' . $limit) : '';
    }

    /**
     * @param string $where
     * @param array $params
     * @return int
     */
    public static function count($where = null, $params = [])
    {
        if (null !== $where) { $where = ' WHERE '. $where; }
        else { $where = ''; }

        return (int) self::query(
            'SELECT count(*) as nb FROM '. self::getTable() . $where,
            $params
        )[0]->nb;
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
            'INSERT INTO '. self::getTable() .'(' . $keys . ') VALUES (?'. str_repeat(', ?', count($values) - 1) .')',
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

        return self::exec(
            'UPDATE '. self::getTable() .' SET '. implode(' = ?, ', $keys) . ' = ?' . ' WHERE id = ?',
            $values
        );
    }

    /**
     * Delete row from database
     * @return mixed
     */
    public function delete() {
        $params = [];
        $values = [];
        if (empty($this->id)) {
            foreach ($this as $k => $v) {
                if (null != $v) {
                    $params[] = $k;
                    $values[] = $v;
                }
            }
        }
        else {
            $params = ['id'];
            $values[] = $this->id;
        }
        $res = $this->exec('DELETE FROM ' . self::getTable() . ' WHERE ' . implode(' = ? AND ', $params) . ' = ?', $values);
        unset($this);
        return $res;
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
    public static function exec($statement, $params){
        $q = self::_getPdo()->prepare($statement);
        return $q->execute($params);
    }
}
