# FrameworkPHP Documentation

## Créer ses propres annotations

Pour définir vos propres annotations, il vous faut créer, dans le répertoire `Annotations`, une classe donc le nom finit par "Annotation".
La classe doit également implémenter l'interface `Interfaces\Analysable` et appartenir au namespace `Annotations`

```php
<?php

namespace Annotations;

use Interfaces\Analysable;

class ExempleAnnotation implements Analysable{
  // Code
}
```

Les annotations sont ensuite définies par des constantes de classe sous forme d'expression régulière (regex).
Le nom de la constante est arbitraire, et vous pouvez utiliser vos propres expressions régulières afin de pouvoir récupérer ce que vous voulez.

Le système utilisant les expressions régulières, vous pouvez utiliser des parenthèses afin de capturer une partie de l'annotation.

Vous pouvez également utiliser les modificateurs d'expression régulière de php (par exemple `i` pour ne pas être sensible à la case).

> Note :
> Lors de leur utilisation, les annotations commencent par un arobase (`@`) sur une ligne de commentaire multiple.
> Ici, il ne faut définir que ce qui se situe _après_ l'arobase (`@`)

```php
<?
const REGEX_EXEMPLE       = '/EXEMPLE/';
const REGEX_CASSE         = '/Casse/i';
const REGEX_CAPTURE       = '/nombre=(\d+)/';
```
Ces exemples correspondront à ces cas :
```php
REGEX_EXEMPLE :
<?
/**
 * @EXEMPLE
 */
?>

REGEX_CASSE :
<?
/**
 * @casse
 * @CASSE
 * @cASse
 */
?>

REGEX_CAPTURE :
<?
/**
 * @nombre=3
 * @nombre=42
 */
?>
```


Ensuite, il vous faut créer la méthode `public static function analyse($annotation)` exigée par l'interface `Interfaces\Analysable`. Pour chaque constante que vous avez définit, il faut tester si l'annotation correspond à l'expression régulière, et retourner ce que vous voulez.

```php
<?
public static function analyse($annotation){
  if(preg_match(self::REGEX_EXEMPLE, $annotation, $match)){
    return array(
      'exemple' => 'Ceci est un exemple'
    );
  }
}
```
> Note : `$match` contient les correspondances capturées.
> `$match[0]` contiendra toujours l'intégralité de la chaîne.
> `$match[1]` contiendra le contenu de la première paranthèse capturante et ainsi de suite.


L'ensemble des annotations trouvées juste avant un **attribut** ou une **méthode** seront regroupées comme étant annotation de celui/celle-ci.


### Voici la mise en pratique des exemples présentés
L'annotation :
```php
<?php

namespace Annotations;

use Interfaces\Analysable;

class ExempleAnnotation implements Analysable{
  const REGEX_EXEMPLE       = '/EXEMPLE/';
  const REGEX_CASSE         = '/Casse/i';
  const REGEX_CAPTURE       = '/nombre=(\d+)/';

  public static function analyse($annotation){
    if(preg_match(self::REGEX_EXEMPLE, $annotation, $match)){
      return array(
        'exemple' => 'Ceci est un exemple'
      );
    }
    if(preg_match(self::REGEX_CASSE, $annotation, $match)){
      return array(
        'casse' => true
      );
    }
    if(preg_match(self::REGEX_CAPTURE, $annotation, $match)){
      return array(
        'valeur' => intval($match[1])
      );
    }
  }
}
```

Le fichier contenant les annotations :
```php
<?php

namespace Entites;

class Exemple{

  /**
   * @EXEMPLE
   */
  private $att;

  /**
   * @caSSe
   */
  private $att2;

  /**
   * @nombre=14
   */
  public function somme($test){
    // Code
  }
}
```

Résultat :
```php
<?php

'Entites\Exemple' => array(
  'attributs' => array(
    'attr' => array(
      'visibilite'  => 'private',
      'exemple'     => 'Ceci est un exemple'
    ),
    'attr2' => array(
      'visibilite'  => 'private',
      'casse'       => true
    )
  ),
  'methodes' => array(
    'somme' => array(
      'visibilite'  => 'public',
      'valeur'      => 14
    )
  )
)
```

Bien sûr, vous pouvez créer des annotations bien plus complexe, comme des routes, ou correspondant aux paramètres des méthodes (par exemple) en utilisant des expression régulières un peu plus complexe
