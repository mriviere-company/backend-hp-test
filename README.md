# Production scheduling API — technical test

*[Version française plus bas](#français)*

A technical test from autumn 2024: a REST API that manages customer orders for a production
line and computes the **production schedule**. Built with **Laravel 11 / PHP 8.2** and
MySQL. Its front end is [`frontend-hp-test`](https://github.com/mriviere-company/frontend-hp-test)
(Next.js).

## What it does

- **Orders CRUD** with their items and products, protected by an API key.
- **Schedule computation**: for each order due in the future, production runs back to back;
  the production time of each item depends on the product's production speed, and a
  **changeover delay** is added whenever the product type changes. An order that would end
  after its due date is moved earlier so that it ends on that date.

## How it is built

Refactored along SOLID principles: thin controllers, a `ScheduleService`
that orchestrates, a `ProductionTimeCalculator` behind an interface (swappable calculation
rule), a `ChangeoverDelayService`, and an `OrderRepositoryInterface` for data access.

## Run it

```bash
cp .env.example .env   # set API_KEY and the DB_* settings
composer install
php artisan migrate --seed
php artisan serve
php artisan test
```

| Route | Method | Purpose |
|---|---|---|
| `/api/orders` | GET | List orders |
| `/api/order` | POST | Create an order |
| `/api/order/{id}` | PUT / DELETE | Update / delete an order |
| `/api/get-order/{id}` | GET | One order |
| `/api/products` | GET | List products |
| `/schedule` | GET | Computed production schedule |

## Known limits (as delivered)

- Orders are scheduled in database order, not sorted by due date, and the schedule is not
  optimized (a sort by due date and a changeover-aware ordering are the next steps).
- Moving a late order earlier can make it overlap the previous one, or start in the past:
  the next step is to flag such an order as late rather than overlap the line.
- Test coverage is minimal (one model test).
- CORS allows every origin (`'*'`): it must be restricted to the front end's URL in
  production, and CSRF protection added on write routes.
- The demo that was hosted at the time is no longer online.

---

<a id="français"></a>

# API de planification de production — test technique

Test technique de l'automne 2024 : une API REST qui gère les commandes clients d'une ligne de
production et calcule le **planning de production**. Réalisée en **Laravel 11 / PHP 8.2**
avec MySQL. Son front est [`frontend-hp-test`](https://github.com/mriviere-company/frontend-hp-test)
(Next.js).

## Ce qu'elle fait

- **CRUD des commandes**, avec leurs lignes et leurs produits, protégé par une clé d'API.
- **Calcul du planning** : les commandes à échéance future sont produites à la suite ; le
  temps de production de chaque ligne dépend de la cadence du produit, et un **délai de
  changement de série** s'ajoute à chaque changement de type de produit. Une commande qui
  finirait après sa date due est avancée pour se terminer à cette date.

## Conception

Refactorisée selon les principes SOLID : contrôleurs fins, un
`ScheduleService` qui orchestre, un `ProductionTimeCalculator` derrière une interface (règle
de calcul interchangeable), un `ChangeoverDelayService` et un `OrderRepositoryInterface`
pour l'accès aux données.

## Limites connues (à la livraison)

- Les commandes sont planifiées dans l'ordre de la base, sans tri par date due ni
  optimisation : trier par échéance et regrouper les séries sont les étapes suivantes.
- Avancer une commande en retard peut la faire chevaucher la précédente, voire commencer
  dans le passé : il faudrait plutôt la signaler comme en retard.
- Couverture de tests minimale (un test de modèle).
- CORS ouvert à toutes les origines (`'*'`) : à restreindre à l'URL du front en production,
  avec une protection CSRF sur les routes d'écriture.
- La démo hébergée à l'époque n'est plus en ligne.
