<?php

namespace App\model;

use PDO;


class Expense extends \Core\Model {
    public $errors = [];

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function save($user_id) {
        $this->validate();
        if (empty($this->errors)) {
            $sql = 'INSERT INTO expenses VALUES (NULL,
                        :user_id,
                        :expense_category_assigned_to_user_id,
                        :payment_method_assigned_to_user_id,
                        :amount,
                        :date_of_expense,
                        :expense_comment)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $this->payment, PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $this->categorySelect, PDO::PARAM_INT);
            $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);
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

    public function getExpensesCategories() {
		$userID = $this->setUserID();
		$sql = "SELECT name, id, limit_amount
                FROM expenses_category_assigned_to_users
                WHERE user_id = :user_id";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
		$stmt->execute();
		$expensesCategories = $stmt->fetchAll();
		return $expensesCategories;
    }


	public function getPaymentMethods() {
        $userID = $this->setUserID();
        $sql = "SELECT name, id
                FROM payment_methods_assigned_to_users
                WHERE user_id = :user_id";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
		$stmt->execute();
		$paymentMethods = $stmt->fetchAll();
		return $paymentMethods;
    }


    public function validate() {
        if ($this->amount <= 0) {
            $this->errors[0] = 'Wpisz poprawną kwotę!';
        }
		if (!isset($this->payment)) {
            $this->errors[1] = 'Wybierz sposób płatności!';
		}
        if (!isset($this->categorySelect)) {
            $this->errors[2] = 'Wybierz kategorię!';
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

    public static function expense($id) {
        $sql = "SELECT *
                FROM expenses_category_assigned_to_users
                WHERE user_id = :user_id AND id = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findCategoryName($name) {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findPaymentName($name) {
        $sql = 'SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :name';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function validateExpenseCategory($name) {
        if ($this->findCategoryName($name)) {
            $this->errors[4] = 'Kategoria o takiej nazwie już istnieje.';
        }
    }

    public function validatePaymentMethod($name) {
        if ($this->findPaymentName($name)) {
            $this->errors[4] = 'Sposób płatności o takiej nazwie już istnieje.';
        }
    }

    public function changeToUpperCase($name) {
        $toSmall = strtolower($name);
        return ucfirst($toSmall);
    }

    public function newCategory() {
        $name = $this->changeToUpperCase($this->newCategory);
        $this->validateExpenseCategory($name);
        if (empty($this->errors)) {
            $userID = $this->setUserID();
            $sql = "INSERT INTO expenses_category_assigned_to_users
                    VALUES (null, :user_id, :name, null)";
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    public function newPaymentMethod() {
        $name = $this->changeToUpperCase($this->newCategory);
        $this->validatePaymentMethod($name);
        if (empty($this->errors)) {
            $userID = $this->setUserID();
            $sql = "INSERT INTO payment_methods_assigned_to_users
                     VALUES (null, :user_id, :name)";
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    public function deleteCategory() {
        if(empty($this->errors)) {
            $sql='DELETE FROM expenses_category_assigned_to_users
                  WHERE id = :id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);
            return $stmt->execute();
		}
		return false;
    }

    public function deletePaymentMethod() {
        if(empty($this->errors)) {
            $sql='DELETE FROM payment_methods_assigned_to_users
                  WHERE id = :id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);
            return $stmt->execute();
		}
		return false;
    }

    public function editCategory() {
        $name = $this->changeToUpperCase($this->newName);
        $this->validateExpenseCategory($name);
        if(empty($this->errors)) {
            $sql='UPDATE expenses_category_assigned_to_users
                  SET name = :name WHERE id = :id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
		}
		return false;
    }

    public function editPaymentMethod() {
        $name = $this->changeToUpperCase($this->newName);
        $this->validatePaymentMethod($name);
        if(empty($this->errors)) {
            $sql='UPDATE payment_methods_assigned_to_users
                  SET name = :name WHERE id = :id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
		}
		return false;
    }

    public function ifEmptyLimit() {
        if ($this->limit == 0 || $this->limit == null) {
            return $this->limit = 0.00;
        }
    }

    public function validateLimit() {
        if ($this->limit < 0) {
            $this->errors[6] = 'Limit nie może być ujemny.';
        }
    }

    public function setLimit() {
        $this->validateLimit();
        $this->ifEmptyLimit();
        if(empty($this->errors)) {
            $sql='UPDATE expenses_category_assigned_to_users
                  SET limit_amount = :limit WHERE id = :id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }


    public static function getGroupedExpenses($id, $firstDate, $lastDate) {
        $sql = "SELECT name ,SUM(amount) AS sum
                FROM expenses
                INNER JOIN expenses_category_assigned_to_users
                ON expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
                WHERE expenses.user_id = :userId
                AND date_of_expense
                BETWEEN :firstDate AND :lastDate
                AND expense_category_assigned_to_user_id = :id
                GROUP BY name
                ORDER BY sum DESC";
        $db = static::getDB();
        $stmt = $db -> prepare($sql);
        $stmt -> bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> bindValue(':firstDate', $firstDate, PDO::PARAM_STR);
        $stmt -> bindValue(':lastDate', $lastDate, PDO::PARAM_STR);
        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLimit($id) {
        $sql = "SELECT limit_amount
                FROM expenses_category_assigned_to_users
                WHERE id = :id
                AND user_id = :userId";
        $db = static::getDB();
        $stmt = $db -> prepare($sql);
        $stmt -> bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }
}