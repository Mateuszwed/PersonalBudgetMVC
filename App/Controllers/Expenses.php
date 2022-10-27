<?php

namespace App\Controllers;

use \App\Models\Expense;
use \Core\View;
use \App\Auth;
use \App\Flash;




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
    public function getExpensesAction()
    {

        $categoryID = $this->route_params['id'];
        $date = $this->route_params['date'];
        $lastDate = $this->route_params['lastdate'];

        $data = Expense::getGroupedExpenses($categoryID, $date, $lastDate);

        echo json_encode($data);

    }

    public function getCategoryLimitAction()
    {

        $id = $this->route_params['id'];
        $date = Expense::getLimit($id);

        echo json_encode($date);
    }

    public function checkLimit(){

        $id = $this->route_params['id'];

        $data = Expense::checkLimit($id);

        echo json_encode($data);

    }

}
