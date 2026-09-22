# VVS FLAWLESS — convention SEO des images produit

Les nouvelles images doivent être compréhensibles par un humain avant même d’ouvrir le fichier. On évite les noms génériques du type `IMG_9832.webp`, `01-front.webp` ou `black-marble.webp` pour les nouvelles photos publiques.

## Format recommandé

`NNN-model-slug/NN-description-produit-vue.webp`

Exemples :

- `009-bicolore-turquoise/01-montre-moissanite-vvs-bicolore-turquoise-face.webp`
- `009-bicolore-turquoise/02-montre-moissanite-vvs-bicolore-turquoise-angle.webp`
- `009-bicolore-turquoise/03-montre-moissanite-vvs-bicolore-turquoise-bracelet.webp`
- `010-sport-bracelet-noir/01-montre-iced-out-moissanite-vvs-bracelet-noir-face.webp`

## Règles

- 1 dossier = 1 montre.
- Le numéro du dossier ne change jamais.
- Les images d’une montre commencent à `01`.
- Le nom décrit le produit et la vue : `face`, `angle`, `profil`, `dos`, `bracelet`, `fermoir`, `cadran`, `detail`.
- Utiliser des mots naturels et utiles, pas une liste de mots-clés. Un seul nom doit suffire à comprendre ce que montre la photo.
- Utiliser **WebP** pour les photos publiques du catalogue sauf besoin technique particulier.
- Conserver des dimensions cohérentes pour une même série. Base actuelle recommandée : **1448 × 1086** pour les grandes images et variante d’environ **720 px** de large pour les cartes.
- Viser une image suffisamment nette sans poids inutile. En pratique, la plupart des visuels WebP du catalogue devraient rester sous quelques centaines de Ko ; éviter les PNG de plusieurs Mo lorsqu’un WebP visuellement équivalent suffit.
- Les PNG/JPG sources peuvent être archivés, mais le site public doit privilégier la variante WebP optimisée.
- Le prochain identifiant disponible est indiqué dans `resources/data/watch-image-catalog.json`.
- Les noms publics restent génériques et sans marque tierce lorsque la montre n’est pas un produit officiel de cette marque.

## Texte alternatif (`alt`)

Le `alt` doit décrire ce qui est réellement visible, par exemple :

`Montre bicolore sertie de moissanite VVS couleur D, cadran turquoise`

Pour une autre vue :

`Montre bicolore en moissanite VVS couleur D, vue du bracelet`

Ne pas répéter artificiellement « montre diamant », « iced-out », « VVS », « Belgique » dans chaque image. Le `alt` sert d’abord à décrire l’image ; le contexte SEO/GEO vient aussi du titre, de la fiche produit, des guides et des données structurées.

## Schema Product

Lorsqu’une fiche possède plusieurs photos actives dans `watch-image-catalog.json`, elles doivent toutes être reprises dans la propriété `image` du schema `Product`. Le contrôleur produit utilise déjà la galerie active pour générer cette liste : ajouter les nouvelles vues dans `images` suffit pour qu’elles soient exposées au schema après vérification.
