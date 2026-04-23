// Login
async function loginUser(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const formData = new FormData();
    formData.append("action", "login");
    formData.append("email", email);
    formData.append("password", password);

    const response = await fetch("../Controllers/UserController.php", {
        method: "POST",
        body: formData,
    });

    const data = await response.json();
    alert(data.message);

    if (data.success) {
        // Redirect to dashboard after login
        window.location.href = "userDashboard.html";
    }
}

// Register
async function registerUser(event) {
    event.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const formData = new FormData();
    formData.append("action", "register");
    formData.append("name", name);
    formData.append("email", email);
    formData.append("password", password);

    const response = await fetch("../Controllers/UserController.php", {
        method: "POST",
        body: formData,
    });

    const data = await response.json();
    alert(data.message);

    if (data.success) {
        window.location.href = "login.html";
    }
}
