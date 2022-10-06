<?php

namespace App\Models;

use PDO;

/**
 * User model
 *
 * PHP version 7.2
 */
class Income extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];
	
	public $amount = 0.1;

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the income model with the current property values
     *
     * @return boolean  True if the income was saved, false otherwise
     */
    public function save($user_id)
    {
        $this->validate();
    
        if (empty($this->errors)) {

            $sql = 'INSERT INTO incomes VALUES (NULL,
                        :user_id, 
                        :income_category_assigned_to_user_id, 
                        :amount, 
                        :date_of_income, 
                        :income_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment', $this->comment, PDO::PARAM_STR);
            
            return $stmt->execute();
       }

       return false;
    }

	
	public function setUserID () {
		if (isset($_SESSION['user_id'])) {

				return $userID = $_SESSION['user_id'];
			} else {

				return '';
			}
	}
	
	public function getIncomesCategories() {

        $userID = $this->setUserID();

        $sql = "SELECT ic.name, ic.id
                FROM incomes_category_assigned_to_users AS ic 
                WHERE ic.user_id = $userID";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
        
        $incomesCategories = $stmt->fetchAll();

		return $incomesCategories;
    }
	
	
    public function validate()
    {
        if ($this->amount <= 0) {
            $this->errors[0] = 'Wpisz poprawną kwotę!';
        }

        if (!isset($this->category)) {
            $this->errors[1] = 'Wybierz kategorię!';
        }
		
		$year = substr($this->date , 0, 4);
		$month = substr($this->date , 5, 2);
		$day = substr($this->date , 8, 2);
		
		if($year < 2001) {
			$this->errors[2] = 'Data nie może być wcześniejsza niż 2001r.';
		}
		  
		$currentdate = date('Y-m-d');
		$currentyear = substr($currentdate, 0, 4);
		$currentmonth = substr($currentdate, 5, 2);
		$currentday = substr($currentdate, 8, 2);
	  
		if($year > $currentyear) {
			$this->errors[3] = 'Data nie może być z przyszłości!';
		}
		
		elseif($year == $currentyear) {
		  if($month > $currentmonth) {
			$this->errors[3] = 'Data nie może być z przyszłości!';
		}
		  
		elseif($month == $currentmonth) {
			if($day > $currentday) {
			$this->errors[3] = 'Data nie może być z przyszłości!';
		
			}
          }
        }
    }
}