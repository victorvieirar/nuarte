$(function() {
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            var parent = $($(this).attr('parent'));
            navigateTo(target, parent);
        }
        return false;
    });
})

function navigateTo(target, parent) {
    target.removeClass('behind');
    slideDown(target);
    slideUp(parent);
    parent.addClass('behind');
}