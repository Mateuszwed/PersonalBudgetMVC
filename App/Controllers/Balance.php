<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\BalanceData;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Balance extends Authenticated
{

    /**
     * Show the index page
     *
     * @return void
     */
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
		
		$incomesSum = 0;
        foreach($incomesGenerally as $amountIncome){
            $incomesSum += $amountIncome['SUM(amount)'];
        } 
		
		$expensesSum = 0;
        foreach($expensesGenerally as $amountExpense){
            $expensesSum += $amountExpense['SUM(amount)'];
        } 
       
		View::renderTemplate('Balance/balance.html', [
            'incomesGenerally' => $incomesGenerally,
            'expensesGenerally' => $expensesGenerally,
            'incomesSum' => $incomesSum,
            'expensesSum' => $expensesSum
			
        ]);
    }
}