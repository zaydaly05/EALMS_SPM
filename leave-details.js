(function() {
    var params = new URLSearchParams(window.location.search);
    var id = params.get('id');
    var list = EalmsStore.getLeaveRequests();
    var req = id ? list.find(function(r) {
        return r.id === id;
    }) : null;

    function setText(id, text) {
        var el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    function fmt(iso) {
        return new Date(iso + 'T12:00:00').toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    var closeBtn = document.querySelector('.close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'dashboard.html';
        });
    }

    if (!req) {
        setText('leaveTitle', 'Leave request');
        setText('detailStart', '—');
        setText('detailEnd', '—');
        setText('detailReason', 'No matching request. Open this page from your dashboard after selecting a request.');
        setText('detailType', '—');
        setText('detailDays', '—');
        setText('detailSubmitted', '—');
        var badge = document.getElementById('detailStatusBadge');
        if (badge) {
            badge.textContent = 'Unknown';
            badge.className = 'status-badge pending';
        }
        return;
    }

    setText('leaveTitle', 'Leave request');
    setText('detailStart', fmt(req.startDate));
    setText('detailEnd', fmt(req.endDate));
    setText('detailReason', req.reason ? req.reason : '—');
    setText('detailType', EalmsStore.leaveLabels[req.leaveType] || req.leaveType);
    setText('detailDays', String(EalmsStore.countLeaveDays(req.startDate, req.endDate)));
    setText('detailSubmitted', new Date(req.submittedAt).toLocaleString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit'
    }));

    var badge = document.getElementById('detailStatusBadge');
    if (badge) {
        var st = req.status || 'pending';
        badge.textContent = st.charAt(0).toUpperCase() + st.slice(1);
        badge.className = 'status-badge ' + (st === 'approved' ? 'approved' : st === 'rejected' ? 'rejected' : 'pending');
    }
})();
