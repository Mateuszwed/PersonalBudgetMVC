<?php

namespace App\Controllers;

use \App\Models\Expense;
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
		$categories = new Expense();
		$categoriesExpenses = $categories->getExpensesCategories();
		$paymentMethod = $categories->getPaymentMethods();
		
        View::renderTemplate('Expenses/add.html', [
		'categoriesExpenses' => $categoriesExpenses,
		'paymentMethod' => $paymentMethod
		]);
    }
	
    public function createAction()
    {
        $user = Auth::getUser();
        $expense = new Expense($_POST);

        if ($expense->save($user->id)) {

            View::renderTemplate('Expenses/success.html');

        } else {
            Flash::addMessage('Nie udało się zarejestrować wydatku.', Flash::WARNING);
           	
			$categories = new Expense();
			$categoriesExpenses = $categories->getExpensesCategories();
			$paymentMethod = $categories->getPaymentMethods();
			
            View::renderTemplate('Expenses/add.html', [
                'expense' => $expense,
				'categoriesExpenses' => $categoriesExpenses,
				'paymentMethod' => $paymentMethod
            ]);
        }
    }

}
