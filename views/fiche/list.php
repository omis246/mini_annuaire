<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Fiches</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .fiche-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .fiche-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px #0001;
            overflow: hidden;
        }

        .fiche-table th,
        .fiche-table td {
            padding: 12px 18px;
            text-align: left;
        }

        .fiche-table th {
            background: #f3f8fc;
            color: #336699;
            font-size: 1.08em;
            border-bottom: 2px solid #e0e7ef;
        }

        .fiche-table tr {
            transition: background 0.18s;
        }

        .fiche-table tr:hover {
            background: #f6fbff;
        }

        .fiche-table td {
            border-bottom: 1px solid #f0f0f0;
        }

        .fiche-btn {
            display: inline-block;
            padding: 6px 14px;
            margin: 0 2px;
            border-radius: 6px;
            font-size: 0.97em;
            text-decoration: none;
            background: #eaf6eb;
            color: #256d3b;
            border: 1px solid #bfe2c2;
            transition: all 0.14s;
        }

        .fiche-btn:hover {
            background: #d2f5e3;
            color: #155a2c;
        }

        .fiche-btn.delete {
            background: #fbeaea;
            color: #c72b2b;
            border: 1px solid #f7bcbc;
        }

        .fiche-btn.delete:hover {
            background: #ffdede;
            color: #a30000;
        }

        @media (max-width: 800px) {

            .fiche-table,
            .fiche-table thead,
            .fiche-table tbody,
            .fiche-table th,
            .fiche-table td,
            .fiche-table tr {
                display: block;
            }

            .fiche-table tr {
                margin-bottom: 18px;
            }

            .fiche-table th {
                position: absolute;
                left: -9999px;
                top: -9999px;
            }

            .fiche-table td {
                border: none;
                position: relative;
                padding-left: 50%;
                min-height: 32px;
            }

            .fiche-table td:before {
                position: absolute;
                left: 16px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #336699;
            }

            .fiche-table td:nth-child(1):before {
                content: 'Libellé';
            }

            .fiche-table td:nth-child(2):before {
                content: 'Description';
            }

            .fiche-table td:nth-child(3):before {
                content: 'Catégories';
            }

            .fiche-table td:nth-child(4):before {
                content: 'Actions';
            }
        }
    </style>
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
        <div class="fiche-header">
            <h1>Fiches</h1>
            <a href="index.php?controller=fiche&action=create" class="btn-main fiche-btn icon-btn" style="background:#3b7bbf;color:#fff;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px;">
                    <circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="2" />
                    <path stroke="#fff" stroke-width="2" d="M12 8v8M8 12h8" />
                </svg>Ajouter une fiche</a>
        </div>
        <table class="fiche-table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Description</th>
                    <th>Catégories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fiches as $fiche): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fiche['libelle']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($fiche['description'])); ?></td>
                        <td>
                            <?php
                            $ficheObj = new Fiche();
                            $cats = $ficheObj->getCategories($fiche['id']);
                            $catLabels = array_map(function ($c) {
                                return htmlspecialchars($c['libelle']);
                            }, $cats);
                            echo implode(', ', $catLabels);
                            ?>
                        </td>
                        <td>
                            <a href="index.php?controller=fiche&action=update&id=<?php echo $fiche['id']; ?>" class="fiche-btn icon-btn" title="Modifier"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" style="vertical-align:middle;">
                                    <path stroke="#256d3b" stroke-width="2" d="M5 20h14M7 17l9-9a2.121 2.121 0 1 0-3-3l-9 9v3h3z" />
                                </svg></a>
                            <form method="POST" action="index.php?controller=fiche&action=delete" style="display:inline;">
    <input type="hidden" name="id" value="<?php echo $fiche['id']; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
    <button type="submit" class="fiche-btn delete delete-fiche icon-btn" title="Supprimer" onclick="return confirm('Confirmer la suppression de cette fiche ?');" style="border:none;background:none;padding:0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" style="vertical-align:middle;">
            <rect x="5" y="7" width="14" height="12" rx="2" stroke="#c72b2b" stroke-width="2" />
            <path stroke="#c72b2b" stroke-width="2" d="M3 7h18M10 11v4M14 11v4M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
        </svg>
    </button>
</form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn-main icon-btn" style="margin-top: 30px;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px;">
                <path stroke="#256d3b" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18" />
            </svg>Retour accueil</a>
        <script src="/js/dynamic-delete.js"></script>
    </div>
</body>

</html>