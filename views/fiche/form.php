<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($fiche) ? 'Modifier' : 'Ajouter'; ?> une fiche</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .form-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px #0001;
            padding: 30px 35px;
            max-width: 480px;
            margin: 30px auto;
        }
        .form-group {
            margin-bottom: 22px;
        }
        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #336699;
        }
        .form-group input[type="text"], .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            background: #f3f8fc;
            font-size: 1em;
            transition: border 0.15s;
        }
        .form-group input[type="text"]:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: #3b7bbf;
            background: #fff;
            outline: none;
        }
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            justify-content: flex-end;
        }
        .btn-main {
            padding: 9px 22px;
            border-radius: 6px;
            font-size: 1em;
            border: none;
            cursor: pointer;
            background: #3b7bbf;
            color: #fff;
            transition: background 0.13s;
            text-decoration: none;
        }
        .btn-main:hover {
            background: #2563a6;
        }
        .btn-main.cancel {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-main.cancel:hover {
            background: #cbd5e1;
        }
        @media (max-width: 600px) {
            .form-card { padding: 16px 6vw; }
        }
    </style>
</head>
<body>
<div class="main-wrapper">
    <h1><?php echo isset($fiche) ? 'Modifier' : 'Ajouter'; ?> une fiche</h1>
    <?php if (!empty($success_message)) : ?>
        <div class="alert-success" style="background:#e6faea;color:#207a3c;padding:12px 18px;border-radius:7px;margin-bottom:20px;text-align:center;">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div class="alert-error" style="background:#fee2e2;color:#b91c1c;padding:12px 18px;border-radius:7px;margin-bottom:20px;text-align:center;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <div class="form-card">
        <?php if (!empty($errors['general'])) : ?>
            <div style="background:#fee2e2;color:#b91c1c;padding:10px 15px;border-radius:7px;margin-bottom:18px;text-align:center;">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>
        <form method="post" autocomplete="off" id="fiche-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
    <label for="libelle">Libellé</label>
    <input type="text" id="libelle" name="libelle" required value="<?php echo isset($fiche) ? htmlspecialchars($fiche['libelle']) : ''; ?>">
    <?php if (!empty($errors['libelle'])) : ?>
        <div style="color:#b91c1c;font-size:0.97em;margin-top:3px;">✖ <?php echo $errors['libelle']; ?></div>
    <?php endif; ?>
</div>
            <div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" required rows="4"><?php echo isset($fiche) ? htmlspecialchars($fiche['description']) : ''; ?></textarea>
    <?php if (!empty($errors['description'])) : ?>
        <div style="color:#b91c1c;font-size:0.97em;margin-top:3px;">✖ <?php echo $errors['description']; ?></div>
    <?php endif; ?>
</div>
            <div class="form-group">
    <label for="categories">Catégories <span style="font-weight:normal;color:#888;font-size:0.95em;">(Ctrl+clic pour multi-sélectionner)</span></label>
    <select id="categories" name="categories[]" multiple size="5" style="background:#f6faff;">
        <?php
        $ficheCatIds = isset($fiche_categories) ? array_column($fiche_categories, 'id') : [];
        foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if (in_array($cat['id'], $ficheCatIds)) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div style="font-size:0.93em;color:#6b7280;margin-top:4px;">Astuce : Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs catégories.</div>
    <?php if (!empty($errors['categories'])) : ?>
        <div style="color:#b91c1c;font-size:0.97em;margin-top:3px;">✖ <?php echo $errors['categories']; ?></div>
    <?php endif; ?>
</div>
            <div class="form-actions">
                <button type="submit" class="btn-main">Enregistrer</button>
                <a href="index.php?controller=fiche&action=index" class="btn-main cancel" id="cancel-btn">Annuler</a>
            </div>
        </form>
        <script>
            // Confirmation à l'enregistrement
            document.getElementById('fiche-form').addEventListener('submit', function(e) {
                if(!confirm('Voulez-vous vraiment enregistrer cette fiche ?')) {
                    e.preventDefault();
                }
            });
            // Confirmation à l'annulation
            document.getElementById('cancel-btn').addEventListener('click', function(e) {
                if(!confirm('Annuler la modification/ajout de la fiche ?')) {
                    e.preventDefault();
                }
            });
        </script>
    </div>
</div>
</body>
</html>
