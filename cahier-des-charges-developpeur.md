
# BOUTIQUE EN LIGNE 

## 1. RÉSUMÉ DES RÔLES UTILISATEURS ==> done ce point

| Rôle                   | Authentification           | 
| ---------------------- | -------------------------- |
| **Commerçant (Admin)** | login |
| **Client (Visiteur)**  | login+signup | 
---

## 2. COMMERÇANT (ADMIN) — PAGES & FONCTIONNALITÉS

### Layout global admin ==> done 

- **Sidebar gauche** : Menu de navigation permanent
- **Header top** : Nom du commerçant, bouton déconnexion, lien vers le site public
- **Zone contenu** : Contenu de la page active
- **Responsive** : Sur mobile, la sidebar devient un menu hamburger

#### Éléments du menu sidebar :==> done

1. Dashboard
2. Commandes
3. Produits
4. Catégories
5. Clients
6. Livraison (Wilayas)
7. Marketing (Codes promo)
8. Paramètres

---

### 2.1 PAGE : Connexion Admin ==> done

**Description** : Page de connexion du commerçant.

**Contenu de la page** :

- Formulaire de connexion :
    - Champ **Email** (input email, required)
    - Champ **Mot de passe** (input password, required)
    - Checkbox **Se souvenir de moi**
    - Bouton **Se connecter**
- Message d'erreur si identifiants incorrects

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Se connecter | Authentifie le commerçant via Laravel Auth, puis redirige vers le dashboard |
| Se souvenir de moi | Crée un cookie remember_token pour session persistante |
| Validation | Email valide requis, mot de passe requis, message d'erreur clair |

---

### 2.2 PAGE : Dashboard (Accueil Admin)

**Description** : Vue d'ensemble de l'activité de la boutique.

**Contenu de la page** :

#### Section 1 — 4 Widgets (cards statistiques)

| Widget              | Donnée affichée                        | Icône |
| ------------------- | -------------------------------------- | ----- |
| Chiffre d'affaires  | Total des commandes livrées (en DA)    | 💰    |
| Commandes du jour   | Nombre de commandes créées aujourd'hui | 📦    |
| Clients             | Nombre total de clients enregistrés    | 👥    |
| Produits en rupture | Nombre de variantes avec stock = 0     | ⚠️    |

#### Section 2 — Graphique des ventes

- Graphique en barres ou courbes
- Filtrage par période : **7 derniers jours** / **30 derniers jours** (boutons toggle)
- Axe X : dates, Axe Y : montant des ventes (DA)

#### Section 3 — Top 5 produits vendus

- Tableau : Rang | Produit | Quantité vendue | Revenu total

#### Section 4 — Dernières commandes (10 dernières)

- Tableau : # | Client | Montant | Statut (badge couleur) | Wilaya | Date
- Chaque ligne est cliquable → redirige vers le détail de la commande

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Changer période graphique | Toggle 7j/30j, recharge les données du graphique (Livewire) |

---

### 2.3 PAGE : Liste des Produits ==> done

**Description** : Affiche tous les produits avec filtres et actions.

**Contenu de la page** :

#### Barre d'actions

- Bouton **+ Ajouter un produit** → ouvre le formulaire de création produit
- Champ **Recherche** (par nom du produit, temps réel Livewire)

#### Filtres (sidebar ou inline)

| Filtre    | Type                           | Options                            |
| --------- | ------------------------------ | ---------------------------------- |
| Catégorie | Select dropdown                | Liste des catégories               |
| Statut    | Select dropdown                | Actif / Inactif / Tous             |
| Stock     | Select dropdown                | En stock / Rupture de stock / Tous |
| Marque    | Select dropdown                | Liste des marques existantes       |
| Prix      | Range slider ou inputs min/max | Min DA — Max DA                    |

#### Tableau des produits

| Colonne      | Contenu                                                               |
| ------------ | --------------------------------------------------------------------- |
| Image        | Miniature de l'image principale du produit                            |
| Nom          | Nom du produit (cliquable → page édition)                             |
| Catégorie    | Nom de la catégorie                                                   |
| Prix de base | Prix affiché en DA                                                    |
| Stock total  | Somme des stocks de toutes les variantes                              |
| Statut       | Badge Actif (vert) / Inactif (gris)                                   |
| Actions      | Boutons : **Modifier** (icône crayon), **Supprimer** (icône poubelle) |

#### Pagination

- 15 produits par page
- Navigation : Précédent / Suivant / Numéros de pages

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Ajouter un produit | Ouvre le formulaire de création produit |
| Rechercher | Filtre en temps réel par nom (Livewire, debounce 300ms) |
| Filtrer | Applique les filtres, recharge le tableau (Livewire, sans rechargement page) |
| Réinitialiser filtres | Bouton pour remettre tous les filtres à zéro |
| Modifier un produit | Ouvre le formulaire de modification du produit sélectionné |
| Supprimer un produit | Modal de confirmation : "Êtes-vous sûr de vouloir supprimer ce produit ?" → Supprime le produit + ses variantes + ses images (cascade) |
| Changer statut rapide | Toggle actif/inactif directement depuis le tableau (Livewire) |
| Trier | Cliquer sur l'en-tête de colonne pour trier (Nom, Prix, Stock, Date) |

---

Créer / Modifier un Produit

**Description** : Formulaire complet de création ou modification d'un produit avec gestion des variantes.

**Contenu de la page** :

#### Section 1 — Informations générales

| Champ          | Type                               | Validation                          |
| -------------- | ---------------------------------- | ----------------------------------- |
| Nom du produit | Input text                         | Required, max 255 caractères        |
| Description    | Textarea (éditeur riche optionnel) | Nullable                            |
| Catégorie      | Select dropdown                    | Nullable, liste des catégories      |
| Marque         | Input text                         | Nullable, max 100 caractères        |
| Prix de base   | Input number                       | Required, min 0, décimal 2 chiffres |
| Statut         | Toggle switch                      | Actif / Inactif (défaut : Actif)    |

#### Section 2 — Images du produit

- Zone d'upload drag & drop (ou clic pour sélectionner)
- **Upload multiple** : Jusqu'à 10 images par produit
- Affichage des images uploadées en miniatures
- **Réordonner** : Glisser-déposer pour changer l'ordre (sort_order)
- **Supprimer une image** : Bouton × sur chaque miniature
- **Image principale** : La première image dans l'ordre = image principale
- Formats acceptés : JPG, PNG, WEBP
- Taille max par image : 2 Mo

#### Section 3 — Variantes du produit (UI dynamique Livewire)

**Étape 1 — Sélection des attributs** :

- Checkboxes pour activer les types d'attributs : ☑ Taille | ☑ Couleur | ☑ Pointure
- Pour chaque attribut activé, saisir les valeurs :
    - Taille : Tags input → S, M, L, XL (ajouter/supprimer des tags)
    - Couleur : Tags input → Noir, Blanc, Rouge (ajouter/supprimer des tags)
    - Pointure : Tags input → 39, 40, 41, 42 (ajouter/supprimer des tags)

**Étape 2 — Génération automatique des combinaisons** :

- Bouton **Générer les variantes**
- Le système crée le produit cartésien de toutes les valeurs sélectionnées
- Exemple : Couleur (Noir, Blanc) × Pointure (42, 43) → 4 variantes

**Étape 3 — Tableau des variantes générées** :
| Colonne | Type | Détail |
|---------|------|--------|
| Combinaison | Texte (lecture seule) | Ex: "Noir / 42" |
| SKU | Input text | Auto-généré mais modifiable, unique |
| Prix | Input number | Pré-rempli avec prix de base, modifiable |
| Stock | Input number | Défaut 0, min 0 |
| Action | Bouton | **Supprimer** cette variante |

- Bouton **Ajouter une variante manuellement** (pour cas spéciaux)
- Bouton **Appliquer un prix à toutes** (remplir le même prix pour toutes les variantes)
- Bouton **Appliquer un stock à toutes** (remplir le même stock pour toutes les variantes)

#### Boutons du formulaire

- **Enregistrer** → Sauvegarde le produit + images + variantes
- **Annuler** → Retour à la liste sans sauvegarder

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Créer le produit | Valide le formulaire, crée le produit en BDD, upload les images, crée les variantes, redirige vers la liste avec message succès |
| Modifier le produit | Valide, met à jour le produit, gère les images ajoutées/supprimées, met à jour/ajoute/supprime les variantes |
| Ajouter une image | Upload immédiat ou à la soumission, aperçu instantané |
| Supprimer une image | Supprime l'image du stockage et de la BDD |
| Réordonner images | Drag & drop, met à jour le sort_order |
| Activer un attribut | Affiche le champ de saisie des valeurs pour cet attribut |
| Désactiver un attribut | Supprime les valeurs et recalcule les variantes |
| Ajouter une valeur d'attribut | Ajoute un tag, recalcule les combinaisons possibles |
| Supprimer une valeur d'attribut | Supprime le tag, supprime les variantes concernées |
| Générer les variantes | Crée toutes les combinaisons, affiche le tableau |
| Modifier SKU/Prix/Stock d'une variante | Édition inline dans le tableau |
| Supprimer une variante | Supprime la ligne du tableau |
| Appliquer prix à toutes | Remplit le même prix pour toutes les variantes |
| Appliquer stock à toutes | Remplit le même stock pour toutes les variantes |
| Auto-génération SKU | Format : `{MARQUE}-{COULEUR}-{TAILLE}` en majuscules |
| Validation | Vérifie SKU uniques, prix > 0, stock >= 0, au moins 1 variante |

---

### 2.5 PAGE : Liste des Commandes ==> done 

**Description** : Affiche toutes les commandes avec filtres et gestion des statuts.

**Contenu de la page** :

#### Barre d'actions

- Champ **Recherche** (par nom client, téléphone, numéro de commande)
- Bouton **Exporter** (optionnel : export CSV/Excel des commandes filtrées)

#### Filtres

| Filtre           | Type              | Options                                                     |
| ---------------- | ----------------- | ----------------------------------------------------------- |
| Statut           | Select dropdown   | Tous / En attente / Confirmée / Expédiée / Livrée / Annulée |
| Wilaya           | Select dropdown   | Liste des 58 wilayas                                        |
| Date             | Date range picker | Du — Au                                                     |
| Méthode paiement | Select dropdown   | Tous / COD / BaridiMob / CIB                                |

#### Compteurs rapides (badges au-dessus du tableau)

- En attente : **X** | Confirmées : **X** | Expédiées : **X** | Livrées : **X** | Annulées : **X**
- Cliquer sur un badge = filtre rapide par ce statut

#### Tableau des commandes

| Colonne   | Contenu                                                                                        |
| --------- | ---------------------------------------------------------------------------------------------- |
| #         | Numéro de commande (cliquable → détail)                                                        |
| Client    | Nom du client                                                                                  |
| Téléphone | Numéro de téléphone                                                                            |
| Wilaya    | Nom de la wilaya                                                                               |
| Total     | Montant total en DA (produits + livraison)                                                     |
| Statut    | Badge couleur : pending=jaune, confirmed=bleu, shipped=violet, delivered=vert, cancelled=rouge |
| Date      | Date de création (format : dd/mm/yyyy HH:mm)                                                   |
| Actions   | Bouton **Voir** (icône œil), boutons changement de statut rapide                               |

#### Pagination

- 20 commandes par page

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Rechercher | Recherche en temps réel par nom, téléphone ou numéro commande (Livewire) |
| Filtrer par statut | Filtre le tableau, mise à jour sans rechargement |
| Filtrer par wilaya | Filtre par wilaya sélectionnée |
| Filtrer par date | Filtre par plage de dates |
| Cliquer sur badge statut | Filtre rapide par ce statut |
| Voir une commande | Ouvre la page de détail de la commande |
| Changement statut rapide | Select dropdown inline sur chaque ligne : changer le statut directement depuis le tableau (Livewire) |
| Exporter CSV | Génère un fichier CSV des commandes filtrées (colonnes : #, Client, Téléphone, Wilaya, Total, Statut, Date) |
| Trier | Par date, total, statut (clic sur en-tête colonne) |

---

### 2.6 PAGE : Détail d'une Commande==> done 

**Description** : Affiche toutes les informations d'une commande avec possibilité de la gérer.

**Contenu de la page** :

#### Section 1 — En-tête commande

- Numéro de commande : **#1234**
- Date de création : **dd/mm/yyyy à HH:mm**
- Statut actuel : Badge couleur
- **Select changement de statut** : Dropdown → Changer le statut + Bouton **Confirmer**

#### Section 2 — Informations client

| Champ     | Valeur                       |
| --------- | ---------------------------- |
| Nom       | Nom complet du client        |
| Téléphone | Numéro cliquable (lien tel:) |
| Email     | Email si disponible          |
| Wilaya    | Nom de la wilaya             |
| Adresse   | Adresse complète             |

#### Section 3 — Produits commandés

| Colonne       | Contenu              |
| ------------- | -------------------- |
| Image         | Miniature du produit |
| Produit       | Nom du produit       |
| Variante      | Ex: "Noir / 42"      |
| SKU           | SKU de la variante   |
| Prix unitaire | Prix en DA           |
| Quantité      | Quantité commandée   |
| Sous-total    | Prix × Quantité      |

#### Section 4 — Récapitulatif financier

| Ligne               | Montant                                 |
| ------------------- | --------------------------------------- |
| Sous-total produits | Somme des sous-totaux                   |
| Frais de livraison  | Coût livraison wilaya                   |
| Code promo appliqué | -X DA (si applicable, afficher le code) |
| **Total**           | **Montant final en DA**                 |

#### Section 5 — Livraison

| Champ                            | Type                                   | Détail                        |
| -------------------------------- | -------------------------------------- | ----------------------------- |
| Transporteur                     | Select dropdown                        | Yalidine / ZR Express / Autre |
| Numéro de tracking               | Input text                             | Saisie libre                  |
| Bouton **Enregistrer livraison** | Sauvegarde les infos de livraison      |
| Statut livraison                 | Badge : En attente / Expédiée / Livrée |

#### Section 6 — Notes internes

- Textarea pour ajouter des notes internes sur la commande
- Bouton **Sauvegarder la note**

#### Section 7 — Historique (optionnel)

- Timeline des changements de statut : Date + Ancien statut → Nouveau statut

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Changer le statut | Sélectionner nouveau statut + confirmer. Met à jour en BDD. Si "shipped" → demande le transporteur + tracking |
| Enregistrer livraison | Sauvegarde transporteur + tracking dans la table `shipments` |
| Sauvegarder note | Enregistre la note dans le champ `notes` de la commande |
| Annuler la commande | Bouton rouge : Modal de confirmation → Passe le statut à "cancelled", **restaure le stock des variantes** |
| Confirmer la commande | Passe de "pending" à "confirmed" |
| Marquer comme expédiée | Passe à "shipped", crée/met à jour l'entrée dans `shipments` |
| Marquer comme livrée | Passe à "delivered", enregistre `delivered_at`, crée l'entrée dans `payments` (si COD) |
| Imprimer la commande | Bouton **Imprimer** → Ouvre une version imprimable (bon de commande) |
| Retour à la liste | Bouton **← Retour aux commandes** |

---

### 2.7 PAGE : Liste des Catégories ==> done 

**Description** : Gestion des catégories de produits (hiérarchie parent/enfant).

**Contenu de la page** :

#### Barre d'actions

- Bouton **+ Ajouter une catégorie**
- Champ **Recherche** par nom de catégorie

#### Affichage

- **Vue arborescente** (tree view) montrant la hiérarchie :
    ```
    📁 Chaussures
       ├── 👟 Chaussures Homme
       ├── 👠 Chaussures Femme
    📁 Vêtements
       ├── 👕 T-shirts
       ├── 👖 Pantalons
    ```
- Chaque catégorie affiche : Nom | Nombre de produits | Actions

#### Modal / Formulaire Ajout-Modification

| Champ               | Type            | Validation                                                             |
| ------------------- | --------------- | ---------------------------------------------------------------------- |
| Nom de la catégorie | Input text      | Required, max 255, unique                                              |
| Catégorie parente   | Select dropdown | Nullable (si vide = catégorie racine), liste des catégories existantes |

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Ajouter une catégorie | Ouvre un modal avec le formulaire. Crée la catégorie en BDD |
| Modifier une catégorie | Ouvre le modal pré-rempli. Met à jour le nom et/ou le parent |
| Supprimer une catégorie | Modal de confirmation. Si la catégorie a des produits : "Cette catégorie contient X produits. Les produits seront dissociés." Supprime la catégorie, les produits passent à `category_id = NULL`. Les sous-catégories passent à `parent_id = NULL` |
| Rechercher | Filtre l'arborescence par nom (Livewire) |
| Voir les produits | Lien vers la liste produits filtrée par cette catégorie |

---

### 2.8 PAGE : Liste des Clients

**Description** : Affiche tous les clients enregistrés (créés automatiquement lors des commandes).

**Contenu de la page** :

#### Barre d'actions

- Champ **Recherche** (par nom, téléphone, email)

#### Tableau des clients

| Colonne           | Contenu                             |
| ----------------- | ----------------------------------- |
| Nom               | Nom du client (cliquable → détail)  |
| Téléphone         | Numéro de téléphone                 |
| Email             | Email (ou "—" si non renseigné)     |
| Wilaya            | Wilaya du client                    |
| Nb commandes      | Nombre total de commandes passées   |
| Total dépensé     | Somme des commandes livrées (en DA) |
| Dernière commande | Date de la dernière commande        |
| Actions           | Bouton **Voir**                     |

#### Pagination

- 20 clients par page

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Rechercher | Recherche temps réel par nom, téléphone ou email (Livewire) |
| Voir un client | Ouvre la fiche détaillée du client |
| Trier | Par nom, nb commandes, total dépensé, dernière commande |

---

### 2.9 PAGE : Détail d'un Client

**Description** : Fiche complète d'un client avec son historique.

**Contenu de la page** :

#### Section 1 — Informations client

| Champ         | Valeur                    |
| ------------- | ------------------------- |
| Nom           | Nom complet               |
| Téléphone     | Numéro (lien tel:)        |
| Email         | Email ou "—"              |
| Wilaya        | Nom de la wilaya          |
| Adresse       | Adresse complète          |
| Client depuis | Date de première commande |

#### Section 2 — Statistiques client (cards)

| Stat              | Valeur                          |
| ----------------- | ------------------------------- |
| Total commandes   | Nombre de commandes             |
| Total dépensé     | Somme en DA (commandes livrées) |
| Panier moyen      | Moyenne par commande            |
| Dernière commande | Date                            |

#### Section 3 — Historique des commandes

- Tableau : # | Date | Produits | Total | Statut (badge couleur)
- Chaque ligne cliquable → détail commande

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Voir une commande | Ouvre la page de détail de la commande |
| Retour à la liste | Bouton **← Retour aux clients** |

---

### 2.10 PAGE : Gestion Livraison (Wilayas)

**Description** : Configuration des frais de livraison par wilaya.

**Contenu de la page** :

#### Barre d'actions

- Champ **Recherche** par nom de wilaya
- Bouton **Appliquer un tarif global** (applique le même prix à toutes les wilayas)

#### Tableau des 58 wilayas

| Colonne        | Contenu                                                   |
| -------------- | --------------------------------------------------------- |
| Code           | Numéro de la wilaya (01-58)                               |
| Nom            | Nom de la wilaya                                          |
| Prix livraison | Input number éditable inline (en DA)                      |
| Statut         | Toggle : Livraison activée / désactivée pour cette wilaya |
| Actions        | Bouton **Sauvegarder** (si modifié)                       |

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Modifier un prix | Édition inline du prix, bouton sauvegarder par ligne (Livewire) |
| Appliquer tarif global | Modal : Saisir un prix → Applique à toutes les wilayas |
| Activer/Désactiver une wilaya | Toggle switch. Si désactivée, la wilaya n'apparaît plus au checkout |
| Rechercher | Filtre par nom de wilaya |
| Sauvegarder tout | Bouton global pour sauvegarder toutes les modifications en une fois |

---

### 2.11 PAGE : Marketing (Codes Promo)

**Description** : Gestion des codes promotionnels.

**Contenu de la page** :

#### Barre d'actions

- Bouton **+ Créer un code promo**
- Champ **Recherche** par code

#### Tableau des codes promo

| Colonne          | Contenu                                              |
| ---------------- | ---------------------------------------------------- | ------------- | ---------------------- |
| Code             | Le code promo (ex: SOLDES2026)                       |
| Type             | Badge : Pourcentage (%) ou Montant fixe (DA)         |
| Valeur           | Montant de la réduction                              |
| Commande minimum | Montant min de commande pour appliquer               |
| Utilisations     | Utilisé X / Max Y (ou illimité)                      |
| Expiration       | Date d'expiration (ou "Pas d'expiration")            |
| Statut           | Badge Actif (vert) / Expiré (rouge) / Inactif (gris) |
| Actions          | **Modifier**                                         | **Supprimer** | **Activer/Désactiver** |

#### Modal / Formulaire Ajout-Modification

| Champ                     | Type                               | Validation                                |
| ------------------------- | ---------------------------------- | ----------------------------------------- |
| Code promo                | Input text                         | Required, unique, max 50, majuscules auto |
| Type de réduction         | Radio : Pourcentage / Montant fixe | Required                                  |
| Valeur                    | Input number                       | Required, > 0. Si pourcentage : max 100   |
| Commande minimum          | Input number                       | Défaut 0, min 0                           |
| Nombre max d'utilisations | Input number                       | Nullable (vide = illimité)                |
| Date d'expiration         | Date picker                        | Nullable (vide = pas d'expiration)        |
| Statut actif              | Toggle switch                      | Défaut : Actif                            |

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Créer un code promo | Ouvre modal, valide et crée en BDD |
| Modifier un code promo | Ouvre modal pré-rempli, met à jour |
| Supprimer un code promo | Modal de confirmation → Supprime. Les commandes passées conservent la réduction appliquée |
| Activer / Désactiver | Toggle rapide dans le tableau |
| Rechercher | Filtre par code (Livewire) |
| Vérification auto | Un code expiré passe automatiquement en statut "Expiré" |

---

### 2.12 PAGE : Paramètres de la Boutique

**Description** : Configuration générale de la boutique.

**Contenu de la page** :

#### Section 1 — Informations générales

| Champ              | Type                     | Validation                       |
| ------------------ | ------------------------ | -------------------------------- |
| Nom de la boutique | Input text               | Required, max 255                |
| Logo               | Upload image (1 fichier) | Nullable, JPG/PNG/WEBP, max 1 Mo |
| Téléphone          | Input text               | Nullable, max 20                 |
| Email de contact   | Input email              | Nullable                         |
| Adresse            | Textarea                 | Nullable                         |

#### Section 2 — Apparence

| Champ                | Type         | Validation                           |
| -------------------- | ------------ | ------------------------------------ |
| Couleur principale   | Color picker | Défaut #3B82F6, format hex           |
| Aperçu en temps réel | Preview      | Montre l'effet de la couleur choisie |

#### Section 3 — Paiement

| Champ                         | Type                    | Détail                                  |
| ----------------------------- | ----------------------- | --------------------------------------- |
| Paiement à la livraison (COD) | Toggle (toujours actif) | Obligatoire, ne peut pas être désactivé |
| BaridiMob                     | Toggle                  | Activer/Désactiver                      |
| CIB                           | Toggle                  | Activer/Désactiver                      |

#### Section 4 — Conditions de vente

| Champ               | Type                               | Validation                           |
| ------------------- | ---------------------------------- | ------------------------------------ |
| Conditions de vente | Textarea (éditeur riche optionnel) | Nullable, affiché sur le site public |

#### Bouton

- **Enregistrer les modifications** → Sauvegarde tous les paramètres

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Modifier les paramètres | Enregistre toutes les modifications en BDD (table `settings`) |
| Uploader le logo | Upload + aperçu. Remplace l'ancien logo si existant |
| Supprimer le logo | Bouton × pour retirer le logo |
| Changer la couleur | Color picker, aperçu en direct |
| Activer/Désactiver paiement | Toggle les méthodes de paiement visibles au checkout |

---

### 2.13 PAGE : Mon Profil / Changer mot de passe

**Description** : Le commerçant peut modifier son profil et son mot de passe.

**Contenu de la page** :

#### Section 1 — Informations du compte

| Champ | Type        | Validation                |
| ----- | ----------- | ------------------------- |
| Nom   | Input text  | Required, max 255         |
| Email | Input email | Required, unique, max 255 |

#### Section 2 — Changer le mot de passe

| Champ                             | Type           | Validation                  |
| --------------------------------- | -------------- | --------------------------- |
| Mot de passe actuel               | Input password | Required pour confirmer     |
| Nouveau mot de passe              | Input password | Required, min 8 caractères  |
| Confirmer le nouveau mot de passe | Input password | Required, doit correspondre |

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Modifier le profil | Met à jour nom et email |
| Changer le mot de passe | Vérifie l'ancien mot de passe, hash le nouveau, met à jour |
| Déconnexion | Bouton dans le header → Détruit la session, puis ouvre la page de connexion |

---

## 3. CLIENT (VISITEUR / ACHETEUR) — PAGES & FONCTIONNALITÉS

> **Note** : Le client n'a PAS de compte. Il navigue librement et passe commande en remplissant un formulaire. Le panier est stocké en **session** (côté serveur) ou **localStorage** (côté client).

---

### 3.1 PAGE : Accueil

**Description** : Page vitrine de la boutique.

**Contenu de la page** :

#### Header (présent sur toutes les pages)

- Logo de la boutique (lien vers l'accueil)
- Menu de navigation : Accueil | Catalogue | Catégories (dropdown)
- Icône panier avec **badge compteur** (nombre d'articles dans le panier)
- Sur mobile : Menu hamburger

#### Section 1 — Bannière promotionnelle

- Image grande largeur (slider ou image fixe)
- Texte promotionnel + bouton CTA "Voir les produits"

#### Section 2 — Catégories

- Grille de cards : Image de catégorie | Nom de la catégorie
- Chaque card filtre le catalogue sur la catégorie sélectionnée

#### Section 3 — Produits populaires (8 produits)

- Grille de cards produits (4 colonnes desktop, 2 colonnes mobile) :
    - Image principale du produit
    - Nom du produit
    - Prix (à partir de X DA si variantes avec prix différents)
    - Badge "Rupture de stock" si toutes les variantes sont à stock 0
- Chaque card ouvre la fiche du produit sélectionné

#### Footer (présent sur toutes les pages)

- Nom de la boutique
- Téléphone de contact
- Email
- Lien vers les conditions de vente
- "© 2026 — Nom de la boutique"

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Cliquer sur une catégorie | Redirige vers le catalogue filtré par cette catégorie |
| Cliquer sur un produit | Redirige vers la page produit |
| Cliquer sur l'icône panier | Ouvre la page panier |

---

### 3.2 PAGE : Catalogue (Liste des produits)

**Description** : Affiche tous les produits de la boutique avec filtres.

**Contenu de la page** :

#### Barre de recherche

- Champ **Recherche** par nom de produit (Livewire, temps réel)

#### Filtres (sidebar sur desktop, drawer sur mobile)

| Filtre        | Type                           | Détail                                    |
| ------------- | ------------------------------ | ----------------------------------------- |
| Catégorie     | Checkboxes                     | Liste des catégories (multi-sélection)    |
| Prix          | Range slider ou inputs min/max | Filtre par plage de prix                  |
| Taille        | Checkboxes                     | S, M, L, XL... (valeurs existantes)       |
| Couleur       | Boutons couleurs ou checkboxes | Valeurs existantes                        |
| Pointure      | Checkboxes                     | Valeurs existantes                        |
| Disponibilité | Checkbox                       | Afficher uniquement les produits en stock |

#### Tri

- Select dropdown : Pertinence | Prix croissant | Prix décroissant | Plus récent

#### Grille de produits

- 4 colonnes (desktop), 2 colonnes (mobile)
- Card produit :
    - Image principale
    - Nom du produit
    - Prix (à partir de X DA)
    - Badge "Nouveau" si créé dans les 7 derniers jours
    - Badge "Rupture" si stock = 0

#### Pagination

- 16 produits par page
- Boutons Précédent / Suivant

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Rechercher | Filtre en temps réel par nom (Livewire, debounce 300ms) |
| Appliquer les filtres | Les filtres se combinent (ET logique), mise à jour sans rechargement |
| Changer le tri | Réordonne les produits selon le critère choisi |
| Cliquer sur un produit | Ouvre la fiche du produit sélectionné |
| Réinitialiser les filtres | Bouton pour tout remettre à zéro |
| URL avec paramètres | Les filtres sont reflétés dans l'URL pour partage |

---

### 3.3 PAGE : Détail Produit

**Description** : Fiche complète d'un produit avec sélection de variante et ajout au panier.

**Contenu de la page** :

#### Section 1 — Galerie d'images

- Image principale grande
- Miniatures en dessous (cliquables pour changer l'image principale)
- Zoom au survol (desktop)
- Swipe sur mobile

#### Section 2 — Informations produit

| Élément        | Détail                                                                    |
| -------------- | ------------------------------------------------------------------------- |
| Nom du produit | Titre H1                                                                  |
| Prix           | Prix de la variante sélectionnée (mise à jour dynamique)                  |
| Description    | Texte descriptif du produit                                               |
| Catégorie      | Lien vers le catalogue filtré                                             |
| Marque         | Si renseignée                                                             |
| Disponibilité  | "En stock" (vert) ou "Rupture de stock" (rouge) selon la variante choisie |

#### Section 3 — Sélection de variante (dynamique Livewire)

Pour chaque type d'attribut du produit, afficher un sélecteur :

| Attribut | Type d'UI                                 | Comportement                    |
| -------- | ----------------------------------------- | ------------------------------- |
| Couleur  | Boutons visuels (ronds colorés ou labels) | Cliquer sélectionne la couleur  |
| Taille   | Boutons (S, M, L, XL)                     | Cliquer sélectionne la taille   |
| Pointure | Boutons (39, 40, 41, 42)                  | Cliquer sélectionne la pointure |

**Logique de sélection** :

- Quand le client sélectionne une combinaison, le système identifie la variante correspondante
- Le **prix se met à jour** selon la variante
- Le **stock se met à jour** ("En stock" / "Rupture")
- Les options indisponibles (variantes à stock 0) sont **grisées et non cliquables**
- Si la combinaison n'existe pas → Message "Cette combinaison n'est pas disponible"

#### Section 4 — Quantité et ajout au panier

| Élément                           | Type                          | Détail                                                                                                       |
| --------------------------------- | ----------------------------- | ------------------------------------------------------------------------------------------------------------ |
| Quantité                          | Input number avec boutons +/- | Min 1, Max = stock de la variante                                                                            |
| Bouton **Ajouter au panier**      | Bouton principal              | Ajoute la variante sélectionnée + quantité au panier                                                         |
| Bouton **Commander via WhatsApp** | Bouton secondaire (vert)      | Ouvre WhatsApp avec message pré-rempli : "Bonjour, je souhaite commander {Produit} — {Variante} — {Prix} DA" |

#### Section 5 — Produits similaires (optionnel)

- 4 produits de la même catégorie
- Même format card que le catalogue

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Sélectionner un attribut | Met à jour le prix, le stock et le statut de disponibilité (Livewire) |
| Changer la quantité | Boutons +/- ou saisie directe. Ne peut pas dépasser le stock |
| Ajouter au panier | Ajoute variante_id + quantité en session. Notification toast "Produit ajouté au panier". Met à jour le badge compteur du panier dans le header |
| Commander via WhatsApp | `window.open('https://wa.me/{NUMERO}?text={MESSAGE_ENCODE}')` |
| Cliquer sur miniature image | Change l'image principale affichée |
| Cliquer sur produit similaire | Redirige vers la fiche de ce produit |

---

### 3.4 PAGE : Panier

**Description** : Récapitulatif des articles avant la commande.

**Contenu de la page** :

#### État vide

- Message : "Votre panier est vide"
- Bouton "Continuer mes achats" → redirige vers le catalogue

#### Tableau / Liste des articles

| Colonne       | Contenu                                            |
| ------------- | -------------------------------------------------- |
| Image         | Miniature du produit                               |
| Produit       | Nom du produit + détail variante (ex: "Noir / 42") |
| Prix unitaire | Prix de la variante en DA                          |
| Quantité      | Input number avec boutons +/- (modifiable)         |
| Sous-total    | Prix × Quantité                                    |
| Action        | Bouton **Supprimer** (icône poubelle)              |

#### Récapitulatif

| Ligne            | Montant                       |
| ---------------- | ----------------------------- |
| Sous-total       | Somme de tous les sous-totaux |
| Livraison        | "Calculée à l'étape suivante" |
| **Total estimé** | Sous-total (sans livraison)   |

#### Boutons

- **Continuer mes achats** → Redirige vers le catalogue
- **Passer la commande** → Ouvre l'étape de checkout

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Modifier la quantité | Boutons +/- ou saisie. Min 1, Max = stock de la variante. Mise à jour du sous-total et total (Livewire) |
| Supprimer un article | Supprime l'article du panier (session). Mise à jour du total et du badge compteur |
| Vider le panier | Bouton "Vider le panier" → Modal de confirmation → Supprime tous les articles |
| Vérification stock | À chaque visite du panier, vérifier que le stock est toujours suffisant. Si stock insuffisant → Message d'avertissement + ajustement auto de la quantité |
| Passer la commande | Vérifie que le panier n'est pas vide, redirige vers le checkout |

---

### 3.5 PAGE : Checkout (Passer commande)

**Description** : Formulaire simplifié de commande, optimisé pour le marché algérien.

**Contenu de la page** :

#### Section 1 — Récapitulatif commande (résumé du panier)

- Liste des articles : Produit + Variante | Qté | Prix
- Compact, non modifiable (lien "Modifier le panier" pour revenir au panier)

#### Section 2 — Informations de livraison

| Champ            | Type             | Validation                                                                    |
| ---------------- | ---------------- | ----------------------------------------------------------------------------- |
| Nom complet      | Input text       | Required, max 255                                                             |
| Téléphone        | Input text (tel) | Required, format algérien (0X XX XX XX XX), max 20                            |
| Email            | Input email      | Nullable (optionnel)                                                          |
| Wilaya           | Select dropdown  | Required, liste des 58 wilayas (uniquement celles activées par le commerçant) |
| Adresse complète | Textarea         | Required, min 10 caractères                                                   |

#### Section 3 — Frais de livraison (calcul dynamique)

- Quand le client sélectionne une wilaya, **le frais de livraison s'affiche automatiquement** (Livewire)
- Affichage : "Livraison vers {Wilaya} : **XXX DA**"

#### Section 4 — Code promo (optionnel)

- Input text + Bouton **Appliquer**
- Si valide : Affiche la réduction : "Code {CODE} appliqué : -XXX DA"
- Si invalide : Message d'erreur rouge : "Code promo invalide ou expiré"

#### Section 5 — Méthode de paiement

- Radio buttons :
    - ◉ Paiement à la livraison (COD) — toujours affiché
    - ○ BaridiMob — affiché si activé dans les paramètres
    - ○ CIB — affiché si activé dans les paramètres

#### Section 6 — Récapitulatif final

| Ligne                | Montant                     |
| -------------------- | --------------------------- |
| Sous-total produits  | Somme des articles          |
| Livraison            | Prix livraison de la wilaya |
| Réduction code promo | -XXX DA (si applicable)     |
| **Total à payer**    | **Montant final en DA**     |

#### Bouton

- **Confirmer la commande** (gros bouton vert)

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Remplir le formulaire | Saisie des informations client |
| Sélectionner la wilaya | Calcule et affiche les frais de livraison (Livewire) |
| Appliquer un code promo | Vérifie : code existe + actif + non expiré + max_uses non atteint + commande ≥ min_order. Si OK, applique la réduction |
| Choisir méthode paiement | Sélectionne la méthode (stockée dans la commande) |
| Confirmer la commande | **Validation complète** : 1) Vérifie les champs obligatoires. 2) Vérifie le stock de chaque variante (encore disponible). 3) Crée le client dans `customers` (ou retrouve par téléphone si existant). 4) Crée la commande dans `orders`. 5) Crée les lignes dans `order_items`. 6) **Décrémente le stock** de chaque variante. 7) Incrémente `used_count` du code promo si utilisé. 8) Vide le panier (session). 9) Redirige vers la page de confirmation |
| Validation échouée | Affiche les erreurs sous chaque champ concerné |
| Stock insuffisant | Si entre-temps le stock a changé → Message "Le produit X n'est plus disponible en quantité suffisante" |

---

### 3.6 PAGE : Confirmation de Commande

**Description** : Page affichée après une commande réussie.

**Contenu de la page** :

- Message de succès : "✅ Votre commande a été enregistrée avec succès !"
- Numéro de commande : **#1234**
- Récapitulatif :
    - Produits commandés
    - Total payé
    - Adresse de livraison
    - Méthode de paiement
- Message : "Vous serez contacté par téléphone pour la confirmation."
- Bouton **Retour à l'accueil**
- Bouton **Continuer mes achats**

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Afficher le récapitulatif | Données en lecture seule |
| Sécurité | La page n'est accessible que si l'utilisateur vient de passer cette commande (vérification session). Sinon, redirection vers l'accueil |

---

### 3.7 PAGE : Suivi de Commande (optionnel)

**Description** : Le client peut suivre sa commande avec son numéro de téléphone.

**Contenu de la page** :

#### Formulaire de recherche

| Champ                 | Type       | Validation |
| --------------------- | ---------- | ---------- |
| Numéro de téléphone   | Input text | Required   |
| Numéro de commande    | Input text | Required   |
| Bouton **Rechercher** |            |            |

#### Résultat

- Si trouvée :
    - Numéro de commande
    - Statut actuel (badge couleur)
    - Timeline visuelle : En attente → Confirmée → Expédiée → Livrée
    - Transporteur et numéro de tracking (si expédiée)
- Si non trouvée :
    - Message "Aucune commande trouvée avec ces informations"

**Fonctionnalités** :
| Action | Détail |
|--------|--------|
| Rechercher | Cherche dans `orders` par numéro de commande + téléphone du client associé |
| Afficher le suivi | Montre le statut actuel et la progression |

---

### 3.8 PAGE : Conditions de Vente

**Description** : Affiche les conditions de vente rédigées par le commerçant.

**Contenu de la page** :

- Titre "Conditions de vente"
- Contenu texte depuis la table `settings` (champ `terms`)
- Si vide : "Aucune condition de vente n'a été définie."

---

## 4. RÈGLES MÉTIER GLOBALES

### Gestion du stock

| Règle          | Détail                                                                                |
| -------------- | ------------------------------------------------------------------------------------- |
| Décrémentation | Le stock est décrémenté au moment de la confirmation de commande                      |
| Restauration   | Le stock est restauré si la commande est annulée                                      |
| Rupture        | Si stock = 0, la variante est marquée "Rupture" et ne peut pas être ajoutée au panier |
| Vérification   | Le stock est vérifié au moment du checkout (double vérification)                      |

### Gestion des clients

| Règle                | Détail                                                                                 |
| -------------------- | -------------------------------------------------------------------------------------- |
| Identification       | Le client est identifié par son numéro de téléphone (unique)                           |
| Création automatique | Si le téléphone n'existe pas dans `customers`, un nouveau client est créé              |
| Mise à jour          | Si le téléphone existe déjà, les informations (nom, adresse, wilaya) sont mises à jour |

### Gestion des commandes

| Règle          | Détail                                                                         |
| -------------- | ------------------------------------------------------------------------------ |
| Flux de statut | pending → confirmed → shipped → delivered. À tout moment → cancelled           |
| Annulation     | Seules les commandes `pending` ou `confirmed` peuvent être annulées            |
| Livraison      | Le statut passe à `shipped` uniquement si un transporteur est renseigné        |
| Paiement COD   | Le paiement est enregistré automatiquement quand le statut passe à `delivered` |

### Codes promo

| Règle        | Détail                                                                       |
| ------------ | ---------------------------------------------------------------------------- |
| Application  | Un seul code promo par commande                                              |
| Vérification | Code actif + non expiré + utilisations < max + commande ≥ min_order          |
| Pourcentage  | La réduction est calculée sur le sous-total produits (hors livraison)        |
| Montant fixe | La réduction est soustraite du sous-total. Le total ne peut pas être négatif |

### Suppression en cascade

| Entité supprimée | Impact                                                                                             |
| ---------------- | -------------------------------------------------------------------------------------------------- |
| Produit          | Supprime : variantes, images, variant_attributes                                                   |
| Catégorie        | Les produits liés passent à `category_id = NULL`. Les sous-catégories passent à `parent_id = NULL` |
| Variante         | Supprime : variant_attributes. Les order_items existants conservent la référence                   |
| Code promo       | Les commandes passées conservent la réduction. Le code disparaît                                   |

### Panier (session)

| Règle        | Détail                                                                                  |
| ------------ | --------------------------------------------------------------------------------------- |
| Stockage     | Session Laravel (côté serveur)                                                          |
| Structure    | Tableau : `[{variant_id, quantity, price}]`                                             |
| Durée de vie | Expire avec la session (2 heures par défaut)                                            |
| Vérification | À chaque action panier, vérifier que le produit/variante est toujours actif et en stock |
