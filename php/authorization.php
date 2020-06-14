<?php
session_start();
$dataNewUser = $_POST;
$soltPassword = 'qwerty';
if (empty($dataNewUser['login']) || empty($dataNewUser['password'])) {
	echo json_encode(array('result' => 'error', 'message' => 'Все поля опязательны для заполнения.'));
	exit;
}
$users = simplexml_load_file('../xml/users.xml');
$findUser = $users->xpath("//users/user/login[text()='${dataNewUser["login"]}']/ancestor::user");
if (!empty($findUser)) {
	if (!strcmp($findUser[0]->password, sha1($dataNewUser['password'] . $soltPassword))) {
		$_SESSION['auth'] = true;
		$_SESSION['login'] = $dataNewUser['login'];
		$cookie = htmlspecialchars(getCookie());
		setcookie('log', $dataNewUser['login'], time() + 60 * 60 * 24 * 30);
		setcookie('cookie', $cookie, time() + 60 * 60 * 24 * 30);
		$findUser[0]->cookie = $cookie;
		file_put_contents('../xml/users.xml', $users->saveXML());
		echo json_encode(array('result' => 'success', 'message' => 'Добро пожаловать, ' . $findUser[0]->name . '!'));
		exit;
	}
}
echo json_encode(array('result' => 'error', 'message' => 'Не верный логин и(или) пароль.'));
function getCookie() {
	$cookie = '';
	$cookieLength = 10;
	for ($i = 0; $i < $cookieLength; $i++) {
		$cookie .= chr(mt_rand(33, 126));
	}
	return $cookie;
}
