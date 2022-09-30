<?php

namespace App;

use \App\Models\User;
use \App\Models\RememberdLogin;

class Auth
{

	public static function login($user, $remember_me){
		
		session_regenerate_id(true);
		$_SESSION['user_id'] = $user->id;
	
		if($remember_me){
			$user->rememberLogin();
			
			setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');
		}
	}
	
	public static function logout (){
		
		$_SESSION = [];
		
		if(ini_get('session.use_cookies')){
			$params = session_get_cookie_params();
			
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
				
			);
		}
	session_destroy();
	
	static::forgetLogin();
		
	}

	public static function rememberRequestedPage(){
		
	$_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
		
	}
	
	public static function getReturnToPage(){
		return $_SESSION['return_to'] ?? '/';
	}
	
	public static function getUser()
	{
		
		if (isset($_SESSION['user_id'])) {
			
			return User::findByID($_SESSION['user_id']);
			
		} else {
			
			return static::loginFromRememberCookie();
			
		}
	}
	
	protected static function loginFromRememberCookie(){
		
		$cookie = $_COOKIE['remember_me'] ?? false;
		
		if($cookie){
			
			$rememberd_login = RememberdLogin::findByToken($cookie);
			
			if($rememberd_login && ! $rememberd_login->hasExpired()) {
				
				$user = $rememberd_login->getUser();
				
				static::login($user, false);
				
				return $user;
			}
		}
		
	}
	
	protected static function forgetLogin(){
		
		$cookie = $_COOKIE['rememberd_me'] ?? false;
		
		
		if($cookie){
			
			$rememberd_login = RememberdLogin::findByToken($cookie);
			
			if($rememberd_login){
				
				$rememberd_login->delete();
			}
		}
	}
	
}
