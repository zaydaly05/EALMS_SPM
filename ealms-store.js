/**
 * EALMS shared data (localStorage). Demo persistence until a backend exists.
 */
(function(global) {
    var K = {
        announcements: 'ealms_announcements_v1',
        celebrations: 'ealms_celebrations_v1',
        signups: 'ealms_signups_v1',
        leaveRequests: 'ealms_leave_requests_v1',
        userProfile: 'ealms_user_profile_v1',
        userSettings: 'ealms_user_settings_v1',
        notifications: 'ealms_notifications_v1'
    };

    var DEMO_EMPLOYEE_ID = 'emp-demo';
    var DEMO_EMPLOYEE_NAME = 'Zayd Ali';

    function uid() {
        return 'e_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 9);
    }

    function read(key, fallback) {
        try {
            var raw = localStorage.getItem(key);
            if (!raw) return fallback;
            var data = JSON.parse(raw);
            return data;
        } catch (e) {
            return fallback;
        }
    }

    function write(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (e) {}
    }

    function seedAnnouncements() {
        var t = Date.now() - 3600000;
        return [
            {
                id: uid(),
                title: 'Internship certificates',
                body: 'Requests should be sent to hr@university.edu with the faculty supervisor copied.',
                createdAt: t,
                isNew: true
            },
            {
                id: uid(),
                title: 'Time tracking',
                body: 'Please clock in at arrival and clock out at departure so attendance records stay accurate.',
                createdAt: t - 7200000,
                isNew: false
            }
        ];
    }

    function seedCelebrations() {
        return [
            { id: uid(), personName: 'Zayd Ali', milestoneType: 'birthday', eventDate: '2026-09-08', years: null },
            { id: uid(), personName: 'Amira Ahmed', milestoneType: 'work', eventDate: '2026-04-20', years: 2 },
            { id: uid(), personName: 'Zayed Hazem', milestoneType: 'recognition', eventDate: '2026-04-22', years: null },
            { id: uid(), personName: 'Ali Omar', milestoneType: 'birthday', eventDate: '2026-04-22', years: null }
        ];
    }

    function seedSignups() {
        return [
            {
                id: uid(),
                fullName: 'Sara Malik',
                email: 'sara.malik@organization.com',
                requestedAt: Date.now() - 86400000 * 2,
                status: 'pending'
            },
            {
                id: uid(),
                fullName: 'Omar Farouk',
                email: 'omar.farouk@organization.com',
                requestedAt: Date.now() - 86400000,
                status: 'pending'
            }
        ];
    }

    function seedUserProfile() {
        return {
            displayName: DEMO_EMPLOYEE_NAME,
            email: 'zayd@organization.com',
            department: 'Engineering',
            jobTitle: 'Analyst'
        };
    }

    function seedUserSettings() {
        return {
            notifyAnnouncements: true,
            notifyLeaveEmail: true,
            notifyDigest: false
        };
    }

    function seedNotifications() {
        var now = Date.now();
        return [
            {
                id: uid(),
                title: 'Welcome to EALMS',
                body: 'Use the dashboard to mark attendance, request leave, and stay up to date with announcements.',
                kind: 'system',
                createdAt: now - 86400000 * 4,
                read: true,
                recipientId: DEMO_EMPLOYEE_ID,
                link: ''
            },
            {
                id: uid(),
                title: 'Leave request pending',
                body: 'Your approver will review your sick leave request when they are available.',
                kind: 'leave',
                createdAt: now - 86400000 * 2,
                read: false,
                recipientId: DEMO_EMPLOYEE_ID,
                link: 'dashboard.html'
            },
            {
                id: uid(),
                title: 'New announcement',
                body: 'HR posted an update about internship certificates — check the announcements card.',
                kind: 'announcement',
                createdAt: now - 3600000,
                read: false,
                recipientId: DEMO_EMPLOYEE_ID,
                link: 'dashboard.html'
            }
        ];
    }

    function seedLeave() {
        return [
            {
                id: uid(),
                employeeId: DEMO_EMPLOYEE_ID,
                employeeName: DEMO_EMPLOYEE_NAME,
                leaveType: 'sick',
                startDate: '2026-04-20',
                endDate: '2026-04-21',
                reason: '',
                status: 'pending',
                submittedAt: Date.now() - 86400000 * 3
            },
            {
                id: uid(),
                employeeId: DEMO_EMPLOYEE_ID,
                employeeName: DEMO_EMPLOYEE_NAME,
                leaveType: 'casual',
                startDate: '2026-06-10',
                endDate: '2026-06-10',
                reason: '',
                status: 'approved',
                submittedAt: Date.now() - 86400000 * 10
            }
        ];
    }

    function ensureSeed() {
        if (!read(K.announcements, null)) write(K.announcements, seedAnnouncements());
        if (!read(K.celebrations, null)) write(K.celebrations, seedCelebrations());
        if (!read(K.signups, null)) write(K.signups, seedSignups());
        if (!read(K.leaveRequests, null)) write(K.leaveRequests, seedLeave());
        if (!read(K.userProfile, null)) write(K.userProfile, seedUserProfile());
        if (!read(K.userSettings, null)) write(K.userSettings, seedUserSettings());
        if (!read(K.notifications, null)) write(K.notifications, seedNotifications());
    }

    function initialsFromName(displayName) {
        var parts = (displayName || '').trim().split(/\s+/).filter(Boolean);
        if (!parts.length) return '—';
        if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    }

    function pushNotification(opts) {
        var list = read(K.notifications, []);
        list.unshift({
            id: uid(),
            title: (opts.title || '').trim(),
            body: (opts.body || '').trim(),
            kind: opts.kind || 'system',
            createdAt: Date.now(),
            read: !!opts.read,
            recipientId: opts.recipientId != null ? opts.recipientId : DEMO_EMPLOYEE_ID,
            link: (opts.link || '').trim()
        });
        write(K.notifications, list);
        return list[0];
    }

    var leaveLabels = {
        casual: 'Casual leave',
        sick: 'Sick leave',
        earned: 'Earned leave',
        unpaid: 'Unpaid leave',
        half: 'Half-day'
    };

    function formatShortDate(iso) {
        var d = new Date(iso + 'T12:00:00');
        return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
    }

    function formatDuration(startDate, endDate) {
        if (startDate === endDate) return formatShortDate(startDate);
        return formatShortDate(startDate) + ' – ' + formatShortDate(endDate);
    }

    function countLeaveDays(startDate, endDate) {
        var a = new Date(startDate + 'T12:00:00');
        var b = new Date(endDate + 'T12:00:00');
        var diff = Math.round((b - a) / 86400000) + 1;
        return Math.max(1, diff);
    }

    function relativeTime(ts) {
        var s = Math.floor((Date.now() - ts) / 1000);
        if (s < 60) return 'Just now';
        if (s < 3600) return Math.floor(s / 60) + ' min ago';
        if (s < 86400) return Math.floor(s / 3600) + ' hr ago';
        return new Date(ts).toLocaleDateString(undefined, { month: 'short', day: 'numeric' }) + ' · ' + new Date(ts).toLocaleTimeString(undefined, { hour: 'numeric', minute: '2-digit' });
    }

    var milestoneLabels = {
        birthday: { tag: 'Birthday', className: 'tag--birthday' },
        work: { tag: 'Work anniversary', className: 'tag--work' },
        recognition: { tag: 'Recognition', className: 'tag--recognition' }
    };

    ensureSeed();

    global.EalmsStore = {
        DEMO_EMPLOYEE_ID: DEMO_EMPLOYEE_ID,
        DEMO_EMPLOYEE_NAME: DEMO_EMPLOYEE_NAME,
        leaveLabels: leaveLabels,
        milestoneLabels: milestoneLabels,

        init: ensureSeed,

        getAnnouncements: function() {
            return read(K.announcements, []);
        },

        addAnnouncement: function(opts) {
            var list = this.getAnnouncements();
            list.unshift({
                id: uid(),
                title: (opts.title || '').trim(),
                body: (opts.body || '').trim(),
                createdAt: Date.now(),
                isNew: !!opts.markNew
            });
            write(K.announcements, list);
            var a = list[0];
            var snippet = a.body.length > 100 ? a.body.slice(0, 100) + '…' : a.body;
            pushNotification({
                title: 'New announcement',
                body: a.title + (snippet ? ' — ' + snippet : ''),
                kind: 'announcement',
                recipientId: DEMO_EMPLOYEE_ID,
                link: 'dashboard.html'
            });
            return a;
        },

        getCelebrations: function() {
            return read(K.celebrations, []);
        },

        addCelebration: function(opts) {
            var list = this.getCelebrations();
            list.push({
                id: uid(),
                personName: (opts.personName || '').trim(),
                milestoneType: opts.milestoneType || 'birthday',
                eventDate: opts.eventDate,
                years: opts.years != null && opts.years !== '' ? Number(opts.years) : null
            });
            write(K.celebrations, list);
            return list[list.length - 1];
        },

        getSignupRequests: function() {
            return read(K.signups, []);
        },

        addSignupRequest: function(opts) {
            var list = this.getSignupRequests();
            list.push({
                id: uid(),
                fullName: (opts.fullName || '').trim(),
                email: (opts.email || '').trim().toLowerCase(),
                requestedAt: Date.now(),
                status: 'pending'
            });
            write(K.signups, list);
            return list[list.length - 1];
        },

        setSignupStatus: function(id, status) {
            var list = this.getSignupRequests();
            var i = list.findIndex(function(x) {
                return x.id === id;
            });
            if (i === -1) return false;
            list[i].status = status;
            list[i].decidedAt = Date.now();
            write(K.signups, list);
            return true;
        },

        getLeaveRequests: function(filter) {
            var list = read(K.leaveRequests, []);
            if (!filter) return list.slice();
            return list.filter(function(r) {
                if (filter.employeeId && r.employeeId !== filter.employeeId) return false;
                if (filter.status && r.status !== filter.status) return false;
                return true;
            });
        },

        addLeaveRequest: function(opts) {
            var list = read(K.leaveRequests, []);
            var row = {
                id: uid(),
                employeeId: opts.employeeId || DEMO_EMPLOYEE_ID,
                employeeName: opts.employeeName || DEMO_EMPLOYEE_NAME,
                leaveType: opts.leaveType,
                startDate: opts.startDate,
                endDate: opts.endDate,
                reason: (opts.reason || '').trim(),
                status: 'pending',
                submittedAt: Date.now()
            };
            list.unshift(row);
            write(K.leaveRequests, list);
            return row;
        },

        setLeaveStatus: function(id, status) {
            var list = read(K.leaveRequests, []);
            var i = list.findIndex(function(x) {
                return x.id === id;
            });
            if (i === -1) return null;
            list[i].status = status;
            list[i].decidedAt = Date.now();
            write(K.leaveRequests, list);
            return list[i];
        },

        getUserProfile: function() {
            ensureSeed();
            return read(K.userProfile, seedUserProfile());
        },

        saveUserProfile: function(partial) {
            ensureSeed();
            var cur = read(K.userProfile, seedUserProfile());
            var next = Object.assign({}, cur, partial || {});
            if (next.displayName) next.displayName = String(next.displayName).trim();
            if (next.email) next.email = String(next.email).trim().toLowerCase();
            if (next.department != null) next.department = String(next.department).trim();
            if (next.jobTitle != null) next.jobTitle = String(next.jobTitle).trim();
            write(K.userProfile, next);
            return next;
        },

        getUserSettings: function() {
            ensureSeed();
            return read(K.userSettings, seedUserSettings());
        },

        saveUserSettings: function(partial) {
            ensureSeed();
            var cur = read(K.userSettings, seedUserSettings());
            var next = Object.assign({}, cur, partial || {});
            write(K.userSettings, next);
            return next;
        },

        getInitials: function(displayName) {
            var p = displayName;
            if (p == null || p === '') p = this.getUserProfile().displayName;
            return initialsFromName(p);
        },

        getNotifications: function() {
            ensureSeed();
            var list = read(K.notifications, []);
            return list
                .filter(function(n) {
                    return !n.recipientId || n.recipientId === DEMO_EMPLOYEE_ID;
                })
                .slice()
                .sort(function(a, b) {
                    return b.createdAt - a.createdAt;
                });
        },

        addNotification: function(opts) {
            ensureSeed();
            return pushNotification(opts);
        },

        markNotificationRead: function(id) {
            var list = read(K.notifications, []);
            var i = list.findIndex(function(x) {
                return x.id === id;
            });
            if (i === -1) return false;
            list[i].read = true;
            write(K.notifications, list);
            return true;
        },

        markAllNotificationsRead: function() {
            var list = read(K.notifications, []);
            list.forEach(function(n) {
                if (!n.recipientId || n.recipientId === DEMO_EMPLOYEE_ID) n.read = true;
            });
            write(K.notifications, list);
        },

        getUnreadNotificationCount: function() {
            return this.getNotifications().filter(function(n) {
                return !n.read;
            }).length;
        },

        formatDuration: formatDuration,
        countLeaveDays: countLeaveDays,
        relativeTime: relativeTime
    };
})(typeof window !== 'undefined' ? window : this);
