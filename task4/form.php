<!DOCTYPE html>
<html lang='ru'>
  <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='style.css'>
    <title>Задание 4</title>
  </head>
  
  <body>

    <?php
      if (!empty($messages)) {
        print('<div id="messages" class="alert alert-primary">');
        foreach ($messages as $message) {
          print($message);
        }
        print('</div>');
      }
    ?>
    
    <h3>ФОРМА</h3>
    <form action='index.php' method='POST' class='form'>
      <div>
        
        <label>
          Фамилия, Имя, Отчество:<br />
          <input name='name' placeholder='Введите ФИО' maxlength='50' required/>
        </label><br />
  
        
        <label>
          Номер телефона:<br />
          <input name='phone' type='tel' placeholder='+79001110011' maxlength='12' required/>
        </label><br />
      </div>
      
      <div>
        
        <label>
          Адрес электронной почты:<br />
          <input name='email' type='email' placeholder='Введите вашу почту' maxlength='50' required/>
        </label><br />
        
        
        <label>
          Дата рождения:<br />
          <input name='date' type='date' value='1970-01-01' min='1900-01-01' max='2020-01-01' required/>
        </label><br />
      </div>
      
      <div>
        Пол: <div class='mr1'></div> <br />
        <label>
          <input name='sex' type='radio' value='male' />
        М
        <div></div>
          <input name='sex' type='radio' value='female' />
        Ж <div class='mr2'></div>
        </label><br />
  
        <label>
          Любимый язык программирования:<br/>
          <select name='langs[]' multiple='multiple'>
            <option value='Pascal'>           Pascal</option>
            <option value='C'>                C</option>
            <option value='C++'>              C++</option>
            <option value='JavaScript'>       JavaScript</option>
            <option value='PHP'>              PHP</option>
            <option value='Python'>           Python</option>
            <option value='Java'>             Java</option>
            <option value='Haskel'>           Haskel</option>
            <option value='Clojure'>          Clojure</option>
            <option value='Prolog'>           Prolog</option>
            <option value='Scala'>            Scala</option>
          </select>
        </label><br />
      </div>
      
      <label>
        Биография:<br />
        <textarea name='bio' maxlength='250'> </textarea>
      </label><br />

      <label>
        <input name='checkmark' type='checkbox'/>
        С контрактом ознакомлен(а)</label><br/>

      <input type='submit' value='Сохранить'/>
    </form>
  </body>
</html>
