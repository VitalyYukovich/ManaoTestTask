<?php
session_start();
if (!empty($_SESSION['auth'])) {
	if (!empty($_COOKIE['log']) && !empty($_COOKIE['cookie'])) {
		$login = $_COOKIE['log'];
		$cookie = $_COOKIE['cookie'];
		$users = simplexml_load_file('../xml/users.xml');
		$findUser = $users->xpath("//users/user/login[text()='${login}']/ancestor::user");
		if (!strcmp($cookie, $findUser[0]->cookie)) {
			$_SESSION['auth'] = true;
			$_SESSION['login'] = (string) $findUser[0]->login;
			echo json_encode(array('result' => 'success', 'message' => 'Добро пожаловать, ' . $findUser[0]->name . '!'));
			exit;
		}
	}
}
echo json_encode(array('result' => 'error', 'message' => 'Cессии и кук не найдено.'));
?>