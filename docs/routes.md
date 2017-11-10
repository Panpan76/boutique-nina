# FrameworkPHP Documentation

## Gérer ses routes

#### Créer des routes

Par défaut, il y a 2 classes proposant des annotations pour les routes :
`Annotations\RouteAnnotation` et `Annotations\SecuriteAnnotation`.

La première propose de récupérer un nom et une url pour une méthode d'un controlleur.
> Note :
> Le nom et l'url doivent être unique, une exception sera générée le cas échant

```php
<?
const REGEX = '/Route\(\'(.*)\', url="(.*)"\)/'; // Route('<nom de la route>', url="<url correspondante>")
```

La seconde permet de gérer toutes les annotations relatives à la sécurité :
- Doit être connecté
- Doit **ne pas** être connecté
- A un rôle
- A un droit

> Note :
> La notion de rôle et de droits est relative à l'utilisateur connecté

```php
<?
const REGEX_CONNECTE      = '/requiert\(\'?Connecté\'?\)/';
const REGEX_NON_CONNECTE  = '/requiert\(\'?Non connecté\'?\)/';
const REGEX_ROLES         = '/requiert\(Role="(.*)"\)/';
const REGEX_DROITS        = '/requiert\(Droit="(.*)"\)/';
```


Pour créer une route, vous pouvez alors combiner ces annotations (ou créer les votres).
> Note :
> Intialement, les routes ne seront récupérées que sur les méthodes des controlleurs (présents dans `src/Controlleurs/`).

Ainsi, si vous souhaitez créer une route accessible depuis l'url `/index` (où `/` est la racine du projet), qui requiert d'être connecté et d'avoir le rôle `membre` vous pouvez faire :
```php
<?
// Dans la classe Controlleurs\MonControlleur
/**
 * @Route('nom_de_la_route', url="/index")
 * @requiert(Connecté)
 * @requiert(Role="membre")
 */
public function maMethode(){}
```
> Note :
> Vous pouvez donner une liste de rôles ou de droits, et les séparant par `,` ou `|`. La sécurité sera validé si l'utilisateur possède **au moins** un de ceux demandés

Imaginons maintenant que vous voulez pouvoir passer des paramètres à votre route. Par exemple je souhaite voir le détail de l'utilisateur #3
```php
<?
// Dans la classe Controlleurs\MonControlleur
/**
 * @Route('voir_utilisateur', url="/utilisateur/{$utilisateur}")
 * @param Entites\Utilisateur $utilisateur Utilisateur que l'on veut voir
 */
public function voirUtilisateur($utilisateur){}
```
Ici, l'adresse pour accéder à la page ne sera pas `/utilisateur/{$utilisateur}` mais `/utilisateur/3`.
La type du paramètre (`Entites\Utilisateur`) étant une entité, le framework va automatiquement convertir la variable en entité.
Il va rechercher en base la correspondance à `Entites\Utilisateur` pour la clé primaire `3`. Ainsi, dans notre méthode, `$utilisateur` sera déjà instancié comme il faut.
> Note :
> Si aucune correspondance n'est trouvée (par exemple : /utilisateur/42 et qu'il n'y a aucun utilisateur #42), une exception sera lancée (affichée dans les logs) et `$utilisateur` vaudra `null`

Bien sûr, on peut passer autre chose que des entités, la valeur sera passée à la méthode sans aucun changement.

> Note :
> Sur le même principe, on peut définir une route avec plusieurs paramètres, et également avec des annotations de sécurité.
