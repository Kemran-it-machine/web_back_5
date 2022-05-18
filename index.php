<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 1);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages['save'] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['powers'] = !empty($_COOKIE['powers_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['agree'] = !empty($_COOKIE['agree_error']);
  // Выдаем сообщения об ошибках.
  if ($errors['name']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('name_error', '', 1);
    // Выводим сообщение.
    $messages['name_message'] = '<div class="error">Заполните имя. <br> Вводите символы только латинского или русского алфавитов. </div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 1);
    $messages['email_message'] = '<div class="error">Заполните e-mail. <br> Вводите символы  только латинского алфавита, цифры и знаки: "@", "-", "_". </div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 1);
    $messages['year_message'] = '<div class="error"> Выберите год рождения. </div>';
  }
  if ($errors['sex']) {
    setcookie('sex_error', '', 1);
    $messages['sex_message'] = '<div class="error"> Выберите пол. </div>';
  }
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 1);
    $messages['limbs_message'] = '<div class="error"> Выберите количество конечностей. </div>';
  }
  if ($errors['powers']) {
    setcookie('powers_error', '', 1);
    $messages[] = '<div class="error"> Выберите хотя бы одну суперспособность. </div>';
  }
  if ($errors['biography']) {
    setcookie('biography_error', '', 1);
    $messages['biography_message'] = '<div class="error"> Расскажите что-нибудь о себе! </div>';
  }
  if ($errors['agree']) {
    setcookie('agree_error', '', 1);
    $messages['agree_message'] = '<div class="error">  Ознакомьтесь с контрактом. </div>';
  }
  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
  $values['powers'] = []; // сделали массивом
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['agree'] = empty($_COOKIE['agree_value']) ? '' : $_COOKIE['agree_value'];

  $powers = array(
    'immortal' => "Бессметрие",
    'walls' => "Прохождение сквозь стены",
    'levitation' => "Левитация",
  );
  if (!empty($_COOKIE['powers_value'])){
    $powers_value = unserialize($_COOKIE['powers_value']);
    foreach ( $powers_value as $p )
    {
      if ( !empty($powers[$p])) {
        $values['powers'][$p] = $p;
      }
    }
  }
  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $errors = FALSE;
  //name
  if (empty($_POST['name'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('name_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match("/^\s*[a-zA-Zа-яА-Я'][a-zA-Zа-яА-Я-' ]+[a-zA-Zа-яА-Я']?\s*$/u", $_POST['name'])){
    setcookie('name_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }
  //email
  if (empty($_POST['email'])) {
    setcookie('email_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+.[a-zA-Z.]{2,5}$/", $_POST['email'])){
    setcookie('email_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
  //year
  if (empty($_POST['year'])) {
    setcookie('year_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
  }
  //sex
  if (empty($_POST['sex'])) {
    setcookie('sex_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('sex_value', $_POST['sex'], time() + 30 * 24 * 60 * 60);
  }
  //limbs
  if (empty($_POST['limbs'])) {
    setcookie('limbs_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
  }
  //powers
  if(empty($_POST['powers'])){
    setcookie('powers_error', ' ', time() + 24 * 60 * 60);
    setcookie('powers_value', '', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else{
    foreach ($_POST['powers'] as $key => $value) {
      $powers[$key] = $value;
    }
    setcookie('powers_value', serialize($powers), time() + 30 * 24 * 60 * 60);
  }
  //biography
  if (empty($_POST['biography'])) {
    setcookie('biography_error', ' ', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
  }
  //agree
  if (empty($_POST['agree'])) {
    setcookie('agree_error', ' ', time() + 24 * 60 * 60);
    setcookie('agree_value', '', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('agree_value', $_POST['agree'], time() + 30 * 24 * 60 * 60);
  }

// Сохранить в Cookie признаки ошибок и значения полей.`

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 1);
    setcookie('email_error', '', 1);
    setcookie('year_error', '', 1);
    setcookie('sex_error', '', 1);
    setcookie('limbs_error', '', 1);
    setcookie('powers_error', '', 1);
    setcookie('biography_error', '', 1);
    setcookie('agree_error', '', 1);
  }

  // Сохранение в БД.

  $name = $_POST['name'];
	$email = $_POST['email'];
	$year = intval(htmlspecialchars($_POST['year']));
	$sex = htmlspecialchars($_POST['sex']);
	$limbs = intval(htmlspecialchars($_POST['limbs']));
	$superPowers = $_POST['powers'];
	$biography = htmlspecialchars($_POST['biography']);

	$serverName = 'localhost';
	$user = "u47669";
	$pass = "7643625";
	$dbName = $user;

	$db = new PDO("mysql:host=$serverName;dbname=$dbName", $user, $pass, array(PDO::ATTR_PERSISTENT => true));

	$lastId = null;
	try {
		$stmt = $db->prepare("INSERT INTO user2 (name, email, date, sex, limbs, biography) VALUES (:name, :email, :date, :sex, :limbs, :biography)");
		$stmt->execute(array('name' => $name, 'email' => $email, 'date' => $year, 'sex' => $sex, 'limbs' => $limbs, 'biography' => $biography));
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
			$stmt = $db->prepare("INSERT INTO user_power2 (id, power) VALUES (:id, :power)");
			$stmt->execute(array('id' => $lastId, 'power' => intval($value)));
		}
	} catch (PDOException $e) {
		print('Error : ' . $e->getMessage());
		exit();
	}
	$db = null;

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}