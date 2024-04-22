# FAIT

- composant SectionLogin
- composable inputText
- 



# À FAIRE

# il va falloir régler le soucis sur les autocomplétions de path

- le tableau doit être envoyé en props
- créer un composant carrousel d'img sur lequel je mettrai un tableau
> je ne sais pas si c'est nécessaire de créer un composable de timer juste pour lui pour le moment
> utilisation du modulo pour le reset de la variable du caroussel
> faire en sorte que le texte du carrousel s'affiche en animation aussi
> que le texte change après que l'effet soit terminé (donc détecter quand l'animation termine)
> pareil pour les pastilles peut être ? 
> mettre un width à la div de texte ? surtout pour le titre


- ne pas oublier le soucis sur le timer du carrousel

- Revoir comment je gère les margin top etc des composants
- Des couleurs bleux sont à revoir sur l'inscription
- Prendre le temps de revoir les noms de `variables`, `composants`, `composables`, `path ech`

- visiblement il est possible directement via l'index (le router) de créer un effet de changement de page
> cependant il va falloir s'intéresser aux callback possible apparemment avec le transition
> transition donne des propriétés, et celles ci donnent la possibilité d'appeler des `Callback`

- soucis de pathing à régler (d'auto complétion)

- Pour les input, si je veux créer un effet valide (rouge) et vert donc en respencant les règles de l'input, il faudra pe modifier le système
> ou peut être juste le faire via le parent ou un mix des deux

- faire le header (voir les rélexions dans mes notes)

- à voir aussi si je mets un switch de slide sur le carrousel si je clique sur les patilles
- et en fonction des slide mettre un texte
- l'histoire des key dans les boucles
> ne pas oublier de reset la variable pour la carrousel que j'utilise avec le modulo

- quand je quitte/regarde un onglet autre que le composant carrousel j'ai l'impression que les img switch + vite
> ou peut être un soucis sur le timer ?


- il faudrait peut être passé des props de l'index vers SectionLogin 
- changer le fontmain en poppins


- sur le composant caroussel voir si je reset la variable des slide qui utilise le modulo
- le timer du carrousel peut être voir pour un système qui reset le timing quand on clique pour que ça switch manuellement






- créer deux pages :
- créer des composants input, btn, box, etc

- in line composable ? (qu'est ce que ce terme)
- utilisable de composable ? Quand ?


- créer un composant input pour les mdp quand j'attaquerai le back

- créer un composant pour les aref?












# Composants organisation
- Que les class s'utilisent de l'extérieur donc en props
- Il faut que de l'extérieur (ou intérieur) des composants je puisse utiliser les données

- renommer les composables "use", par useBoolean par exemple etc
- utiliser une var global pour les path






# Mieux utiliser les composables

https://www.youtube.com/watch?v=HJAImAlZzxk&t=345s




# au sujet de la factorisation des handle (comme j'aimais le faire)
-  https://www.linkedin.com/feed/update/urn:li:activity:7182965393467002880/

# Props pour la Configuration: Utilisez des props pour configurer le comportement des composants, comme des labels, des placeholders pour les inputs, des URLs pour les fetches, etc.


# Provide/Inject pour les Dépendances Profondes: Si vous avez des configurations ou des états à partager profondément à travers plusieurs niveaux de composants, utilisez provide et inject.

# Directives Conditionnelles: Utilisez des directives comme v-if, v-else-if, et v-else pour conditionnellement rendre différents éléments ou composants.


