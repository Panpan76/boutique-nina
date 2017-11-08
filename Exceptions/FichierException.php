<?php

namespace Exceptions;

/**
 * Classe FichierException
 * Gère les exceptions relatives aux fichiers. Etend la classe Exception
 *
 * @author Panpan76
 * @date 08/11/2017
 */
class FichierException extends Exception{
  const FICHIER_LOG = 'Fichiers';

  const CREATION_IMPOSSIBLE         = 0;
  const LECTURE_IMPOSSIBLE          = 1;
  const ECRITURE_IMPOSSIBLE         = 2;
  const AUCUN_FICHIER_OUVERT        = 3;
  const ERREUR_LECTURE              = 4;
  const FERMETURE_IMPOSSIBLE        = 5;
  const SUPPRESSION_IMPOSSIBLE      = 6;
  const CREATION_DOSSIER_IMPOSSIBLE = 10;

  public function __construct($nomFichier = '', $code = null){
    switch($code){
      case self::CREATION_IMPOSSIBLE:
        $titre        = "Création ou overture du fichier impossible";
        $description  = "Impossible de créer ou d'ouvrir le fichier '$nomFichier'";
        $type         = 'E';
        break;

      case self::LECTURE_IMPOSSIBLE:
        $titre        = "Lecture du fichier impossible";
        $description  = "Impossible de lire le fichier '$nomFichier'";
        $type         = 'E';
        break;

      case self::ECRITURE_IMPOSSIBLE:
        $titre        = "Ecriture du fichier impossible";
        $description  = "Impossible d'écrire le fichier '$nomFichier'";
        $type         = 'E';
        break;

      case self::AUCUN_FICHIER_OUVERT:
        $titre        = "Le fichier n'a pas été ouvert";
        $description  = "Avant de pouvoir effectuer l'opération demandée, il faut ouvrir le fichier $nomFichier";
        $type         = 'E';
        break;

      case self::ERREUR_LECTURE:
        $titre        = "Lecture du fichier impossible";
        $description  = "Impossible de lire le fichier $nomFichier. Vérifiez les droits d'accès au fichier";
        $type         = 'E';
        break;

      case self::FERMETURE_IMPOSSIBLE:
        $titre        = "Fermeture du fichier impossible";
        $description  = "Impossible de fermer le fichier $nomFichier";
        $type         = 'E';
        break;

      case self::SUPPRESSION_IMPOSSIBLE:
        $titre        = "Suppression du fichier impossible";
        $description  = "Impossible de supprimer le fichier $nomFichier";
        $type         = 'E';
        break;

      case self::CREATION_DOSSIER_IMPOSSIBLE:
        $titre        = "Création du dossier impossible";
        $description  = "Impossible de créer le dossier $nomFichier";
        $type         = 'E';
        break;
    }
    parent::__construct($titre, $description, $type, $code);
  }
}
