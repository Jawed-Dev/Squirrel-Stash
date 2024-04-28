


- il va falloir revoir les composants svg

- ne pas oublier pour les svg de revoir l'effet d'ombre

- mettre le graph et mettre des néon pas trop frappant


# est ce qu'on peut supprimer une transac ou un paiement dans le resume ? / modifier ?


- option pour retirer les néons ?


- il va falloir renommer home pour resume, ou autre nom
- continuer à utiliser le fichier config de tailwind pour des choses qui se réutilisent

- il faut afficher sur le header sur quelle bouton on est
> c'est à dire si sur le resume/home alors le bouton doit être entouré d'un #gris par exemple

- Achat du mois il faut gérer aussi le fait que 
> l'effet sur achat ou paiement différencie sur quelle option on est
> voir pour les couleurs
> mettre à jour au clique

- utiliser seulement un wrap pour les sections ?
- réfléchir aux paiements réccurents et histo de transac - voir comment les agencer
- retirer les néons pour les div dans la section "total achat", etc
> le néon blanc a l'air trop fort
> revoir les effets de profondeur

- mettre les icones dans l'historique de transaction, paiement réccurent

- il faut réfléchir aux composants et composables si nécessaires
> par exemple ce que je dois utiliser depuis le parent (par ex si je mets Janvier, et que ça détecte le changement puis charge)
> reloading de page au changement de param

- Il faudra peut retirer ou changer le "dernier mois"
> en fonction du besoin
> mettre un type au lieu d'un chiffre au besoins






- regrouper par groupe de 2 les div quand nécessaire
- essayer de créer une fonc global pour la largeur du header pour le prendre en compte
- redéfinir les groupes pour le responsive pour prévenir à l'avance
- voir ce qui revient souvent en terme de padding / margin pour en faire des variables

