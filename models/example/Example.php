<?php
namespace Project\Models;
use Project\Config\Database;

class Example
{
    private $_id;
    private $_name;
    private $_pw;

    public function __construct($exampleObject) // Object of example
    {
        $this->_id   = $exampleObject->id   ? $exampleObject->id   : null;
        $this->_name = $exampleObject->name ? $exampleObject->name : null;
        $this->_pw   = $exampleObject->pw   ? $exampleObject->pw   : null;
    }

    public static function get($param = null)
    {
        // $params is not required -> You can remove $params
        if (!empty($param->id)){
            return Database::query('SELECT * FROM example WHERE id = ?', [$param->id]);
        } else {
            return Database::query('SELECT * FROM example');
        }
    }

    public function insert()
    {
        $values = [
            ':id'   => $this->_id,
            ':name' => $this->_name,
            ':pw'   => $this->_pw
        ];

        return Database::exec('INSERT INTO example(id, name, pw) VALUES (:id, :name, :pw)', $values);
    }
}