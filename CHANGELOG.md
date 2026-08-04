# Changelog

## 0.2.0
- Fonctionnalité créneau ( _**branch slot**_ )
    - Crud des créneaux _---EN COURS---_
<br>
<br>  

[Voir les versions antérieures](#versions)

## A venir dans les prochaines version <span style="font-size: 14px;">(non exhaustif)</span>
<!-- NE PAS OUBLIER le fichier "TODO" pour les autres améliorations -->
- Fonctionnalité créneau ( _**branch slot**_ )
    - _Gestion des créneaux_
<br>
- Fonctionnalité réservation ( _**branch booking**_ )
    - _Gestion de la réservation_
<br>
- Fonctionnalité service de pizzas ( _**branch service**_ )
    - _Eviter les modèles et services non valide (début après fin, créneau > durée, ...)_
    - _Permettre d'ordonner les modèles de services (pour simplifier l'utilisation de la liste déroulante des modèles de service lors de la saisie d'un service)_
    - _Page /pizzas/service_
        - _permettre un tri sur les dates de service_
        - _permettre de masquer les services passés_
<br>
- Général 
    - _Styles Bootstrap_
<br>
<br>


## Versions antérieures<a id="versions"></a>
### 0.1.6
---
- Fonctionnalité service de pizzas ( _**branch service**_ )
    - Modification des données de service
        - Modèles ne servant plus que lors de la création (relation conservée à titre informatif)
        - Recopie des données du modèle lors de la création du service
        - Modification des données du service rendu possible ensuite

### 0.1.5
---
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - Suppression des pizzas non visibles dans le formulaire de réservation pour l'admin
    - Mise à jour mineure sur les entêtes des colonnes du panier
    - Mise à jour de l'input "quantité" dans le formulaire d'ajout au panier 
<br>
- Général 
    - Mise à jour de la navbar
    - Suppression de la page /table
    - Ajout de pages fictives pour éviter les 404

### 0.1.4
---
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - Correction/amélioration du fonctionnement du panier quand qtt max atteinte
        - Désactivation du bouton "Ajouter au panier"
        - Affichage d'une alerte quand qtt max atteinte
        - Correction d'un bug permettant de dépasser la qtt max en ajoutant depuis "Ajouter au panier"
        - Factorisation du code JS

### 0.1.3
---
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - Ajout des services disponibles et du formulaire de réservation (sous la liste des pizzas)
<br>
- Toutes les fonctionnalités
    - Modification du style des boutons

### 0.1.2
---
- Fonctionnalité service de pizzas ( _**branch service**_ )
    - CRUD des services
    - Accès aux fonctionnalités depuis un sous-menu de la navbar
    - Ajout de filtres sur les modèles de service (colonne visible uniquement)
    - Modification de la liste déroulante des modèles de service lors de la saisie d'un service
<br>
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - Gestion de la visibilité des pizzas
    - Ajout de filtres (admin uniquement)

### 0.1.1
---
- Fonctionnalité service de pizzas  
_Ajout d'un lien provisoire vers la fonctionnalité (/service/template) depuis /pizzas_

### 0.1.0
---
- Début de l'ajout de la fonctionnalité service de pizzas (admin connecté uniquement) 
    - CRUD des modèles de service

> 🚀 --- **INFO** --- 🚀  
Les services sont créés à l'aide de modèles de service

### 0.0.8
---
- Correction d'un bug sur le checkbox "visible" qui ne pouvait être décoché (Ajout/modification des pizzas)

### 0.0.8
---
- Ajout/modification/suppression des pizzas (admin connecté uniquement)
- Vue de la liste des pizzas
- Pas de styles Bootstrap dans cette version  

_erreur de versionning :_  
version non disponnible sur DockerHub, écrasée par la correction suivante qui aurai due être en version 0.0.9 ...

### 0.0.7
---
- Mise à jour du message renvoyé en cas de mot de passe erroné

### 0.0.6
---
- Correction d'affichage de la police pour les mobiles

### 0.0.5
---
- Mise à jour du déploiement automatique

### 0.0.4
---
- Mise à jour du header avec ajout d'un menu burger
- Mise à jour du footer
- Ajout de conditions sur la force du mot de passe (depuis l'interface graphique et depuis la CLI)

### 0.0.3
---
- Ajout d'un header avec son menu (non finalisé)
- Ajout d'un footer (non finalisé)

### 0.0.2
---
- Test du Changelog

### 0.0.1
---
- Première version de production

