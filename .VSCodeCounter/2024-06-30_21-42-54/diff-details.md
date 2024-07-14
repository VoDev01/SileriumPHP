# Diff Details

Date : 2024-06-30 21:42:54

Directory c:\\Users\\admin\\source\\repos\\Silerium_laravel

Total : 62 files,  1094 codes, 107 comments, 107 blanks, all 1308 lines

[Summary](results.md) / [Details](details.md) / [Diff Summary](diff.md) / Diff Details

## Files
| filename | language | code | comment | blank | total |
| :--- | :--- | ---: | ---: | ---: | ---: |
| [app/Http/Controllers/API/V1/APICategoriesController.php](/app/Http/Controllers/API/V1/APICategoriesController.php) | PHP | 40 | 30 | 8 | 78 |
| [app/Http/Controllers/API/V1/APIHomeController.php](/app/Http/Controllers/API/V1/APIHomeController.php) | PHP | 11 | 0 | 4 | 15 |
| [app/Http/Controllers/API/V1/APIProductsController.php](/app/Http/Controllers/API/V1/APIProductsController.php) | PHP | 57 | 0 | 4 | 61 |
| [app/Http/Controllers/API/V1/APISubcategoriesController.php](/app/Http/Controllers/API/V1/APISubcategoriesController.php) | PHP | 40 | 30 | 9 | 79 |
| [app/Http/Controllers/API/V1/ProductsController.php](/app/Http/Controllers/API/V1/ProductsController.php) | PHP | -46 | -33 | -12 | -91 |
| [app/Http/Controllers/Admin/AdminPanelController.php](/app/Http/Controllers/Admin/AdminPanelController.php) | PHP | 18 | 0 | 4 | 22 |
| [app/Http/Controllers/Admin/ProductsAdminPanelController.php](/app/Http/Controllers/Admin/ProductsAdminPanelController.php) | PHP | 72 | 0 | 4 | 76 |
| [app/Http/Controllers/Admin/UsersAdminPanelController.php](/app/Http/Controllers/Admin/UsersAdminPanelController.php) | PHP | 13 | 0 | 4 | 17 |
| [app/Http/Controllers/CatalogController.php](/app/Http/Controllers/CatalogController.php) | PHP | -97 | 0 | -4 | -101 |
| [app/Http/Controllers/CategoriesController.php](/app/Http/Controllers/CategoriesController.php) | PHP | -17 | 0 | -4 | -21 |
| [app/Http/Controllers/FallbackController.php](/app/Http/Controllers/FallbackController.php) | PHP | 10 | 0 | 4 | 14 |
| [app/Http/Controllers/Product/CatalogController.php](/app/Http/Controllers/Product/CatalogController.php) | PHP | 98 | 0 | 4 | 102 |
| [app/Http/Controllers/Product/CategoriesController.php](/app/Http/Controllers/Product/CategoriesController.php) | PHP | 18 | 0 | 4 | 22 |
| [app/Http/Controllers/UserController.php](/app/Http/Controllers/UserController.php) | PHP | -314 | 0 | -10 | -324 |
| [app/Http/Controllers/User/UserAuthController.php](/app/Http/Controllers/User/UserAuthController.php) | PHP | 147 | 6 | 10 | 163 |
| [app/Http/Controllers/User/UserController.php](/app/Http/Controllers/User/UserController.php) | PHP | 39 | 0 | 4 | 43 |
| [app/Http/Controllers/User/UserOrderController.php](/app/Http/Controllers/User/UserOrderController.php) | PHP | 60 | 0 | 4 | 64 |
| [app/Http/Controllers/User/UserReviewController.php](/app/Http/Controllers/User/UserReviewController.php) | PHP | 98 | 0 | 4 | 102 |
| [app/Http/Kernel.php](/app/Http/Kernel.php) | PHP | 1 | 0 | 0 | 1 |
| [app/Http/Middleware/AuthorizeAdminMiddleware.php](/app/Http/Middleware/AuthorizeAdminMiddleware.php) | PHP | 23 | 7 | 4 | 34 |
| [app/Models/Product.php](/app/Models/Product.php) | PHP | 10 | 0 | 0 | 10 |
| [app/Models/Role.php](/app/Models/Role.php) | PHP | 16 | 0 | 4 | 20 |
| [app/Models/Subcategory.php](/app/Models/Subcategory.php) | PHP | 3 | 0 | 0 | 3 |
| [app/Models/User.php](/app/Models/User.php) | PHP | 4 | 0 | 0 | 4 |
| [app/Providers/AuthServiceProvider.php](/app/Providers/AuthServiceProvider.php) | PHP | 12 | -1 | 0 | 11 |
| [app/Providers/RouteServiceProvider.php](/app/Providers/RouteServiceProvider.php) | PHP | 23 | 0 | -1 | 22 |
| [app/Services/EmailValidationService.php](/app/Services/EmailValidationService.php) | PHP | 4 | 0 | 2 | 6 |
| [app/Services/MakeUserService.php](/app/Services/MakeUserService.php) | PHP | 2 | 0 | 0 | 2 |
| [app/Services/PhoneValidationService.php](/app/Services/PhoneValidationService.php) | PHP | 4 | 0 | 2 | 6 |
| [app/Services/ValidatePasswordHashService.php](/app/Services/ValidatePasswordHashService.php) | PHP | -2 | 5 | 0 | 3 |
| [app/View/Components/APILayout.php](/app/View/Components/APILayout.php) | PHP | 13 | 11 | 5 | 29 |
| [app/View/Components/AdminLayout.php](/app/View/Components/AdminLayout.php) | PHP | 13 | 11 | 5 | 29 |
| [app/View/Components/CustomPagination.php](/app/View/Components/CustomPagination.php) | PHP | 14 | 11 | 5 | 30 |
| [database/migrations/2024_06_16_152404_users_roles.php](/database/migrations/2024_06_16_152404_users_roles.php) | PHP | 23 | 10 | 4 | 37 |
| [database/migrations/2024_06_28_192044_users_email_verified.php](/database/migrations/2024_06_28_192044_users_email_verified.php) | PHP | 21 | 10 | 4 | 35 |
| [database/migrations/2024_06_30_180731_user_emailverified_nullable.php](/database/migrations/2024_06_30_180731_user_emailverified_nullable.php) | PHP | 15 | 10 | 4 | 29 |
| [resources/views/admin/home.blade.php](/resources/views/admin/home.blade.php) | PHP | 43 | 0 | 4 | 47 |
| [resources/views/admin/products/create.blade.php](/resources/views/admin/products/create.blade.php) | PHP | 85 | 0 | 2 | 87 |
| [resources/views/admin/products/delete.blade.php](/resources/views/admin/products/delete.blade.php) | PHP | 84 | 0 | 2 | 86 |
| [resources/views/admin/products/index.blade.php](/resources/views/admin/products/index.blade.php) | PHP | 37 | 0 | 1 | 38 |
| [resources/views/admin/products/update.blade.php](/resources/views/admin/products/update.blade.php) | PHP | 109 | 0 | 1 | 110 |
| [resources/views/admin/profile.blade.php](/resources/views/admin/profile.blade.php) | PHP | 64 | 0 | 0 | 64 |
| [resources/views/admin/traffic.blade.php](/resources/views/admin/traffic.blade.php) | PHP | 0 | 0 | 1 | 1 |
| [resources/views/admin/users/ban.blade.php](/resources/views/admin/users/ban.blade.php) | PHP | 0 | 0 | 1 | 1 |
| [resources/views/admin/users/delete.blade.php](/resources/views/admin/users/delete.blade.php) | PHP | 0 | 0 | 1 | 1 |
| [resources/views/admin/users/index.blade.php](/resources/views/admin/users/index.blade.php) | PHP | 40 | 0 | 0 | 40 |
| [resources/views/admin/users/orders.blade.php](/resources/views/admin/users/orders.blade.php) | PHP | 0 | 0 | 1 | 1 |
| [resources/views/admin/users/reviews.blade.php](/resources/views/admin/users/reviews.blade.php) | PHP | 0 | 0 | 1 | 1 |
| [resources/views/admin/users/roles.blade.php](/resources/views/admin/users/roles.blade.php) | PHP | 0 | 0 | 1 | 1 |
| [resources/views/apihome.blade.php](/resources/views/apihome.blade.php) | PHP | 40 | 0 | 1 | 41 |
| [resources/views/components/admin-layout.blade.php](/resources/views/components/admin-layout.blade.php) | PHP | 62 | 0 | 6 | 68 |
| [resources/views/components/api-layout.blade.php](/resources/views/components/api-layout.blade.php) | PHP | 51 | 0 | 3 | 54 |
| [resources/views/components/custom-pagination.blade.php](/resources/views/components/custom-pagination.blade.php) | PHP | 3 | 0 | 0 | 3 |
| [resources/views/components/layout.blade.php](/resources/views/components/layout.blade.php) | PHP | -4 | 0 | 0 | -4 |
| [resources/views/error.blade.php](/resources/views/error.blade.php) | PHP | 6 | 0 | 0 | 6 |
| [resources/views/user/auth/login.blade.php](/resources/views/user/auth/login.blade.php) | PHP | -5 | 0 | -1 | -6 |
| [routes/api.php](/routes/api.php) | PHP | -10 | -10 | -5 | -25 |
| [routes/api/api.php](/routes/api/api.php) | PHP | 9 | 10 | 4 | 23 |
| [routes/web.php](/routes/web.php) | PHP | -50 | -10 | -11 | -71 |
| [routes/web/admin.php](/routes/web/admin.php) | PHP | 22 | 0 | 5 | 27 |
| [routes/web/user.php](/routes/web/user.php) | PHP | 43 | 0 | 4 | 47 |
| [routes/web/web.php](/routes/web/web.php) | PHP | 19 | 10 | 6 | 35 |

[Summary](results.md) / [Details](details.md) / [Diff Summary](diff.md) / Diff Details