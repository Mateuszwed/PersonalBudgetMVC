<?php

namespace App\controller;

use \Core\View;
use \App\model\BalanceData;


class Balance extends Authenticated {
    public function showAction()
    {
		$balance = new BalanceData($_POST);
        if(!isset($_GET['option'])&&!isset($_POST['dateBegin'])&&!isset($_POST['dateEnd'])){
			$option = 1;
        } else if(isset($_GET['option'])){
			$option = $_GET['option'];
		} else if(isset($_POST['dateBegin']) || isset($_POST['dateEnd'])){
			$option = 4;
        }
        if (! empty($balance)) {
			$date = $balance->setPeriodTime($option);
			$begin = $date[0];
			$end = $date[1];
		} else {
			$begin = date('Y-m'.'-01');
			$end = date("Y-m-d");
        }
		$incomesGenerally = $balance->getIncomesGenerally($begin, $end);
        $expensesGenerally = $balance->getExpensesGenerally($begin, $end);
		$expensesPieChart = $balance->getExpensesPieChart($begin, $end);
		$incomesPieChart = $balance->getIncomesPieChart($begin, $end);
		$incomesSum = 0;
        foreach($incomesGenerally as $amountIncome){
            $incomesSum += $amountIncome['SUM(amount)'];
        }
		$expensesSum = 0;
        foreach($expensesGenerally as $amountExpense){
            $expensesSum += $amountExpense['SUM(amount)'];
        }
		$balance = $incomesSum - $expensesSum;
        $balance = number_format($balance, 2, '.' , '');
		if($balance > 0){
            $balanceSentence = 'Gratulacje! Świetnie zarządzasz finansami! = '.$balance.' ZŁ';
        } else if ($balance == 0) {
            $balanceSentence = 'Nie udało Ci się zaoszczędzić. Wychodzisz na zero!';
        } else {
            $balanceSentence = 'Uważaj! Wpadasz w długi! = '.$balance.' ZŁ';
        }
       
		View::renderTemplate('Balance/balance.html', [
			'incomesPieChart' => $incomesPieChart,
			'expensesPieChart' => $expensesPieChart,
            'incomesGenerally' => $incomesGenerally,
            'expensesGenerally' => $expensesGenerally,
            'incomesSum' => $incomesSum,
            'expensesSum' => $expensesSum,
			'balanceSentence' => $balanceSentence
			
        ]);
    }
}