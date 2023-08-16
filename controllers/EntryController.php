<?php

namespace controllers;

require_once "models/Entry.php";
require_once "app/Controlleur.php";

use models\Entry;
use models\Category;
use app\Controlleur;
use app\Request;

class EntryController extends Controlleur
{
    public function index()
    {
        $data = Entry::getAll();
        return $this->render("index", $data);
    }

    public function add()
    {
        $categories = Category::getAll();
        return $this->render("add", ["categories" => $categories]);
    }

    public function edit($request)
    {
        $entrieId = is_array($request) ? $request[1] : $request;
        $entrie = Entry::findOne(['id' => $entrieId]);

        if (!$entrie) {
            echo "404 not found";
            exit;
        }

        return $this->render("edit", ["entrie" => $entrie]);
    }

    public function delete($request)
    {
        $entryId = is_array($request) ? $request[1] : $request;
        $entry = Entry::findOne(['id' => $entryId]);

        if (!$entry) {
            echo "404 not found";
            exit;
        }

        Entry::delete(["id" => $entryId]);

        $data = Entry::getAll();
        return $this->render("index", $data);
    }

    public function store($request)
    {
        $body = $request[0]->postBody();
        $entry = new Entry($body['libelle'], $body['description'], $body['category_ids']);
        $entry->loadData($body);
        $entry->save();

        return $this->index();
    }

    public function show($request)
    {
        $entryId = is_string($request) ? $request : $request[1];
        $entry = Entry::findOne(['id' => $entryId]);

        if (!$entry) {
            echo "404 not found";
            exit;
        }

        return $this->render("show", $entry);
    }

    public function updateForm($id)
    {
        $entry = Entry::findOne($id);

        if (!$entry) {
            // Faire quelque chose si l'entrée n'est pas trouvée
        }

        return $this->render("update_form", ['entry' => $entry]);
    }
}
