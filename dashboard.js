(function() {
    var dateEl = document.getElementById('dashboardTodayDate');
    var badge = document.getElementById('dashboardPendingBadge');
    var todayBtn = document.getElementById('todayRequestLeave');
    var requestBtn = document.querySelector('.request-btn');
    var requestModal = document.getElementById('requestModal');

    if (dateEl) {
        dateEl.textContent = new Date().toLocaleDateString(undefined, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    if (todayBtn && requestBtn && requestModal) {
        todayBtn.addEventListener('click', function() {
            requestModal.classList.add('active');
        });
    }

    function updateTodayPendingLeaveBadge() {
        if (!badge) return;
        var n = EalmsStore.getLeaveRequests({
            employeeId: EalmsStore.DEMO_EMPLOYEE_ID,
            status: 'pending'
        }).length;
        if (n > 0) {
            badge.textContent = n === 1 ? '1 pending leave' : n + ' pending leave';
            badge.hidden = false;
        } else {
            badge.hidden = true;
        }
    }

    function announcementIcon() {
        var span = document.createElement('span');
        span.className = 'announcement-icon';
        span.setAttribute('aria-hidden', 'true');
        span.innerHTML =
            '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11v2a1 1 0 001 1h1l4 4V7L5 11H4a1 1 0 00-1 1z"/><path d="M15.54 8.46a5 5 0 010 7.07"/><path d="M19.07 4.93a9 9 0 010 14.14"/></svg>';
        return span;
    }

    function renderAnnouncements() {
        var container = document.getElementById('announcementsList');
        var badgeEl = document.getElementById('announcementsBadge');
        if (!container) return;
        var list = EalmsStore.getAnnouncements().slice().sort(function(a, b) {
            return b.createdAt - a.createdAt;
        });
        container.innerHTML = '';
        list.forEach(function(a) {
            var art = document.createElement('article');
            art.className = 'announcement-item';
            art.appendChild(announcementIcon());
            var div = document.createElement('div');
            div.className = 'announcement-content';
            var p = document.createElement('p');
            var strong = document.createElement('strong');
            strong.textContent = a.title + ':';
            p.appendChild(strong);
            p.appendChild(document.createTextNode(' '));
            var bodySpan = document.createElement('span');
            bodySpan.textContent = a.body;
            p.appendChild(bodySpan);
            var dateSpan = document.createElement('span');
            dateSpan.className = 'date';
            dateSpan.textContent = EalmsStore.relativeTime(a.createdAt);
            div.appendChild(p);
            div.appendChild(dateSpan);
            art.appendChild(div);
            container.appendChild(art);
        });
        if (badgeEl) {
            var newCount = list.filter(function(x) {
                return x.isNew;
            }).length;
            if (newCount > 0) {
                badgeEl.textContent = newCount === 1 ? '1 new' : newCount + ' new';
                badgeEl.hidden = false;
            } else {
                badgeEl.textContent = 'Updated';
                badgeEl.hidden = false;
            }
        }
    }

    function formatCelebrationDateLine(c) {
        var meta = EalmsStore.milestoneLabels[c.milestoneType] || EalmsStore.milestoneLabels.birthday;
        var d = new Date(c.eventDate + 'T12:00:00');
        var dateStr = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
        if (c.milestoneType === 'work' && c.years != null) {
            return dateStr + ' (' + c.years + ' ' + (c.years === 1 ? 'year' : 'years') + ')';
        }
        return dateStr;
    }

    function renderCelebrations() {
        var container = document.getElementById('celebrationsList');
        if (!container) return;
        var list = EalmsStore.getCelebrations().slice().sort(function(a, b) {
            return a.eventDate.localeCompare(b.eventDate);
        });
        container.innerHTML = '';
        list.forEach(function(c) {
            var meta = EalmsStore.milestoneLabels[c.milestoneType] || EalmsStore.milestoneLabels.birthday;
            var row = document.createElement('div');
            row.className = 'celebration-item';
            var inner = document.createElement('div');
            var nameP = document.createElement('p');
            nameP.className = 'person-name';
            nameP.textContent = c.personName;
            var small = document.createElement('small');
            var tag = document.createElement('span');
            tag.className = 'tag ' + meta.className;
            tag.textContent = meta.tag;
            small.appendChild(tag);
            small.appendChild(document.createTextNode(' · ' + formatCelebrationDateLine(c)));
            inner.appendChild(nameP);
            inner.appendChild(small);
            row.appendChild(inner);
            container.appendChild(row);
        });
    }

    function renderLeaveRequests() {
        var tbody = document.getElementById('leaveRequestsTbody');
        if (!tbody) return;
        var list = EalmsStore.getLeaveRequests({ employeeId: EalmsStore.DEMO_EMPLOYEE_ID }).slice();
        list.sort(function(a, b) {
            return b.submittedAt - a.submittedAt;
        });
        tbody.innerHTML = '';
        list.forEach(function(r) {
            var tr = document.createElement('tr');
            var tdDur = document.createElement('td');
            tdDur.textContent = EalmsStore.formatDuration(r.startDate, r.endDate);
            var tdType = document.createElement('td');
            tdType.textContent = EalmsStore.leaveLabels[r.leaveType] || r.leaveType;
            var tdDays = document.createElement('td');
            tdDays.textContent = String(EalmsStore.countLeaveDays(r.startDate, r.endDate));
            var tdSt = document.createElement('td');
            var span = document.createElement('span');
            span.className = 'status ' + (r.status === 'pending' ? 'pending' : r.status === 'approved' ? 'approved' : 'rejected');
            span.textContent = r.status.charAt(0).toUpperCase() + r.status.slice(1);
            tdSt.appendChild(span);
            var tdLink = document.createElement('td');
            var a = document.createElement('a');
            a.href = 'leave-details.html?id=' + encodeURIComponent(r.id);
            a.className = 'see-more';
            a.textContent = 'View';
            tdLink.appendChild(a);
            tr.appendChild(tdDur);
            tr.appendChild(tdType);
            tr.appendChild(tdDays);
            tr.appendChild(tdSt);
            tr.appendChild(tdLink);
            tbody.appendChild(tr);
        });
        updateTodayPendingLeaveBadge();
    }

    function refreshLists() {
        EalmsStore.init();
        renderAnnouncements();
        renderCelebrations();
        renderLeaveRequests();
        if (window.EalmsHeader) {
            EalmsHeader.applyProfileChrome();
            EalmsHeader.updateNotificationDot();
        }
    }

    refreshLists();

    var closeBtn = document.querySelector('.close-btn');
    var requestForm = document.getElementById('requestForm');

    if (requestBtn && requestModal) {
        requestBtn.addEventListener('click', function() {
            requestModal.classList.add('active');
        });
    }

    if (closeBtn && requestModal) {
        closeBtn.addEventListener('click', function() {
            requestModal.classList.remove('active');
        });
    }

    if (requestModal) {
        requestModal.addEventListener('click', function(e) {
            if (e.target === requestModal) {
                requestModal.classList.remove('active');
            }
        });
    }

    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var leaveType = document.getElementById('leaveType').value;
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;
            var reason = document.getElementById('reason').value;
            if (!leaveType || !startDate || !endDate) {
                alert('Please complete leave type and dates.');
                return;
            }
            if (endDate < startDate) {
                alert('End date must be on or after the start date.');
                return;
            }
            var prof = EalmsStore.getUserProfile();
            var displayName = prof.displayName || EalmsStore.DEMO_EMPLOYEE_NAME;
            var row = EalmsStore.addLeaveRequest({
                employeeId: EalmsStore.DEMO_EMPLOYEE_ID,
                employeeName: displayName,
                leaveType: leaveType,
                startDate: startDate,
                endDate: endDate,
                reason: reason
            });
            EalmsStore.addNotification({
                title: 'Leave request submitted',
                body:
                    (EalmsStore.leaveLabels[leaveType] || leaveType) +
                    ' · ' +
                    EalmsStore.formatDuration(startDate, endDate) +
                    ' — pending approval.',
                kind: 'leave',
                link: 'leave-details.html?id=' + encodeURIComponent(row.id)
            });
            requestForm.reset();
            if (requestModal) requestModal.classList.remove('active');
            refreshLists();
            if (window.EalmsHeader) EalmsHeader.updateNotificationDot();
            alert('Your leave request has been submitted for approval.');
        });
    }

    window.addEventListener('storage', function() {
        refreshLists();
    });
})();

// Mark attendance (Present / Absent / Remote)
(function() {
    var STORAGE_KEY = 'ealms_daily_attendance';
    var buttons = document.querySelectorAll('.attendance-btn');
    var feedback = document.getElementById('attendanceFeedback');
    if (!buttons.length || !feedback) return;

    var labels = { present: 'Present', absent: 'Absent', remote: 'Remote' };

    function todayISO() {
        return new Date().toISOString().slice(0, 10);
    }

    function setSelected(btn, announce) {
        buttons.forEach(function(b) {
            var on = b === btn;
            b.classList.toggle('is-selected', on);
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        var status = btn.dataset.status;
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({ date: todayISO(), status: status }));
        } catch (err) {}
        if (announce) {
            feedback.textContent = 'Attendance saved as ' + labels[status] + ' for today.';
        }
    }

    function restore() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            var data = JSON.parse(raw);
            if (data.date !== todayISO() || !data.status) return;
            var match = document.querySelector('.attendance-btn[data-status="' + data.status + '"]');
            if (match) {
                setSelected(match, false);
                feedback.textContent = "Today's attendance: " + labels[data.status] + '.';
            }
        } catch (err) {}
    }

    buttons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setSelected(btn, true);
        });
    });

    restore();
})();
