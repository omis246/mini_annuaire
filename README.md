# Mini Annuaire PHP sécurisé

## Fonctionnalités principales

- Gestion complète des fiches et des catégories (CRUD) :
  - Catégories : Arborescence multi-niveaux avec ajout, modification, suppression, et affichage en organigramme (Treant.js).
  - Fiches : Création, modification, suppression, avec association à plusieurs catégories.
- Architecture MVC maison : Contrôleurs, modèles, et vues séparés, sans framework PHP externe.
- Connexion à la base de données via Singleton PDO (`config/Database.php`).

## Sécurité

- **Validation et sanitation centralisées** : Toutes les entrées utilisateur sont validées (`validateInt`) et nettoyées (`sanitizeString`) dans `utils/validation.php`.
- **Protection CSRF** : Implémentée sur les formulaires de suppression (catégories et fiches) et de création/modification des fiches. À ajouter pour la création/modification des catégories (`category/form.php`).
- **Gestion centralisée des erreurs** : Erreurs journalisées dans `logs/error.log` et messages génériques affichés à l'utilisateur (`utils/error.php`).
- **Protection XSS** : Toutes les sorties dans les vues sont échappées avec `htmlspecialchars`.
- **Headers HTTP de sécurité** : Définis dans `index.php` :
  - `X-Frame-Options: SAMEORIGIN`
  - `X-Content-Type-Options: nosniff`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: no-referrer-when-downgrade`
- **Sessions sécurisées** : Recommandation d'ajouter dans `index.php` (avant `session_start`) :
  ```php
  ini_set('session.cookie_httponly', 1);
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      ini_set('session.cookie_secure', 1);
  }
  ```
