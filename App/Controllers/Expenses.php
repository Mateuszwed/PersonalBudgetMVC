<?php

namespace App\Controllers;

use \App\Models\Expense;
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
class Expenses extends Authenticated
{

    public function addAction()
    {
        View::renderTemplate('Expenses/add.html');
    }
	
    public function createAction()
    {
        $user = Auth::getUser();
        $expense = new Expense($_POST);

        if ($expense->save($user->id)) {

            View::renderTemplate('Expenses/success.html');

        } else {
            Flash::addMessage('Nie udało się zarejestrować wydatku.', Flash::WARNING);
            
            $settings = new SettingsData();
            $categoriesExpenses = $settings->getExpensesCategories();
            $paymentMethods = $settings->getPaymentMethods();

            View::renderTemplate('Expenses/add.html', [
                'expense' => $expense,
                'categoriesExpenses' => $categoriesExpenses,
                'paymentMethods' => $paymentMethods
            ]);
        }
    }

}
