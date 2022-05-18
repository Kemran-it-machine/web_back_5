<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title> Web_Backend_4 </title>
  </head>

  <body>
    <h1> Добро пожаловать </h1>
    <?php
    if (!empty($messages)) {
      if (isset($messages['save']))
        print('<div id="messages" class="ok">');
      else print('<div id="messages">');
      foreach ($messages as $message) {
        print($message);
      }
      print('</div>');
    }
    ?>
    <div class = "container">
    <form action="" method="POST" >
      <p>
        <label>
          <strong> Имя: </strong> <br> <br>
          <input  name="name" placeholder = "Введите Ваше имя." 
            <?php if (!empty($errors['name'])) {
              print 'class="error"';} ?> 
            <?php if(empty($errors['name'])&&!empty($values['name'])) 
              print 'class="ok"';?> 
            value = "<?php isset($_COOKIE['name_error'])? print trim($_COOKIE['name_error']) : print $values['name']; ?>">
        </label>
      </p>
      <p>
        <label>
          <strong> E-mail: </strong> <br> <br>
          <input name="email" placeholder="Введите Ваш E-mail" type="text" 
            <?php if(!empty($errors['email']))  
              print 'class="error"';?> 
            <?php if(empty($errors['email'])&&!empty($values['email'])) 
              print 'class="ok"';?>
            value="<?php isset($_COOKIE['email_error'])? print trim($_COOKIE['email_error']) : print $values['email']; ?>">
        </label>
      </p>
      <p>
        <label>
          <strong> Год рождения </strong> <br> <br>
          <select name="year" 
            <?php if(!empty($errors['year']))  
              print 'class="error"';?> 
            <?php if(empty($errors['year'])&&!empty($values['year'])) 
              print 'class="ok"';?>>
              <option selected value="<?php !empty($values['year']) ? print ($values['year']) : print '' ?>"></option> // возможно нужно поставить selected
                <?php
                for ($i = 2004; $i >= 1920; $i--) {
                  echo "<option value = '$i'> $i </option>";
                }
                ?>
          </select>
        </label>
      </p>
      <p>
        <label <?php if(!empty($errors['sex'])) print 'class="error_check"'?>> 
          <strong> Пол </strong> <br> <br>
          <input type="radio" name="sex" value="male" <?php if (isset($values['sex'])&&$values['sex'] == 'male') print("checked"); ?>> Мужской <br> <br>
          <input type="radio" name="sex" value="female" <?php if (isset($values['sex'])&&$values['sex'] == 'female') print("checked"); ?>> Женский <br>
        </label>
      </p>
      <p>
        <label <?php if(isset($_COOKIE['limbs_error'])) print 'class="error_check"'?>>
          <strong> Количество конечностей </strong> <br> <br>
          <input type="radio" name="limbs" value="1" <?php if (isset($values['limbs'])&&$values['limbs'] == '1') print("checked"); ?>> 1
          <input type="radio" name="limbs" value="2" <?php if (isset($values['limbs'])&&$values['limbs'] == '2') print("checked"); ?>> 2
          <input type="radio" name="limbs" value="3" <?php if (isset($values['limbs'])&&$values['limbs'] == '3') print("checked"); ?>> 3
          <input type="radio" name="limbs" value="4" <?php if (isset($values['limbs'])&&$values['limbs'] == '4') print("checked"); ?>> 4
        </label>
      </p>
      <p>
        <label <?php if(!empty($errors['powers'])) print 'class="error_check"'?>> <strong> Сверхспособности: </strong> </label> <br> <br>
        <select multiple name="powers[]">
          <option value="immortal" <?php if(isset($values['powers']['immortal'])&&$values['powers']['immortal']=='immortal') print("checked");?>> Бессмертие </option>
          <option value="walls" <?php if(isset($values['powers']['walls'])&&$values['powers']['walls']=='walls') print("checked");?>> Прохождение сквозь стены </option>
          <option value="levitaion" <?php if(isset($values['powers']['levitation'])&&$values['powers']['levitation']=='levitation') print("checked");?>> Левитация </option>
        </select>
      </p>
      <p>
        <label>
          <strong> Биография: </strong> <br> <br>
          <textarea id="biography" name="biography" <?php if(!empty($errors['biography']))  print 'class="error"';?> <?php if(empty($errors['biography'])&&!empty($values['biography'])) print 'class="ok"';?>><?php isset($_COOKIE['biography_error']) ? print trim($_COOKIE['biography_error']) : print $values['biography'] ?></textarea>
        </label>
      </p>  
      <p>
        <label <?php if(!empty($errors['agree'])) print 'class="error_check"'?>>
          <input type="checkbox" name="agree" value = "agree" <?php if (isset($values['agree'])&&$values['agree'] == 'agree') print("checked"); ?>> <strong> С контрактом ознакомлен(-а) </strong>
        </label>
      </p>
      <p>
        <input class="button" type="submit" value="Отправить">
      </p>
    </form>
              </div>
  </body>

</html>