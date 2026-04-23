
        let dashboardData = {
            totalUsers: 0,
            totalLeaves: 0,
            celebrations: []
        };

        async function fetchDashboardData() {
            try {
                // Use base URL if available, otherwise try relative path
                const baseUrl = window.BASE_URL || '';
                // Remove trailing slash if present, then add the path
                const cleanBaseUrl = baseUrl.replace(/\/$/, '');
                const apiUrl = cleanBaseUrl + '/Public/js/getUserCount.php';
                
                console.log('Fetching dashboard data from:', apiUrl);
                
                const response = await fetch(apiUrl);
                if (!response.ok) {
                    throw new Error('Failed to fetch dashboard data: ' + response.status + ' ' + response.statusText);
                }
                const data = await response.json();
                
                // Check if there's an error in the response
                if (data.error) {
                    console.error('API Error:', data.error);
                    throw new Error(data.error);
                }
                
                console.log('Dashboard data received:', data);
                return data;
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
                // Fallback to default values if fetch fails
                return {
                    totalUsers: 0,
                    totalLeaves: 0,
                    celebrations: []
                };
            }
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
            if (element) {
                animateValue(element, 0, count, 1000);
            }
        }

        function updateTotalLeaves(count) {
            const element = document.getElementById('totalLeaves');
            if (element) {
                animateValue(element, 0, count, 1000);
            }
        }

        function updateCelebrations(celebrations) {
            const listElement = document.getElementById('celebrationList');
            
            if (!listElement) {
                console.warn('Celebration list element not found');
                return;
            }
            
            if (!celebrations || celebrations.length === 0) {
                listElement.innerHTML = '<p class="card-description">No upcoming celebrations</p>';
                return;
            }

            let html = '';
            celebrations.forEach(celebration => {
                // Sanitize and ensure we have valid data
                const name = celebration.name || 'Unknown';
                const type = celebration.type || 'Reminder';
                const date = celebration.date || 'TBD';
                
                // Escape HTML to prevent XSS
                const safeName = name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                const safeType = type.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                const safeDate = date.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                
                html += `
                    <div class="celebration-item">
                        <div class="celebration-name">${safeName}</div>
                        <div class="celebration-date"><i class="fas fa-calendar"></i> ${safeDate}</div>
                        <span class="celebration-type">${safeType}</span>
                    </div>
                `;
            });
            listElement.innerHTML = html;
        }

        async function refreshDashboard() {
            const btn = document.querySelector('.refresh-btn');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Refreshing...';
                btn.disabled = true;
            }

            const data = await fetchDashboardData();
            
            // Ensure we have valid numbers
            const totalUsers = parseInt(data.totalUsers) || 0;
            const totalLeaves = parseInt(data.totalLeaves) || 0;
            
            updateTotalUsers(totalUsers);
            updateTotalLeaves(totalLeaves);
            // Celebrations are now loaded via PHP, so we don't update them via JavaScript
            // updateCelebrations(data.celebrations || []);

            dashboardData = data;

            if (btn) {
                btn.innerHTML = '<i class="fas fa-sync-alt"></i>Refresh Data';
                btn.disabled = false;
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            refreshDashboard();
        });

        setInterval(() => {
            refreshDashboard();
        }, 300000);