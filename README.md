`composer install` pour télécharger les dépendances

Se rendre sur http://localhost/<nom-du-projet>/ pour accéder à l'interface

On retrouve 5 parties, 1 pour chaque élément du CRUD (2 pour la lecture)

Pour la suppression, on passe par défaut l'ID dans l'URL. On peut modifier cela en allant à la ligne 70 d'APIFetch.js

En allant sur http://localhost/<nom-du-projet>/dist on retrouve la documentation

Si on veut se servir de cURL, il faut d'abord modifier l'URL dans chaque fichier du dossier curl
