-- Index pour accélérer les requêtes sur les catégories et l'association fiche-catégorie
ALTER TABLE categories ADD INDEX idx_id_parent (id_parent);
ALTER TABLE fiche_categories ADD INDEX idx_fiche_id (fiche_id), ADD INDEX idx_categorie_id (categorie_id);
