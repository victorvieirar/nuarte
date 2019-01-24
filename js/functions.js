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
    $('#reservations-container table tbody tr td:last-child').click(cancelReservation);
})

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
    if(confirm('Tem certeza que deseja cancelar sua reserva?')) {
        var button = $(this);
        var studentEnrollment = button.attr('data-studentEnrollment');
        var reservationDate = button.attr('data-reservationDate');
        var reservationEnd = button.attr('data-reservationEnd');

        $.ajax({
            method: "POST",
            url: "../php/ajax/cancel-reservation.php",
            data: {
                cancel: true,
                studentEnrollment: studentEnrollment,
                reservationDate: reservationDate,
                reservationEnd: reservationEnd
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                button.parent().parent().remove();
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