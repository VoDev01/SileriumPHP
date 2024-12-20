# Diff Details

Date : 2024-12-17 21:54:18

Directory c:\\Users\\admin\\source\\repos\\Silerium_laravel

Total : 77 files,  1874 codes, 118 comments, 177 blanks, all 2169 lines

[Summary](results.md) / [Details](details.md) / [Diff Summary](diff.md) / Diff Details

## Files
| filename | language | code | comment | blank | total |
| :--- | :--- | ---: | ---: | ---: | ---: |
| [app/Http/Controllers/API/V1/APIProductsController.php](/app/Http/Controllers/API/V1/APIProductsController.php) | PHP | 14 | 0 | -1 | 13 |
| [app/Http/Controllers/API/V1/APIReviewsController.php](/app/Http/Controllers/API/V1/APIReviewsController.php) | PHP | 3 | 0 | 0 | 3 |
| [app/Http/Controllers/API/V1/APIUsersController.php](/app/Http/Controllers/API/V1/APIUsersController.php) | PHP | 8 | 0 | 0 | 8 |
| [app/Http/Controllers/Admin/ProductsAdminPanelController.php](/app/Http/Controllers/Admin/ProductsAdminPanelController.php) | PHP | -8 | 0 | 3 | -5 |
| [app/Http/Controllers/Admin/UsersAdminPanelController.php](/app/Http/Controllers/Admin/UsersAdminPanelController.php) | PHP | 109 | 0 | 6 | 115 |
| [app/Http/Controllers/BannedController.php](/app/Http/Controllers/BannedController.php) | PHP | 10 | 0 | 4 | 14 |
| [app/Http/Controllers/Product/CatalogController.php](/app/Http/Controllers/Product/CatalogController.php) | PHP | 22 | 0 | 1 | 23 |
| [app/Http/Controllers/Seller/SellerProductsController.php](/app/Http/Controllers/Seller/SellerProductsController.php) | PHP | -5 | 0 | 3 | -2 |
| [app/Http/Controllers/Seller/SellerReviewsController.php](/app/Http/Controllers/Seller/SellerReviewsController.php) | PHP | -7 | -1 | -4 | -12 |
| [app/Http/Kernel.php](/app/Http/Kernel.php) | PHP | 7 | 0 | 0 | 7 |
| [app/Http/Middleware/AuthorizeAdminApiMiddleware.php](/app/Http/Middleware/AuthorizeAdminApiMiddleware.php) | PHP | 26 | 7 | 4 | 37 |
| [app/Http/Middleware/AuthorizeAdminPanelMiddleware.php](/app/Http/Middleware/AuthorizeAdminPanelMiddleware.php) | PHP | -3 | 0 | 0 | -3 |
| [app/Http/Middleware/AuthorizeApiKeyMiddleware.php](/app/Http/Middleware/AuthorizeApiKeyMiddleware.php) | PHP | 26 | 7 | 4 | 37 |
| [app/Http/Middleware/AuthorizeApiLoggedInMiddleware.php](/app/Http/Middleware/AuthorizeApiLoggedInMiddleware.php) | PHP | 26 | 7 | 4 | 37 |
| [app/Http/Middleware/AuthorizeSellerAdminApiMiddleware.php](/app/Http/Middleware/AuthorizeSellerAdminApiMiddleware.php) | PHP | 25 | 7 | 4 | 36 |
| [app/Http/Middleware/AuthorizeSellerApiMiddleware.php](/app/Http/Middleware/AuthorizeSellerApiMiddleware.php) | PHP | 26 | 7 | 4 | 37 |
| [app/Http/Middleware/CheckBannedUser.php](/app/Http/Middleware/CheckBannedUser.php) | PHP | 40 | 7 | 4 | 51 |
| [app/Http/Middleware/TimezoneBasedOnIP.php](/app/Http/Middleware/TimezoneBasedOnIP.php) | PHP | 13 | 7 | 4 | 24 |
| [app/Http/Requests/API/Products/APIProductsSearchRequest.php](/app/Http/Requests/API/Products/APIProductsSearchRequest.php) | PHP | 0 | 0 | -1 | -1 |
| [app/Http/Requests/API/Reviews/APIDeleteReviewRequest.php](/app/Http/Requests/API/Reviews/APIDeleteReviewRequest.php) | PHP | 1 | -1 | 0 | 0 |
| [app/Http/Requests/API/Reviews/APIUpdateReviewRequest.php](/app/Http/Requests/API/Reviews/APIUpdateReviewRequest.php) | PHP | 6 | -1 | 0 | 5 |
| [app/Http/Requests/API/Reviews/APIUserReviewsSearchRequest.php](/app/Http/Requests/API/Reviews/APIUserReviewsSearchRequest.php) | PHP | 2 | 0 | 0 | 2 |
| [app/Http/Requests/User/UserBanRequest.php](/app/Http/Requests/User/UserBanRequest.php) | PHP | 20 | 10 | 5 | 35 |
| [app/Models/BannedUser.php](/app/Models/BannedUser.php) | PHP | 23 | 0 | 8 | 31 |
| [app/Models/User.php](/app/Models/User.php) | PHP | 33 | 0 | 0 | 33 |
| [app/Models/UserApiKey.php](/app/Models/UserApiKey.php) | PHP | 27 | 0 | 8 | 35 |
| [app/Policies/OrderPolicy.php](/app/Policies/OrderPolicy.php) | PHP | 47 | 0 | 10 | 57 |
| [app/Policies/ProductPolicy.php](/app/Policies/ProductPolicy.php) | PHP | 38 | 0 | 9 | 47 |
| [app/Policies/ReviewPolicy.php](/app/Policies/ReviewPolicy.php) | PHP | 37 | 0 | 9 | 46 |
| [app/Providers/AuthServiceProvider.php](/app/Providers/AuthServiceProvider.php) | PHP | 53 | -1 | 5 | 57 |
| [app/Services/SearchFormKeyAuthService.php](/app/Services/SearchFormKeyAuthService.php) | PHP | -30 | 0 | -4 | -34 |
| [app/Services/SearchFormPaginateResponseService.php](/app/Services/SearchFormPaginateResponseService.php) | PHP | 24 | 0 | 4 | 28 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormCheckboxInput.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormCheckboxInput.php) | PHP | 9 | 0 | 3 | 12 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormCheckboxInputs.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormCheckboxInputs.php) | PHP | -9 | 0 | -3 | -12 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormHiddenInput.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormHiddenInput.php) | PHP | 18 | 0 | 4 | 22 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormHiddenInputs.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormHiddenInputs.php) | PHP | -18 | 0 | -4 | -22 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormInput.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormInput.php) | PHP | 27 | 0 | 3 | 30 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormInputs.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormInputs.php) | PHP | -27 | 0 | -3 | -30 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormQueryInput.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormQueryInput.php) | PHP | 14 | 0 | 3 | 17 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormQueryInputs.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormQueryInputs.php) | PHP | -16 | 0 | -3 | -19 |
| [app/View/Components/ComponentsMethods/SearchForm/SearchFormProductsSearchMethod.php](/app/View/Components/ComponentsMethods/SearchForm/SearchFormProductsSearchMethod.php) | PHP | 17 | 0 | 0 | 17 |
| [app/View/Components/ComponentsMethods/SearchForm/SearchFormReviewsSearchMethod.php](/app/View/Components/ComponentsMethods/SearchForm/SearchFormReviewsSearchMethod.php) | PHP | -11 | 0 | -4 | -15 |
| [app/View/Components/ComponentsMethods/SearchForm/SearchFormUsersSearchMethod.php](/app/View/Components/ComponentsMethods/SearchForm/SearchFormUsersSearchMethod.php) | PHP | 23 | -1 | 1 | 23 |
| [composer.json](/composer.json) | JSON | 2 | 0 | 0 | 2 |
| [composer.lock](/composer.lock) | JSON | 516 | 0 | 0 | 516 |
| [database/factories/BannedUserFactory.php](/database/factories/BannedUserFactory.php) | PHP | 15 | 8 | 4 | 27 |
| [database/factories/RoleFactory.php](/database/factories/RoleFactory.php) | PHP | 12 | 8 | 4 | 24 |
| [database/factories/UserApiKeyFactory.php](/database/factories/UserApiKeyFactory.php) | PHP | 13 | 8 | 4 | 25 |
| [database/migrations/2024_12_05_181330_banned_users.php](/database/migrations/2024_12_05_181330_banned_users.php) | PHP | 24 | 10 | 4 | 38 |
| [database/migrations/2024_12_09_192735_users_api_keys.php](/database/migrations/2024_12_09_192735_users_api_keys.php) | PHP | 22 | 10 | 4 | 36 |
| [database/migrations/2024_12_16_164353_banned_users_admin_id_foreign.php](/database/migrations/2024_12_16_164353_banned_users_admin_id_foreign.php) | PHP | 21 | 10 | 4 | 35 |
| [database/migrations/2024_12_17_172440_banned_users_datetime.php](/database/migrations/2024_12_17_172440_banned_users_datetime.php) | PHP | 22 | 10 | 4 | 36 |
| [database/seeders/RolesSeeder.php](/database/seeders/RolesSeeder.php) | PHP | -4 | 0 | 0 | -4 |
| [resources/views/admin/index.blade.php](/resources/views/admin/index.blade.php) | PHP | -7 | 0 | -3 | -10 |
| [resources/views/admin/products/delete.blade.php](/resources/views/admin/products/delete.blade.php) | PHP | -1 | 0 | 1 | 0 |
| [resources/views/admin/products/index.blade.php](/resources/views/admin/products/index.blade.php) | PHP | 5 | 0 | 0 | 5 |
| [resources/views/admin/products/reviews.blade.php](/resources/views/admin/products/reviews.blade.php) | PHP | 79 | 0 | 1 | 80 |
| [resources/views/admin/products/update.blade.php](/resources/views/admin/products/update.blade.php) | PHP | 29 | 0 | 0 | 29 |
| [resources/views/admin/users/ban.blade.php](/resources/views/admin/users/ban.blade.php) | PHP | 107 | 0 | 0 | 107 |
| [resources/views/admin/users/index.blade.php](/resources/views/admin/users/index.blade.php) | PHP | 30 | 0 | 1 | 31 |
| [resources/views/admin/users/orders.blade.php](/resources/views/admin/users/orders.blade.php) | PHP | 46 | 0 | -3 | 43 |
| [resources/views/admin/users/reviews.blade.php](/resources/views/admin/users/reviews.blade.php) | PHP | 46 | 0 | -1 | 45 |
| [resources/views/admin/users/roles.blade.php](/resources/views/admin/users/roles.blade.php) | PHP | -35 | 0 | -3 | -38 |
| [resources/views/banned.blade.php](/resources/views/banned.blade.php) | PHP | 41 | 0 | 3 | 44 |
| [resources/views/catalog/products.blade.php](/resources/views/catalog/products.blade.php) | PHP | -9 | 0 | 0 | -9 |
| [resources/views/components/search-form.blade.php](/resources/views/components/search-form.blade.php) | PHP | -1 | 0 | 0 | -1 |
| [resources/views/seller/products/reviews.blade.php](/resources/views/seller/products/reviews.blade.php) | PHP | 66 | 0 | -1 | 65 |
| [routes/api/api.php](/routes/api/api.php) | PHP | 10 | 0 | 2 | 12 |
| [routes/web/admin.php](/routes/web/admin.php) | PHP | 5 | 0 | 1 | 6 |
| [routes/web/seller.php](/routes/web/seller.php) | PHP | 10 | 0 | 2 | 12 |
| [routes/web/user.php](/routes/web/user.php) | PHP | 10 | 0 | 2 | 12 |
| [routes/web/web.php](/routes/web/web.php) | PHP | 2 | 0 | 1 | 3 |
| [tests/Feature/API/APIProductTest.php](/tests/Feature/API/APIProductTest.php) | PHP | 33 | 0 | 12 | 45 |
| [tests/Feature/API/APIReviewTest.php](/tests/Feature/API/APIReviewTest.php) | PHP | 41 | 0 | 16 | 57 |
| [tests/Feature/API/APISubcategoriesTest.php](/tests/Feature/API/APISubcategoriesTest.php) | PHP | 38 | 0 | 10 | 48 |
| [tests/Feature/API/APIUsersTest.php](/tests/Feature/API/APIUsersTest.php) | PHP | 10 | 0 | 3 | 13 |
| [tests/Feature/User/BanUserTest.php](/tests/Feature/User/BanUserTest.php) | PHP | 36 | 0 | 15 | 51 |

[Summary](results.md) / [Details](details.md) / [Diff Summary](diff.md) / Diff Details