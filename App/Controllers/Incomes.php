<?php

namespace App\Controllers;

use \App\Models\Income;
use \App\Models\SettingsData;
use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Incomes extends Authenticated
{

    public function addAction()
    {
        View::renderTemplate('Incomes/add.html');
    }
	
	 public function createAction()
    {
        $user = Auth::getUser();
        $income = new Income($_POST);
        
        if ($income->save($user->id)) {

            View::renderTemplate('Incomes/success.html');

        } else {
            Flash::addMessage('Nie udało się zarejestrować przychodu.', Flash::WARNING);
            
        
            View::renderTemplate('Incomes/add.html', [
                'income' => $income
            ]);
        }
    }

}
