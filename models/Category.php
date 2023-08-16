<?php

namespace models;

use app\DbModel;

class Category extends DbModel
{
    public $id;
    public $libelle;
    public $description;
    public $parent_id;

    public function __construct($libelle, $description, $parent_id = null)
    {
        $this->libelle = $libelle;
        $this->description = $description;
        $this->parent_id = $parent_id;
    }

    public function rules()
    {
        return [
            'libelle' => [self::RULE_REQUIRED],
            'description' => [self::RULE_REQUIRED],
        ];
    }

    public static function tableName()
    {
        return "categories";
    }

    public static function attributes()
    {
        return ["libelle", "description", 'parent_id'];
    }
}
