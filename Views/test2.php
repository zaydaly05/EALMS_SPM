<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 400px;
            margin-left: auto;
        }

        .dashboard-title {
            color: #000000;
            font-size: 28px;
            margin-bottom: 30px;
            text-align: center;
        }

        .summary-card {
            background-color: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 123, 255, 0.2);
            border-color: #007bff;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .card-title {
            color: #000000;
            font-size: 18px;
            font-weight: 600;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .card-value {
            font-size: 36px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .card-description {
            color: #666666;
            font-size: 14px;
            line-height: 1.6;
        }

        .celebration-item {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #007bff;
        }

        .celebration-item:last-child {
            margin-bottom: 0;
        }

        .celebration-name {
            color: #000000;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .celebration-date {
            color: #666666;
            font-size: 13px;
        }

        .celebration-type {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            margin-top: 5px;
        }

        .refresh-btn {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            transition: all 0.3s ease;
        }

        .refresh-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .refresh-btn i {
            margin-right: 8px;
        }

        .loading {
            text-align: center;
            color: #666666;
            font-size: 14px;
            padding: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .summary-card {
            animation: fadeIn 0.5s ease;
            
        }

        .summary-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .summary-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .summary-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        @media (max-width: 480px) {
            .dashboard-container {
                max-width: 100%;
            }

            .card-value {
                font-size: 28px;
            }

            .card-title {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="dashboard-title">Dashboard Summary</h1>

        <div class="summary-card" id="usersCard">
            <div class="card-header">
                <h2 class="card-title">Total Users</h2>
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="card-value" id="totalUsers">0</div>
            <p class="card-description">Active employees registered in the system</p>
        </div>

        <div class="summary-card" id="leavesCard">
            <div class="card-header">
                <h2 class="card-title">Total Leaves</h2>
                <div class="card-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
            </div>
            <div class="card-value" id="totalLeaves">0</div>
            <p class="card-description">Leave requests this month (Approved & Pending)</p>
        </div>

        <div class="summary-card" id="celebrationCard">
            <div class="card-header">
                <h2 class="card-title">Celebration Reminders</h2>
                <div class="card-icon">
                    <i class="fas fa-birthday-cake"></i>
                </div>
            </div>
            <div id="celebrationList">
                <div class="loading">Loading celebrations...</div>
            </div>
        </div>

        <button class="refresh-btn" onclick="refreshDashboard()">
            <i class="fas fa-sync-alt"></i>Refresh Data
        </button>
    </div>

    <script>
        let dashboardData = {
            totalUsers: 0,
            totalLeaves: 0,
            celebrations: []
        };

        function fetchDashboardData() {
            return new Promise((resolve) => {
                setTimeout(() => {
                    const data = {
                        totalUsers: 156,
                        totalLeaves: 23,
                        celebrations: [
                            {
                                name: "Ahmed Mohamed",
                                type: "Birthday",
                                date: "Nov 22, 2025"
                            },
                            {
                                name: "Sara Hassan",
                                type: "Work Anniversary",
                                date: "Nov 25, 2025"
                            },
                            {
                                name: "Mohamed Ali",
                                type: "Birthday",
                                date: "Nov 28, 2025"
                            },
                            {
                                name: "Fatima Ibrahim",
                                type: "Work Anniversary",
                                date: "Dec 1, 2025"
                            }
                        ]
                    };
                    resolve(data);
                }, 500);
            });
        }

        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                element.textContent = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        function updateTotalUsers(count) {
            const element = document.getElementById('totalUsers');
            animateValue(element, 0, count, 1000);
        }

        function updateTotalLeaves(count) {
            const element = document.getElementById('totalLeaves');
            animateValue(element, 0, count, 1000);
        }

        function updateCelebrations(celebrations) {
            const listElement = document.getElementById('celebrationList');
            
            if (celebrations.length === 0) {
                listElement.innerHTML = '<p class="card-description">No upcoming celebrations</p>';
                return;
            }

            let html = '';
            celebrations.forEach(celebration => {
                html += `
                    <div class="celebration-item">
                        <div class="celebration-name">${celebration.name}</div>
                        <div class="celebration-date"><i class="fas fa-calendar"></i> ${celebration.date}</div>
                        <span class="celebration-type">${celebration.type}</span>
                    </div>
                `;
            });
            listElement.innerHTML = html;
        }

        async function refreshDashboard() {
            const btn = document.querySelector('.refresh-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Refreshing...';
            btn.disabled = true;

            const data = await fetchDashboardData();
            
            updateTotalUsers(data.totalUsers);
            updateTotalLeaves(data.totalLeaves);
            updateCelebrations(data.celebrations);

            dashboardData = data;

            btn.innerHTML = '<i class="fas fa-sync-alt"></i>Refresh Data';
            btn.disabled = false;
        }

        window.addEventListener('DOMContentLoaded', () => {
            refreshDashboard();
        });

        setInterval(() => {
            refreshDashboard();
        }, 300000);
    </script>
</body>
</html>