<?php

namespace app;

require_once "routes/Router.php";
require_once "app/Request.php";
require_once "app/Response.php";
require_once "app/Database.php";
require_once "app/Migration.php";

use app\Response;
use app\Router;
use app\Request;
use app\Database;
use app\Migration;

class App
{
    public $router;
    public $request;
    public $response;
    public static $ROOT_DIR;
    public static $app;
    public $db;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->db = new Database();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

        $migration = new Migration();
        $migration->runMigrations();
    }

    public function run()
    {
        $this->router->resolve();
    }
}
