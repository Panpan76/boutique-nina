<?php
/**
 * Toutes les requêtes capturées par le htaccess arrive ici
 *
 * @author Panpan76
 * @date 08/11/2017
 */
session_start(); // On initialise une session
require_once 'autoload.php'; // On récupère notre autoload
require_once 'fonctions.php'; // On récupère nos fonctions générique

$noyau = Application::getInstance(); // On lance notre application
