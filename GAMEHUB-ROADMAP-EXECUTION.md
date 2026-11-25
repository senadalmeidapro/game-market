# GAMEHUB — Plan d’exécution et suivi de la roadmap

Ce document transforme le périmètre du [dossier de conception GAMEHUB](<GAMEHUB — Definitive Scope and Design Dossier (Laravel).md>) en tâches de développement suivables.

## Comment utiliser ce plan

- Le calendrier est découpé en **12 semaines de 5 jours ouvrés**. `S1-J1` signifie « semaine 1, jour 1 ».
- Les semaines sont relatives à la date réelle de démarrage. Aucune date fixe n’est imposée tant que la disponibilité de l’équipe n’est pas connue.
- Cocher une tâche uniquement quand le code est intégré et son critère d’acceptation vérifié.
- Une tâche bloquée reste non cochée; inscrire la cause dans la note de la semaine et déplacer sa dépendance avant les tâches qui en dépendent.
- Estimation indicative pour une personne à temps plein. Les tâches d’upload direct vers S3, les courriels et l’exploitation peuvent demander plus de temps selon les services retenus.

## Situation de départ

### Déjà en place

- [x] Les migrations du schéma GAMEHUB ont été générées et appliquées dans le cadre du travail précédent.
- [x] Les modèles Eloquent GAMEHUB existent et sont organisés par domaine sous `app/Models`.
- [x] Le fournisseur de panneau Filament et le chemin `/admin` existent.
- [x] Le socle d’authentification Laravel existe.
- [x] Les migrations de rôles et permissions ainsi que les modèles de rôles sont présents.

### À construire

- [ ] Les ressources métier Filament ne sont pas encore présentes dans `app/Filament`.
- [ ] La vitrine est encore une page d’accueil de base; le parcours boutique reste à construire.

### Contraintes de cette roadmap

- Le développement commence par le **back-office Filament**.
- Les fiches éditeur sont des données internes; il n’y a pas de portail éditeur dans le MVP.
- Les commandes, le panier, la liste de souhaits et la préparation du paiement peuvent avancer sans prestataire.
- Aucune initiation de paiement, confirmation de paiement ou commande payée ne doit être possible tant que le prestataire n’est pas choisi, configuré, vérifié et explicitement activé.
- Le tableau de bord client Laravel et les pages starter inutiles ont été retirés; le panneau d’administration Filament reste utile.
- Les éléments V2 (commissions et règlements éditeurs, paiements multiples, avis, remboursements partiels, etc.) ne sont pas inclus dans les 12 semaines.

## Vue d’ensemble

| Semaine | Domaine principal | Livrable de fin de semaine | État |
|---|---|---|---|
| 1 | Accès, sécurité et structure Filament | Back-office protégé, rôles opérationnels, navigation de base | ⬜ À faire |
| 2 | Référentiels et éditeurs | Gestion des catégories, genres, plateformes et éditeurs | ⬜ À faire |
| 3 | Fiches produits | Création, modification, recherche et archivage des produits | ⬜ À faire |
| 4 | Versions, fichiers et publication | Gestion des versions et garde-fous de publication | ⬜ À faire |
| 5 | Promotions, clients, commandes et support | Gestion administrative des opérations courantes | ⬜ À faire |
| 6 | Paramètres, audit, rapports et tableau de bord | Back-office MVP cohérent et recette interne | ⬜ À faire |
| 7 | Vitrine et catalogue public | Parcours public de découverte des produits | ⬜ À faire |
| 8 | Panier et liste de souhaits | Parcours visiteur et client avec fusion après connexion | ⬜ À faire |
| 9 | Préparation de commande | Récapitulatif client et calcul serveur, paiement toujours coupé | ⬜ À faire |
| 10 | Bibliothèque et téléchargements | Bibliothèque et accès aux fichiers sous licence | ⬜ À faire |
| 11 | Courriels, support et préparation paiement | Flux opérationnels; paiement inactif par défaut | ⬜ À faire |
| 12 | Stabilisation et préparation de mise en service | MVP relu, sauvegardé, documenté et prêt pour recette | ⬜ À faire |

---

# Semaine 1 — Accès, sécurité et structure Filament

**Objectif :** établir une base d’administration sûre avant d’exposer des données métier.

### Jour 1 — Vérifier le socle

- [ ] Confirmer le fonctionnement de `/admin`, la connexion Filament et la configuration du panneau.
- [ ] Vérifier les migrations, les noms de tables, les casts et relations nécessaires au RBAC.
- [ ] Établir les conventions des ressources Filament : libellés français, groupes de navigation, noms de permissions et règles d’archivage.
- [ ] Noter les écarts entre le schéma existant et les contraintes du dossier; ne créer une migration complémentaire que si un manque est confirmé.

**Avancement attendu :** une liste validée des écarts et conventions; pas encore de ressource produit.

### Jour 2 — Rôles et permissions

- [ ] Définir les permissions par module : catalogue, commandes, remboursements, promotions, finance, utilisateurs, paramètres, audit et support.
- [ ] Créer ou compléter un seeder idempotent des rôles de base : super administrateur, gestionnaire catalogue, finance, marketing et support.
- [ ] Attribuer le jeu minimal de permissions à chaque rôle selon le dossier.
- [ ] Garder les rôles configurables en base plutôt que coder des noms de rôle comme seule barrière d’accès.

**Avancement attendu :** une installation répétée conserve les mêmes rôles et permissions sans doublons.

### Jour 3 — Authentification d’administration

- [ ] Vérifier que les comptes qui entrent dans Filament sont des comptes d’administration autorisés.
- [ ] Exiger l’authentification à deux facteurs des comptes administrateurs avant l’accès au panneau.
- [ ] Prévoir la création/invitation des comptes staff et la désactivation d’un compte.
- [ ] Vérifier les limites de sécurité autour des sessions et de la réinitialisation du mot de passe.

**Avancement attendu :** un client normal ne peut pas ouvrir le back-office; un admin sans 2FA n’accède pas aux fonctions protégées.

### Jour 4 — Autorisations et audit

- [ ] Relier les permissions Spatie aux policies Laravel des premières ressources.
- [ ] Définir la journalisation pour les connexions administratives, changements de rôle, publication et changements sensibles.
- [ ] Empêcher le retrait ou la rétrogradation du dernier super administrateur.
- [ ] Ajouter des réponses d’accès refusé compréhensibles, sans révéler de données protégées.

**Avancement attendu :** toute action administrative sensible vérifie une policy et peut être retracée.

### Jour 5 — Navigation, expérience et revue de la semaine

- [ ] Organiser la navigation Filament par domaines : Catalogue, Commerce, Clients et support, Finance, Configuration.
- [ ] Appliquer l’identité GAMEHUB, le titre et les libellés français utiles.
- [ ] Masquer les entrées de navigation inaccessibles au rôle connecté; conserver le contrôle serveur par policy.
- [ ] Faire une revue manuelle avec chaque rôle de test.
- [ ] Mettre à jour l’état de la semaine et consigner les blocages ci-dessous.

**Critères de sortie :** accès admin contrôlé, 2FA obligatoire, rôles initiaux chargés, dernier super administrateur protégé, événements sensibles audités.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 2 — Référentiels du catalogue et éditeurs

**Objectif :** permettre à l’équipe de préparer les données réutilisées dans les fiches produits.

### Jour 1 — Ressource catégories

- [ ] Créer la ressource Filament Catégories avec liste, recherche, tri et formulaire.
- [ ] Gérer le slug et les règles de validation des champs requis.
- [ ] Afficher l’état d’utilisation et bloquer la suppression d’une catégorie affectée à un produit.
- [ ] Contrôler les permissions de lecture et de modification.

### Jour 2 — Ressource genres

- [ ] Créer la ressource Genres selon les mêmes conventions de formulaire et de table.
- [ ] Gérer l’affectation multiple aux produits depuis le modèle relationnel existant.
- [ ] Empêcher la suppression d’un genre déjà utilisé.
- [ ] Vérifier les libellés et messages d’erreur en français.

### Jour 3 — Ressource plateformes

- [ ] Créer la ressource Plateformes pour Windows, Linux et Autre.
- [ ] Ajouter des valeurs initiales cohérentes par seeder si le schéma prévoit des référentiels éditables.
- [ ] Empêcher les doublons et la suppression d’une plateforme utilisée par des versions.
- [ ] Vérifier l’affichage des plateformes dans les sélecteurs de formulaire.

### Jour 4 — Ressource éditeurs

- [ ] Créer la ressource Éditeurs avec coordonnées utiles et statut contractuel.
- [ ] Prévoir le stockage privé du contrat quand la configuration de stockage est prête.
- [ ] Bloquer la publication d’un produit lié à un éditeur dont le contrat n’est pas signé, selon la règle métier.
- [ ] Ne pas créer de compte ni d’espace de connexion éditeur.

### Jour 5 — Revue des référentiels

- [ ] Vérifier les relations catégories, genres, plateformes et éditeurs avec les modèles actuels.
- [ ] Vérifier les droits de chaque rôle et les erreurs de suppression.
- [ ] Vérifier qu’un rôle catalogue peut gérer ces référentiels et qu’un rôle support ne peut pas les modifier.
- [ ] Compléter cette feuille de suivi.

**Critères de sortie :** les quatre ressources sont utilisables, les données référentielles ne peuvent pas être supprimées lorsqu’elles sont utilisées et l’accès est restreint par permissions.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 3 — Fiches produits

**Objectif :** construire l’écran central du back-office : la gestion de jeux, photos et vidéos.

### Jour 1 — Formulaire et liste produits

- [ ] Créer la ressource Produits et une table admin avec recherche, filtres, tri, type, statut, prix et date de mise à jour.
- [ ] Construire les sections du formulaire : identité, description, type, prix, taxonomies, éditeur et statut.
- [ ] Prévoir un état initial `draft` pour toute création.
- [ ] Vérifier l’utilisation des attributs de masse et casts définis sur le modèle.

### Jour 2 — Règles d’identité et de prix

- [ ] Valider titre, slug unique, type de produit et description.
- [ ] Valider prix entier en XOF et l’écriture d’un historique lorsqu’un prix change.
- [ ] Éviter qu’une modification du prix actuel réécrive les prix des commandes historiques.
- [ ] Ne pas proposer les champs de ventes, commissions ou remises V2 dans cette première version.

### Jour 3 — Types et associations

- [ ] Adapter les champs visibles au type jeu, photo ou vidéo.
- [ ] Ajouter la sélection des catégories, genres, plateformes et éditeur.
- [ ] Ajouter les actions d’archivage et de dépublication distinctes de la suppression.
- [ ] Vérifier les cas d’un produit sans éditeur et d’un produit sans taxonomy.

### Jour 4 — Médias de présentation

- [ ] Ajouter couverture et captures d’écran via la médiathèque.
- [ ] Ajouter l’URL de bande-annonce externe avec validation de domaine/format appropriée.
- [ ] Définir les conversions d’image prévues pour les aperçus publics.
- [ ] S’assurer que les originaux des produits photo/vidéo ne deviennent pas publics par erreur.

### Jour 5 — Audit des modifications produit

- [ ] Journaliser les changements de champs sensibles avec utilisateur, date et valeurs avant/après.
- [ ] Créer une vue d’historique lisible sur la fiche admin.
- [ ] Vérifier les politiques de lecture, création, modification, archivage et suppression.
- [ ] Faire une revue manuelle du cycle brouillon → modification → archivage.

**Critères de sortie :** un gestionnaire catalogue peut créer et modifier les trois types de fiches; toute nouvelle fiche est un brouillon; les prix historiques et les actions sensibles sont préservés.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 4 — Exigences, versions, fichiers et publication

**Objectif :** rendre le catalogue publiable sans exposer les fichiers distribuables.

### Jour 1 — Exigences système

- [ ] Ajouter à la fiche jeu les exigences minimales et recommandées par système d’exploitation.
- [ ] Valider qu’une exigence appartient bien à un produit de type jeu.
- [ ] Présenter les exigences dans un ordre clair dans le back-office.

### Jour 2 — Versions de produit

- [ ] Ajouter la gestion des versions, notes de version et date de publication.
- [ ] Assurer l’unicité du numéro de version selon la contrainte du dossier.
- [ ] Gérer le drapeau `latest` par produit et plateforme pour qu’il n’existe pas plusieurs dernières versions concurrentes.
- [ ] Vérifier la compatibilité plateforme/version.

### Jour 3 — Stockage privé et fichiers

- [ ] Configurer un disque privé séparé des médias publics.
- [ ] Ajouter la gestion des fichiers téléchargeables avec taille, clé de stockage et checksum SHA-256.
- [ ] Contrôler les extensions, types MIME et limites acceptées.
- [ ] Vérifier qu’aucun lien public permanent vers un fichier original n’est rendu par l’admin.

### Jour 4 — Gros fichiers et contrôles de stockage

- [ ] Évaluer et configurer le transfert multipart/chunké vers le stockage objet retenu, sans faire transiter des fichiers de plusieurs Go par une requête PHP classique.
- [ ] Ajouter un job de calcul/validation du checksum après transfert.
- [ ] Afficher état du transfert, taille et résultat du checksum à l’administrateur.
- [ ] Documenter les variables de configuration et le comportement local lorsque le stockage objet n’est pas configuré.

### Jour 5 — Publication et dépublication

- [ ] Implémenter une action explicite de publication et une action de dépublication.
- [ ] Bloquer la publication sans prix, couverture, et version/fichier distribuable adapté au type.
- [ ] Bloquer la publication si le contrat de l’éditeur associé n’est pas signé.
- [ ] Garder l’accès aux propriétaires existants lorsqu’un produit est dépublié ou archivé.
- [ ] Vérifier l’audit des transitions de statut.

**Critères de sortie :** publication impossible sans contenu requis; fichiers de distribution stockés en privé et vérifiés; règles de version et d’éditeur appliquées.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 5 — Promotions, clients, commandes et support

**Objectif :** donner aux équipes métier les ressources admin essentielles sans activer les paiements.

### Jour 1 — Promotions

- [ ] Créer la ressource Promotions avec code, type de remise, valeur, dates et limites d’utilisation.
- [ ] Valider qu’une réduction fixe ou en pourcentage reste dans les limites commerciales.
- [ ] Afficher les usages et statistiques disponibles sans permettre d’éditer un historique d’utilisation.
- [ ] Réserver l’accès d’écriture au marketing et au super administrateur.

### Jour 2 — Comptes clients

- [ ] Créer une vue admin en lecture avec recherche, statut de vérification et date de création.
- [ ] Ajouter suspension/réactivation avec motif, confirmation et audit.
- [ ] Empêcher la modification du mot de passe ou l’exposition d’attributs secrets depuis la ressource.
- [ ] Limiter l’accès selon la matrice des rôles.

### Jour 3 — Commandes en consultation

- [ ] Créer une ressource de consultation des commandes et lignes avec instantanés de prix.
- [ ] Ajouter filtres par référence, client, état et période.
- [ ] Présenter clairement les états brouillon/en attente/annulée/payée si ces états sont déjà définis dans le domaine.
- [ ] Ne pas proposer d’action permettant de marquer manuellement une commande comme payée.

### Jour 4 — Paiements et remboursements désactivés

- [ ] Présenter les tentatives de paiement uniquement si elles existent; aucun moyen de paiement n’est sélectionnable par défaut.
- [ ] Ajouter les indicateurs d’activation désactivée et les contrôles de configuration requis.
- [ ] Préparer les autorisations de consultation et d’approbation de remboursement sans appeler un prestataire absent.
- [ ] S’assurer qu’aucun faux succès de paiement ne déclenche une licence ou une écriture financière.

### Jour 5 — Tickets de support

- [ ] Créer les ressources tickets et messages avec statuts ouvert, en attente et résolu.
- [ ] Permettre la consultation du client et de sa commande liée en lecture seule.
- [ ] Ajouter réponse interne/externe selon le schéma et l’architecture mail retenus.
- [ ] Vérifier les droits du support et la séparation entre proposition et approbation de remboursement.
- [ ] Ajouter la modération Filament des avis et commentaires : publier/masquer, auteur, note, fil de réponses et audit.

**Critères de sortie :** promotion, client, commande et ticket gérables; aucune action admin ne peut contourner l’état de paiement désactivé.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 6 — Paramètres, audit, rapports et tableau de bord

**Objectif :** terminer la première tranche du back-office et la faire valider avant de construire la boutique.

### Jour 1 — Paramètres et pages légales

- [ ] Créer un écran de réglages avec nom de boutique, devise, taux de taxe, durée des liens, limite de téléchargements et délai de remboursement.
- [ ] Valider les valeurs et appliquer des valeurs de repli sûres.
- [ ] Créer les pages de conditions, confidentialité et politique de remboursement éditables par super admin.
- [ ] Auditer chaque modification de paramètre.

### Jour 2 — Consultation d’audit

- [ ] Créer la ressource de consultation du journal d’audit avec filtres acteur, type d’action, entité et période.
- [ ] Masquer les valeurs secrètes et données personnelles qui ne doivent pas apparaître.
- [ ] Rendre le journal non modifiable depuis Filament.
- [ ] Limiter l’accès à la consultation à la permission prévue.

### Jour 3 — Tableaux financiers et exports

- [ ] Créer les vues de ventes, réductions, remboursements et ventes par éditeur selon les données disponibles.
- [ ] Exporter en CSV avec contrôle d’accès et journalisation de l’export.
- [ ] Ne pas présenter de règlement de commission éditeur dans le MVP.
- [ ] Prévoir des états vides utiles tant qu’il n’y a pas encore de ventes.

### Jour 4 — Dashboard admin

- [ ] Afficher les indicateurs MVP disponibles : clients, produits publiés, commandes, revenus, panier moyen, remboursements, téléchargements.
- [ ] Ajouter filtre de période et meilleurs produits si les données le permettent.
- [ ] Alimenter les widgets depuis des agrégats plutôt que faire des scans coûteux à chaque affichage.
- [ ] Afficher explicitement l’absence de données ou l’état désactivé des paiements.

### Jour 5 — Recette back-office

- [ ] Parcourir les ressources avec les cinq rôles définis.
- [ ] Vérifier les actions interdites, les validations, les états vides et les erreurs de base de données.
- [ ] Corriger les problèmes bloquants avant d’entamer la vitrine.
- [ ] Documenter la création du premier super administrateur et la configuration locale.

**Critères de sortie :** catalogue administrable de bout en bout; accès par rôle cohérent; ressources principales auditées; paramètres et pages légales disponibles; dashboard et états vides lisibles.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 7 — Vitrine et catalogue public

**Objectif :** rendre consultables les produits publiés dans un parcours public rapide et adapté au français.

### Jour 1 — Structure boutique

- [ ] Construire le layout public GAMEHUB : navigation, recherche, accès compte, panier et pied de page.
- [ ] Prévoir les états connecté, visiteur et petit écran.
- [ ] Rendre accessibles les liens légaux et les pages d’aide prévues.

### Jour 2 — Liste catalogue

- [ ] Afficher uniquement les produits publiés et non archivés.
- [ ] Ajouter pagination de 24 produits et cartes utilisant les médias publics optimisés.
- [ ] Ajouter tri par nouveautés et prix croissant/décroissant.
- [ ] Prévoir un état vide et une gestion claire des produits récemment dépubliés.

### Jour 3 — Recherche et filtres

- [ ] Ajouter recherche plein texte sur titre et description avec PostgreSQL.
- [ ] Ajouter filtres type, catégorie, genre, plateforme, éditeur et prix.
- [ ] Conserver les filtres dans l’URL lorsque pertinent.
- [ ] Vérifier que les filtres ne divulguent pas les produits en brouillon.

### Jour 4 — Page produit

- [ ] Créer la page détaillée avec description, prix, éditeur, catégories, plateformes et exigences système.
- [ ] Afficher les previews adaptés aux produits photo/vidéo sans exposer les originaux.
- [ ] Ajouter les actions « ajouter au panier » et « ajouter à la liste de souhaits ».
- [ ] Afficher une réponse claire si le produit devient indisponible.
- [ ] Afficher la note moyenne, les avis publiés et leur fil de commentaires/réponses.
- [ ] Permettre à un client connecté de noter et commenter selon les règles métier.

### Jour 5 — Revue catalogue public

- [ ] Vérifier navigation clavier, contrastes, titres et descriptions d’images.
- [ ] Vérifier les URL, statuts HTTP des produits archivés et le cache éventuel.
- [ ] Vérifier le comportement sur téléphone et connexion lente.
- [ ] Traiter les anomalies qui empêchent le parcours de découverte.

**Critères de sortie :** visiteur peut trouver un produit publié, appliquer des filtres et consulter sa fiche sans accéder aux données privées.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 8 — Panier et liste de souhaits

**Objectif :** permettre la constitution et la conservation du panier et de la liste de souhaits.

### Jour 1 — Panier visiteur

- [ ] Créer le panier invité lié à la session, sans faire confiance à un identifiant fourni comme preuve de propriété.
- [ ] Ajouter et retirer un produit; imposer quantité égale à 1 pour les biens numériques.
- [ ] Refuser les produits non publiés et les doublons.
- [ ] Présenter un total indicatif clairement séparé du calcul définitif de commande.

### Jour 2 — Panier client

- [ ] Associer le panier au client authentifié.
- [ ] Vérifier le propriétaire côté serveur à chaque lecture et modification.
- [ ] Empêcher l’ajout d’un produit déjà possédé si l’utilisateur a une licence valide.
- [ ] Gérer les produits devenus indisponibles entre deux visites.

### Jour 3 — Fusion à la connexion

- [ ] Fusionner le panier invité dans le panier du compte après connexion.
- [ ] Dédupliquer les produits et revalider prix, disponibilité et propriété.
- [ ] Ne jamais accepter les prix ni le total envoyés par le navigateur.
- [ ] Afficher les produits retirés pendant la fusion avec un message utile.

### Jour 4 — Liste de souhaits

- [ ] Créer les actions ajouter, retirer et consulter la liste persistante du client.
- [ ] Ajouter une liste invitée si retenue; sinon afficher clairement que la connexion est requise pour persister.
- [ ] Fusionner la liste invitée à la connexion sans doublon si le choix invité est implémenté.
- [ ] Empêcher la lecture ou la modification de la liste d’un autre compte.

### Jour 5 — Revue des parcours

- [ ] Parcourir les scénarios visiteur, client connecté et client qui se connecte après ajout.
- [ ] Vérifier les produits dépubliés, possédés, supprimés ou dont le prix a changé.
- [ ] Vérifier que le total est recalculé par le serveur.
- [ ] Résoudre les anomalies de propriété des données.

**Critères de sortie :** panier et souhaits persistent selon le statut utilisateur, la fusion ne crée pas de doublons et les montants du navigateur ne font jamais autorité.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 9 — Promotions et préparation de commande

**Objectif :** mener le client jusqu’à un récapitulatif validé, sans lancer de paiement.

### Jour 1 — Application des promotions

- [ ] Ajouter la saisie et le retrait d’un code promo.
- [ ] Vérifier dates, plafond global, plafond par client, type de remise et produits applicables.
- [ ] Afficher des erreurs différentes pour code inexistant, pas encore valide, expiré, épuisé ou déjà utilisé.
- [ ] Enregistrer l’utilisation uniquement au moment métier prévu; ne pas la compter au simple affichage.

### Jour 2 — Service de calcul serveur

- [ ] Centraliser le calcul sous-total, remise, taxe incluse et total dans une Action métier.
- [ ] Stocker les montants en unités entières XOF.
- [ ] Recalculer avant d’afficher le récapitulatif; ignorer tout total envoyé par le client.
- [ ] Vérifier les limites d’arrondi et les remises supérieures au total.

### Jour 3 — Informations de compte et vérification

- [ ] Exiger une session authentifiée et un email vérifié avant de poursuivre au récapitulatif de commande.
- [ ] Charger côté serveur l’identité, l’adresse/contact requis et les données de panier.
- [ ] Permettre la correction des informations de profil par les écrans existants.
- [ ] Refuser un panier vide, expiré ou appartenant à un autre client.

### Jour 4 — Écran de checkout préparatoire

- [ ] Présenter les articles, prix recalculés, remise, taxe incluse et total.
- [ ] Afficher les conditions de vente et liens légaux.
- [ ] Présenter un état « paiement indisponible pour le moment » au lieu d’un bouton d’initiation actif.
- [ ] Ne pas créer de commande payée ni de licence à cette étape.

### Jour 5 — Vérification de la frontière de paiement

- [ ] Vérifier qu’aucun endpoint, bouton, job ou callback ne peut déclarer un paiement réussi lorsque le drapeau est désactivé.
- [ ] Vérifier les tentatives d’accès avec un identifiant de panier modifié.
- [ ] Vérifier que l’écran checkout n’expose pas des données d’un autre compte.
- [ ] Documenter précisément les conditions futures d’activation du prestataire.

**Critères de sortie :** checkout de revue fonctionnel et sécurisé; le client peut préparer son achat mais ne peut pas payer ni obtenir une commande payée.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 10 — Bibliothèque, licences et téléchargements

**Objectif :** construire le domaine de livraison et son interface, déclenchés uniquement par une commande réellement confirmée.

### Jour 1 — États commande, paiement et licence

- [ ] Formaliser les transitions autorisées d’une commande, d’une tentative de paiement et d’une licence.
- [ ] Définir l’événement interne de paiement confirmé comme seul déclencheur de délivrance.
- [ ] Garantir l’idempotence : rejouer une confirmation ne crée pas plusieurs licences.
- [ ] Maintenir l’intégration paiement inactive par défaut.

### Jour 2 — Bibliothèque client

- [ ] Créer la page bibliothèque listant les produits détenus.
- [ ] Afficher les versions et plateformes disponibles selon les licences actives.
- [ ] Prévoir les états vides pour un nouveau compte.
- [ ] Vérifier que chaque élément visible appartient au client connecté.

### Jour 3 — Autorisation de téléchargement

- [ ] Vérifier licence active, produit, version, suspension éventuelle du compte et limites quotidiennes.
- [ ] Générer une URL signée de courte durée (15 minutes maximum selon le dossier).
- [ ] Ne pas exposer une clé S3 publique ou un chemin permanent au client.
- [ ] Refuser immédiatement une licence révoquée pour les nouvelles autorisations.

### Jour 4 — Historique, quotas et administration

- [ ] Enregistrer utilisateur, version, IP et date pour chaque autorisation de téléchargement.
- [ ] Appliquer le quota quotidien configuré.
- [ ] Ajouter dans Filament une action de révocation de licence avec motif, permission et audit.
- [ ] Vérifier le comportement reprise/range du stockage retenu.

### Jour 5 — Scénarios de livraison

- [ ] Vérifier les réponses sans licence, avec licence expirée/révoquée et compte suspendu.
- [ ] Vérifier les liens expirés et les limites de téléchargement.
- [ ] Vérifier qu’un produit dépublié reste disponible aux acheteurs ayant une licence valide.
- [ ] Documenter les réglages de stockage nécessaires.

**Critères de sortie :** ressources et parcours bibliothèque prêts; aucun accès à un fichier sans autorisation serveur courte durée; délivrance impossible depuis un paiement non confirmé.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 11 — Courriels, support et architecture de paiement désactivée

**Objectif :** relier les fonctions opérationnelles et isoler l’intégration future du prestataire.

### Jour 1 — Courriels transactionnels

- [ ] Mettre en file les courriels de vérification, réinitialisation, support et événements de commande prévus.
- [ ] Vérifier langue, liens, expiration et contenu sans secrets.
- [ ] Configurer tentatives et échecs de livraison selon la queue Laravel.
- [ ] Ne pas envoyer de confirmation « achat payé » sans confirmation vérifiée.

### Jour 2 — Journal des courriels et notifications métier

- [ ] Enregistrer les statuts d’envoi nécessaires à la ressource admin Mail Log.
- [ ] Permettre le diagnostic d’un échec sans afficher les secrets ou contenus sensibles inutilement.
- [ ] Vérifier la relance sûre des tâches qui peuvent être rejouées.

### Jour 3 — Support client de bout en bout

- [ ] Permettre la création d’un ticket lié au compte et, si pertinent, à une commande.
- [ ] Afficher les messages au client et dans Filament selon les autorisations.
- [ ] Envoyer un avis de nouveau ticket/réponse sans bloquer le flux si le fournisseur mail est temporairement indisponible.
- [ ] Conserver les propositions de remboursement comme propositions tant que paiement et approbation ne sont pas activés.

### Jour 4 — Contrat abstrait de paiement

- [ ] Définir l’interface interne d’un prestataire : démarrer, vérifier, rembourser et valider la signature webhook.
- [ ] Isoler la logique spécifique à un futur prestataire dans un adaptateur dédié.
- [ ] Ajouter le drapeau désactivé par défaut et le verrou côté serveur sur l’ensemble des points d’entrée.
- [ ] Ne pas inventer ni sélectionner les paramètres d’un prestataire absent.

### Jour 5 — Préparation à l’activation future

- [ ] Définir la checklist d’activation : prestataire sélectionné, secrets configurés, URL callback, sandbox, carte et Mobile Money vérifiés, remboursements testés, approbation super admin.
- [ ] Auditer tout changement de drapeau et limiter l’action au super admin.
- [ ] S’assurer qu’environnement local, staging et production restent désactivés par défaut.
- [ ] Vérifier l’affichage historique des tentatives sans possibilité de fabriquer un succès.

**Critères de sortie :** support et courriels opérationnels selon configuration; interface paiement préparée mais inutilisable jusqu’à une activation explicitement approuvée après validation fournisseur.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

# Semaine 12 — Stabilisation, sécurité et préparation de lancement

**Objectif :** transformer le MVP développé en version vérifiable et exploitable.

### Jour 1 — Parcours complets par rôle

- [ ] Parcourir la gestion d’un produit de brouillon à publié, puis dépublié.
- [ ] Vérifier que chaque rôle peut faire uniquement les opérations attendues.
- [ ] Vérifier l’invitation, la 2FA, la suspension staff et la protection du dernier super admin.
- [ ] Corriger les défauts bloquants trouvés lors des parcours.

### Jour 2 — Parcours clients

- [ ] Parcourir inscription, vérification email, connexion, profil, panier et liste de souhaits.
- [ ] Parcourir la préparation checkout avec compte vérifié et non vérifié.
- [ ] Confirmer qu’aucune commande payée n’est créée tant que les paiements sont désactivés.
- [ ] Parcourir bibliothèque et téléchargements avec des états de licence valides et invalides.

### Jour 3 — Données, performances et accessibilité

- [ ] Vérifier les requêtes des listes Filament et storefront, pagination et chargement des relations.
- [ ] Vérifier l’indexation/recherche PostgreSQL et les états vides.
- [ ] Vérifier les formulaires avec clavier, libellés, messages et contrastes de base.
- [ ] Contrôler les limites d’upload et l’absence de fichiers privés dans le répertoire public.

### Jour 4 — Exploitation

- [ ] Vérifier la stratégie de backup base et métadonnées fichiers.
- [ ] Effectuer un exercice documenté de restauration sur un environnement non production.
- [ ] Vérifier les queues, tâches planifiées, logs, alertes d’erreur et endpoint de santé.
- [ ] Documenter les variables d’environnement, stockage, emails, Redis et tâches cron/scheduler.

### Jour 5 — Livraison de la version MVP

- [ ] Résoudre les anomalies bloquantes; reporter les améliorations non essentielles de façon tracée.
- [ ] Confirmer les migrations dans un environnement vierge et les données de référence nécessaires.
- [ ] Faire valider le parcours admin puis le parcours client par le responsable produit.
- [ ] Marquer le MVP prêt pour la recette, avec la liste des éléments encore bloqués par le choix du prestataire.
- [ ] Mettre à jour ce document et noter la prochaine étape réelle.

**Critères de sortie :** parcours principaux vérifiés, restauration documentée, configuration d’exploitation connue, paiements toujours coupés tant que la checklist fournisseur n’est pas satisfaite.

**Avancement de la semaine :** `0 / 5 jours` — **État :** ⬜ À faire — **Blocages / notes :** _à compléter_

---

## Suivi global

À mettre à jour à la fin de chaque semaine.

| Semaine | Jours terminés | Avancement | État | Date de revue | Note / décision |
|---|---:|---:|---|---|---|
| 1 | 0/5 | 0 % | ⬜ À faire | — | — |
| 2 | 0/5 | 0 % | ⬜ À faire | — | — |
| 3 | 0/5 | 0 % | ⬜ À faire | — | — |
| 4 | 0/5 | 0 % | ⬜ À faire | — | — |
| 5 | 0/5 | 0 % | ⬜ À faire | — | — |
| 6 | 0/5 | 0 % | ⬜ À faire | — | — |
| 7 | 0/5 | 0 % | ⬜ À faire | — | — |
| 8 | 0/5 | 0 % | ⬜ À faire | — | — |
| 9 | 0/5 | 0 % | ⬜ À faire | — | — |
| 10 | 0/5 | 0 % | ⬜ À faire | — | — |
| 11 | 0/5 | 0 % | ⬜ À faire | — | — |
| 12 | 0/5 | 0 % | ⬜ À faire | — | — |

## Conditions non négociables avant activation des paiements

- [ ] Prestataire choisi et contrat/compte marchand approuvé.
- [ ] Clés conservées dans l’environnement secret approprié, jamais dans le dépôt.
- [ ] Webhooks signés, idempotents et vérifiés côté serveur.
- [ ] Tests sandbox réussis pour Mobile Money et carte si ces deux moyens sont retenus.
- [ ] Vérification serveur du statut final, comportement d’échec/timeout et remboursement complet validés.
- [ ] Procédure de rapprochement et gestion des écarts documentées.
- [ ] Activation autorisée par un super administrateur et action auditée.

Tant que chaque condition applicable n’est pas remplie, le drapeau de paiement reste désactivé et aucun client ne peut initier un paiement.
