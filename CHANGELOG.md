# Changelog

## 0.1.5
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - Suppression des pizzas non visibles dans le formulaire de réservation pour l'admin
    - Mise à jour mineure sur les entêtes des colonnes du panier
    - Mise à jour de l'input "quantité" dans le formulaire d'ajout au panier
<br>
<br>

## A venir dans les prochaines version <span style="font-size: 14px;">(non exhaustif)</span>
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - _Gestion de la réservation_
<br>
- Fonctionnalité service de pizzas ( _**branch service**_ )
    - _Permettre d'ordonner les modèles de services (pour simplifier l'utilisation de la liste déroulante des modèles de service lors de la saisie d'un service)_
    - _Page /pizzas/service_
        - _permettre un tri sur les dates de service_
        - _permettre de masquer les services passés_
<br>
- Général 
    - _Styles Bootstrap_
<br>
<br>

## Versions antérieures
### 0.1.4
---
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - **Fait** _(dans cette version)_  
        - Correction/amélioration du fonctionnement du panier quand qtt max atteinte
            - Désactivation du bouton "Ajouter au panier"
            - Affichage d'une alerte quand qtt max atteinte
            - Correction d'un bug permettant de dépasser la qtt max en ajoutant depuis "Ajouter au panier"
            - Factorisation du code JS
    - _Reste à faire (non exhaustif)_ 
        - _Suppression des pizzas non visibles dans le formulaire de réservation pour l'admin_


### 0.1.3
---
- Fonctionnalité pizzas ( _**branch pizza**_ )
    - Ajout des services disponibles et du formulaire de réservation (sous la liste des pizzas)

- Toutes les fonctionnalités
    - Modification du style des boutons

### 0.1.2
---
- Fonctionnalité service de pizzas ( _**branch service**_ )
    - **Fait** _(dans cette version)_  
        - CRUD des services
        - Accès aux fonctionnalités depuis un sous-menu de la navbar
        - Ajout de filtres sur les modèles de service (colonne visible uniquement)
        - Modification de la liste déroulante des modèles de service lors de la saisie d'un service
    - _Reste à faire (non exhaustif)_ 
        - _Permettre d'ordonner les modèles de services (pour simplifier l'utilisation de la liste déroulante des modèles de service lors de la saisie d'un service)_
        - _Styles Bootstrap_
<br>
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
    - **Fait** _(dans cette version)_   
        - CRUD des modèles de service
    
    - _Reste à faire (non exhaustif)_  
        - _CRUD des services_  
        - _Styles Bootstrap_

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

