<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Catégories</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/responsive-categories.css">
</head>

<body>
    <div class="main-wrapper">
        <?php if (!empty($success_message)) : ?>
            <div id="toast-success" class="toast-success">
                <?php echo $success_message; ?>
            </div>
            <script>
                setTimeout(function() {
                    var toast = document.getElementById('toast-success');
                    if (toast) {
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 600);
                    }
                }, 3200);
            </script>
        <?php endif; ?>
        <style>
            .toast-success {
                position: fixed;
                left: 50%;
                bottom: 36px;
                transform: translateX(-50%);
                background: #21a345;
                color: #fff;
                padding: 14px 32px;
                border-radius: 8px;
                box-shadow: 0 4px 24px #0002;
                font-size: 1.08em;
                z-index: 9999;
                min-width: 200px;
                text-align: center;
                opacity: 1;
                transition: opacity 0.6s;
            }
        </style>
        <h1>Catégories</h1>
        <div>
            <a href="index.php?controller=category&action=create" class="btn-main icon-btn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px;">
                    <circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="2" />
                    <path stroke="#fff" stroke-width="2" d="M12 8v8M8 12h8" />
                </svg>Ajouter une catégorie</a>
            <a href="index.php?controller=fiche&action=index" class="btn-main" style="background:#7bc87e;color:#222;">Gérer les fiches</a>
            <a href="index.php?controller=category&action=orgchart" class="btn-main" style="background:#3b7bbf;color:#fff;">Organigramme style schéma</a>
            <?php if (!empty($erreur_suppression)) : ?>
                <div class="error"><strong><?php echo htmlspecialchars($erreur_suppression); ?></strong></div>
            <?php endif; ?>
            <?php
            function afficherArbre($categories, $niveau = 0)
            {
                if (!$categories) return;
                echo $niveau === 0 ? '<div class="cat-list">' : '<ul>';
                foreach ($categories as $cat) {
                    if ($niveau === 0) {
                        echo '<div class="card cat-card cat-niveau-' . $niveau . '"><div class="cat-row">';
                        echo '<span class="cat-label">' . htmlspecialchars($cat['libelle']) . '</span>';
                        echo '<span class="cat-actions">';
                        echo '<a href="index.php?controller=category&action=update&id=' . $cat['id'] . '" title="Modifier" class="icon-btn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#256d3b" stroke-width="2" d="M5 20h14M7 17l9-9a2.121 2.121 0 1 0-3-3l-9 9v3h3z"/></svg></a>';
                        echo '<a href="index.php?controller=category&action=delete&id=' . $cat['id'] . '" class="delete-category icon-btn" title="Supprimer"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="5" y="7" width="14" height="12" rx="2" stroke="#c72b2b" stroke-width="2"/><path stroke="#c72b2b" stroke-width="2" d="M3 7h18M10 11v4M14 11v4M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg></a>';
                        echo '</span>';
                        echo '</div>';
                        if (!empty($cat['enfants'])) {
                            echo '<div class="cat-children">';
                            afficherArbre($cat['enfants'], $niveau + 1);
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<li class="cat-niveau-' . $niveau . '">';
                        echo '<span class="cat-label">' . htmlspecialchars($cat['libelle']) . '</span>';
                        echo '<span class="cat-actions">';
                        echo '<a href="index.php?controller=category&action=update&id=' . $cat['id'] . '" title="Modifier" class="icon-btn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#256d3b" stroke-width="2" d="M5 20h14M7 17l9-9a2.121 2.121 0 1 0-3-3l-9 9v3h3z"/></svg></a>';
                        echo '<a href="index.php?controller=category&action=delete&id=' . $cat['id'] . '" class="delete-category icon-btn" title="Supprimer"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="5" y="7" width="14" height="12" rx="2" stroke="#c72b2b" stroke-width="2"/><path stroke="#c72b2b" stroke-width="2" d="M3 7h18M10 11v4M14 11v4M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg></a>';
                        echo '</span>';
                        if (!empty($cat['enfants'])) {
                            afficherArbre($cat['enfants'], $niveau + 1);
                        }
                        echo '</li>';
                    }
                }
                echo $niveau === 0 ? '</div>' : '</ul>';
            }
            afficherArbre($categories);
            ?>
        </div>
        <script src="/js/dynamic-delete.js"></script>
    </div>
</body>

</html>