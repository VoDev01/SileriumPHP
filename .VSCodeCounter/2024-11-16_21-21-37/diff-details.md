# Diff Details

Date : 2024-11-16 21:21:37

Directory c:\\Users\\admin\\source\\repos\\Silerium_laravel

Total : 38 files,  356 codes, 30 comments, 37 blanks, all 423 lines

[Summary](results.md) / [Details](details.md) / [Diff Summary](diff.md) / Diff Details

## Files
| filename | language | code | comment | blank | total |
| :--- | :--- | ---: | ---: | ---: | ---: |
| [app/Http/Controllers/API/V1/APIProductsController.php](/app/Http/Controllers/API/V1/APIProductsController.php) | PHP | 12 | 0 | 2 | 14 |
| [app/Http/Controllers/API/V1/APIUsersController.php](/app/Http/Controllers/API/V1/APIUsersController.php) | PHP | -1 | 0 | 0 | -1 |
| [app/Http/Controllers/Admin/ProductsAdminPanelController.php](/app/Http/Controllers/Admin/ProductsAdminPanelController.php) | PHP | -13 | 0 | 0 | -13 |
| [app/Http/Controllers/Admin/UsersAdminPanelController.php](/app/Http/Controllers/Admin/UsersAdminPanelController.php) | PHP | -29 | 0 | 0 | -29 |
| [app/Http/Controllers/Seller/SellerAuthController.php](/app/Http/Controllers/Seller/SellerAuthController.php) | PHP | 10 | 0 | 0 | 10 |
| [app/Http/Controllers/Seller/SellerController.php](/app/Http/Controllers/Seller/SellerController.php) | PHP | 5 | 0 | 0 | 5 |
| [app/Http/Controllers/Seller/SellerOrdersController.php](/app/Http/Controllers/Seller/SellerOrdersController.php) | PHP | 6 | -1 | 0 | 5 |
| [app/Http/Controllers/Seller/SellerProductsController.php](/app/Http/Controllers/Seller/SellerProductsController.php) | PHP | 66 | 0 | 2 | 68 |
| [app/Http/Middleware/Authenticate.php](/app/Http/Middleware/Authenticate.php) | PHP | 5 | 0 | 0 | 5 |
| [app/Http/Requests/API/APIProductsRequest.php](/app/Http/Requests/API/APIProductsRequest.php) | PHP | -22 | -10 | -5 | -37 |
| [app/Http/Requests/API/APIProductsSearchRequest.php](/app/Http/Requests/API/APIProductsSearchRequest.php) | PHP | -17 | -10 | -5 | -32 |
| [app/Http/Requests/API/APIUserSearchRequest.php](/app/Http/Requests/API/APIUserSearchRequest.php) | PHP | 21 | 10 | 5 | 36 |
| [app/Http/Requests/API/Products/APIProductsCreateRequest.php](/app/Http/Requests/API/Products/APIProductsCreateRequest.php) | PHP | 22 | 10 | 5 | 37 |
| [app/Http/Requests/API/Products/APIProductsDeleteRequest.php](/app/Http/Requests/API/Products/APIProductsDeleteRequest.php) | PHP | 16 | 10 | 5 | 31 |
| [app/Http/Requests/API/Products/APIProductsSearchRequest.php](/app/Http/Requests/API/Products/APIProductsSearchRequest.php) | PHP | 20 | 10 | 5 | 35 |
| [app/Http/Requests/API/Products/APIProductsUpdateRequest.php](/app/Http/Requests/API/Products/APIProductsUpdateRequest.php) | PHP | 21 | 10 | 5 | 36 |
| [app/Services/ManualPaginatorService.php](/app/Services/ManualPaginatorService.php) | PHP | 0 | 0 | 1 | 1 |
| [app/Services/SearchFormKeyAuthService.php](/app/Services/SearchFormKeyAuthService.php) | PHP | 30 | 0 | 4 | 34 |
| [app/Services/UpdateSessionValueJson.php](/app/Services/UpdateSessionValueJson.php) | PHP | 22 | 0 | 3 | 25 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormCheckboxInputs.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormCheckboxInputs.php) | PHP | 0 | 0 | 1 | 1 |
| [app/View/Components/ComponentsInputs/SearchForm/SearchFormQueryInputs.php](/app/View/Components/ComponentsInputs/SearchForm/SearchFormQueryInputs.php) | PHP | 2 | 0 | 0 | 2 |
| [app/View/Components/ComponentsMethods/SearchForm/SearchFormSearchMethod.php](/app/View/Components/ComponentsMethods/SearchForm/SearchFormSearchMethod.php) | PHP | 39 | 1 | 4 | 44 |
| [app/View/Components/SearchForm.php](/app/View/Components/SearchForm.php) | PHP | -4 | 0 | 0 | -4 |
| [config/app.php](/config/app.php) | PHP | -4 | 0 | 0 | -4 |
| [database/seeders/ProductSeeder.php](/database/seeders/ProductSeeder.php) | PHP | 1 | 0 | 0 | 1 |
| [database/seeders/UserSeeder.php](/database/seeders/UserSeeder.php) | PHP | 2 | 0 | 0 | 2 |
| [resources/views/admin/products/index.blade.php](/resources/views/admin/products/index.blade.php) | PHP | -7 | 0 | 0 | -7 |
| [resources/views/admin/products/reviews.blade.php](/resources/views/admin/products/reviews.blade.php) | PHP | 6 | 0 | 0 | 6 |
| [resources/views/components/search-form.blade.php](/resources/views/components/search-form.blade.php) | PHP | 1 | 0 | 0 | 1 |
| [resources/views/seller/account.blade.php](/resources/views/seller/account.blade.php) | PHP | 4 | 0 | 0 | 4 |
| [resources/views/seller/orders/orders.blade.php](/resources/views/seller/orders/orders.blade.php) | PHP | 27 | 0 | -1 | 26 |
| [resources/views/seller/products/delete.blade.php](/resources/views/seller/products/delete.blade.php) | PHP | 1 | 0 | 1 | 2 |
| [resources/views/seller/products/index.blade.php](/resources/views/seller/products/index.blade.php) | PHP | -31 | 0 | -1 | -32 |
| [resources/views/seller/products/products-list.blade.php](/resources/views/seller/products/products-list.blade.php) | PHP | 43 | 0 | 1 | 44 |
| [resources/views/seller/products/update.blade.php](/resources/views/seller/products/update.blade.php) | PHP | 49 | 0 | 1 | 50 |
| [routes/web/seller.php](/routes/web/seller.php) | PHP | 17 | 0 | 0 | 17 |
| [tests/Feature/API/APIProductTest.php](/tests/Feature/API/APIProductTest.php) | PHP | 34 | 0 | 4 | 38 |
| [tests/Feature/Seller/SellerAuthTest.php](/tests/Feature/Seller/SellerAuthTest.php) | PHP | 2 | 0 | 0 | 2 |

[Summary](results.md) / [Details](details.md) / [Diff Summary](diff.md) / Diff Details