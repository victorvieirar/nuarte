<?php

require_once '../php/config/database.php';
require_once '../php/controllers/course.php';
require_once '../php/controllers/instrument.php';
require_once '../php/controllers/reservation.php';

session_start();

if(!isset($_SESSION['student'])) {
    session_destroy();
    header('location: ../');
}

$student = $_SESSION['student'];

$courses = getCourses($conn);
$instruments = getInstruments($conn);
$reservations = getStudentReservations($conn, $student);

$instrumentsAvailable = $instruments;
foreach($instrumentsAvailable as $index => $instrument) {
    foreach($reservations as $reservation) {
        if($reservation['instrument'] == $instrument['reference']) {
            unset($instrumentsAvailable[$index]);
        }
    }
}
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
        <link type="text/css" rel="stylesheet" href="../css/jquery-ui.min.css">
        <link type="text/css" rel="stylesheet" href="../css/styles.css">
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    </head>
    <body id="user"> 
        <div id="sidebar">
            <span class="logo small"></span>
            <div id="hello"><span class="uppercase gray small medium">Fala, </span><span class="blue uppercase bold"><?php echo explode(' ', $student['name'])[0];?>!</span></div>
            <nav>
                <ul id="menu">
                    <li><a class="bold gray uppercase active" href="#profile">meu perfil</a></li>
                    <li><a class="bold gray uppercase" href="#instruments">visualizar instrumentos</a></li>
                    <li><a class="bold gray uppercase" href="#reservations">minhas reservas</a></li>
                    <li><a class="bold gray uppercase" href="#reserve">realizar reserva</a></li>
                </ul>
                <ul id="exit">
                    <li><a class="bold red uppercase" href="../php/operations/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>

        <section id="profile" class="user-page active">
            <div class="top">
                <h3 class="bold gray uppercase">Meu perfil</h3>
            </div>
            <div id="profile-form">
                <form action="../php/operations/update-user.php" method="post">
                    <input type="hidden" name="enrollment" id="update_enrollment" value="<?php echo $student['enrollment']; ?>">
                    <div class="form-group">
                        <input type="text" value="<?php echo $student['enrollment']; ?>" placeholder=" " disabled>
                        <label for="update_enrollment" class="gray regular">matrícula</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" id="update_name" value="<?php echo $student['name']; ?>" placeholder=" ">
                        <label for="update_name" class="gray regular">nome</label>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="update_email" value="<?php echo $student['email']; ?>" placeholder=" ">
                        <label for="update_email" class="gray regular">e-mail</label>
                    </div>
                    <div class="form-group">
                        <select name="course" id="course">
                            <?php
                            foreach($courses as $course) {
                                echo $course['id'];
                                echo $student['course'];
                            ?>
                            <option value="<?php echo $course['id']; ?>" <?php if($course['id'] == $student['course']) echo 'selected'; ?>><?php echo $course['name']; ?></option>
                            <?php
                            }                        
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="update_password" value="<?php echo $student['password']; ?>" placeholder=" ">
                        <label for="update_password" class="gray regular">senha</label>
                    </div>
                    <button type="submit" name="update">salvar dados</button>
                </form>
            </div>
        </section>

        <section id="instruments" class="user-page">
            <div class="top">
                <h3 class="bold gray uppercase">Visualizar instrumentos</h3>
            </div>
            <div id="instruments-container">
                <table class="uppercase">
                    <thead>
                        <tr class="bold gray">
                            <td>Código</td>
                            <td>Instrumento</td>
                            <td>Disponibilidade</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($instruments as $instrument) {
                            $instrumentReservations = getFirstInstrumentReservation($conn, $instrument);
                            $reservationDate = null;
                            $reservationEnd = null;
                            $now = new DateTime("now");

                            if(!empty($instrumentReservations)) {
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
                <h3 class="bold gray uppercase">Minhas reservas</h3>
            </div>
            <div id="reservations-container">
                <table class="uppercase">
                    <thead>
                        <tr class="bold gray">
                            <td>Cód.</td>
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

                            $canCancel = TRUE;
                            if($status > 0) {
                                $canCancel = FALSE;
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
                            <td><?php echo $startTime; ?></td>
                            <td><?php echo $endTime; ?></td>
                            <td><?php if($reservation['backDate'] != null) echo $backTime; else echo "-"; ?></td>
                            <td>
                            <?php if($term) { ?>
                                <span class="badge badge-pill badge-success medium">no prazo</span>
                            <?php } else { ?>
                                <span class="badge badge-pill badge-danger medium">atrasado</span>
                            <?php } ?>
                            </td>
                            <td><span id="status" class="badge badge-pill badge-<?php echo $statusClass; ?> medium"><?php echo $statusText; ?></span></td>
                            <?php if($canCancel) { ?><td class="yellow pointer" data-studentEnrollment="<?php echo $student['enrollment']; ?>" data-reservationDate="<?php echo $reservation['reservationDate']; ?>" data-instrument="<?php echo $reservation['instrument']; ?>"><i class="fas fa-times"></i> Cancelar</td>
                            <?php } else { ?><td class="gray not-allowed"><i class="fas fa-ban"></i></td><?php } ?>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="reserve" class="user-page">
            <div class="top">
                <h3 class="bold gray uppercase">Realizar reserva</h3>
            </div>
            <?php if($student['block'] > 0) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Ops!</strong> Você possui uma punição por itens atrasados e, por isso, está impedido de fazer reservas por <?php echo $student['block']; ?> dia(s). Regularize sua situação e tente novamente.
                    <button type="button" class="close no-button" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div id="reserve-form">
            <form action="<?php if($student['block'] == 0) { ?>../php/operations/reserve.php<?php } ?>" method="post" class="<?php if($student['block'] > 0) echo 'disabled'; ?>">
                    <div class="form-group">
                        <select name="instrument" id="instrument">
                            <option value="" disabled selected>Instrumento</option>
                            <?php
                            foreach($instrumentsAvailable as $instrument) {
                                $instrumentReservations = getInstrumentReservations($conn, $instrument);
                            ?>
                            <option value="<?php echo $instrument['reference']; ?>"><?php echo $instrument['name']; ?></option>
                            <?php
                            }                        
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="reservationDate" id="reservationDate" min="<?php echo date("Y-m-d"); ?>" placeholder=" " autocomplete="off">
                        <label for="reservationDate" class="gray regular">data</label>
                    </div>
                    <input type="hidden" name="studentEnrollment" value="<?php echo $student['enrollment']; ?>">
                    <button type="submit" name="reserve">fazer reserva</button>
                </form>
            </div>
        </section>
    </body>
    <script src="https://isuttell.github.io/sine-waves/javascripts/sine-waves.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/functions.js"></script>
</html>