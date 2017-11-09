# FrameworkPHP _(0.3.3)_

Création, découverte et utilisation d'un framework PHP

--------------

Structure du projet :
- Annotations
  - Annotation.php _(Classe générique analysant une annotation)_
  - ...
- Exceptions
  - Exception.php _(Classe mère des exceptions)_
  - ...
- Gestionnaires
  - Annotation.php  _(Gestionnaire d'annotations)_
  - Fichier.php _(Gestionnaire de fichiers)_
- Interfaces
  - Analysable.php
- Logguers
  - Logguer.php _(Classe du logguer)_
- src _(Dossier des fichiers source du développeur)_

A faire :
- [x] Autoload
- [x] Gestion des Exceptions
- [x] Gestion des logs
- [x] Gestion des fichiers
- [x] Système d'annotations (par fichier)
- [x] Système de gestion des entités
  - [x] Sélection
  - [x] Insertion
  - [x] Modification
  - [x] Suppression
- [ ] Gestion des requêtes
  - [ ] Redirection
  - [ ] Historique (pour retourner à une page précédente)
  - [ ] Paramètres (POST/GET)
- [ ] Système de formulaires (relative aux annotations des entités)
  - [ ] Génération d'un token unique, qui a statut (validé ou non)
  - [ ] Formulaire d'ajout
  - [ ] Formulaire de modification
  - [ ] Formulaire de suppression
