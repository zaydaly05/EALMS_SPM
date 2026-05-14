(function() {
    var profileForm = document.getElementById('profileForm');
    var notifForm = document.getElementById('notifForm');
    var toast = document.getElementById('settingsToast');

    function showToast(msg) {
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.add('is-visible');
        clearTimeout(showToast._t);
        showToast._t = setTimeout(function() {
            toast.classList.remove('is-visible');
        }, 2400);
    }

    function fillProfile() {
        var p = EalmsStore.getUserProfile();
        var el = function(id) {
            return document.getElementById(id);
        };
        if (el('fieldDisplayName')) el('fieldDisplayName').value = p.displayName || '';
        if (el('fieldEmail')) el('fieldEmail').value = p.email || '';
        if (el('fieldDepartment')) el('fieldDepartment').value = p.department || '';
        if (el('fieldJobTitle')) el('fieldJobTitle').value = p.jobTitle || '';
    }

    function fillNotif() {
        var s = EalmsStore.getUserSettings();
        var a = document.getElementById('prefAnnouncements');
        var b = document.getElementById('prefLeave');
        var c = document.getElementById('prefDigest');
        if (a) a.checked = !!s.notifyAnnouncements;
        if (b) b.checked = !!s.notifyLeaveEmail;
        if (c) c.checked = !!s.notifyDigest;
    }

    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            EalmsStore.saveUserProfile({
                displayName: document.getElementById('fieldDisplayName').value,
                email: document.getElementById('fieldEmail').value,
                department: document.getElementById('fieldDepartment').value,
                jobTitle: document.getElementById('fieldJobTitle').value
            });
            if (window.EalmsHeader) EalmsHeader.applyProfileChrome();
            showToast('Profile saved.');
        });
    }

    if (notifForm) {
        notifForm.addEventListener('submit', function(e) {
            e.preventDefault();
            EalmsStore.saveUserSettings({
                notifyAnnouncements: document.getElementById('prefAnnouncements').checked,
                notifyLeaveEmail: document.getElementById('prefLeave').checked,
                notifyDigest: document.getElementById('prefDigest').checked
            });
            showToast('Notification preferences saved.');
        });
    }

    fillProfile();
    fillNotif();
    if (window.EalmsHeader) EalmsHeader.applyProfileChrome();
})();
