
<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();

  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Результаты сохранены.';
  }

  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date'] = !empty($_COOKIE['date_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['langs'] = !empty($_COOKIE['langs_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['checkmark'] = !empty($_COOKIE['checkmark_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">ФИО не указаны!</div>';
  }
  if ($errors['phone']) {
    setcookie('phone_error', '', 100000);
    $messages[] = '<div class="error">Номер телефона не указан!</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Почта не указана!</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  // TODO: аналогично все поля.

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');

  exit();
}
else {
  $errors = FALSE;
  
  if ( empty($_POST['name']) || !preg_match('/([A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+)/', $_POST['name'])) {
    print('ФИО не указаны!<br/>');
    $errors = TRUE;
  }
  if (empty($_POST['name'])) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match('/([А-Яа-я-]+\s[А-Яа-я-]+\s[А-Яа-я-]+)/', $_POST['name'])) {
    setcookie('name_error', '2', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
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
}
