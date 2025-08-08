<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($categorie) ? 'Modifier' : 'Ajouter'; ?> une catégorie</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="main-wrapper">
    <h1><?php echo isset($categorie) ? 'Modifier' : 'Ajouter'; ?> une catégorie</h1>
    <div class="form-card">
        <?php if (!empty($erreur)) : ?>
            <div class="error"><strong><?php echo htmlspecialchars($erreur); ?></strong></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <label for="libelle">Libellé</label>
                <input type="text" id="libelle" name="libelle" required value="<?php echo isset($categorie) ? htmlspecialchars($categorie['libelle']) : ''; ?>">
            </div>
            <?php if (isset($parent_path) && count($parent_path) > 0): ?>
                <div class="parent-path" style="margin-bottom:10px;color:#336699;font-size:0.98em;background:#f3f8fc;padding:8px 16px;border-radius:6px;">
                    <strong>Chemin actuel&nbsp;:</strong> <?php echo implode(' &gt; ', $parent_path); ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="id_parent">Catégorie parente</label>
                <select id="id_parent" name="id_parent">
                    <option value=""<?php if ((isset($categorie) && empty($categorie['id_parent'])) || (isset($_POST['id_parent']) && $_POST['id_parent'] === '')) echo ' selected'; ?>>Aucune</option>
                    <?php
function afficherOptions($categories, $niveau = 0, $id_courant = null) {
    foreach ($categories as $cat) {
        // Empêcher de choisir soi-même comme parent
        if ($id_courant && $cat['id'] == $id_courant) continue;
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $niveau);
        echo '<option value="' . $cat['id'] . '"';
        if ((isset($categorie) && (string)$categorie['id_parent'] === (string)$cat['id']) ||
            (isset($_POST['id_parent']) && (string)$_POST['id_parent'] === (string)$cat['id'])) echo ' selected';
        echo '>' . $indent . htmlspecialchars($cat['libelle']) . '</option>';
        if (!empty($cat['enfants'])) {
            afficherOptions($cat['enfants'], $niveau + 1, $id_courant);
        }
    }
}
?>
<?php afficherOptions($categories, 0, isset($categorie) ? $categorie['id'] : null); ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-main">Enregistrer</button>
                <a href="index.php?controller=category&action=index" class="btn-main" style="background:#e5e7eb;color:#374151;">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
