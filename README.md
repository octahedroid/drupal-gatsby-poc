# drupal-gatsby-poc

[![CircleCI](https://circleci.com/gh/octahedroid/drupal-gatsby-poc.svg?style=shield)](https://circleci.com/gh/octahedroid/drupal-gatsby-poc)
[![Dashboard drupal-gatsby-poc](https://img.shields.io/badge/dashboard-drupal_gatsby_poc-yellow.svg)](https://dashboard.pantheon.io/sites/45e9c64e-ab92-4a5e-8fa8-e0d2aaaa09fc#dev/code)
[![Dev Site drupal-gatsby-poc](https://img.shields.io/badge/site-drupal_gatsby_poc-blue.svg)](http://dev-drupal-gatsby-poc.pantheonsite.io/)

## Requirements
Lando should be installed and running:
* https://lando.dev/download/

## Getting started

Clone this repo <code>git clone git@github.com:octahedroid/drupal-gatsby-poc.git</code>.
### Lando
1. Start Lando: <code>lando start</code>
2. Sync DB and files: <code>lando pull -c none -d dev -f dev</code>
3. Run composer <code>lando composer install</code>
4. The local site should be available at: https://drupal-gatsby-poc.lndo.site/
#### Notes
* We never commit the local .lando.yml file to the repository.

### Usage
* <code>lando start</code> Bring up all the services
* <code>lando rebuild</code> Rebuild all the services
* <code>lando db-import _filename.sql_</code> Import the selected database file into the service. This file should be placed in the same folder as the .lando.yml file.

### Creating default content
Default content has been included for the Home, Blog, Contact and JAMStack pages.
1. Run the included Lando command to create default content: <code>lando content</code>
### Installing modules
Modules are installed using composer, and should be enabled and their configuration exported using drush:
1. <code>lando composer require drupal/module_name </code>
2. <code>lando drush en module_name</code>
3. <code>lando drush cex</code>

### Configuration synchronization
Configuration can be exported and imported with Drush
#### Importing
<code>lando drush cim</code>
#### Exporting
<code>lando drush cex</code>

### Drupal conventions

#### Folder structure
All custom modules should be placed in <code>/web/modules/custom</code>
