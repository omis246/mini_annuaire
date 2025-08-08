// JavaScript pour suppression dynamique AJAX avec feedback et animation

document.addEventListener('DOMContentLoaded', function () {
    // Pour les boutons de suppression de fiche
    document.querySelectorAll('.delete-fiche').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Supprimer ?')) return;

            const row = btn.closest('tr');
            fetch(btn.href, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        row.style.backgroundColor = '#f8d7da';
                        row.style.transition = 'opacity 0.5s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 500);
                        showToast('Fiche supprimée avec succès');
                    } else {
                        showToast(data.error || 'Erreur lors de la suppression', true);
                    }
                })
                .catch(() => showToast('Erreur réseau', true));
        });
    });
    // Pour les liens de suppression de catégorie
    document.querySelectorAll('.delete-category').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Supprimer ?')) return;
            const container = btn.closest('li') || btn.closest('.cat-card');
            fetch(btn.href, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (container) {
                            container.style.backgroundColor = '#f8d7da';
                            container.style.transition = 'opacity 0.5s';
                            container.style.opacity = '0';
                            setTimeout(() => container.remove(), 500);
                        }
                        showToast('Catégorie supprimée avec succès');
                    } else {
                        showToast(data.error || 'Erreur lors de la suppression', true);
                    }
                })
                .catch(() => showToast('Erreur réseau', true));
        });
    });

    // Toast notification
    function showToast(msg, error) {
        let toast = document.createElement('div');
        toast.textContent = msg;
        toast.style.position = 'fixed';
        toast.style.bottom = '30px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.background = error ? '#dc3545' : '#28a745';
        toast.style.color = '#fff';
        toast.style.padding = '10px 20px';
        toast.style.borderRadius = '6px';
        toast.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
        toast.style.zIndex = 9999;
        toast.style.fontSize = '1.1em';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
});
