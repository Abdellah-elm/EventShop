# 🎫 Event Shop Maroc

**Event Shop** est une plateforme web complète de gestion d'événements et de billetterie en ligne. Elle permet aux utilisateurs de découvrir des événements, de réserver des places et de générer instantanément leurs billets électroniques (PDF + QR Code).

![Symfony](https://img.shields.io/badge/Symfony-7-blue?logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-9-orange?logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-blueviolet?logo=bootstrap)
![Groq](https://img.shields.io/badge/Chatbot_IA-Groq_/_Llama_3.3-green)

![demo](pic/a.png)

---

## Fonctionnalités Principales

### Pour les Clients (Front-Office)

* **Catalogue d'événements :** Consultation des événements à venir (Mawazine, Derby Casablanca, Marrakech du Rire...).
* **Système d'authentification :** Inscription et connexion sécurisée.
* **Réservation :** Achat de billets en temps réel avec gestion des stocks.
* **E-Billet :** Génération automatique d'un billet au format **PDF** incluant un **QR Code unique** pour le contrôle d'accès.
* **Espace Personnel :** Historique des commandes et téléchargement des billets.
* **Chatbot IA :** Assistant virtuel intelligent (widget flottant) pour obtenir des infos sur les événements, réserver et poser des questions — propulsé par **Groq / Llama 3.3 70B**.

### Pour les Administrateurs (Back-Office)

* **Dashboard :** Vue d'ensemble de l'activité.
* **Gestion CRUD :** Création, modification et suppression d'événements, d'utilisateurs et de billets.
* **Sécurité :** Accès restreint par rôles (`ROLE_ADMIN`).

![demo](pic/b.png)

---

## Stack Technique

Ce projet met en œuvre les technologies modernes du développement web PHP :

* **Framework :** Symfony 7 (MVC Architecture).
* **Langage :** PHP 8.2+.
* **Base de Données :** MySQL (Relations OneToMany, ManyToOne).
* **ORM :** Doctrine (Entités, Migrations).
* **Frontend :** Twig, Bootstrap 5 (Responsive Design).
* **Services Tiers :**
    * `dompdf/dompdf` : Génération de fichiers PDF.
    * `endroid/qr-code` : Génération de QR Codes dynamiques (nécessite l'extension `gd`).
    * `Groq API` : Chatbot IA avec le modèle Llama 3.3 70B (gratuit).
* **Déploiement :** Railway (CI/CD via GitHub).

![demo](pic/d.png)
![demo](pic/c.png)

---

## Chatbot IA

Le chatbot est accessible via le **widget flottant** (bulle 💬 en bas à droite) sur toutes les pages.

**Capacités :**
* Renseigner sur les événements disponibles (dates, lieux, prix, places restantes)
* Guider les utilisateurs dans le processus de réservation
* Répondre aux questions fréquentes sur la plateforme
* Support client en français, 24h/24

**Architecture :** Le chatbot interroge la base de données en temps réel pour fournir des informations à jour sur les événements, puis utilise l'API Groq (Llama 3.3 70B) pour générer des réponses contextuelles et naturelles.

![demo](pic/WhatsApp Image 2026-03-31 at 02.26.45.png)
---

## Installation Locale

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

3.  **Configurer l'environnement :**

    Créez un fichier `.env.local` à la racine du projet :
    ```env
    DATABASE_URL="mysql://user:password@127.0.0.1:3306/event-shop2?serverVersion=8.0.32&charset=utf8mb4"
    GROQ_API_KEY=votre_cle_groq
    ```
    > Obtenez une clé Groq **gratuite** sur [console.groq.com](https://console.groq.com)

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
    | Rôle | Email | Mot de passe |
    |------|-------|-------------|
    | Admin | admin@gmail.com | admin |
    | Client | test@gmail.com | test |

6.  **Lancer le serveur :**
    ```bash
    symfony server:start
    ```
    L'application est accessible sur `http://localhost:8000`

---

## Déploiement

Ce projet est configuré pour un déploiement continu sur **Railway**.

* Configuration de l'environnement via variables (`APP_ENV=prod`).
* Base de données MySQL gérée via Railway Database.
* Variable `GROQ_API_KEY` à ajouter dans les settings Railway.

---

## Auteur

**Abdellah El M.** — [@Abdellah-elm](https://github.com/Abdellah-elm)
