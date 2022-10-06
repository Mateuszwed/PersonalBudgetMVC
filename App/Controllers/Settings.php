<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Setting;
use \App\Auth;
use \App\Flash;


class Settings extends Authenticated
{

    public function editAction()
    {
		
		View::renderTemplate('Settings/edit.html');
	}
		
		
	public function editPasswordAction(){
		
		View::renderTemplate('Settings/pass.html');
	}
	
		public function editEmailAction(){
		
		View::renderTemplate('Settings/email.html');
	}
	
	public function createPasswordAction(){
		
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
	
		public function createEmailAction(){
		
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
}