# CAHIER DES CHARGES — BOUTIQUE EN LIGNE (MONO-BOUTIQUE)

---

## 1. OBJECTIF DU PROJET

Développer une boutique en ligne mono-commerçant adaptée au marché algérien (paiement à la livraison, wilayas, livraison locale). Le commerçant gère ses produits, commandes et clients depuis un dashboard admin. Les clients consultent les produits et passent commande.

---

## 2. UTILISATEURS CIBLES

### 2.1 Commerçant (propriétaire boutique)

- Gère ses produits (CRUD)
- Gère ses commandes
- Gère ses clients
- Configure la livraison et les wilayas
- Consulte ses statistiques (dashboard)
- Configure les paramètres de la boutique

### 2.2 Client final

- Consulte les produits
- Passe commande
- Suit sa livraison

---

## 3. ARCHITECTURE TECHNIQUE

### Stack

- **Backend + Frontend** : Laravel (Blade + Livewire )
- **CSS** : Tailwind CSS cdn 
- **Base de données** : MySQL
- **Cache** : Redis (recommandé)

## 4. MODULE PRODUITS

### 4.1 Gestion produits

- CRUD complet (créer, lire, modifier, supprimer)
- Catégories hiérarchiques
- Images multiples par produit
- Statut actif / inactif

### 4.2 Variantes (CRITIQUE)

Le système doit gérer des variantes combinées :

- Taille
- Couleur
- Pointure

**Exemple**

Produit : Chaussure Nike

Variantes :

- Noir / 42
- Noir / 43
- Blanc / 42

### Contraintes

- Stock par variante
- Prix par variante
- SKU unique par variante

---

## 5. MODULE COMMANDES

### 5.1 Création commande

- Via le site web (checkout)
- Via bouton WhatsApp (option)

### 5.2 Champs obligatoires

- Nom client
- Téléphone
- Wilaya
- Adresse

### 5.3 Statuts de commande

- En attente (`pending`)
- Confirmée (`confirmed`)
- Expédiée (`shipped`)
- Livrée (`delivered`)
- Annulée (`cancelled`)

---

## 6. MODULE LIVRAISON (Algérie)

### Fonctionnalités

- Gestion des 58 wilayas
- Tarification par wilaya (prix configurable)
- Intégration transporteurs (API future : Yalidine, ZR Express)

### Champs

- Wilaya
- Prix livraison
- Transporteur
- Numéro de tracking

---

## 7. MODULE CLIENTS

- Liste des clients
- Historique commandes par client
- Total dépensé par client
- Recherche par téléphone / nom

---

## 8. MODULE PAIEMENT

### Méthodes

- **Paiement à la livraison** (obligatoire, par défaut)
- Intégration future :
  - BaridiMob
  - CIB

---

## 9. MODULE DASHBOARD (Commerçant)

### Indicateurs (widgets)

- Chiffre d'affaires
- Nombre de commandes du jour
- Nombre de clients
- Produits en rupture de stock

### Graphiques

- Ventes journalières (7 jours / 30 jours)
- Top produits vendus

### Dernières commandes

Tableau : Client | Montant | Statut | Wilaya

---

## 10. MODULE MARKETING

- Codes promo (pourcentage / montant fixe)
- Réductions sur produits
- Notifications clients (option)

---

## 11. PARAMÈTRES BOUTIQUE

- Nom de la boutique
- Logo
- Couleurs du thème
- Conditions de vente
- Informations de contact

---

## 12. BASE DE DONNÉES (MIGRATIONS LARAVEL)

### UTILISATEURS (commerçant uniquement)

```sql
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```

### PARAMÈTRES BOUTIQUE

```sql
CREATE TABLE settings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  store_name VARCHAR(255),
  logo VARCHAR(255) NULL,
  phone VARCHAR(20) NULL,
  email VARCHAR(255) NULL,
  address TEXT NULL,
  primary_color VARCHAR(7) DEFAULT '#3B82F6',
  terms TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```

### CATÉGORIES

```sql
CREATE TABLE categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  parent_id BIGINT UNSIGNED NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

### PRODUITS

```sql
CREATE TABLE products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id BIGINT UNSIGNED NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  base_price DECIMAL(10,2) NOT NULL,
  brand VARCHAR(100) NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

### IMAGES PRODUITS

```sql
CREATE TABLE product_images (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

### ATTRIBUTS (taille, couleur, pointure…)

```sql
CREATE TABLE attributes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```

### VALEURS ATTRIBUTS

```sql
CREATE TABLE attribute_values (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  attribute_id BIGINT UNSIGNED NOT NULL,
  value VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
);
```

### VARIANTES PRODUIT

```sql
CREATE TABLE product_variants (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  sku VARCHAR(100) UNIQUE NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

### VARIANTES ↔ ATTRIBUTS

```sql
CREATE TABLE variant_attributes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  variant_id BIGINT UNSIGNED NOT NULL,
  attribute_value_id BIGINT UNSIGNED NOT NULL,
  FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
  FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE
);
```

**Exemple :**

- Chaussure Nike
  - Variante 1 → SKU: NIKE-BLK-42 | Noir / 42 | 5500 DA | Stock: 10
  - Variante 2 → SKU: NIKE-WHT-43 | Blanc / 43 | 5500 DA | Stock: 5

### CLIENTS

```sql
CREATE TABLE customers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(255) NULL,
  address TEXT NULL,
  wilaya_id INT UNSIGNED NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (wilaya_id) REFERENCES wilayas(id)
);
```

### WILAYAS (Algérie — 58 wilayas)

```sql
CREATE TABLE wilayas (
  id INT UNSIGNED PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);
```

### FRAIS LIVRAISON PAR WILAYA

```sql
CREATE TABLE shipping_rates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  wilaya_id INT UNSIGNED NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (wilaya_id) REFERENCES wilayas(id)
);
```

### COMMANDES

```sql
CREATE TABLE orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  shipping_cost DECIMAL(10,2) DEFAULT 0,
  status ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  payment_method ENUM('cod','baridimob','cib') DEFAULT 'cod',
  notes TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);
```

### DÉTAILS COMMANDE

```sql
CREATE TABLE order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  variant_id BIGINT UNSIGNED NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (variant_id) REFERENCES product_variants(id)
);
```

### LIVRAISON

```sql
CREATE TABLE shipments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  delivery_company VARCHAR(100) NULL,
  tracking_number VARCHAR(100) NULL,
  status VARCHAR(50) DEFAULT 'pending',
  shipped_at DATETIME NULL,
  delivered_at DATETIME NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

### PAIEMENTS

```sql
CREATE TABLE payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method VARCHAR(50) NOT NULL,
  status VARCHAR(50) DEFAULT 'pending',
  paid_at DATETIME NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

### CODES PROMO

```sql
CREATE TABLE coupons (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  type ENUM('percentage','fixed') NOT NULL,
  value DECIMAL(10,2) NOT NULL,
  min_order DECIMAL(10,2) DEFAULT 0,
  max_uses INT NULL,
  used_count INT DEFAULT 0,
  expires_at DATE NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```

---

## 13. INTERFACE UTILISATEUR (UI/UX)

### STYLE GLOBAL (IDENTITÉ VISUELLE)

#### Couleurs

- **Bleu** `#3B82F6` (principal)
- **Blanc** `#FFFFFF` (fond)
- **Gris clair** `#F3F4F6` (cards, bordures)
- **Vert** `#10B981` (succès / commandes validées)
- **Rouge** `#EF4444` (erreurs / annulations)
- **Jaune** `#F59E0B` (en attente)

---

## 14. DASHBOARD COMMERÇANT (LAYOUT)

### Layout principal

```
[ Sidebar gauche ] | [ Header top          ]
                   | [ Contenu principal   ]
```

### Sidebar (menu)

- Dashboard
- Commandes
- Produits
- Catégories
- Clients
- Livraison
- Marketing
- Paramètres

### Pages du Dashboard

#### Page Dashboard (accueil)

- 4 Widgets cards (CA, Commandes du jour , Clients, Produits en Ruptures)
- Graphique ventes (7j / 30j)
- Top produits vendus
- Tableau dernières commandes: Client Montant Statut Wilaya

#### Page Produits

**Liste** — Tableau :
| Image | Nom | Prix | Stock | Statut | Actions |

**Filtres** : Catégorie, Prix, Stock, Marque

**Ajout/Modification produit** (formulaire) :

- Nom, Description, Catégorie, Marque, Prix de base
- Upload images multiples
- **Section Variantes** (UI dynamique) :
  - Choix attributs : Taille → S / M / L | Couleur → Noir / Blanc | Pointure → 40 / 41 / 42
  - Génération automatique des combinaisons
  - Tableau Stock pa variantes : SKU | Prix | Stock

#### Page Commandes

**Liste** — Tableau :
| # | Client | Wilaya | Total | Statut | Date |

**Couleurs statuts** :

- `pending` → Jaune
- `confirmed` → Bleu
- `shipped` → Violet
- `delivered` → Vert
- `cancelled` → Rouge

**Détail commande** :

- Infos client (Nom, Téléphone, Adresse, Wilaya)
- Produits commandés (Produit + variante, Quantité, Prix)
- Livraison (Transporteur, Tracking)
- Changer le statut

#### Page Clients

Tableau : Nom | Téléphone | Nb commandes | Total dépensé
Click → historique complet du client

#### Page Livraison

- Configuration prix par wilaya
- Transporteurs (Yalidine, ZR Express)

#### Page Marketing

- Gestion codes promo
- Réductions sur produits

#### Page Paramètres

- Nom boutique, Logo, Domaine
- Méthodes de paiement activées
- Conditions de vente

---

## 15. FRONT OFFICE (SITE CLIENT — Blade)

### Page accueil

- Bannière promo
- Produits populaires
- Catégories

### Page catalogue

Filtres : Prix, Taille, Couleur, Pointure
Grille de produits avec pagination

### Page produit

- Galerie Images + vidéo
- Choix variantes (Taille, Couleur, Pointure)
- Prix dynamique selon variante
- Boutons : **Ajouter au panier** | **Commander via WhatsApp**

### Panier

- Liste produits avec quantités
- Sous-total + frais livraison
- Total

### Checkout (ultra simple — optimisé Algérie)

- Nom
- Téléphone
- Wilaya (select → calcul auto frais livraison)
- Adresse
- Code promo (optionnel)
- Bouton **Confirmer la commande**

---

## 16. RESPONSIVE DESIGN

- Compatible mobile (mobile-first avec Tailwind)
- Boutons larges et tactiles
- Checkout optimisé smartphone
- Menu hamburger sur mobile

---

## 17. SÉCURITÉ

- Authentification Laravel (sessions + CSRF)
- Hash mot de passe 
- Validation des données (Form Requests)
- Protection contre XSS et SQL injection 

---

## 18. PERFORMANCE

- Pagination obligatoire
- Lazy loading images
- Eager loading relations (`with()`)
- Indexation base de données (FK, colonnes filtrées)
- Cache Redis

## 17. SCALABILITÉ

Le système doit supporter :

- 10 000 boutiques
- 100 000 produits
- trafic simultané
