<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Результаты сохранены.');
  }

  include('form.html');
  exit();
}
//////////////////////////////////////
// =========== POST ===========
else {
  // ERRORS
  $errors = FALSE;

  // name
  if (empty($_POST['name'])) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match('/^([А-Я]{1}[а-яё]{1,23}|[A-Z]{1}[a-z]{1,23})$/u', $_POST["name"])) {
      setcookie('name_error', '2', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }

  // phone
  if (empty($_POST['phone'])) {
    setcookie('phone_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
  }

  // email
  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }

  // langs
  $langs = array();

  foreach ($_POST['langs'] as $key => $value) {
      $langs[$key] = $value;
  }

  if (!sizeof($langs)) {
    setcookie('langs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('langs_value', json_encode($langs), time() + 30 * 24 * 60 * 60);
  }

  // bio
  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }

  // checkbox
  if (empty($_POST['check'])) {
    setcookie('check_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  // other
  setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
  setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);

  if ($errors) {
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('name_error', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('langs_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('check_error', '', 100000);
  }
/////////////////////////////
// Проверяем ошибки.
$errors = FALSE;


if ( empty($_POST['name']) || !preg_match('/([A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+)/', $_POST['name'])) {
  print('ФИО не указаны!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['phone']) || !is_numeric($_POST['phone'])) {
  print('Номер телефона не указан!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['email']) ) {
  print('Почта не указана!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['date']) ) {
  print('Дата рождения не указана!<br/>');
  $errors = TRUE;
}
if ( !isset($_POST['sex']) || !in_array($_POST['sex'], array('male', 'female')) ) {
  print('Пол не указан!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['langs']) ) {
  print('Языки программирования не выбраны!<br/>');
  $errors = TRUE;
}

if (empty($_POST['bio']) ) {
  print('Заполните биографию.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['checkmark']) || $_POST['checkmark'] != 'on') {
  print('Отметьте чекбокс.<br/>');
  $errors = TRUE;
}

if ($errors) {
  exit();
}

// Сохранение в базу данных.

$user = 'u67354';
$pass = '3075308';
$db = new PDO('mysql:host=localhost;dbname=u67354', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
// Подготовленный запрос. Не именованные метки.
try {
    $stmt = $db->prepare('INSERT INTO form SET name = ?, phone = ?, email = ?, date = ?, sex = ?, bio = ?');
    $stmt -> execute(array( $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['date'], $_POST['sex'], $_POST['bio'] ));
  
    $id = $db->lastInsertId();
    
    foreach ($_POST['langs'] as $lang) {
      $stmt = $db->prepare('INSERT INTO lang_table (id, lang) VALUES (?, ?)');
      $stmt->execute([$id, $lang]);
    }
  }
  catch(PDOException $e){
    print('Ошибка: ' . $e->getMessage());
    exit();
  }
  
  header('Location: ?save=1');
