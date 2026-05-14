(function() {
    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function closePanels() {
        var panel = qs('#notificationPanel');
        var btn = qs('#notificationBtn');
        var menu = qs('#userMenuPanel');
        var menuBtn = qs('#userMenuTrigger');
        if (panel) {
            panel.hidden = true;
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }
        if (menu) {
            menu.hidden = true;
            if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
        }
    }

    function updateNotificationDot() {
        if (typeof EalmsStore === 'undefined') return;
        var dot = qs('#notificationDot');
        if (!dot) return;
        var n = EalmsStore.getUnreadNotificationCount();
        dot.hidden = n === 0;
    }

    function renderNotificationList() {
        var listEl = qs('#notificationList');
        if (!listEl || typeof EalmsStore === 'undefined') return;
        EalmsStore.init();
        var items = EalmsStore.getNotifications();
        listEl.innerHTML = '';
        if (!items.length) {
            var empty = document.createElement('p');
            empty.className = 'notification-empty';
            empty.textContent = 'No notifications yet.';
            listEl.appendChild(empty);
            return;
        }
        items.forEach(function(n) {
            var row = document.createElement('button');
            row.type = 'button';
            row.className = 'notification-item' + (n.read ? '' : ' is-unread');
            row.dataset.id = n.id;
            if (n.link) row.dataset.link = n.link;
            var t = document.createElement('p');
            t.className = 'notification-item-title';
            t.textContent = n.title;
            var b = document.createElement('p');
            b.className = 'notification-item-body';
            b.textContent = n.body || '';
            var time = document.createElement('span');
            time.className = 'notification-item-time';
            time.textContent = EalmsStore.relativeTime(n.createdAt);
            row.appendChild(t);
            row.appendChild(b);
            row.appendChild(time);
            listEl.appendChild(row);
        });
    }

    function applyProfileChrome() {
        if (typeof EalmsStore === 'undefined') return;
        var p = EalmsStore.getUserProfile();
        var h = qs('#dashboardWelcomeHeading');
        if (h && p.displayName) {
            var parts = p.displayName.trim().split(/\s+/).filter(Boolean);
            var first = parts.length ? parts[0] : 'there';
            h.textContent = 'Welcome back, ' + first;
        }
        var settingsName = qs('#settingsPageName');
        if (settingsName && p.displayName) {
            settingsName.textContent = p.displayName;
        }
        document.querySelectorAll('[data-user-avatar]').forEach(function(el) {
            el.textContent = EalmsStore.getInitials(p.displayName);
        });
    }

    function init() {
        if (typeof EalmsStore === 'undefined') return;

        var bell = qs('#notificationBtn');
        var panel = qs('#notificationPanel');
        var profileBtn = qs('#userMenuTrigger');
        var menu = qs('#userMenuPanel');

        if (bell && panel) {
            bell.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var willOpen = panel.hidden;
                closePanels();
                if (willOpen) {
                    panel.hidden = false;
                    bell.setAttribute('aria-expanded', 'true');
                    renderNotificationList();
                }
            });
            panel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        var markAll = qs('#markAllReadBtn');
        if (markAll) {
            markAll.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                EalmsStore.markAllNotificationsRead();
                renderNotificationList();
                updateNotificationDot();
            });
        }

        var listEl = qs('#notificationList');
        if (listEl) {
            listEl.addEventListener('click', function(e) {
                var row = e.target.closest('.notification-item');
                if (!row || !row.dataset.id) return;
                e.stopPropagation();
                EalmsStore.markNotificationRead(row.dataset.id);
                var link = row.dataset.link;
                if (link) {
                    window.location.href = link;
                    return;
                }
                renderNotificationList();
                updateNotificationDot();
            });
        }

        if (profileBtn && menu) {
            profileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var willOpen = menu.hidden;
                closePanels();
                if (willOpen) {
                    menu.hidden = false;
                    profileBtn.setAttribute('aria-expanded', 'true');
                }
            });
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        document.addEventListener('click', function() {
            closePanels();
        });

        window.addEventListener('storage', function() {
            updateNotificationDot();
            if (panel && !panel.hidden) renderNotificationList();
            applyProfileChrome();
        });

        applyProfileChrome();
        updateNotificationDot();
    }

    window.EalmsHeader = {
        applyProfileChrome: applyProfileChrome,
        updateNotificationDot: updateNotificationDot,
        renderNotificationList: renderNotificationList
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
