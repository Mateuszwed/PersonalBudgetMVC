<?php

namespace App\controller;

use \Core\View;


class Home extends \Core\Controller
{
    public function indexAction() {
        View::renderTemplate('Home/index.html');
    }
}
