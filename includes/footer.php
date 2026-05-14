<?php // includes/footer.php ?>

<!-- CONFIRM DELETE MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>⚠️ Confirm Delete</h3>
        <p id="deleteModalMsg">Are you sure you want to delete this record? This action cannot be undone.</p>
        <div class="modal-actions">
            <a id="deleteConfirmBtn" href="#" class="btn btn-red">Yes, Delete</a>
            <button onclick="closeDeleteModal()" class="btn btn-gray">Cancel</button>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, name) {
    document.getElementById('deleteModalMsg').textContent = 'Are you sure you want to delete "' + name + '"? This cannot be undone.';
    document.getElementById('deleteConfirmBtn').href = url;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
</body>
</html>
