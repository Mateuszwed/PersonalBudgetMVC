<?php

namespace App\Models;

use PDO;
use App\Models\User;
use App\Models\Expense;

class Setting extends \Core\Model
{

    public $errors = [];


    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function editPassword($user_id)
    {
		$this->validatePassword();

		if (empty($this->errors)) {

			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users SET password = :password WHERE id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
       }

       return false;
    }

	    public function editEmail($user_id)
    {
		$this->validateEmail($user_id);

		if (empty($this->errors)) {


            $sql = 'UPDATE users SET email = :email WHERE id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

            return $stmt->execute();
       }

       return false;
    }


        public function validatePassword()
    {

        if (strlen($this->password) < 6) {
            $this->errors[1] = 'Hasło musi zawierać co najmniej 6 znaków';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[1] = 'Hasło musi zawierać co najmniej jedną litere.';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[1] = 'Hasło musi zawierać co najmniej jedną cyfre';
        }
		if ($this->password != $this->password_confirmation) {
			$this->errors[1] = 'Hasła muszą być takie same';
		}

    }

	    public function validateEmail($user_id)
    {

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Nieprawidłowy adres email';
        }

        if (User::emailExists($this->email, $user_id?? null)) {
            $this->errors[] = 'Ten adres email jest już zajęty';
        }

		if ($this->email != $this->email_confirmation) {
			$this->errors[1] = 'Adresy e-mail muszą być takie same';
		}

    }

}