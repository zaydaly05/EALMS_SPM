document.getElementById("submitAttendance").addEventListener("click", async (e) => {
    e.preventDefault();

    const employee_id = document.getElementById("employee_id").value;
    const status = document.getElementById("status").value;

    const formData = new FormData();
    formData.append("employee_id", employee_id);
    formData.append("status", status);

    const res = await fetch("../Controllers/AttendanceController.php", {
        method: "POST",
        body: formData
    });

    const data = await res.json();
    alert(data.message);
});
document.getElementById("viewAttendance").addEventListener("click", async (e) => {
    e.preventDefault(); });

    const employee_id = document.getElementById("employee_id_view").value;          