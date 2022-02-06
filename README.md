# project_web3_symfony

after cloning the project don't forget to launch the command : **composer install**
<br>
after this lauch ==> **symfony serve -d**
<br>
after this lauch symfony serve do ==> **docker-compose up -d**
<br>
<br>
to load table => **symfony console doctrine:migrations:migrate**
<br>
to load some fixtures => **symfony console doctrine:fixtures:load**
<br>
if you want to list your migrations before execute the command line up there do this
<br>
**symfony console doctrine:migrations:list**
