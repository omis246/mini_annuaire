<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($fiche) ? 'Modifier' : 'Ajouter'; ?> une fiche</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1><?php echo isset($fiche) ? 'Modifier' : 'Ajouter'; ?> une fiche</h1>
    <div class="form-card">
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="libelle">Libellé</label>
                <input type="text" id="libelle" name="libelle" required value="<?php echo isset($fiche) ? htmlspecialchars($fiche['libelle']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required rows="4"><?php echo isset($fiche) ? htmlspecialchars($fiche['description']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="categories">Catégories</label>
                <select id="categories" name="categories[]" multiple size="5">
                    <?php
                    $ficheCatIds = isset($fiche_categories) ? array_column($fiche_categories, 'id') : [];
                    foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if (in_array($cat['id'], $ficheCatIds)) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['libelle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-main">Enregistrer</button>
                <a href="index.php?controller=fiche&action=index" class="btn-main" style="background:#e5e7eb;color:#374151;">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
