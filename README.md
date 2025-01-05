# Annuaire - Application Web

## 📌 Lien du Dépôt Git

Le code source de l'application est disponible à l'adresse suivante :  
[**Lien vers le dépôt Git**](https://gitlabinfo.iutmontp.univ-montp2.fr/capom/annuaire)

---

## 🚀 Fonctionnement de l'Annuaire

### Comment utiliser l'application ?

1. **Accès à l'interface** :  
   Une fois le serveur lancé, vous pouvez accéder à l'application via votre navigateur en entrant l'adresse suivante :
   ```
   http://localhost/annuaire/public/
   ```
2. **Base de données** :
   Une base de données a été créée et configurée pour stocker les informations des utilisateurs. Le schéma des tables est disponible dans les fichiers de migration.

3. **Les routes disponibles** :

   - `GET /` :  
     Affiche la liste des utilisateurs disponibles dans l'annuaire. La pagination est gérée via le paramètre de requête `page`.
     Exemple d'URL :
     ```
     http://localhost/annuaire/public/
     ```
     
   - `GET /signUp` et `POST /signUp` :  
     Permet de créer un nouvel utilisateur via un formulaire d'inscription.

   - `GET /signIn` et `POST /signIn` :  
     Permet à un utilisateur de se connecter à l'application.

   - `GET /update/{code}` et `POST /update/{code}` :  
     Permet à un utilisateur de mettre à jour son profil via son code unique.
     Exemple d'URL :
     ```
     http://localhost/annuaire/public/update/xvzeg2fezuj15a
     ```
     
   - `GET /profil/{code}` :  
     Affiche le profil personnel d'un utilisateur en fonction de son code unique.
     Exemple d'URL :
     ```
     http://localhost/annuaire/public/profil/xvzeg2fezuj15a
     ```

   - `GET /delete/{code}` :  
     Supprime un utilisateur identifié par son code unique. Un utilisateur ne peut supprimer que son propre profil, sauf s'il dispose des permissions d'administration.
     Exemple d'URL :
     ```
     http://localhost/annuaire/public/delete/xvzeg2fezuj15a
     ```

   - `POST /api/checkCode` :
     Vérifie si un code donné est déjà utilisé par un autre utilisateur. Retourne un conflit si le code est trouvé pour un autre utilisateur connecté.
   
   - `GET /api/userJSON` :
     Récupère les informations d'un utilisateur au format JSON en fonction de son code unique.

---

## 🛠️ Instructions pour créer un nouvel utilisateur

Pour créer un nouvel utilisateur via la ligne de commande, utilisez la commande suivante :

```bash
php bin/console app:create-user [login] [password] [email] [visibility] [options]
```

### Arguments requis :

- `login` : **Login de l'utilisateur** (obligatoire)
- `password` : **Mot de passe de l'utilisateur** (obligatoire)
- `email` : **Email de l'utilisateur** (obligatoire)

### Arguments optionnels :

- `visibility` : **Visibilité de l'utilisateur** (par défaut : true)
- `code` : **Code unique de l'utilisateur** (optionnel)
- `--country` : **Pays de l'utilisateur** (optionnel)
- `--department` : **Département de l'utilisateur** (optionnel)
- `--function` : **Métier de l'utilisateur** (optionnel)
- `--postal_adresse` : **Adresse postale de l'utilisateur** (optionnel)
- `--last_name` : **Nom de l'utilisateur** (optionnel)
- `--first_name` : **Prénom de l'utilisateur** (optionnel)
- `--roles` : **Rôles de l'utilisateur** (optionnel)

### Exemples d'utilisation :

1. **Créer un utilisateur en mode itératif :**
    ```bash
   php bin/console app:create-user
   ```
2. **Créer un utilisateur avec les arguments requis :**
   ```bash
   php bin/console app:create-user john_doe secretPassword0 john@example.com
   ```

3. **Créer un utilisateur en spécifiant des options :**
   ```bash
   php bin/console app:create-user john_doe secretPassword0 john@example.com true xvzeg2fezuj15a --country France --department IT --function Developer --postal_adresse 75001 --last_name John --first_name Doe --roles ROLE_ADMIN
   ```

### Interaction avec l'utilisateur :

Lors de l'exécution de la commande, des invites seront affichées pour saisir les valeurs manquantes, comme le mot de passe, le code, le pays, etc. Le système vérifie également si le code est déjà pris.

---

## 👥 Récapitulatif de l'investissement des membres du groupe

### Contribution de chaque membre :

1. **Maylin Enzo** :
    - Développement du back-end.
    - Développement du front-end.
    - Rédaction du fichier README et gestion du dépôt Git.
    - Gestion des migrations et de la configuration de la base de données.

2. **Capo Mathys** :
    - Développement du back-end.
    - Développement du front-end.
    - Rédaction du fichier README et gestion du dépôt Git.
    - Gestion des migrations et de la configuration de la base de données.

3. **Bavoillot Léanne** :
   - Développement du back-end.
   - Développement du front-end.
   - Rédaction du fichier README et gestion du dépôt Git.
   - Gestion des migrations et de la configuration de la base de données.

---

## 💡 Remarques supplémentaires

- Assurez-vous de lancer Annuaire dans un docker.
---

**Merci d'utiliser notre super Annuaire !**

---