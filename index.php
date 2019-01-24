<?php

include 'php/config/database.php';
include 'php/controllers/course.php';

$courses = getCourses($conn);

?>
<html>
    <head>
        <title>Nuarte</title>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/styles.css">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body>
        <div class="waves-container"><canvas id="waves"></canvas></div>
        
        <section id="home" class="page">
            <span id="welcome" class="small regular gray">bem-vindos ao</span>
            <span class="logo"></span>
            <div id="guitar"><div><hr><hr><hr><hr><hr><hr></div></div>
            <a id="reserve-button" class="anchor button blue regular" href="#login" parent="#home">reservar</a>
            <a id="how-works" class="anchor link small gray medium" href="#" parent="#home">como funciona?</a>
        </section>

        <section id="login" class="page">
            <span class="logo small"></span>
            <form id="login-form" action="php/operations/login.php" method="post">
                <div class="form-group">
                    <input type="text" name="enrollment" id="enrollment" placeholder=" ">
                    <label for="enrollment" class="gray regular">matrícula</label>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder=" ">
                    <label for="password" class="gray regular">senha</label>
                </div>
                <button type="submit" name="login">entrar</button>
            </form>
            <span class="semibold small gray">Não tenho um cadastro. <a class="anchor bold blue" href="#register" parent="#login">Quero me cadastrar.</a></span>
            <a id="back-home-button" href="#home" parent="#login" class="anchor button gray regular">voltar</a>
        </section>

        <section id="register" class="page">
            <span class="logo small"></span>
            <form id="register-form" action="php/operations/register.php" method="post">
                <div class="form-group">
                    <input type="text" name="enrollment" id="register_enrollment" placeholder=" ">
                    <label for="enrollment" class="gray regular">matrícula</label>
                </div>
                <div class="form-group">
                    <input type="text" name="name" id="register_name" placeholder=" ">
                    <label for="register_name" class="gray regular">nome</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="register_email" placeholder=" ">
                    <label for="register_email" class="gray regular">e-mail</label>
                </div>
                <div class="form-group">
                    <select name="course" id="course">
                        <option value="" disabled selected>Selecione seu curso</option>
                        <?php
                        foreach($courses as $course) {
                        ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
                        <?php
                        }                        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="register_password" placeholder=" ">
                    <label for="register_password" class="gray regular">senha</label>
                </div>
                <button type="submit" name="register">criar minha conta</button>
            </form>
            <span class="semibold small gray">Já tenho uma conta. <a class="anchor bold blue" href="#login" parent="#register">Quero fazer o login.</a></span>
            <a id="back-home-button" href="#home" parent="#register" class="anchor button gray regular">voltar</a>
        </section>
    </body>
    <script src="https://isuttell.github.io/sine-waves/javascripts/sine-waves.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="js/animations.js"></script>
    <script src="js/functions.js"></script>
</html>