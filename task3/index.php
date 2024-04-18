<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.html');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;


if ( empty($_POST['name']) ) {
  print('ФИО не указаны!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['phone']) ) {
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
if ( !isset($_POST['sex']) ) {
  print('Пол не указан!<br/>');
  $errors = TRUE;
}

if ( !isset($_POST['langs']) ) {
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
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u67354';
$pass = '3075308';
$db = new PDO('mysql:host=localhost;dbname=u67354', $user, $pass, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
// Подготовленный запрос. Не именованные метки.
try {
    $stmt = $db->prepare("INSERT INTO form SET name = ?, phone = ?, email = ?, date = ?, sex = ?, bio = ?");
    $stmt -> execute(array( $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['date'], $_POST['sex'], $_POST['bio'] ));
  
    $application_id = $db->lastInsertId();
    
    foreach ($_POST['langs'] as $language) {
      $stmt = $db->prepare("INSERT INTO lang_table (id, language) VALUES (?, ?)");
      $stmt->execute([$id, $language]);
    }
  }
  catch(PDOException $e){
    print('Ошибка: ' . $e->getMessage());
    exit();
  }
  
  header('Location: ?save=1');
