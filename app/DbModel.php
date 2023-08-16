<?php

namespace app;

require_once 'app/Model.php';

use app\App;
use app\Model;

abstract class DbModel extends Model
{
    abstract public static function tableName();
    abstract public static function attributes();

    public static function getAll()
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM $tableName";

        $statement = self::prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(function ($attr) {
            return ":$attr";
        }, $attributes);

        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES(" . implode(',', $params) . ")");

        foreach ($attributes as $attribute) {
            $statement->bindValue(':' . $attribute, $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    public static function findOne($where = null, $operator = null)
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM $tableName";

        if ($where !== null) {
            $attributes = array_keys($where);
            $conditions = [];

            foreach ($where as $attribute => $value) {
                $conditions[] = "$attribute = :$attribute";
            }

            $operator = $operator !== null ? $operator : 'AND';
            $sql .= " WHERE " . implode(" $operator ", $conditions);
        }

        $statement = self::prepare($sql);

        if ($where !== null) {
            foreach ($where as $attribute => $value) {
                $statement->bindValue(":$attribute", $value);
            }
        }


        // var_dump($statement);
        // exit;
        $statement->execute();
        return $statement->fetchAll();
    }


    public static function findAll($where = null, $operator = null)
    {

        $tableName = static::tableName();
        $sql = "SELECT * FROM $tableName";

        if ($where !== null) {
            $attributes = array_keys($where);
            $conditions = [];

            foreach ($where as $attribute => $value) {
                $conditions[] = "$attribute = :$attribute";
            }

            $operator = $operator !== null ? $operator : 'AND';
            $sql .= " WHERE " . implode(" $operator ", $conditions);
        }

        $statement = self::prepare($sql);

        if ($where !== null) {
            foreach ($where as $attribute => $value) {
                $statement->bindValue(":$attribute", $value);
            }
        }




        $statement->execute();
        // var_dump( $statement->fetchAll());
        // exit;
        return $statement->fetchAll();
    }

    public function update($where, $values)
    {
        $tableName = static::tableName();
        $setValues = [];
        $conditions = [];

        foreach ($values as $attribute => $value) {
            $setValues[] = "$attribute = :$attribute";
        }

        foreach ($where as $attribute => $value) {
            $conditions[] = "$attribute = :where_$attribute";
        }

        $setClause = implode(', ', $setValues);
        $whereClause = implode(' AND ', $conditions);

        $sql = "UPDATE $tableName SET $setClause WHERE $whereClause";

        $statement = self::prepare($sql);

        foreach ($values as $attribute => $value) {
            $statement->bindValue(":$attribute", $value);
        }

        foreach ($where as $attribute => $value) {
            $statement->bindValue(":where_$attribute", $value);
        }
        $statement->execute();
    }


    public static function delete($where)
    {
        $tableName = static::tableName();
        $conditions = [];

        foreach ($where as $attribute => $value) {
            $conditions[] = "$attribute = :$attribute";
        }

        $whereClause = implode(' AND ', $conditions);

        $sql = "DELETE FROM $tableName WHERE $whereClause";

        $statement = self::prepare($sql);

        foreach ($where as $attribute => $value) {
            $statement->bindValue(":$attribute", $value);
        }

        $statement->execute();
    }


    public function  deleteAll()
    {
        $tableName = static::tableName();

        $sql = "DELETE FROM $tableName";

        $statement = self::prepare($sql);

        $statement->execute();
    }


    public static function prepare($sql)
    {
        return App::$app->db->getConnection()->prepare($sql);
    }
}
