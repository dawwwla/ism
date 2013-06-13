# Ism project
Liens utiles :

Bundles - http://knpbundles.com/

Documentation - http://symfony.com/doc/master/cookbook/index.html

Composer - http://getcomposer.org/

Code source du blog construit grâce au [cours du Site du Zéro](http://www.siteduzero.com/informatique/tutoriels/developpez-votre-site-web-avec-le-framework-symfony2).

## 1. Définir vos paramètres d'application
http://symfony.com/fr/doc/master/book/installation.html
Enlever .dist et rajouter ses propres paramètres

## 2. Installer les vendors
php composer.phar install

## 3. Quelques commandes utiles
Compléte une entité
php app/console doctrine:generate:entities IsmBlogBundle:Article

Créée une entité
php app/console doctrine:generate:entity

Affiche les requêtes / Met à jours la BBD
php app/console doctrine:schema:update [--dump-sql | --force]

Tester ces requêtes
php app/console doctrine:query:dql "select a from IsmBlogBundle:Article a"

Charger les datafixtures
php app/console doctrine:fixtures:load

Créer un bundle
php app/console generate:bundle

Installer les nouveaux bundle avec composer
php composer.phar install

Plus d'information ?
Voir ici http://www.kitpages.fr/fr/cms/22/aide-memoire-symfony2
