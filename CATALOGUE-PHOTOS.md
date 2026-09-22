# Photos du catalogue — état SEO/GEO

La série actuelle utilise quatre visuels sur fond marbre noir avec décor noir et doré. Le site sert les variantes WebP optimisées pour l’affichage public. Les fichiers sources plus lourds peuvent rester archivés, mais les nouvelles images destinées au site doivent suivre la convention décrite dans `IMAGE_NAMING.md`.

## Modèles actuels

- `VVS-C001` — cadran bleu, chiffres romains : relié à sa fiche produit ; le prix est récupéré depuis la fiche.
- `VVS-C002` — bicolore, bracelet intégré : à partir de 650 €.
- `VVS-C004` — carrée noire, chiffres romains : à partir de 850 €.
- `VVS-C008` — sportive, bracelet noir : à partir de 650 €.

Les références `VVS-Cxxx` et les identifiants du JSON sont des références techniques internes aux visuels. Elles ne doivent pas servir de nom commercial visible pour le client. Les associations d’images reposent sur des chemins explicitement connus dans `resources/data/watch-image-catalog.json`, jamais sur le numéro d’une ligne de base de données.

## Image SEO

Les fichiers actuels `black-marble.webp` proviennent d’une série déjà intégrée. On ne les renomme pas à chaud afin d’éviter de casser les chemins existants. Les prochaines photos doivent en revanche utiliser dès l’import des noms descriptifs comme `01-montre-moissanite-vvs-cadran-bleu-face.webp` ou `02-montre-iced-out-moissanite-vvs-bracelet-noir-angle.webp`.

Les règles complètes sont dans `IMAGE_NAMING.md` : WebP pour les photos publiques, dimensions cohérentes, variante carte plus légère, `alt` descriptif et absence de keyword stuffing.

Les visuels WebP actuels de la série principale font environ 1448 × 1086, avec des versions carte d’environ 720 px. C’est une bonne base pour conserver une grille visuellement stable et éviter de charger de gros PNG de plusieurs mégaoctets quand ils n’apportent aucun gain visible.

## Galeries et schema Product

Pour une vraie fiche produit reliée à la base, toutes les images actives déclarées dans `images` pour son entrée catalogue sont reprises par la galerie. Le contrôleur transforme ensuite cette galerie en tableau `image` du schema `Product`. Quand de nouvelles vues sont ajoutées — face, angle, cadran, bracelet, fermoir ou dos — elles doivent être ajoutées proprement à l’entrée concernée pour être exposées au produit après vérification.

Les modèles uniquement présentés dans le catalogue et renvoyant encore vers le contact ne reçoivent pas artificiellement une fiche `Product` ou des `Offer` inventées. Une vraie fiche doit être créée ou reliée avant de leur attribuer ces données structurées.

## Prochain ajout d’images

- Importer les nouvelles photos directement en WebP avec un nom descriptif.
- Garder des proportions cohérentes pour toutes les cartes d’une même série.
- Ajouter plusieurs vues utiles dans `resources/data/watch-image-catalog.json`.
- Vérifier le `alt` dans la langue affichée et la présence des images de galerie dans le schema `Product` pour les fiches reliées.
- Contrôler le poids des fichiers avant mise en ligne et conserver les originaux lourds hors du chemin public lorsqu’ils ne sont pas nécessaires.

Aucune dépendance Laravel, Vue, Inertia, Tailwind ou Vite n’est modifiée par ce travail SEO/GEO.
