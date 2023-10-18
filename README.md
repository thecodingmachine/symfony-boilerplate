Test technique Haytham TANNOUCH:

Etapes pour initialiser et tester le livrable:

1- Cloner le projet:
	git clone -b v2 git@github.com:thecodingmachine/symfony-boilerplate.git

2- Checkout sur la branch de test 
	git checkout test_haytham_tannouch

3- Initier le projet:
	make init-dev

4- Lancer les containers docker et initialiser/demarrer les projets (back + front)
	make up

5- Reinitialiser la base de données et charger les fixtures:
	make reset-db

6- Tester les differentes fonctionnalité:
	Utilisateur admin: admin@tcm.com/ pwd: admin
	Autres utilisateurs: email (liste des utlisateurs)/ pwd: password pour tous les utilisateurs non admin