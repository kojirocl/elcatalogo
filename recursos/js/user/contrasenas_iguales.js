document.querySelector('form').addEventListener('submit', function(event) {
var password = document.getElementById('password').value;
var password2 = document.getElementById('password2').value;
if (password !== password2) {
    event.preventDefault();
    alert('Las contraseñas no coinciden.');
}
});
