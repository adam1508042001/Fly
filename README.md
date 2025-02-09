# Fly

Fly est une application web développée pour la compagnie aérienne **Flying Web**. Cette application vise à fournir une gestion efficace des vols, des aéroports et des réservations pour la compagnie, tout en offrant une expérience utilisateur fluide et intuitive pour les clients.

L'objectif principal est de permettre à Flying Web de gérer ses opérations aériennes (avions, aéroports, planification des vols) et d'offrir aux clients la possibilité de rechercher des vols, de réserver leurs billets, et d'imprimer leurs cartes d'embarquement.


L'application aura :
un admin features:
Accessible uniquement aux administrateurs (équipe Flying Web) en cliquant sur une series de touche quand on est sur l'application Fly, cette section permet de gérer les avions, les aéroports, la planification des vols et les réservations.

Coté client:
 Accessible aux clients pour rechercher des vols, réserver leurs places et imprimer leurs billets.



Admin features: 

  - CRUD Avions : Gestion des modèles d'avions (A320, B747, etc.), des identifiants, du nombre de places passagers et des dimensions.
  - CRUD Aéroports : Gestion des aéroports avec leurs villes et la longueur des pistes compatibles.

  
Planification des vols :

  - Création et gestion des vols : Définir l'aéroport de départ, la date/heure UTC, l'aéroport d'arrivée, l'avion assigné et un numéro de vol unique.
  - Consultation et annulation des vols à venir.
  - Gestion des passagers : Lister, consulter et annuler les passagers par vol.
  - Suivi des statuts des avions (décollage, atterrissage).


  Règles de gestion :

   - Un avion ne peut décoller que s'il a atterri.
  - Les avions trop grands ne peuvent pas être affectés à des pistes trop courtes.
  - Les réservations ne peuvent pas être modifiées après le décollage d'un avion.




Réservation de vols 

    - Moteur de recherche de vols : Permet aux clients de rechercher des vols à venir en spécifiant :
    - Date de départ, ville de départ et ville d’arrivée.
    - Tolérance de recherche (plus ou moins X jours).
  - Propositions de vols retour en fonction de la date d’arrivée.
  - Réservation de vols :
    - Entrer le nombre de passagers.
    - Ajouter les noms et prénoms des passagers.
  - Confirmation de réservation : Envoi automatique d’un email contenant :
    - Un lien sécurisé pour consulter, annuler ou modifier la réservation.
    - Une carte d’embarquement PDF avec les informations du vol et un QR Code pour accéder à la réservation.



Le front-end de Fly est développé avec Vue.js
Le back-end est développé en PHP, garantissant une gestion sécurisée et performante des opérations du serveur.


Les données sont stockées dans une base SQL


- Front-end :
  - Vue.js 
  - Vue Router pour la navigation
  - TailwindCss


  Back-end:
  - PHP avec composer
  - Framework Laravel
  - PHPMailer pour l'envoi des emails de confirmation.


  Gestion de versionning:
  -Initialisation de projet sur la branche develop
    -Création de branches features pour chaque fonctionnalité et push sur la develop une fois fini fusion de feature avec develop.
    - push de la branche develop vers le dépot distant.

