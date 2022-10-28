<?php

namespace App\Models;

use PDO;


class Income extends \Core\Model
{

    public $errors = [];

	public $amount = 0.1;


    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }


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

        $sql = "SELECT name, id
                FROM incomes_category_assigned_to_users
                WHERE user_id = :user_id";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
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

        public function deleteCategory() {

            if(empty($this->errors)) {
                $sql='DELETE FROM incomes_category_assigned_to_users
                      WHERE id = :id';

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);

                return $stmt->execute();
            }
            return false;

        }


        public static function findCategoryName($name)
        {
            $sql = 'SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :name';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            $stmt->execute();

            return $stmt->fetch();
        }

        public function validateExpenseCategory($name){

            if ($this->findCategoryName($name)) {
                $this->errors[4] = 'Kategoria o takiej nazwie już istnieje.';
            }

        }

        public function changeToUpperCase($name){

            $toSmall = strtolower($name);

            return ucfirst($toSmall);

        }

        public function newCategory() {

            $name = $this->changeToUpperCase($this->newCategory);

            $this->validateExpenseCategory($name);

            if (empty($this->errors)) {
                $userID = $this->setUserID();

                $sql = "INSERT INTO incomes_category_assigned_to_users
                VALUES (null, :user_id, :name)";

                $db = static::getDB();
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);

                return $stmt->execute();
            }
            return false;
        }

        public function editCategory() {

            $name = $this->changeToUpperCase($this->newName);

            $this->validateExpenseCategory($name);

            if(empty($this->errors)) {

                $sql='UPDATE incomes_category_assigned_to_users
                        SET name = :name WHERE id = :id';

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);

                return $stmt->execute();
            }
            return false;

        }

}