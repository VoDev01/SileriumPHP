let error = document.getElementById('error-attempts_available_in');
if(error)
{
    let button = error.parentElement.querySelector('button[type=submit]');
    button.disabled = true;
    let x = setInterval(function(){
        let message = error.innerHTML.split(' '); 
        message[1] -= 1;
        error.innerHTML = message.join(' ');
    }, 1000);
    button.disabled = true;
}