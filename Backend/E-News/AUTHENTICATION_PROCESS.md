# Guide complet : Processus d'Authentification E-News

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Configuration requise](#configuration-requise)
3. [Processus d'inscription (Register)](#processus-dinscription-register)
4. [Vérification d'email](#vérification-demail)
5. [Processus de connexion (Login)](#processus-de-connexion-login)
6. [Mot de passe oublié (Forgot Password)](#mot-de-passe-oublié-forgot-password)
7. [Réinitialisation du mot de passe (Reset Password)](#réinitialisation-du-mot-de-passe-reset-password)
8. [Déconnexion (Logout)](#déconnexion-logout)
9. [Flux complet d'authentification](#flux-complet-dauthentification)
10. [Configuration SMTP](#configuration-smtp)
11. [Sécurité et bonnes pratiques](#sécurité-et-bonnes-pratiques)
12. [Dépannage](#dépannage)

---

## 🎯 Vue d'ensemble

Le système d'authentification E-News utilise **Laravel Sanctum** pour la gestion des tokens API et implémente un système complet de vérification d'email et de réinitialisation de mot de passe. Voici les fonctionnalités principales :

- ✅ Inscription avec vérification d'email obligatoire
- ✅ Connexion avec vérification de l'email
- ✅ Vérification d'email via lien sécurisé
- ✅ Renvoi d'email de vérification
- ✅ Mot de passe oublié
- ✅ Réinitialisation de mot de passe
- ✅ Déconnexion (token unique ou tous les tokens)
- ✅ Gestion des tokens avec Sanctum

---

## ⚙️ Configuration requise

### 1. Variables d'environnement (`.env`)

```env
# Application
APP_NAME=E-News
APP_URL=http://localhost:8000
APP_KEY=base64:... (généré avec php artisan key:generate)

# Frontend URL (pour les liens dans les emails)
FRONTEND_URL=http://localhost:3000

# Mail Configuration (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@enews.com
MAIL_FROM_NAME="E-News"

# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Redis (pour le cache)
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_CACHE_DB=1
```

### 2. Migration de la base de données

Assurez-vous que les migrations ont été exécutées :

```bash
php artisan migrate
```

Les tables suivantes sont nécessaires :
- `users` (avec `email_verified_at`)
- `password_reset_tokens` (pour la réinitialisation de mot de passe)
- `personal_access_tokens` (pour Sanctum)

---

## 📝 Processus d'inscription (Register)

### 1. Endpoint

```
POST /api/auth/register
```

### 2. Corps de la requête

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "categories_user": ["technology", "sports"]
}
```

### 3. Processus

1. **Validation des données** : Le contrôleur valide les données via `UserRequestValidation`
2. **Création de l'utilisateur** : Un nouvel utilisateur est créé dans la base de données
3. **Hash du mot de passe** : Le mot de passe est hashé avec `Hash::make()`
4. **Événement Registered** : L'événement `Registered` est déclenché
5. **Envoi de l'email de vérification** : L'email de vérification est envoyé automatiquement via `VerifyEmailNotification`
6. **Création du token** : Un token Sanctum est créé pour l'utilisateur
7. **Réponse** : L'utilisateur reçoit le token et les informations utilisateur

### 4. Réponse réussie (201)

```json
{
    "access_token": "1|abcdef123456...",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "categories_user": ["technology", "sports"]
    },
    "message": "Registration successful. Please verify your email address.",
    "email_verified": false
}
```

### 5. Points importants

- ✅ L'utilisateur peut se connecter immédiatement après l'inscription (token créé)
- ⚠️ Mais l'email doit être vérifié avant de pouvoir se reconnecter
- 📧 L'email de vérification est envoyé automatiquement
- 🔐 Le mot de passe est hashé avec bcrypt

---

## 📧 Vérification d'email

### 1. Email de vérification

Lors de l'inscription, un email est automatiquement envoyé à l'utilisateur contenant :

- **Sujet** : "Vérifiez votre adresse email - E-News"
- **Contenu** : Message personnalisé avec le nom de l'utilisateur
- **Lien** : URL de vérification signée et sécurisée
- **Validité** : Le lien expire après 24 heures

### 2. Lien de vérification

Le lien généré ressemble à :

```
http://localhost:8000/api/auth/email/verify/1/abc123def456?signature=...
```

**Composants du lien** :
- `id` : ID de l'utilisateur
- `hash` : Hash SHA1 de l'email de l'utilisateur
- `signature` : Signature temporaire Laravel (expire après 24h)

### 3. Vérification via endpoint

#### Endpoint publique

```
GET /api/auth/email/verify/{id}/{hash}
```

**Processus** :
1. Vérification de la signature de l'URL
2. Vérification du hash de l'email
3. Vérification si l'email est déjà vérifié
4. Marquer l'email comme vérifié (`email_verified_at = now()`)
5. Déclencher l'événement `Verified`

#### Réponse réussie (200)

```json
{
    "message": "Email verified successfully",
    "email_verified": true,
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

### 4. Renvoyer l'email de vérification

#### Endpoint protégé

```
POST /api/auth/email/verification-notification
Headers: Authorization: Bearer {token}
```

**Utilisation** : Si l'utilisateur n'a pas reçu l'email ou si le lien a expiré.

#### Réponse réussie (200)

```json
{
    "message": "Verification link sent to your email",
    "email_verified": false
}
```

**Limitation** : Throttling de 6 requêtes par minute (middleware `throttle:6,1`)

### 5. Vérifier le statut de vérification

#### Endpoint protégé

```
GET /api/auth/email/verification-status
Headers: Authorization: Bearer {token}
```

#### Réponse (200)

```json
{
    "email_verified": true,
    "message": "Email verified",
    "user": {
        "id": 1,
        "email_verified_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

---

## 🔐 Processus de connexion (Login)

### 1. Endpoint

```
POST /api/auth/login
```

### 2. Corps de la requête

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

### 3. Processus

1. **Validation des données** : Validation via `LoginRequestValidation`
2. **Recherche de l'utilisateur** : Recherche par email
3. **Vérification du mot de passe** : Vérification avec `Hash::check()`
4. **Vérification de l'email** : ⚠️ **L'email doit être vérifié**
5. **Création du token** : Création d'un token Sanctum
6. **Réponse** : Retour du token et des informations utilisateur

### 4. Réponse réussie (200)

```json
{
    "access_token": "2|xyz789abc123...",
    "token_type": "Bearer",
    "message": "Login successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2024-01-15T10:30:00.000000Z"
    },
    "email_verified": true
}
```

### 5. Réponse si email non vérifié (403)

```json
{
    "message": "Please verify your email address before logging in.",
    "email_verified": false,
    "user_id": 1
}
```

**Action requise** : L'utilisateur doit vérifier son email avant de pouvoir se connecter.

### 6. Réponse si credentials invalides (401)

```json
{
    "message": "Invalid credentials"
}
```

---

## 🔑 Mot de passe oublié (Forgot Password)

### 1. Endpoint

```
POST /api/auth/forgot-password
```

### 2. Corps de la requête

```json
{
    "email": "john@example.com"
}
```

### 3. Processus

1. **Validation de l'email** : Vérification que l'email existe dans la base de données
2. **Génération du token** : Génération d'un token de réinitialisation
3. **Stockage du token** : Le token est stocké dans la table `password_reset_tokens`
4. **Envoi de l'email** : Envoi de l'email avec le lien de réinitialisation via `ResetPasswordNotification`
5. **Réponse sécurisée** : Même message si l'email existe ou non (sécurité)

### 4. Email de réinitialisation

L'email contient :

- **Sujet** : "Réinitialisation de votre mot de passe - E-News"
- **Contenu** : Message personnalisé
- **Lien** : URL frontend avec token et email
- **Validité** : Le token expire après 60 minutes

### 5. Lien de réinitialisation

Le lien généré ressemble à :

```
http://localhost:3000/reset-password?token=abc123&email=john@example.com
```

**Note** : Le lien pointe vers le frontend, qui appellera ensuite l'API pour réinitialiser le mot de passe.

### 6. Réponse (200)

```json
{
    "message": "If that email address exists in our system, we have sent a password reset link.",
    "status": "sent"
}
```

**Sécurité** : Le message est identique que l'email existe ou non pour éviter l'énumération d'emails.

---

## 🔄 Réinitialisation du mot de passe (Reset Password)

### 1. Endpoint

```
POST /api/auth/reset-password
```

### 2. Corps de la requête

```json
{
    "token": "abc123def456...",
    "email": "john@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### 3. Processus

1. **Validation des données** : Validation du token, email et nouveau mot de passe
2. **Vérification du token** : Vérification que le token est valide et non expiré
3. **Recherche de l'utilisateur** : Recherche de l'utilisateur par email
4. **Hash du nouveau mot de passe** : Hash du nouveau mot de passe
5. **Mise à jour** : Mise à jour du mot de passe dans la base de données
6. **Révoquer les tokens** : ⚠️ **Tous les tokens Sanctum sont révoqués** (sécurité)
7. **Événement** : Déclencher l'événement `PasswordReset`
8. **Suppression du token** : Le token de réinitialisation est supprimé

### 4. Réponse réussie (200)

```json
{
    "message": "Password reset successfully. You can now login with your new password.",
    "status": "success"
}
```

### 5. Réponse si token invalide (422)

```json
{
    "message": "This password reset token is invalid or has expired.",
    "status": "error",
    "error": "INVALID_TOKEN"
}
```

### 6. Points importants

- ✅ Le token expire après 60 minutes
- ✅ Tous les tokens Sanctum sont révoqués après réinitialisation
- ✅ L'utilisateur doit se reconnecter après réinitialisation
- ✅ Le mot de passe doit être confirmé (champ `password_confirmation`)

---

## 🚪 Déconnexion (Logout)

### 1. Déconnexion (token actuel)

#### Endpoint protégé

```
POST /api/auth/logout
Headers: Authorization: Bearer {token}
```

#### Processus

1. **Récupération du token** : Récupération du token actuel utilisé
2. **Suppression** : Suppression du token de la base de données
3. **Réponse** : Confirmation de la déconnexion

#### Réponse (200)

```json
{
    "message": "Logout successfully"
}
```

### 2. Déconnexion complète (tous les tokens)

#### Endpoint protégé

```
POST /api/auth/logout-all
Headers: Authorization: Bearer {token}
```

#### Processus

1. **Récupération de l'utilisateur** : Récupération de l'utilisateur authentifié
2. **Suppression** : Suppression de **tous** les tokens de l'utilisateur
3. **Réponse** : Confirmation de la déconnexion

#### Réponse (200)

```json
{
    "message": "All sessions logged out successfully"
}
```

**Utilisation** : Utile pour déconnecter l'utilisateur de tous les appareils (sécurité).

---

## 🔄 Flux complet d'authentification

### Scénario 1 : Inscription et vérification

```
1. User → POST /api/auth/register
   ↓
2. Backend → Crée l'utilisateur
   ↓
3. Backend → Envoie l'email de vérification
   ↓
4. Backend → Retourne le token
   ↓
5. User → Reçoit l'email
   ↓
6. User → Clique sur le lien de vérification
   ↓
7. Frontend → GET /api/auth/email/verify/{id}/{hash}
   ↓
8. Backend → Vérifie et marque l'email comme vérifié
   ↓
9. User → Peut maintenant se connecter
```

### Scénario 2 : Connexion

```
1. User → POST /api/auth/login
   ↓
2. Backend → Vérifie les credentials
   ↓
3. Backend → Vérifie si l'email est vérifié
   ↓
4a. Si email non vérifié → 403 (doit vérifier)
4b. Si email vérifié → Retourne le token
   ↓
5. User → Utilise le token pour les requêtes API
```

### Scénario 3 : Mot de passe oublié

```
1. User → POST /api/auth/forgot-password
   ↓
2. Backend → Génère le token de réinitialisation
   ↓
3. Backend → Envoie l'email avec le lien
   ↓
4. User → Reçoit l'email
   ↓
5. User → Clique sur le lien (vers frontend)
   ↓
6. Frontend → Affiche le formulaire de réinitialisation
   ↓
7. User → Saisit le nouveau mot de passe
   ↓
8. Frontend → POST /api/auth/reset-password
   ↓
9. Backend → Vérifie le token
   ↓
10. Backend → Met à jour le mot de passe
   ↓
11. Backend → Révoque tous les tokens
   ↓
12. User → Doit se reconnecter
```

---

## 📧 Configuration SMTP

### 1. Configuration dans `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@enews.com
MAIL_FROM_NAME="E-News"
```

### 2. Services SMTP recommandés

#### Pour le développement (Mailtrap)

- **Host** : `smtp.mailtrap.io`
- **Port** : `2525` ou `587`
- **Encryption** : `tls`
- **Avantage** : Capture les emails pour les tests



### 4. Configuration du frontend URL

Assurez-vous de configurer `FRONTEND_URL` dans `.env` pour que les liens dans les emails pointent vers le bon frontend :


---

## 🔒 Sécurité et bonnes pratiques

### 1. Sécurité des tokens

- ✅ Les tokens Sanctum sont stockés dans la base de données
- ✅ Les tokens sont révoqués lors de la réinitialisation du mot de passe
- ✅ Possibilité de déconnexion complète (tous les tokens)
- ✅ Les tokens n'expirent pas automatiquement (gérer manuellement si nécessaire)

### 2. Sécurité des mots de passe

- ✅ Hash avec bcrypt (par défaut Laravel)
- ✅ Minimum 8 caractères requis
- ✅ Confirmation du mot de passe requise
- ✅ Tokens de réinitialisation expirant après 60 minutes

### 3. Sécurité des emails

- ✅ URLs signées pour la vérification d'email (expirent après 24h)
- ✅ Hash SHA1 de l'email dans l'URL
- ✅ Messages identiques pour éviter l'énumération d'emails
- ✅ Throttling sur le renvoi d'email (6 requêtes/minute)

### 4. Validation

- ✅ Validation stricte des données d'entrée
- ✅ Vérification de l'existence de l'email
- ✅ Vérification de la correspondance du mot de passe
- ✅ Vérification de l'état de vérification de l'email

### 5. Recommandations

- 🔐 Utiliser HTTPS en production
- 🔐 Configurer CORS correctement
- 🔐 Implémenter un système de rate limiting
- 🔐 Logger les tentatives de connexion échouées
- 🔐 Implémenter 2FA (optionnel, futur)

---

## 🛠️ Dépannage

### Problème 1 : L'email de vérification n'est pas envoyé

**Solutions** :
1. Vérifier la configuration SMTP dans `.env`
2. Vérifier les logs : `storage/logs/laravel.log`
3. Tester avec Mailtrap pour le développement
4. Vérifier que la queue n'est pas utilisée (sinon exécuter `php artisan queue:work`)

### Problème 2 : Le lien de vérification est invalide

**Solutions** :
1. Vérifier que `APP_KEY` est configuré dans `.env`
2. Vérifier que l'URL dans l'email est correcte
3. Vérifier que le lien n'a pas expiré (24h)
4. Vérifier la configuration de `APP_URL`

### Problème 3 : Impossible de se connecter après inscription

**Causes possibles** :
1. L'email n'est pas vérifié (vérifier le statut)
2. Les credentials sont incorrects
3. Le token a expiré

**Solutions** :
1. Vérifier le statut de vérification : `GET /api/auth/email/verification-status`
2. Renvoyer l'email de vérification : `POST /api/auth/email/verification-notification`
3. Vérifier les logs pour les erreurs

### Problème 4 : Le token de réinitialisation est invalide

**Solutions** :
1. Vérifier que le token n'a pas expiré (60 minutes)
2. Vérifier que l'email correspond au token
3. Générer un nouveau token en redemandant un email

### Problème 5 : Les emails ne sont pas reçus

**Solutions** :
1. Vérifier le dossier spam
2. Vérifier la configuration SMTP
3. Vérifier les logs Laravel
4. Utiliser Mailtrap pour tester

### Commandes utiles

```bash
# Vider le cache de configuration
php artisan config:clear

# Vider le cache des routes
php artisan route:clear

# Vider le cache de l'application
php artisan cache:clear

# Régénérer la clé de l'application
php artisan key:generate

# Voir les routes
php artisan route:list

# Tester l'envoi d'email
php artisan tinker
```

---

## 📚 Références

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Laravel Authentication Documentation](https://laravel.com/docs/authentication)
- [Laravel Password Reset Documentation](https://laravel.com/docs/passwords)

---

## 🎓 Conclusion

Le système d'authentification E-News est complet et sécurisé. Il implémente :

- ✅ Inscription avec vérification d'email
- ✅ Connexion avec vérification de l'email
- ✅ Réinitialisation de mot de passe
- ✅ Gestion des tokens avec Sanctum
- ✅ Sécurité renforcée (tokens révoqués, URLs signées, etc.)

**Prochaines améliorations possibles** :
- 🔐 Authentification à deux facteurs (2FA)
- 🔐 Gestion des sessions actives
- 🔐 Notifications de connexion suspecte
- 🔐 Limitation du nombre de tentatives de connexion

---

**Date de création** : 2024-01-15  
**Version** : 1.0  
**Auteur** : E-News Development Team

