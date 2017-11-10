# FrameworkPHP Documentation

## Fonctionnement des requêtes


Lorsque l'on essaie d'accéder à une ressource (page, adresse, image, fichier, etc.) sur l'application, le premier intervant est le fichier `.htaccess` présent à la racine du projet.
Son rôle est rediriger les demandes là où il faut ; il différencie notamment les ressources (qu'il va chercher dans le répertoire correspondant et retourner sans modification) des adresses.

Lorsqu'une adresse est _capturée_ par le `.htaccess`, elle est envoyé à `web.php` qui nous sert juste à lancer notre `Application` et à récupérer les éléments indispensables (`session`, `autoloader`, etc.).

Notre `Application` va donc charger différents outils, dont le gestionnaire de requêtes `Gestionnaires\GestionnaireRequetes`.
C'est lui qui en premier va instancier la requête principale avec la première instance de `Gestionnaires\Requete`.
Les principaux attributs de la classe `Requete` sont initialisés comme ci-dessous :
```php
<?
$this->url = $_GET['page']; // L'url
if(isset($_GET['page'])){
  unset($_GET['page']);
}
$this->parametres = array_merge($_POST, $_GET); // Les données
$this->estEnvoyee = ($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false; // Si un formulaire a été envoyé
$this->pagePrecedente = $_SERVER['HTTP_REFERER']; // La page précédente
```
Comme vous pouvez le constater, l'url demandée est contenu dans la super-variable `$_GET['page']`. Ceci est possible grâce au `.htaccess`.
