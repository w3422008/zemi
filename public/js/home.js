function openModal() {
    document.getElementById('problemModal').style.display = 'flex';
}

function closeModal(event) {
    if (!event || event.target === document.getElementById('problemModal') || event.target.classList.contains('modal-close')) {
        document.getElementById('problemModal').style.display = 'none';
    }
}

// ESCキーでモーダルを閉じる
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
