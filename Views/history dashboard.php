<?php

require_once './Controllers/HistoryC.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: /login');
    exit;
}

$historyController = new HistoryC();
$historyData = $historyController->getUserHistory($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>History</title>
  <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/history.css" />
</head>

<body>
<div class="container">

  <!-- Sidebar -->
  <aside class="sidebar">
      <div class="logo"><img src="<?php echo $base_url; ?>Public/Images/EALMS Logo.png" style="width: 180px;" height="auto" alt="EALMS Logo"></div>
    <nav>
      <a href="<?php echo $base_url; ?>dashboard">📊 Dashboard</a>
      <a href="<?php echo $base_url; ?>history" class="active">⏰ History</a>
    </nav>
  </aside>

  <!-- Main -->
  <div class="main">
    
    <?php include_once 'header.php'; ?>

    <main class="content">
      <h1 class="page-title">History</h1>

      <!-- Filters -->
      <section class="history-filters">
        <div class="tabs">
          <button class="tab active" data-status="present">Present</button>
          <button class="tab" data-status="halfday">Half Day</button>
          <button class="tab" data-status="absent">Absent</button>
        </div>

        <div class="period-buttons">
          <button class="period-btn active" data-period="1w">1W</button>
          <button class="period-btn" data-period="1m">1M</button>
          <button class="period-btn" data-period="3m">3M</button>
          <button class="period-btn" data-period="6m">6M</button>
          <button class="period-btn" data-period="1y">1Y</button>
          <button class="period-btn" data-period="all">ALL</button>
        </div>

        <div class="date-range">
          <input type="date" id="start-date">
          <input type="date" id="end-date">
        </div>
      </section>

      <!-- Table -->
      <table class="history-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Day</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Work Time</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="history-table-body"></tbody>
      </table>
    </main>
  </div>
</div>

<?php include_once 'footer.php'; ?>

<!-- PHP → JS DATA -->
<script>
const historyData = <?= json_encode($historyData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script>
const tabs = document.querySelectorAll(".tab");
const periodButtons = document.querySelectorAll(".period-btn");
const tableBody = document.querySelector("#history-table-body");
const startDateInput = document.querySelector("#start-date");
const endDateInput = document.querySelector("#end-date");

let selectedStatus = "present";
let selectedPeriod = "1w";

function parseDate(d) { return new Date(d); }

function getPeriodStartDate(period) {
  const now = new Date();
  switch(period){
    case "1w": return new Date(now.setDate(now.getDate() - 7));
    case "1m": return new Date(now.setMonth(now.getMonth() - 1));
    case "3m": return new Date(now.setMonth(now.getMonth() - 3));
    case "6m": return new Date(now.setMonth(now.getMonth() - 6));
    case "1y": return new Date(now.setFullYear(now.getFullYear() - 1));
    default: return new Date(0);
  }
}

function filterData() {
  let data = historyData.filter(r => r.type !== 'leave' && r.status === selectedStatus || r.type === 'leave');

  if (startDateInput.value) {
    data = data.filter(r => new Date(r.work_date) >= new Date(startDateInput.value));
  } else {
    data = data.filter(r => new Date(r.work_date) >= getPeriodStartDate(selectedPeriod));
  }

  if (endDateInput.value) {
    data = data.filter(r => new Date(r.work_date) <= new Date(endDateInput.value));
  }

  return data;
}

function renderTable() {
  const data = filterData();
  if (!data.length) {
    tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center">No records found</td></tr>`;
    return;
  }

  tableBody.innerHTML = data.map(r => {
    if (r.type === 'leave') {
      return `
        <tr>
          <td>${new Date(r.work_date).toLocaleDateString()}</td>
          <td>${new Date(r.work_date).toLocaleDateString('en-US',{weekday:'long'})}</td>
          <td>Leave</td>
          <td>-</td>
          <td>${r.status}</td>
          <td>Approved</td>
        </tr>
      `;
    } else {
      return `
        <tr>
          <td>${new Date(r.work_date).toLocaleDateString()}</td>
          <td>${new Date(r.work_date).toLocaleDateString('en-US',{weekday:'long'})}</td>
          <td>${r.check_in ?? '-'}</td>
          <td>${r.check_out ?? '-'}</td>
          <td>${r.work_time ?? '00H 00M'}</td>
          <td>${r.status.charAt(0).toUpperCase()+r.status.slice(1)}</td>
        </tr>
      `;
    }
  }).join('');
}

tabs.forEach(tab => {
  tab.onclick = () => {
    tabs.forEach(t => t.classList.remove("active"));
    tab.classList.add("active");
    selectedStatus = tab.dataset.status;
    renderTable();
  };
});

periodButtons.forEach(btn => {
  btn.onclick = () => {
    periodButtons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    selectedPeriod = btn.dataset.period;
    startDateInput.value = endDateInput.value = "";
    renderTable();
  };
});

[startDateInput, endDateInput].forEach(i => i.onchange = renderTable);

renderTable();
</script>
</body>
</html>
