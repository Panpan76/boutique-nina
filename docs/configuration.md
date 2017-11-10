# FrameworkPHP Documentation

## La configuration

Dans FrameworkPHP, l'ensemble de la configuration se fait au sein de la classe `Config` présente à la racine du projet.
Toutes les méthodes de cette classe doivent être `static`.

On peut voir les méthodes de cette classe comme de petits regroupements de constantes.

Par exemple, si on veut accéder à la configuration de la base de données.
```php
<?
// Dans la classe Config
public static function BaseDeDonnees(){
  $base['SGBD'] = 'mysql';
  $base['HOST'] = 'localhost';
  $base['USER'] = 'root';
  $base['PASS'] = '';
  $base['BASE'] = 'framework_php';

  return $base;
}
```

```php
<?
// N'importe où, où l'on a besoin de cette configuration
$configBDD = Config::BaseDeDonnees();
$config['HOST']; // Contient le serveur sur lequel se trouve la base de données
```

Vous pouvez ainsi ajouter autant de méthodes que vous voulez, pour autant de configuration.

#### CSS/JS

Prenons le cas de la configuration des CSS
```php
<?
// Dans la classe Config
public static function CSS(){
  $css['DOSSIER'] = self::Application()['ADRESSE'].'/src/ressources/css/';
  $css['MIN']     = array(
    'bootstrap'     => 'bootstrap.css',
  );

  return $css;
}
```
On y retrouve l'adresse des fichiers css et la liste des fichiers css à inclure automatiquement sur toutes les vues.
Pour ajouter une nouvelle feuille de style CSS automatiquement, il suffit d'ajouter une ligne à ce tableau : un nom (arbitraire et unique), le fichier à inclure : `'nom' => 'fichier.css'`.

Le comportement des fichiers JavaScript est identique

> Note :
> On utilise l'adresse du répertoire (et on passe donc par le `.htaccess`)
