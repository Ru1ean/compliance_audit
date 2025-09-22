document.addEventListener('DOMContentLoaded', function() {
    var addBtn = document.getElementById('addRequirementBtn');
    var modal = document.getElementById('addRequirementModal');
    var closeBtn = document.getElementById('closeModalBtn');

    if (addBtn && modal && closeBtn) {
        addBtn.onclick = function() {
            modal.style.display = 'flex';
        };
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        };
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    }
});
// for requirments.php