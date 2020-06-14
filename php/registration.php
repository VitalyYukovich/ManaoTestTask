<?php
$soltPassword = 'qwerty';
$dataNewUser = $_POST;
if (empty($dataNewUser['login']) || empty($dataNewUser['password']) || empty($dataNewUser['confirmPassword']) || empty($dataNewUser['email']) || empty($dataNewUser['name'])) {
	echo json_encode(array('result' => 'error', 'message' => 'Все поля опязательны для заполнения.'));
	exit;
}

if (strcmp($dataNewUser['password'], $dataNewUser['confirmPassword'])) {
	echo json_encode(array('result' => 'error', 'message' => 'Пароли должны совпадать.'));
	exit;
}

$users = simplexml_load_file('../xml/users.xml');
$checkLogin = empty($users->xpath("//users/user/login[text()='${dataNewUser["login"]}']"));
if (!$checkLogin) {
	echo json_encode(array('result' => 'error', 'message' => 'Пользователь с такими логином уже существует.'));
	exit;
}

$checkEmail = empty($users->xpath("//users/user/email[text()='${dataNewUser["email"]}']"));
if (!$checkEmail) {
	echo json_encode(array('result' => 'error', 'message' => 'Пользователь с такими email уже существует.'));
	exit;
}

$newUser = $users->addChild('user');
$newUser->addChild('login', $dataNewUser['login']);
$newUser->addChild('password', sha1($dataNewUser['password'] . $soltPassword));
$newUser->addChild('email', $dataNewUser['email']);
$newUser->addChild('name', $dataNewUser['name']);
$newUser->addChild('cookie');
file_put_contents('../xml/users.xml', $users->saveXML());
echo json_encode(array('result' => 'success', 'message' => 'Вы зарегистрированы!'));
exit;
?>