# Deployment

Deployment use https://github.com/hpatoio/DeployBundle

1. Configuration

    * Parameters:
        Set in your parameters.yml the following parameters:
    
            deploy_host: kisaan.prod
            deploy_dir: /var/www/kisaan.prod/Symfony
            deploy_user: kisaan
    
    * php.ini:
        Add this to your php.ini:
    
            extension=mongodb.so

2. Deploy

    * Dry-Run:
    
            php bin/console project:deploy prod
    
    * Real:
    
            php bin/console project:deploy --go prod
    
     For more informations see https://github.com/hpatoio/DeployBundle

3. Post deployment tasks

    * Install/Update Vendors:
    
            export SYMFONY_ENV=prod && php composer.phar install --no-dev --prefer-dist --optimize-autoloader
            php composer.phar dump-autoload --optimize
    
    * Clear Symfony Cache:
    
            php bin/console cache:clear --env=prod --no-debug
    
    * Dump Assetic Assets:
    
            php bin/console assetic:dump --env=prod --no-debug

4. Add crons

    See [crons documentation](crons.md)

