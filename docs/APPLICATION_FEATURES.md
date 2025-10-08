# Application features

## Authentication and onboarding
- Nursery operators can register, log in, and manage passwords through dedicated routes for registration, login, reset links, and password updates, with support for OAuth social providers and email verification flows.【F:routes/web.php†L47-L129】
- Profile management lets authenticated nursery staff update their account details or deactivate their profile, and provides an explicit logout endpoint.【F:routes/web.php†L47-L53】

## Nursery operations dashboard
- The dashboard aggregates counts for seedling services, purchase requests, warehouse items, and seed sales for the signed-in nursery, alongside installment summaries to highlight upcoming collections and dues.【F:app/Http/Controllers/NurseryUserDashboardController.php†L14-L45】

## Seedling services lifecycle
- Nurseries can list, create, update, and delete seedling services with filtering, media uploads, and status changes, while exporting reports or sharing offerings with other nurseries and farmers at configurable prices.【F:routes/web.php†L94-L101】【F:app/Http/Controllers/SeedlingServiceController.php†L21-L205】【F:app/Http/Controllers/SeedlingServiceController.php†L243-L320】
- Shared seedlings can be browsed with visibility into reservations and tray availability, and the system prevents unauthorized sharing or status updates by enforcing role checks.【F:app/Http/Controllers/SeedlingServiceController.php†L262-L305】【F:app/Http/Controllers/SeedlingServiceController.php†L309-L320】

## Seed sales management
- Seed sales records track farm customers, associated warehouse inventory, payment methods (cash or installments), and support status updates, exports, and auditing of installment schedules.【F:routes/web.php†L102-L108】【F:app/Http/Controllers/NurserySeedsSaleController.php†L19-L188】

## Seedling purchase requests
- Nurseries can file or edit purchase requests for their own services or shared seedlings, capture who requested them (farmer or nursery), and manage payments, with exports and deletion guarded by admin-only permissions.【F:routes/web.php†L109-L147】【F:app/Http/Controllers/SeedlingPurchaseRequestController.php†L20-L167】
- Reserved shared seedlings trigger dedicated endpoints for creating reservation requests and updating their approval status, including automatic creation of new seedling services when requests are accepted.【F:routes/web.php†L144-L147】【F:app/Http/Controllers/SeedlingPurchaseRequestController.php†L170-L235】

## Warehouse inventory tracking
- The warehouse module records incoming seed lots from supply stores with entity types, quantities, pricing, and payment plans, offers filtering and export, and protects updates with authorization checks.【F:routes/web.php†L112-L118】【F:app/Http/Controllers/NurseryWarehouseEntityController.php†L20-L178】

## CRM helpers and reference data
- Quick search and inline creation endpoints help nurseries maintain contact lists for farmers and agricultural supply stores, ensuring mobile numbers remain unique and linked to the nursery.【F:routes/web.php†L85-L90】【F:app/Http/Controllers/FarmUserController.php†L11-L48】【F:app/Http/Controllers/AgriculturalSupplyStoreUserController.php†L10-L37】
- Seed type search and creation APIs streamline categorizing services, warehouse entries, and sales with a consistent reference list.【F:routes/web.php†L91-L92】【F:app/Http/Controllers/SeedTypeController.php†L10-L34】

## Season planning
- A reusable season ledger lets nurseries define start and end dates for planting cycles, then associate those seasons with farms, services, sales, inventory, installments, or products for unified reporting.【F:database/migrations/2024_12_18_000000_create_seasons_table.php†L1-L26】【F:app/Traits/HasSeasons.php†L9-L40】
