<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiches</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1>Fiches</h1>
    <a href="index.php?controller=fiche&action=create">Ajouter une fiche</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Libellé</th>
            <th>Description</th>
            <th>Catégories</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($fiches as $fiche): ?>
            <tr>
                <td><?php echo htmlspecialchars($fiche['libelle']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($fiche['description'])); ?></td>
                <td>
                    <?php
                    $ficheObj = new Fiche();
                    $cats = $ficheObj->getCategories($fiche['id']);
                    $catLabels = array_map(function($c) { return htmlspecialchars($c['libelle']); }, $cats);
                    echo implode(', ', $catLabels);
                    ?>
                </td>
                <td>
                    <a href="index.php?controller=fiche&action=update&id=<?php echo $fiche['id']; ?>">Modifier</a>
                    <a href="index.php?controller=fiche&action=delete&id=<?php echo $fiche['id']; ?>" class="delete-fiche">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="index.php">Retour accueil</a>
<script src="/js/dynamic-delete.js"></script>
</body>
</html>
