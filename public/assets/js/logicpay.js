window.addEventListener('message', function(event)
{
    var token = JSON.parse(event.data);
    var mytoken = document.getElementById('mytoken');
    mytoken.value = token.message;
    Livewire.emit('logicToken', token.message);
}, false);

