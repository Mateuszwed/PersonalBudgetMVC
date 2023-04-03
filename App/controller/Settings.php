<?php

namespace App\controller;

use \Core\View;
use \App\model\Setting;
use \App\model\Expense;
use \App\model\Income;
use \App\Auth;
use \App\Flash;


class Settings extends Authenticated {

    public function editAction() {
		View::renderTemplate('Settings/edit.html');
	}

	public function editPasswordAction() {
		View::renderTemplate('Settings/pass.html');
	}

    public function editEmailAction() {
		View::renderTemplate('Settings/email.html');
	}

    public function editExpensesAction() {
		$categories = new Expense();
		$categoriesExpenses = $categories->getExpensesCategories();
		View::renderTemplate('Settings/editExpenses.html',  [
		'categoriesExpenses' => $categoriesExpenses
		]);
	}

    public function editIncomesAction() {
		$categories = new Income();
		$categoriesIncomes = $categories->getIncomesCategories();
		View::renderTemplate('Settings/editIncomes.html',  [
		'categoriesIncomes' => $categoriesIncomes
		]);
	}

    public function editPaymentAction() {
		$payments = new Expense();
		$paymentMethod = $payments->getPaymentMethods();
		View::renderTemplate('Settings/editPayments.html',  [
		'paymentMethods' => $paymentMethod
		]);
	}

	public function createPasswordAction() {
		$user = Auth::getUser();
        $editPassword = new Setting($_POST);
        if ($editPassword->editPassword($user->id)) {
			Flash::addMessage('Hasło zostało pomyślnie zmienone.', Flash::SUCCESS);
            View::renderTemplate('Settings/edit.html');
        } else {
			Flash::addMessage('Nie udało się zmienić hasła.', Flash::WARNING);
            View::renderTemplate('Settings/pass.html', [
			'editPassword' => $editPassword
			]);
        }
    }

    public function createEmailAction() {
    $user = Auth::getUser();
    $editEmail = new Setting($_POST);
    if ($editEmail->editEmail($user->id)) {
        Flash::addMessage('Adres e-mail został pomyślnie zmienony.', Flash::SUCCESS);
        View::renderTemplate('Settings/edit.html');
    } else {
        Flash::addMessage('Nie udało się zmienić adresu e-mail.', Flash::WARNING);
        View::renderTemplate('Settings/email.html', [
        'editEmail' => $editEmail
        ]);
    }
}

    public function addNewExpenseCategoryAction() {
    $newExpenseCategory = new Expense($_POST);
        if ($newExpenseCategory->newCategory()) {
            Flash::addMessage('Dodano nową kategorię.');
            $this -> redirect('/Settings/editExpenses');
        } else {
            $categories = new Expense();
            $categoriesExpenses = $categories->getExpensesCategories();
            Flash::addMessage('Nie udało się dodać nowej kategorii, spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editExpenses.html',  [
            'categoriesExpenses' => $categoriesExpenses,
            'expense' => $newExpenseCategory
            ]);
        }
     }

    public function addNewPaymentMethodAction() {
        $newPaymentMethod = new Expense($_POST);
        if ($newPaymentMethod->newPaymentMethod()) {
            Flash::addMessage('Dodano nowy sposób płatności.');
            $this -> redirect('/Settings/editPayment');
        } else {
            $payment = new Expense();
            $paymentMethods = $payment->getPaymentMethods();
            Flash::addMessage('Nie udało się dodać nowej kategorii, spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editPayments.html',  [
            'paymentMethods' => $paymentMethods,
            'payment' => $newPaymentMethod
            ]);
        }
    }

    public function addNewIncomeCategoryAction() {
        $newIncomeCategory = new Income($_POST);
        if ($newIncomeCategory->newCategory()) {
            Flash::addMessage('Dodano nową kategorię.');
            $this -> redirect('/Settings/editIncomes');
        } else {
            $categories = new Income();
            $categoriesIncomes = $categories->getIncomesCategories();
            Flash::addMessage('Nie udało się dodać nowej kategorii, spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editIncomes.html',  [
            'categoriesIncomes' => $categoriesIncomes,
            'income' => $newIncomeCategory
            ]);
        }
    }

    public function deleteExpenseCategoryAction() {
        $category = new Expense($_POST);
        if ($category->deleteCategory()) {
            Flash::addMessage('Kategoria została usunięta pomyślnie.');
            $this -> redirect('/Settings/editExpenses');
        } else {
            Flash::addMessage('Nie udało się usunąć kategorii. Spróbuj ponownie później.', Flash::WARNING);
             $this -> redirect('/Settings/editExpenses');
        }
    }

    public function deletePaymentMethodAction() {
        $payment = new Expense($_POST);
        if ($payment->deletePaymentMethod()) {
            Flash::addMessage('Metoda została usunięta pomyślnie.');
            $this -> redirect('/Settings/editPayment');
        } else {
            Flash::addMessage('Nie udało się usunąć metody płatności. Spróbuj ponownie później.', Flash::WARNING);
            $this -> redirect('/Settings/editPayments');
        }
    }

    public function deleteIncomeCategoryAction() {
        $category = new Income($_POST);
        if ($category->deleteCategory()) {
            Flash::addMessage('Kategoria została usunięta pomyślnie.');
            $this -> redirect('/Settings/editIncomes');
        } else {
            Flash::addMessage('Nie udało się usunąć kategorii. Spróbuj ponownie później.', Flash::WARNING);
            $this -> redirect('/Settings/editIncomes');
        }
    }

    public function editExpenseCategoryAction() {
        $category = new Expense($_POST);
        if ($category->editCategory()) {
            Flash::addMessage('Nazwa kategorii została zmieniona.');
            $this -> redirect('/Settings/editExpenses');
        } else {
            $categoriesExpenses = $category->getExpensesCategories();
            Flash::addMessage('Nie udało się edytować kategorii. Spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editExpenses.html',  [
            'categoriesExpenses' => $categoriesExpenses,
            'expense' => $category
            ]);
        }
    }


    public function editPaymentMethodAction() {
        $payment = new Expense($_POST);
        if ($payment->editPaymentMethod()) {
            Flash::addMessage('Nazwa sposobu płatności została zmieniona.');
            $this -> redirect('/Settings/editPayment');
        } else {
            $paymentMethods = $payment->getPaymentMethods();
            Flash::addMessage('Nie udało się edytować sposobu płatności. Spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editPayments.html',  [
            'paymentMethods' => $paymentMethods,
            'payment' => $payment
            ]);
        }
    }

    public function editIncomeCategoryAction() {
        $category = new Income($_POST);
        if ($category->editCategory()) {
            Flash::addMessage('Nazwa kategorii została zmieniona.');
            $this -> redirect('/Settings/editIncomes');
        } else {
            $categoriesIncomes = $category->getIncomesCategories();
            Flash::addMessage('Nie udało się edytować kategorii. Spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editIncomes.html',  [
            'categoriesIncomes' => $categoriesIncomes,
            'income' => $category
            ]);
        }
    }

    public function setLimitAction() {
        $category = new Expense($_POST);
        if ($category->setLimit()) {
            Flash::addMessage('Limit został ustawiony.');
            $this -> redirect('/Settings/editExpenses');
        } else {
            $categoriesExpenses = $category->getExpensesCategories();
            Flash::addMessage('Nie udało ustawić się limitu dla tej kategorii. Spróbuj ponownie.', Flash::WARNING);
            View::renderTemplate('Settings/editExpenses.html',  [
            'categoriesExpenses' => $categoriesExpenses,
            'expense' => $category
            ]);
        }
    }
}