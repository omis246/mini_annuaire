<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Catégories</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <h1>Catégories</h1>
    <div style="max-width: 900px; margin: 0 auto;">
        <a href="index.php?controller=category&action=create" class="btn-main">+ Ajouter une catégorie</a>
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
                    echo '<a href="index.php?controller=category&action=update&id=' . $cat['id'] . '" title="Modifier">✏️</a>';
                    echo '<a href="index.php?controller=category&action=delete&id=' . $cat['id'] . '" class="delete-category" title="Supprimer">🗑️</a>';
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
                    echo '<a href="index.php?controller=category&action=update&id=' . $cat['id'] . '" title="Modifier">✏️</a>';
                    echo '<a href="index.php?controller=category&action=delete&id=' . $cat['id'] . '" class="delete-category" title="Supprimer">🗑️</a>';
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
        <a href="index.php" class="btn-main" style="margin-top: 30px;">Retour accueil</a>
    </div>
    <script src="/js/dynamic-delete.js"></script>
</body>

</html>