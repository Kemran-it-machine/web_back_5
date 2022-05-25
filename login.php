<?php
header('Content-Type: text/html; charset=UTF-8');

session_start();

$db_user = 'u47669';   
$db_pass = '7643625';  
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['do'])&&$_GET['do'] == 'logout'){
    session_start();    
    session_unset();
    session_destroy();
    setcookie ("PHPSESSID", "", time() - 3600, '/');
    header("Location: index.php");
    exit;}
?>

<form action="" method="post">
  <p><label for="login">Логин </label><input name="login" /></p>
  <p><label for="pass">Пароль </label><input name="pass" /></p>
  <input type="submit" value="Войти" />
</form>

<?php
}
else {

  $login = $_POST['login'];
  $pass =  $_POST['pass'];

  $db = new PDO('mysql:host=localhost;dbname=u47669', $db_user, $db_pass, array(
    PDO::ATTR_PERSISTENT => true
  ));

  try {
    $stmt = $db->prepare("SELECT * FROM users5 WHERE login = ?");
    $stmt->execute(array(
      $login
    ));
    // Получаем данные в виде массива из БД.
    $user = $stmt->fetch();
    // Сравнием текущий хэш пароля с тем, что достали из базы.
    if (password_verify($pass, $user['pass'])) {
      // Если он верныйы, то записываем логин в сессию.
      $_SESSION['login'] = $login;
    }
    else {
      echo "Неправильный логин или пароль";
      exit();
    }

  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }
  header('Location: ./');
}