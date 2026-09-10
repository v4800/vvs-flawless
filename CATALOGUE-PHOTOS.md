# Photos du catalogue — 10 septembre 2026

Cette série reprend quatre images déjà réalisées : fond en marbre noir, décor noir et doré, lumière chaude. Aucune nouvelle génération, aucun détourage ni changement de couleur ou de montre. Les PNG originaux sont conservés séparément dans le paquet de livraison. Le site utilise des encodages WebP (1448 × 1086) et des variantes de 720 pixels pour les cartes.

| Référence visuelle | Modèle                         | Liaison actuelle                                                   |
| ------------------ | ------------------------------ | ------------------------------------------------------------------ |
| VVS-C001           | Cadran bleu, chiffres romains  | Fiche portant l’image `presidentielle-bleu-romains.webp` ou `.png` |
| VVS-C002           | Bicolore, bracelet intégré     | Contact pour prix, options et délai                                |
| VVS-C004           | Carrée noire, chiffres romains | Contact pour prix, options et délai                                |
| VVS-C008           | Sportive, bracelet noir        | Contact pour prix, options et délai                                |

Les références VVS-C et les identifiants du JSON désignent des images, pas les lignes de la base. Les anciennes associations automatiques aux lignes 46, 47 et 48 sont supprimées : elles pouvaient afficher un autre modèle sous un nom et un prix existants. Les autres produits conservent leur photo enregistrée en base. Aucun prix, stock, produit ni réservation n’est modifié en base.

`resources/data/watch-image-catalog.json` définit les images et les modèles à présenter (`featured`). `legacy_images` contient uniquement les chemins dont la correspondance est établie. Une image explicitement enregistrée dans un dossier du catalogue permet également de rattacher une fiche à ce dossier. Les anciennes images restent sur disque et sont répertoriées sous `archived_images` pour les modèles remplacés.

Pour les trois autres modèles de cette série, il faut encore confirmer les prix et les options avant de créer des fiches avec réservation. Pour uniformiser les autres montres du site, il manque leurs images individuelles dans cette direction artistique. Ne pas substituer un modèle ressemblant et ne pas réutiliser le prix d’une autre montre.

## Vérification

Versions vérifiées dans les fichiers verrouillés : Laravel 13.29.0, inertia-laravel 3.3.1, Vue 3.5.42, Inertia Vue 3.7.0, Tailwind 4.3.3, Vite 8.2.2. Aucune dépendance modifiée.

Contrôles réalisés : formatage et lint frontend, compilation des quatre composants Vue concernés, analyse syntaxique des PHP modifiés, existence et dimensions des nouveaux fichiers image. Les tests de régression couvrent la correspondance des images, la conservation des données, les trois langues et les métadonnées produit.

Le build complet, le contrôle TypeScript et les tests Laravel restent à exécuter dans l’environnement local : PHP est absent de l’environnement de préparation, donc Wayfinder ne peut pas générer ses fichiers. Les tests PHP ajoutés n’ont pas encore été exécutés. Contrôler ensuite `/watches`, `/nl/watches`, `/en/watches` et la fiche bleue, sur mobile et ordinateur.

Documentation de référence : [props Vue](https://vuejs.org/guide/components/props.html), [Laravel 13](https://laravel.com/docs/13.x/responses).
