<?php

namespace controllers;

require_once "models/Category.php";
require_once "app/Controlleur.php";

use models\Category;
use app\Controlleur;
use app\Request;

class CategoryController extends Controlleur
{
    public function index()
    {
        $categories = Category::getAll();
        return $this->render("index", ['categories' => $categories]);
    }

    private function getEnfantCategories($parentId)
    {
        $enfantCategories = Category::findAll(['parent_id' => $parentId]);

        foreach ($enfantCategories as $enfantCategory) {
            $enfantCategory['children'] = $this->getEnfantCategories($enfantCategory['id']);
        }

        return $enfantCategories;
    }

    public function edit($request)
    {
        $categoryId = is_array($request) ? $request[1] : $request;
        $category = Category::findOne(['id' => $categoryId]);

        if (!$category) {
            echo "404 not found";
            exit;
        }

        return $this->render("edit", ["categorie" => $category]);
    }

    public function add()
    {
        $categories = Category::getAll();
        return $this->render("add", ["categories" => $categories]);
    }

    public function store($request)
    {
        $body = $request[0]->postBody();
        if ($body['parent_id'] === null || $body['parent_id'] === "") {
            $entry = new Category($body['libelle'], $body['description']);
            $entry->loadData([$body['libelle'], $body['description']]);
        } else {
            $entry = new Category($body['libelle'], $body['description'], $body['parent_ids']);
            $entry->loadData([$body['libelle'], $body['description'], $body['parent_ids']]);
        }

        $entry->save();

        return $this->index();
    }

    public function show($request)
    {
        $categoryId = is_array($request) ? $request[1] : $request;
        $category = Category::findOne(['id' => $categoryId]);

        if (!$category) {
            echo "404 not found";
            exit;
        }

        $parentCategory = null;
        if ($category['parent_id']) {
            $parentCategory = Category::findOne(['id' => $category['parent_id']]);
        }

        $childCategories = Category::findAll(['parent_id' => $category['id']]);

        return $this->render("show", [
            'category' => $category,
            'parentCategory' => $parentCategory,
            'childCategories' => $childCategories
        ]);
    }

    public function delete($request)
    {
        $categoryId = is_array($request) ? $request[1] : $request;
        $category = Category::findOne(['id' => $categoryId]);

        if (!$category) {
            echo "404 not found";
            exit;
        }

        Category::delete(["id" => $categoryId]);

        $categories = Category::getAll();
        return $this->render("index", ['categories' => $categories]);
    }

    public function updateForm($id)
    {
        $entry = Category::findOne($id);

        if (!$entry) {
        }

        return $this->render("update", ['entry' => $entry]);
    }
}
