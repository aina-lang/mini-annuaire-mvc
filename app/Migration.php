<?php
namespace app;
require_once 'Database.php';

use app\Database;

class Migration {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }


    public function createCategoriesTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                parent_id INT NULL,
                libelle VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
            )
        ";
    
        $this->db->getConnection()->exec($query);
    }
    
    public function createFichesTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS fiches (
                id INT AUTO_INCREMENT PRIMARY KEY,
                libelle VARCHAR(255) NOT NULL,
                description TEXT NOT NULL
            )
        ";
    
        $this->db->getConnection()->exec($query);
    }
    
    public function createFicheCategoryTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS fiche_category (
                fiche_id INT,
                category_id INT,
                PRIMARY KEY (fiche_id, category_id),
                FOREIGN KEY (fiche_id) REFERENCES fiches(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            )
        ";
    
        $this->db->getConnection()->exec($query);
    }
    public function runMigrations() {
        $this->createCategoriesTable();
        $this->createFichesTable();
        $this->createFicheCategoryTable();
    }
}
