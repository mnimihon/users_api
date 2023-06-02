<?php

namespace app\Module;

use app\Core\Db;
use ReflectionClass;
use ReflectionProperty;
use PDO;

abstract class Module
{

    private $db;
    protected $id;

    public function __construct()
    {
        $this->db = Db::instance();
    }

    public function save(): Module
    {
        $class = new ReflectionClass($this);
        $properties = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $properties[] = '`' . $propertyName . '` = "' . $this->$propertyName . '"';
        }
        $values = implode(',', $properties);
        if ($this->id) {
            $sql = 'UPDATE `' . $this->getTableName() . '` SET ' . $values . ' WHERE id = ' . $this->id;
            $this->db->exec($sql);
        } else {
            $sql = 'INSERT INTO `' . $this->getTableName() . '` SET ' . $values;
            $this->db->exec($sql);
            $this->id = $this->db->lastInsertId();
        }

        return $this;
    }

    public function getByConditions(array $conditions = [])
    {
        $whereConditions = null;
        foreach ($conditions as $key => $value) {
            $whereConditions[] = '`' . $key . '` = "' . $value . '"';
        }
        $whereClause = ' WHERE ' . implode(' AND ', $whereConditions);
        $result = $this->db->query('SELECT * FROM ' . $this->getTableName() . $whereClause);
        if ($result) {
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data ? $this->getModule($data) : false;
        } else {
            return false;
        }

    }

    private function getModule(array $data): self
    {
        $module = new static();
        $class = new ReflectionClass($this);
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            if (isset($data[$propertyName])) {
                $module->$propertyName = $data[$propertyName];
            }
        }
        return $module;
    }

    public function delete()
    {
        return $this->db->exec('DELETE FROM ' . $this->getTableName() . ' WHERE id = ' . $this->id);
    }

    private function getTableName(): string
    {
        $class = new ReflectionClass($this);
        return strtolower($class->getShortName());
    }
}