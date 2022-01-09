
if (document.getElementById('requestedaccount-email')) {
    const emailObj = document.getElementById('requestedaccount-email');
    emailObj.addEventListener('blur',(event) => {
        console.log(emailObj.value);
        if (emailObj.value.length == 0) return;
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        $.ajax({
            url: 'index.php?r=site/check-email',
            type: 'post',
            data: {_csrf : csrfToken,  email: emailObj.value},
            async: true,
            cache: false
        })
    .done(function (person) {
        if (person == 'dup') {
            warning = document.getElementById('email-warning');
            warning.style.display = 'block';

        }
        })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);
    });
    });
}
