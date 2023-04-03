<?php

namespace App\controller;


abstract class Authenticated extends \Core\Controller
{

    protected function before()
    {
        $this->requireLogin();
    }
}
