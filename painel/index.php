<?php

require_once '../php/config/database.php';
require_once '../php/controllers/course.php';
require_once '../php/controllers/instrument.php';
require_once '../php/controllers/reservation.php';
require_once '../php/controllers/student.php';

session_start();

$instruments = getInstruments($conn);
$reservations = getReservations($conn);
$students = getStudents($conn);
$coursesTagged = getCoursesTagged($conn);

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
        <link type="text/css" rel="stylesheet" href="../css/styles.css">
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    </head>
    <body id="admin" <?php if(!isset($_SESSION['admin'])) echo 'class="login"'; ?>>
        <?php if(!isset($_SESSION['admin'])) { session_destroy(); ?>
        <section id="admin-login">
            <span class="logo small"></span>
            <form id="login-form" action="../php/operations/login-admin.php" method="post">
                <div class="form-group">
                    <input type="text" name="user" id="user" placeholder=" ">
                    <label for="user" class="gray regular">usuário</label>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder=" ">
                    <label for="password" class="gray regular">senha</label>
                </div>
                <button type="submit" name="login">entrar</button>
            </form>
        </section>    
        
        <?php 
        
        } else { 
            $admin = $_SESSION['admin'];    
        ?>
    
        <div id="sidebar">
            <span class="logo small"></span>
            <div id="hello"><span class="uppercase gray small medium">Fala, </span><span class="blue uppercase bold"><?php echo explode(' ', $admin['user'])[0];?>!</span></div>
            <nav>
                <ul id="menu">
                    <!--<li><a class="bold gray uppercase" href="#resume">resumo</a></li>-->
                    <li><a class="bold gray uppercase active" href="#instruments">instrumentos</a></li>
                    <li><a class="bold gray uppercase" href="#reservations">reservas</a></li>
                    <li><a class="bold gray uppercase" href="#students">usuários</a></li>
                </ul>
                <ul id="exit">
                    <li><a class="bold red uppercase" href="../php/operations/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>

        <section id="resume" class="user-page">
            <div class="top">
                <h3 class="bold gray uppercase">Resumo</h3>
            </div>
            <div>

            </div>
        </section>

        <section id="instruments" class="user-page active">
            <div class="top">
                <h3 class="bold gray uppercase">Adicionar instrumentos</h3>
            </div>
            <div id="instruments-form">
                <form action="../php/operations/add-instrument.php" method="post">
                    <div class="form-group">
                        <input type="number" name="reference" id="instrument-reference" placeholder=" ">
                        <label for="instrument-reference" class="gray regular">referência</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" id="instrument-name" placeholder=" ">
                        <label for="instrument-name" class="gray regular">nome</label>
                    </div>
                    <button type="submit" name="add">adicionar instrumento</button>
                </form>
            </div>
            <div class="top">
                <h3 class="bold gray uppercase">instrumentos</h3>
            </div>
            <div id="instruments-container">
                <table class="uppercase">
                    <thead>
                        <tr class="bold gray">
                            <td>Código</td>
                            <td>Instrumento</td>
                            <td>Disponibilidade</td>
                            <td>Ações</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($instruments as $instrument) {
                            $instrumentReservations = getInstrumentReservations($conn, $instrument);
                            
                            $reservationDate = null;
                            $reservationEnd = null;
                            $now = new DateTime("now");

                            if(!empty($instrumentReservations)) {
                                $instrumentReservations = $instrumentReservations[0];
                                $reservationDate = new DateTime($instrumentReservations['reservationDate']);
                                $reservationEnd = new DateTime($instrumentReservations['reservationEnd']);
                            }
                    ?>
                        <tr class="medium">
                            <td><?php echo $instrument['reference']; ?></td>
                            <td><?php echo $instrument['name']; ?></td>
                            <td>
                            <?php if($reservationDate > $now || empty($instrumentReservations)) { ?>
                                <span class="badge badge-pill badge-success medium">disponível</span>
                            <?php } else { ?>
                                <span class="badge badge-pill badge-danger medium">indisponível</span><span class="red small"><?php echo "até ".$reservationEnd->format("d/m/Y"); ?></span>
                            <?php } ?>
                            </td>
                            <td class="blue pointer" data-reference="<?php echo $instrument['reference']; ?>" data-name="<?php echo $instrument['name']; ?>"><span class="fas fa-pencil-alt"></span> editar</td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <section id="reservations" class="user-page">
            <div class="top">
                <h3 class="bold gray uppercase">Reservas</h3>
            </div>
            <div id="reservations-container">
                <table class="uppercase admin">
                    <thead>
                        <tr class="bold gray">
                            <td>Cód.</td>
                            <td>Matrícula</td>
                            <td>Data da reserva</td>
                            <td>Data de entrega</td>
                            <td>Data de devolução</td>
                            <td>Prazo</td>
                            <td>Status</td>
                            <td>Ações</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($reservations as $reservation) {                          
                            $startTime = new DateTime($reservation['reservationDate']);
                            $endTime = new DateTime($reservation['reservationEnd']);
                            $backTime = null;
                            if($reservation['backDate'] != null) $backTime = new DateTime($reservation['backDate']);
                            $nowTime = new DateTime();

                            $status = $reservation['status'];
                            $statusText = '';
                            $statusClass = '';
                            switch($status) {
                                case 0:
                                $statusText = 'pendente';
                                $statusClass = 'warning';
                                break;
                                
                                case 1:
                                $statusText = 'confirmado';
                                $statusClass = 'success';
                                break;
                                
                                case 2: 
                                $statusText = 'negado';
                                $statusClass = 'danger';
                                break;

                                case 3:
                                $statusText = 'devolvido';
                                $statusClass = 'success';
                            }

                            if($status == 3 && $backTime != null) {
                                $term = $backTime > $endTime ? 0 : 1;
                                $backTime = $backTime->format('d/m/Y');
                            } else {
                                $term = $nowTime > $endTime ? 0 : 1;
                            }
                            
                            $startTime = $startTime->format('d/m/Y');
                            $endTime = $endTime->format('d/m/Y');
                    ?>
                        <tr class="medium">
                            <td><?php echo $reservation['instrument']; ?></td>
                            <td><?php echo $reservation['studentEnrollment']; ?></td>
                            <td><?php echo $startTime; ?></td>
                            <td><?php echo $endTime; ?></td>
                            <td id="back"><?php if($reservation['backDate'] != null) echo $backTime; else echo "-"; ?></td>
                            <td>
                            <?php if($term) { ?>
                                <span class="badge badge-pill badge-success medium">no prazo</span>
                            <?php } else { ?>
                                <span class="badge badge-pill badge-danger medium">atrasado</span>
                            <?php } ?>
                            </td>
                            <td><span id="status" class="badge badge-pill badge-<?php echo $statusClass; ?> medium"><?php echo $statusText; ?></span></td>
                            <td class="pointer" data-studentEnrollment="<?php echo $reservation['studentEnrollment']; ?>" data-reservationDate="<?php echo $reservation['reservationDate']; ?>" data-instrument="<?php echo $reservation['instrument']; ?>"><i class="fas red fa-times"></i> <i class="fas blue fa-check"></i> <i class="fas blue fa-undo"></i></td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="students" class="user-page">
            <div class="top">
                <h3 class="bold gray uppercase">Alunos cadastrados</h3>
            </div>
            <div id="students-container">
                <table class="uppercase">
                    <thead>
                        <tr class="bold gray">
                            <td>Matrícula</td>
                            <td>Nome</td>
                            <td>E-mail</td>
                            <td>Curso</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($students as $student) {          
                    ?>
                        <tr class="medium">
                            <td><?php echo $student['enrollment']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo $coursesTagged[$student['course']]; ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="modal fade" id="editInstrument" tabindex="-1" role="dialog" aria-labelledby="editIntrumentCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editIntrumentLongTitle">Editar instrumento</h5>
                    <button type="button" class="close no-button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../php/operations/update-instrument.php" method="post">
                        <input type="hidden" name="reference" id="edit-instrument-reference" placeholder=" ">    
                        <div class="form-group">
                            <input type="text" name="name" id="edit-instrument-name" placeholder=" ">
                            <label for="instrument-name" class="gray regular">nome</label>
                        </div>
                        <button type="submit" name="add">atualizar instrumento</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="gray" data-dismiss="modal">Fechar</button>
                </div>
                </div>
            </div>
        </div>

        <?php } ?>
    </body>
    <script src="https://isuttell.github.io/sine-waves/javascripts/sine-waves.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/functions.js"></script>
</html>