<?php

namespace App\controller;

use \Core\View;
use \App\model\User;
use \App\Auth;
use \App\Flash;


class Login extends \Core\Controller {

    public function newAction() {
        View::renderTemplate('Login/new.html');
    }

    public function createAction() {
        $user = User::authenticate($_POST['email'], $_POST['password']);
        $remember_me = isset($_POST['remember_me']);
        if ($user) {
            Auth::login($user, $remember_me);
            Flash::addMessage('Zalogowano');
            $this->redirect(Auth::getReturnToPage());
        } else {
            Flash::addMessage('Logowanie nie powiodło się, spróbuj ponownie', Flash::WARNING);
            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }

    public function destroyAction() {
        Auth::logout();
        $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessageAction() {
        Flash::addMessage('Wylogowałeś się');
        $this->redirect('/');
    }
}
