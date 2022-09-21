<?php


namespace App\Models;


class BalanceData extends \Core\Model
{

    public $errors = [];


    public $time = [];


    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }


    public function setPeriodTime($option)
    {
        switch($option){
            case 1: 
                
                $beginCurMonth = date("Y-m-01");
                $endCurMonth = date("Y-m-t");				
                $time[0] = $beginCurMonth;
                $time[1] = $endCurMonth;	
                break;
            case 2: 
               
                $beginPrevMonth = date("Y-m-01", strtotime ("-1 month"));
                $endPrevMonth = date("Y-m-t", strtotime ("-1 month"));				
                $time[0] = $beginPrevMonth;
                $time[1] = $endPrevMonth;	
                break;
            case 3: 
               
                $beginCurYear = date("Y-01-01");
                $endCurYear = date("Y-12-t");				
                $time[0] = $beginCurYear;
                $time[1] = $endCurYear;	
                break;
            case 4: 
                
                $beginDate = $this->dateBegin;
                $endDate = $this->dateEnd;
                if($beginDate >= $endDate || $endDate < $beginDate){
                    $errors[0] = 'Błędny przedział czasowy!';
                    $time[0] = 0;
                    $time[1] = 0;
                } else {
                    $time[0] = $beginDate;
                    $time[1] = $endDate;
                }	
                break;
        }
        return $time;
    }
	

    public function setUserID () {
		if (isset($_SESSION['user_id'])) {

				return $userID = $_SESSION['user_id'];
			} else {

				return '';
			}
	}


    public function getIncomesGenerally($beginOfPeriod, $endOfPeriod) {

        $userID = $this->setUserID();

        $sql =  "SELECT amount, name, date_of_income, SUM(amount), incomes.id FROM incomes, incomes_category_default WHERE user_id = '$userID' AND income_category_assigned_to_user_id = incomes_category_default.id AND date_of_income BETWEEN '$beginOfPeriod' AND '$endOfPeriod' GROUP BY incomes.id ";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
        
        $incomesGenerally = $stmt->fetchAll();

		return $incomesGenerally;
    }
    
	

    public function getExpensesGenerally($beginOfPeriod, $endOfPeriod) {

		$userID = $this->setUserID();

		$sql = "SELECT name, amount, date_of_expense, SUM(amount), expenses.id FROM expenses_category_default, expenses WHERE user_id = '$userID' AND expense_category_assigned_to_user_id = expenses_category_default.id AND date_of_expense BETWEEN '$beginOfPeriod' AND '$endOfPeriod' GROUP BY expenses.id";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$expensesGenerally = $stmt->fetchAll();

		return $expensesGenerally;
    }



}
