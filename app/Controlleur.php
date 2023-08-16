<?php

namespace app;

class Controlleur
{
    public function render($view, $params = array())
    {
        return App::$app->router->renderView($view, $params);
    }
}
