<?php

namespace App\controller;

use \App\model\Income;
use \Core\View;
use \App\Auth;
use \App\Flash;


class Incomes extends Authenticated {

    public function addAction() {
        $categories = new Income();
        $categoriesIncomes = $categories->getIncomesCategories();
        View::renderTemplate('Incomes/add.html', [
            'categoriesIncomes' => $categoriesIncomes
        ]);
    }

    public function createAction() {
        $user = Auth::getUser();
        $income = new Income($_POST);
        if ($income->save($user->id)) {
            View::renderTemplate('Incomes/success.html');
        } else {
            Flash::addMessage('Nie udało się zarejestrować przychodu.', Flash::WARNING);
            $categories = new Income();
            $categoriesIncomes = $categories->getIncomesCategories();
            View::renderTemplate('Incomes/add.html', [
                'income' => $income,
                'categoriesIncomes' => $categoriesIncomes
            ]);
        }
    }
}
