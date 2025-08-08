<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Organigramme des Catégories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.css" />
    <style>
        .Treant { margin-top: 40px; }
        .node { background: #3b7bbf; color: #fff; border-radius: 8px; padding: 8px 20px; font-weight: bold; font-family: Arial, sans-serif; box-shadow: 1px 1px 4px #bbb; }
        .node-example { background: #3b7bbf; }
        .Treant .node { border: none; }
    </style>
</head>
<body>
<div class="main-wrapper">
    <h1 style="font-size:2em;font-weight:bold;color:#223366;margin-bottom:18px;">Organigramme des Catégories</h1>
    <a href="index.php?controller=category&action=index" class="btn-main icon-btn" style="margin-bottom:18px;background:#f3f8fc;color:#256d3b;border:1px solid #bfe2c2;box-shadow:0 2px 8px #0001;font-weight:500;display:inline-flex;align-items:center;gap:7px;padding:10px 22px;transition:background 0.16s;">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" style="vertical-align:middle;"><path stroke="#256d3b" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/></svg>
    Retour à la liste
</a>
<style>
.btn-main.icon-btn:hover {
    background:#eaf6eb;
    color:#155a2c;
    box-shadow:0 4px 14px #256d3b22;
    border-color:#7bc87e;
}
</style>
    <form id="root-select-form" style="margin: 20px 0 30px 0;display:flex;align-items:center;gap:14px;background:#f3f8fc;padding:14px 22px;border-radius:8px;box-shadow:0 2px 8px #0001;">
        <label for="root-select" style="font-weight:500;color:#336699;">Choisir une branche racine :</label>
        <select id="root-select" style="padding:7px 18px;border-radius:6px;border:1px solid #bfe2c2;background:#fff;color:#256d3b;font-size:1em;">
            <option value="all">Toutes</option>
            <?php foreach ($rootCategories as $root) : ?>
                <option value="<?php echo $root['id']; ?>">[<?php echo $root['id']; ?>] <?php echo htmlspecialchars($root['libelle']); ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div id="tree-simple" style="width:100%; min-height:600px; background:#f8fbff; border-radius:14px; box-shadow:0 2px 18px #3b7bbf11; padding:24px 12px 20px 12px; margin-top:22px;"></div>
<p style="color:#666;font-size:0.98em;margin-top:8px;">Astuce : sélectionnez une racine pour n'afficher qu'une branche de l'organigramme.</p>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.min.js"></script>
<script>
// Générer dynamiquement la structure de l'arbre PHP -> JS
var treeData = <?php echo json_encode($treeData); ?>;
var rootSelect = document.getElementById('root-select');

function renderChart(filteredData) {
    document.getElementById('tree-simple').innerHTML = '';
    if(Array.isArray(filteredData) && filteredData.length > 1) {
        var chart_config = {
            chart: {
                container: "#tree-simple",
                connectors: { type: 'step' },
                node: { HTMLclass: 'node-example' }
            },
            nodeStructure: {
                text: { name: 'Catégories' },
                children: filteredData
            }
        };
        new Treant(chart_config);
    } else {
        var chart_config = {
            chart: {
                container: "#tree-simple",
                connectors: { type: 'step' },
                node: { HTMLclass: 'node-example' }
            },
            nodeStructure: filteredData[0] || {text:{name:'Aucune catégorie'}}
        };
        new Treant(chart_config);
    }
}

renderChart(treeData);

function findNodeById(nodes, id) {
    for (var i = 0; i < nodes.length; i++) {
        if (nodes[i].id == id) return nodes[i];
        if (nodes[i].children) {
            var found = findNodeById(nodes[i].children, id);
            if (found) return found;
        }
    }
    return null;
}

rootSelect.addEventListener('change', function() {
    var val = this.value;
    if(val === 'all') {
        renderChart(treeData);
    } else {
        var node = findNodeById(treeData, val);
        if(node) {
            renderChart([node]);
        } else {
            document.getElementById('tree-simple').innerHTML = '<div style="color:red;margin:30px;">Aucune donnée pour cette racine</div>';
            console.log('Aucune donnée trouvée pour la racine sélectionnée', val, treeData);
        }
    }
});

// DEBUG : log treeData au chargement
console.log('treeData:', treeData);
</script>
</body>
</html>
