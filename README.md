# 🎫 Event Shop Maroc

**Event Shop** est une plateforme web complète de gestion d'événements et de billetterie en ligne. Elle permet aux utilisateurs de découvrir des événements, de réserver des places et de générer instantanément leurs billets électroniques (PDF + QR Code).

> 🚀 **Démo en ligne :** [https://php-production-ebe3.up.railway.app/event/](https://php-production-ebe3.up.railway.app/event/)

---

## 🌟 Fonctionnalités Principales

### Pour les Clients (Front-Office)
* **Catalogue d'événements :** Consultation des événements à venir (Mawazine, Derby Casablanca, Marrakech du Rire...).
* **Système d'authentification :** Inscription et connexion sécurisée.
* **Réservation :** Achat de billets en temps réel avec gestion des stocks.
* **E-Billet :** Génération automatique d'un billet au format **PDF** incluant un **QR Code unique** pour le contrôle d'accès.
* **Espace Personnel :** Historique des commandes et téléchargement des billets.

### Pour les Administrateurs (Back-Office)
* **Dashboard :** Vue d'ensemble de l'activité.
* **Gestion CRUD :** Création, modification et suppression d'événements, d'utilisateurs et de billets.
* **Sécurité :** Accès restreint par rôles (`ROLE_ADMIN`).

---

## 🛠️ Stack Technique

Ce projet met en œuvre les technologies modernes du développement web PHP :

* **Framework :** Symfony 6/7 (MVC Architecture).
* **Langage :** PHP 8.2+.
* **Base de Données :** MySQL (Relations OneToMany, ManyToOne).
* **ORM :** Doctrine (Entités, Migrations).
* **Frontend :** Twig, Bootstrap 5 (Responsive Design).
* **Services Tiers :**
    * `dompdf/dompdf` : Génération de fichiers PDF.
    * `endroid/qr-code` : Génération de QR Codes dynamiques (nécessite l'extension `gd`).
* **Déploiement :** Railway (CI/CD via GitHub).

---

## 💻 Installation Locale

Si vous souhaitez lancer ce projet sur votre machine :

1.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/Abdellah-elm/EventShop
    cd EventShop
    ```

2.  **Installer les dépendances :**
    ```bash
    composer install
    ```

3.  **Configurer la base de données :**
    Dupliquez le fichier `.env` en `.env.local` et configurez votre `DATABASE_URL`.

4.  **Créer la base et les tables :**
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5.  **Charger les fausses données (Fixtures) :**
    ```bash
    php bin/console doctrine:fixtures:load
    ```
    *Comptes de démo créés :*
    * Admin : `admin@gmail.com` / `admin`
    * Client : `test@gmail.com` / `test`

6.  **Lancer le serveur :**
    ```bash
    symfony server:start
    ```

---

## 🚀 Déploiement

Ce projet est configuré pour un déploiement continu sur **Railway**.
* Configuration de l'environnement via variables (`APP_ENV=prod`).
* Base de données MySQL gérée via Railway Database.

---

