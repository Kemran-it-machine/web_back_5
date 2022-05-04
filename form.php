<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
// Запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки. 
if (!empty($_POST)) {
	if (empty($_POST["name"])) {
		$errors[] = "Введите имя!";
	}
	if (empty($_POST["email"])) {
		$errors[] = "Введите e-mail!";
	}
	if (empty($_POST["year"])) {
		$errors[] = "Выберите год рождения!";
	}
	if (!isset($_POST["sex"])) {
		$errors[] = "Выберите пол!";
	}
	if (!isset($_POST["limbs"])) {
		$errors[] = "Выберите кол-во конечностей!";
	}
	if (!isset($_POST["superpowers"])) {
		$errors[] = "Выберите хотя бы одну суперспособность!";
	}
	if (empty($_POST["biography"])) {
		$errors[] = "Расскажите что-нибудь о себе!";
	}
} else {
	$errors[] = "Неверные данные формы!";
}
//Завершаем работу скрипта при наличии ошибок ( и выводим причину )
if (isset($errors)) {
	foreach ($errors as $value) {
		echo "$value<br>";
	}
	exit();
}

// Сохранение в базу данных.

$name = htmlspecialchars($_POST["name"]);
$email = htmlspecialchars($_POST["email"]);
$year = intval(htmlspecialchars($_POST["year"]));
$sex = htmlspecialchars($_POST["sex"]);
$limbs = intval(htmlspecialchars($_POST["limbs"]));
$superPowers = $_POST["superpowers"];
$biography = htmlspecialchars($_POST["biography"]);
if (!isset($_POST["agree"])) {
	$agree = 0;
} else {
	$agree = 1;
}

$serverName = "localhost";
$user = 'u47669';
$pass = '7643625';
$dbName = $user;
$db = new PDO("mysql:host=$serverName;dbname=$dbName", $user, $pass, array(PDO::ATTR_PERSISTENT => true));
$lastId = null;
// Подготовленный запрос. Не именованные метки.
/*
try {
  $stmt = $db->prepare("INSERT INTO application (name) SET name = ?");
  $stmt -> execute(array('fio'));
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
*/

try {
	$stmt = $db->prepare("INSERT INTO user (name, email, date, sex, limbs, biography, agreement) VALUES (:name, :email, :date, :sex, :limbs, :biography, :agreement)");
	$stmt->execute(array('name' => $name, 'email' => $email, 'date' => $year, 'sex' => $sex, 'limbs' => $limbs, 'biography' => $biography, 'agreement' => $agree));
	$lastId = $db->lastInsertId();
} catch (PDOException $e) {
	print('Error : ' . $e->getMessage());
	exit();
}

try {
	if ($lastId === null) {
		exit();
	}
	foreach ($superPowers as $value) {
		$stmt = $db->prepare("INSERT INTO user_power (id, power) VALUES (:id, :power)");
		$stmt->execute(array('id' => $lastId, 'power' => $value));
	}
} catch (PDOException $e) {
	print('Error : ' . $e->getMessage());
	exit();
}
$db = null;
echo "Данные сохранены и отправлены!";