$(".daypicker").each(function () {
    $(this).datepicker('disabled');
});

$(".yearpicker").datepicker({ dateFormat: 'yy' }).focus(function () {
    $(".ui-datepicker-month").hide();
});