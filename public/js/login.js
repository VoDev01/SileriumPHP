$('#login_button').on('click', function (event) {
    event.preventDefault();
    let existing_errors = document.getElementsByClassName('error');
    for (let index = 0; index < existing_errors.length; index++) {
        existing_errors[index].remove();
    }
    $.ajax({
        type: 'POST',
        url: '/user/login',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: $("#login_form").serialize(),
        success: function (data) {
            window.location.href = data.redirect;
        },
        error: function (data) {
            let all_errors = data.responseJSON.errors;
            $.each(all_errors, function (key, value) {
                let error_text = document.createElement('span');
                error_text.id = key + '-error';
                error_text.classList.add('error');
                error_text.classList.add('text-danger');
                error_text.innerHTML = value;
                let field_id = '#' + key;
                $(field_id).after(error_text);
            });
        }
    });
});
$('#remember_me').on('click', function () {
    if ($('#remember_me').prop('checked')) {
        $('#remember_me').prop('checked', false);
        $('#remember_me').val(0);
    } else {
        $('#remember_me').prop('checked', true);
        $('#remember_me').val(1);
    }
});