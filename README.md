# FrameworkPHP _(0.5.2)_

Création, découverte et utilisation d'un framework PHP

--------------
### [Documentation](docs/README.md)
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
- [x] Gestion des requêtes
  - [ ] Redirection
  - [ ] Historique (pour retourner à une page précédente)
  - [x] Paramètres (POST/GET)
  - [x] Execution d'une requête
- [x] Système de formulaires (relative aux annotations des entités)
  - [x] Génération d'un token unique, qui a statut (validé ou non)
  - [ ] Formulaire d'ajout
  - [ ] Formulaire de modification
  - [ ] Formulaire de suppression
