(function() {
    var annForm = document.getElementById('announceForm');
    var celForm = document.getElementById('celebrationForm');
    var celType = document.getElementById('celType');
    var celYearsRow = document.getElementById('celYearsRow');
    var celYears = document.getElementById('celYears');
    var celDate = document.getElementById('celDate');
    var flash = document.getElementById('adminFlash');
    var signupTbody = document.getElementById('signupTbody');
    var signupEmpty = document.getElementById('signupEmpty');
    var leaveTbody = document.getElementById('leaveAdminTbody');
    var leaveEmpty = document.getElementById('leaveEmpty');

    function showFlash(msg, ok) {
        if (!flash) return;
        flash.textContent = msg;
        flash.className = 'admin-flash ' + (ok ? 'admin-flash--ok' : 'admin-flash--err');
        clearTimeout(showFlash._t);
        showFlash._t = setTimeout(function() {
            flash.className = 'admin-flash';
            flash.textContent = '';
        }, 3200);
    }

    function syncMilestoneFields() {
        var isWork = celType && celType.value === 'work';
        if (celYearsRow) {
            celYearsRow.hidden = !isWork;
            if (celYears) celYears.required = isWork;
        }
    }

    if (celType) {
        celType.addEventListener('change', syncMilestoneFields);
        syncMilestoneFields();
    }

    if (celDate && !celDate.value) {
        celDate.valueAsDate = new Date();
    }

    function fmtWhen(ts) {
        return new Date(ts).toLocaleString(undefined, {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    function statusSpan(status) {
        var s = document.createElement('span');
        s.className = 'status ' + (status === 'pending' ? 'pending' : status === 'approved' ? 'approved' : 'rejected');
        s.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        return s;
    }

    function renderSignups() {
        if (!signupTbody) return;
        var rows = EalmsStore.getSignupRequests().slice();
        rows.sort(function(a, b) {
            if (a.status === 'pending' && b.status !== 'pending') return -1;
            if (a.status !== 'pending' && b.status === 'pending') return 1;
            return b.requestedAt - a.requestedAt;
        });
        signupTbody.innerHTML = '';
        if (!rows.length) {
            signupEmpty.hidden = false;
            return;
        }
        signupEmpty.hidden = true;
        rows.forEach(function(r) {
            var tr = document.createElement('tr');
            var tdName = document.createElement('td');
            tdName.textContent = r.fullName;
            var tdEmail = document.createElement('td');
            tdEmail.textContent = r.email;
            var tdReq = document.createElement('td');
            tdReq.textContent = fmtWhen(r.requestedAt);
            var tdSt = document.createElement('td');
            tdSt.appendChild(statusSpan(r.status));
            var tdAct = document.createElement('td');
            if (r.status === 'pending') {
                var wrap = document.createElement('div');
                wrap.className = 'admin-actions';
                var bOk = document.createElement('button');
                bOk.type = 'button';
                bOk.className = 'btn-approve';
                bOk.textContent = 'Approve';
                bOk.setAttribute('data-signup-id', r.id);
                bOk.setAttribute('data-action', 'approve');
                var bNo = document.createElement('button');
                bNo.type = 'button';
                bNo.className = 'btn-reject';
                bNo.textContent = 'Reject';
                bNo.setAttribute('data-signup-id', r.id);
                bNo.setAttribute('data-action', 'reject');
                wrap.appendChild(bOk);
                wrap.appendChild(bNo);
                tdAct.appendChild(wrap);
            } else {
                tdAct.textContent = r.decidedAt ? 'Decided ' + fmtWhen(r.decidedAt) : '—';
                tdAct.style.color = 'var(--color-text-muted)';
                tdAct.style.fontSize = '0.75rem';
            }
            tr.appendChild(tdName);
            tr.appendChild(tdEmail);
            tr.appendChild(tdReq);
            tr.appendChild(tdSt);
            tr.appendChild(tdAct);
            signupTbody.appendChild(tr);
        });
    }

    function renderLeaves() {
        if (!leaveTbody) return;
        var rows = EalmsStore.getLeaveRequests().slice();
        rows.sort(function(a, b) {
            if (a.status === 'pending' && b.status !== 'pending') return -1;
            if (a.status !== 'pending' && b.status === 'pending') return 1;
            return b.submittedAt - a.submittedAt;
        });
        leaveTbody.innerHTML = '';
        if (!rows.length) {
            leaveEmpty.hidden = false;
            return;
        }
        leaveEmpty.hidden = true;
        rows.forEach(function(r) {
            var tr = document.createElement('tr');
            var tdEmp = document.createElement('td');
            tdEmp.textContent = r.employeeName || '—';
            var tdDur = document.createElement('td');
            tdDur.textContent = EalmsStore.formatDuration(r.startDate, r.endDate);
            var tdType = document.createElement('td');
            tdType.textContent = EalmsStore.leaveLabels[r.leaveType] || r.leaveType;
            var tdDays = document.createElement('td');
            tdDays.textContent = String(EalmsStore.countLeaveDays(r.startDate, r.endDate));
            var tdSt = document.createElement('td');
            tdSt.appendChild(statusSpan(r.status));
            var tdAct = document.createElement('td');
            if (r.status === 'pending') {
                var wrap = document.createElement('div');
                wrap.className = 'admin-actions';
                var bOk = document.createElement('button');
                bOk.type = 'button';
                bOk.className = 'btn-approve';
                bOk.textContent = 'Approve';
                bOk.setAttribute('data-leave-id', r.id);
                bOk.setAttribute('data-action', 'approve');
                var bNo = document.createElement('button');
                bNo.type = 'button';
                bNo.className = 'btn-reject';
                bNo.textContent = 'Reject';
                bNo.setAttribute('data-leave-id', r.id);
                bNo.setAttribute('data-action', 'reject');
                wrap.appendChild(bOk);
                wrap.appendChild(bNo);
                tdAct.appendChild(wrap);
            } else {
                tdAct.textContent = r.decidedAt ? 'Decided ' + fmtWhen(r.decidedAt) : '—';
                tdAct.style.color = 'var(--color-text-muted)';
                tdAct.style.fontSize = '0.75rem';
            }
            tr.appendChild(tdEmp);
            tr.appendChild(tdDur);
            tr.appendChild(tdType);
            tr.appendChild(tdDays);
            tr.appendChild(tdSt);
            tr.appendChild(tdAct);
            leaveTbody.appendChild(tr);
        });
    }

    function refreshQueues() {
        renderSignups();
        renderLeaves();
    }

    if (annForm) {
        annForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var title = document.getElementById('annTitle').value.trim();
            var body = document.getElementById('annBody').value.trim();
            var markNew = document.getElementById('annMarkNew').checked;
            if (!title || !body) {
                showFlash('Please enter both headline and message.', false);
                return;
            }
            EalmsStore.addAnnouncement({ title: title, body: body, markNew: markNew });
            annForm.reset();
            document.getElementById('annMarkNew').checked = true;
            showFlash('Announcement published.', true);
        });
    }

    if (celForm) {
        celForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var name = document.getElementById('celName').value.trim();
            var type = celType.value;
            var date = celDate.value;
            var years = celYears && celYears.value ? parseInt(celYears.value, 10) : null;
            if (!name || !date) {
                showFlash('Please enter name and event date.', false);
                return;
            }
            if (type === 'work' && (!years || years < 1)) {
                showFlash('Enter the number of years for a work anniversary.', false);
                return;
            }
            EalmsStore.addCelebration({
                personName: name,
                milestoneType: type,
                eventDate: date,
                years: type === 'work' ? years : null
            });
            celForm.reset();
            if (celDate) celDate.valueAsDate = new Date();
            syncMilestoneFields();
            showFlash('Milestone added.', true);
        });
    }

    document.body.addEventListener('click', function(e) {
        var t = e.target;
        if (!(t instanceof HTMLElement)) return;
        var sid = t.getAttribute('data-signup-id');
        if (sid && t.getAttribute('data-action')) {
            var action = t.getAttribute('data-action');
            EalmsStore.setSignupStatus(sid, action === 'approve' ? 'approved' : 'rejected');
            showFlash('Signup request updated.', true);
            refreshQueues();
            return;
        }
        var lid = t.getAttribute('data-leave-id');
        if (lid && t.getAttribute('data-action')) {
            var lact = t.getAttribute('data-action');
            var approved = lact === 'approve';
            var row = EalmsStore.setLeaveStatus(lid, approved ? 'approved' : 'rejected');
            if (row) {
                EalmsStore.addNotification({
                    title: 'Leave request ' + (approved ? 'approved' : 'rejected'),
                    body:
                        (EalmsStore.leaveLabels[row.leaveType] || row.leaveType) +
                        ' · ' +
                        EalmsStore.formatDuration(row.startDate, row.endDate),
                    kind: 'leave',
                    recipientId: row.employeeId,
                    link: 'leave-details.html?id=' + encodeURIComponent(row.id)
                });
            }
            showFlash('Leave request updated.', true);
            refreshQueues();
        }
    });

    refreshQueues();
})();
