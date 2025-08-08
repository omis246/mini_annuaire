# Mini Annuaire PHP sécurisé

## Fonctionnalités principales

- Gestion des fiches et des catégories (CRUD)
- Architecture MVC maison (contrôleurs, modèles, vues séparés)
- Connexion BDD via Singleton PDO

## Sécurité

- **Validation et sanitation** centralisées sur toutes les entrées utilisateur
- **Protection CSRF** sur tous les formulaires critiques (création, modification, suppression)
- **Gestion centralisée des erreurs** (log dans `logs/error.log` + message utilisateur propre)
- **Protection XSS** : toutes les sorties sont échappées dans les vues
- **Headers HTTP de sécurité** ajoutés dans `index.php`
- **Sessions sécurisées** (voir plus bas)

## Performance

- **Index SQL** sur les colonnes critiques (`docs/indexes_performance.sql`)
- Structure légère, requêtes optimisées

## Sécurisation avancée des sessions

Ajoutez dans `index.php` (avant `session_start()` si possible) :

```php
ini_set('session.cookie_httponly', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
```

Pour renforcer la sécurité, appelez `session_regenerate_id(true);` après chaque connexion ou action sensible.

## Checklist sécurité-livraison

- [x] Validation/sanitation centralisée
- [x] CSRF sur tous les formulaires critiques (create, update, delete)
- [x] Gestion centralisée des erreurs
- [x] Headers HTTP de sécurité
- [x] Index SQL sur colonnes critiques
- [x] Sessions sécurisées (bonus)
