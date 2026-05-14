(function() {
    var form = document.getElementById('signupForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        var fullName = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        if (!fullName || !email) {
            alert('Please enter your name and email.');
            return;
        }

        EalmsStore.addSignupRequest({ fullName: fullName, email: email });
        alert('Registration submitted. An administrator will review your request before you can sign in.');
        window.location.href = 'index.html';
    });

    var picture = document.getElementById('picture');
    if (picture) {
        picture.addEventListener('change', function(ev) {
            var filename = ev.target.files[0] && ev.target.files[0].name ? ev.target.files[0].name : 'Choose image';
            var label = document.querySelector('.upload-text');
            if (label) label.textContent = filename;
        });
    }
})();
