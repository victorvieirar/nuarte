$(function() {
    $('a.anchor[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            var parent = $($(this).attr('parent'));
            navigateTo(target, parent);
        }
        return false;
    });

    $('#sidebar nav #menu li a').click(changeUserPage);
    $('#reservations-container table.admin tbody tr td:last-child .fa-times').click(cancelReservation);
    $('#reservations-container table.admin tbody tr td:last-child .fa-check').click(confirmReservation);
    $('#reservations-container table.admin tbody tr td:last-child .fa-undo').click(backReservation);
    $('#reservations-container table:not(.admin) tbody tr td:not(.not-allowed):last-child').click(deleteReservation);
    $('body#admin #instruments-container table tbody tr td:last-child').click(openEditInstrument);
    $('#reserve #instrument').change(updateDaysDatepicker);

    $('form.disabled *').attr('disabled', true);
})

function openEditInstrument() {
    var button = $(this);
    var name = button.attr('data-name');
    var reference = button.attr('data-reference');
    $('#editInstrument').modal('show');
    $('#edit-instrument-name').val(name);
    $('#edit-instrument-reference').val(reference);
}

function updateDaysDatepicker() {
    var instrument = $('#reserve #instrument').val();

    $.ajax({
            method: "POST",
            url: "../php/ajax/find-available.php",
            data: {
                find: true,
                instrument: instrument
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                setupAvailableDays(response.availableDays);
            } else {
                alert("Erro ao procurar reservas");
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
}

function setupAvailableDays(availableDays) {
    enableDays = availableDays;

    $.datepicker.regional['pt-BR'] = {
        closeText: 'Fechar',
        prevText: '&#x3c;Anterior',
        nextText: 'Pr&oacute;ximo&#x3e;',
        currentText: 'Hoje',
        monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
        ],
        dayNames: ['Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);

    $('#reservationDate').datepicker({ dateFormat: 'dd-mm-yy', beforeShowDay: enableAllTheseDays }).datepicker("refresh");
}

function enableAllTheseDays(date) {
    var sdate = $.datepicker.formatDate('d-m-yy', date)
    if ($.inArray(sdate, enableDays) != -1) {
        console.log(sdate)
        return [true];
    }
    return [false];
}

function navigateTo(target, parent) {
    target.removeClass('behind');
    slideDown(target);
    slideUp(parent);
    parent.addClass('behind');
}

function changeUserPage() {
    var button = $(this);
    var target = $(this.hash);
    $('.user-page.active').removeClass('active');
    target.addClass('active');

    $('#sidebar nav #menu li a.active').removeClass('active');
    button.addClass('active');

    return false;
}

function cancelReservation() {
    if (confirm('Tem certeza que deseja negar essa reserva?')) {
        var button = $(this).parent();
        var studentEnrollment = button.attr('data-studentEnrollment');
        var reservationDate = button.attr('data-reservationDate');
        var instrument = button.attr('data-instrument');

        $.ajax({
                method: "POST",
                url: "../php/ajax/reservation.php",
                data: {
                    cancel: true,
                    studentEnrollment: studentEnrollment,
                    reservationDate: reservationDate,
                    instrument: instrument
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    button.parent().find('#status').text('negado').removeClass('badge-warning badge-success').addClass('badge-danger');
                    alert('Reservada negada com sucesso!');
                } else {
                    alert('Desculpe, não conseguimos negar sua reserva, tente novamente.');
                }
            })
            .fail(function(jqXHR, textStatus, msg) {
                alert(msg);
            });
    }
}

function deleteReservation() {
    if (confirm('Tem certeza que deseja cancelar essa reserva?')) {
        var button = $(this);
        var studentEnrollment = button.attr('data-studentEnrollment');
        var reservationDate = button.attr('data-reservationDate');
        var instrument = button.attr('data-instrument');

        $.ajax({
                method: "POST",
                url: "../php/ajax/reservation.php",
                data: {
                    delete: true,
                    studentEnrollment: studentEnrollment,
                    reservationDate: reservationDate,
                    instrument: instrument
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    button.parent().remove();
                    alert('Reservada cancelada com sucesso!');
                } else {
                    alert('Desculpe, não conseguimos cancelar sua reserva, tente novamente.');
                }
            })
            .fail(function(jqXHR, textStatus, msg) {
                alert(msg);
            });
    }
}

function confirmReservation() {
    if (confirm('Tem certeza que deseja confirmar essa reserva?')) {
        var button = $(this).parent();
        var studentEnrollment = button.attr('data-studentEnrollment');
        var reservationDate = button.attr('data-reservationDate');
        var instrument = button.attr('data-instrument');

        $.ajax({
                method: "POST",
                url: "../php/ajax/reservation.php",
                data: {
                    confirm: true,
                    studentEnrollment: studentEnrollment,
                    reservationDate: reservationDate,
                    instrument: instrument
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    button.parent().find('#status').text('confirmado').removeClass('badge-warning badge-danger').addClass('badge-success');
                    alert('Reservada confirmada com sucesso!');
                } else {
                    alert('Desculpe, não conseguimos confirmar sua reserva, tente novamente.');
                }
            })
            .fail(function(jqXHR, textStatus, msg) {
                alert(msg);
            });
    }
}

function backReservation() {
    if (confirm('Tem certeza que deseja confirmar a devolução do item?')) {
        var button = $(this).parent();
        var studentEnrollment = button.attr('data-studentEnrollment');
        var reservationDate = button.attr('data-reservationDate');
        var instrument = button.attr('data-instrument');

        $.ajax({
                method: "POST",
                url: "../php/ajax/reservation.php",
                data: {
                    back: true,
                    studentEnrollment: studentEnrollment,
                    reservationDate: reservationDate,
                    instrument: instrument
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    button.parent().find('#status').text('devolvido').removeClass('badge-warning badge-danger').addClass('badge-success');
                    button.parent().find('#back').text(response.date);
                    alert('Reservada confirmada com sucesso!');
                } else {
                    alert('Desculpe, não conseguimos confirmar sua reserva, tente novamente.');
                }
            })
            .fail(function(jqXHR, textStatus, msg) {
                alert(msg);
            });
    }
}