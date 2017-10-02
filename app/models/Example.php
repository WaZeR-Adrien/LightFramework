<?php
namespace Models;
use Kernel\Database;

class Example extends Database
{
    public function getFieldById()
    {
        return self::findOne(['id' => $this->id]);
    }
}