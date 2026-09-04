# VVS FLAWLESS

Site Laravel + Vue/Inertia de VVS FLAWLESS, consacré à la présentation et à la réservation de montres iced-out serties de moissanite.

## Stack verrouillée

Les versions exactes sont celles de `composer.lock` et `package-lock.json`. Ne lancez pas `composer update` ou `npm update` pour installer le projet.

- PHP 8.3 minimum
- Laravel 13
- Vue 3 et Inertia 3
- Tailwind CSS 4
- Vite Plus / Vite 8
- MySQL en production

## Installation locale

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm ci
php artisan migrate
composer run dev
```

Configurez la base de données et le serveur d'e-mail dans `.env`. Ne versionnez jamais ce fichier.

## Contrôles avant un push

```powershell
npm run check
npm run build
composer ci:check
git diff --check
git status --short --branch
```

`composer ci:check` exécute le formatage en lecture seule, l'analyse statique PHP, la vérification des types Vue et les tests Laravel.

## Mise en production

Avant le déploiement, sauvegardez la base de données et configurez au minimum :

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.be
SESSION_SECURE_COOKIE=true
DB_CONNECTION=mysql
VVS_ADMIN_EMAIL=adresse-administrateur@example.com
```

Configurez également les variables MySQL et SMTP réelles sur l'hébergement. Puis, depuis la version déployée :

```bash
composer install --no-dev --optimize-autoloader --no-interaction
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Ne lancez jamais `migrate:fresh` en production. Vérifiez les permissions de `storage` et `bootstrap/cache`, HTTPS, l'envoi des e-mails et les pages publiques après le déploiement.

## SEO et indexation

- Sitemap : `/sitemap.xml`
- Robots : `/robots.txt`
- Collection FR : `/watches`
- Collection NL : `/nl/watches`

Après la mise en ligne avec le domaine définitif, ajoutez la propriété du domaine à Google Search Console, validez-la idéalement par DNS TXT, envoyez l'URL absolue du sitemap et inspectez la page d'accueil, les collections et les principales fiches. Répétez la vérification dans Bing Webmaster Tools.

L'envoi du sitemap facilite la découverte des pages mais ne garantit aucune position dans les résultats.
