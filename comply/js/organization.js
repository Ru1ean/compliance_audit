document.querySelectorAll('.compliance-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var orgId = this.getAttribute('data-org');
        var reqId = this.getAttribute('data-req');
        var status = this.value;
        fetch('update_compliance.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'org_id=' + encodeURIComponent(orgId) + '&req_id=' + encodeURIComponent(reqId) + '&status=' + encodeURIComponent(status)
        })
        .then(res => res.text())
        .then(data => {
            // Optionally show a message or visual feedback
            // alert(data);
        });
    });
});

document.getElementById('add-org-btn').onclick = function() {
    document.getElementById('addOrgModal').style.display = 'flex';
};

document.querySelectorAll('.org-delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var orgId = this.getAttribute('data-org-id');
        var orgName = this.getAttribute('data-org-name');
        document.getElementById('deleteOrgName').textContent = orgName;
        document.getElementById('deleteOrgIdInput').value = orgId;
        document.getElementById('deleteOrgModal').style.display = 'flex';
    });
});
document.addEventListener('DOMContentLoaded', function() {
    var cancelBtn = document.getElementById('cancelDeleteOrgBtn');
    if (cancelBtn) {
        cancelBtn.onclick = function(e) {
            e.preventDefault();
            document.getElementById('deleteOrgModal').style.display = 'none';
        };
    }
});