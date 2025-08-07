<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($categorie) ? 'Modifier' : 'Ajouter'; ?> une catégorie</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1><?php echo isset($categorie) ? 'Modifier' : 'Ajouter'; ?> une catégorie</h1>
    <div class="form-card">
        <?php if (!empty($erreur)) : ?>
            <div class="error"><strong><?php echo htmlspecialchars($erreur); ?></strong></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="libelle">Libellé</label>
                <input type="text" id="libelle" name="libelle" required value="<?php echo isset($categorie) ? htmlspecialchars($categorie['libelle']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="id_parent">Catégorie parente</label>
                <select id="id_parent" name="id_parent">
                    <option value="">Aucune</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if (isset($categorie) && $categorie['id_parent'] == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['libelle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-main">Enregistrer</button>
                <a href="index.php?controller=category&action=index" class="btn-main" style="background:#e5e7eb;color:#374151;">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
