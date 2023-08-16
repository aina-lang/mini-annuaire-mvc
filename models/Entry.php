<?php

namespace models;

require_once 'app/DbModel.php';

use app\DbModel;

class Entry extends DbModel
{
    public $id;
    public $libelle;
    public $description;
    public $category_ids;

    public function __construct($libelle, $description, $category_ids)
    {
        $this->libelle = $libelle;
        $this->description = $description;
        $this->category_ids = $category_ids;
    }

    public function rules()
    {
        return [
            'libelle' => [self::RULE_REQUIRED],
            'description' => [self::RULE_REQUIRED],
            'category_ids' => [self::RULE_REQUIRED],
        ];
    }

    public static function tableName()
    {
        return "fiches";
    }

    public static function attributes()
    {
        return ["libelle", "description"];
    }
}
