<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
    $route['default_controller'] = 'welcome';
    $route['404_override'] = '';
    $route['translate_uri_dashes'] = FALSE;
// CMS
    $route['api/v1/cms/slider'] = 'Api-V1/Cms/slider';
    $route['api/v1/cms/logos'] = 'Api-V1/Cms/logos';
    $route['api/v1/cms/section6'] = 'Api-V1/Cms/section6';
    $route['api/v1/cms/section71'] = 'Api-V1/Cms/section71';
    $route['api/v1/cms/section72'] = 'Api-V1/Cms/section72';
    $route['api/v1/cms/section73'] = 'Api-V1/Cms/section73';
    $route['api/v1/cms/link1'] = 'Api-V1/Cms/link1';
    $route['api/v1/cms/link2'] = 'Api-V1/Cms/link2';
    $route['api/v1/cms/tel'] = 'Api-V1/Cms/tel';
    $route['api/v1/cms/mail'] = 'Api-V1/Cms/mail';
    $route['api/v1/cms/address'] = 'Api-V1/Cms/address';
    $route['api/v1/cms/icons-social'] = 'Api-V1/Cms/iconsSocial';
    $route['api/v1/cms/icons-social-footer'] = 'Api-V1/Cms/iconsSocialFooter';

// CART
    $route['api/v1/cart'] = 'Api-V1/Cart';
    $route['api/v1/cart/(:any)'] = 'Api-V1/Cart/$1';
    $route['api/v1/cart/clear/(:any)'] = 'Api-V1/Cart/clear/$1';
    $route['api/v1/cart/details'] = 'Api-V1/Cart/details';
    $route['api/v1/cart/validateDraw'] = 'Api-V1/Cart/validateDraw';
    $route['api/v1/cart/validateCod'] = 'Api-V1/Cart/validateCod';
// SALES 
    $route['api/v1/saleweb/(:any)'] = 'Api-V1/SaleWeb/$1';
    $route['api/v1/sale'] = 'Api-V1/Sale';
    $route['api/v1/saleweb'] = 'Api-V1/SaleWeb';
// RESERVES
    $route['api/v1/reserve'] = 'Api-V1/Reserve';
    $route['api/v1/reserve/(:any)'] = 'Api-V1/Reserve/$1';

// LOCATION
    $route['api/v1/location'] = 'Api-V1/Location';

// LOTTERY
    $route['api/v1/lottery/results'] = 'Api-V1/Lottery/results';
    $route['api/v1/lottery/(:any)/historical-results'] = 'Api-V1/Lottery/historical_results/$1';
    $route['api/v1/lottery/(:any)/validate-winning'] = 'Api-V1/Lottery/validate_winning/$1';
    $route['api/v1/lottery'] = 'Api-V1/Lottery';
    $route['api/v1/lottery/(:any)/draws-dates'] = 'Api-V1/Lottery/draws_dates/$1';
    $route['api/v1/lottery/lottery-logo'] = 'Api-V1/Lottery/lottery_logo';
    $route['api/v1/lottery/budget-commitment/(:any)'] = 'Api-V1/Lottery/budget_commitment/$1';
    
// PLACETOPAY
    $route['api/v1/pay'] = 'Api-V1/PlaceToPay';
    $route['api/v1/pay/(:any)'] = 'Api-V1/PlaceToPay/$1';

// PLAY
    $route['api/v1/play'] = 'Api-V1/Play';
    $route['api/v1/play/luck'] = 'Api-V1/Play/luck';
    $route['api/v1/play/fractions-available'] = 'Api-V1/Play/fractions_available';
    $route['api/v1/play/reserve'] = 'Api-V1/Play/reserve';

// PRIZE
    $route['api/v1/prize/my-prizes/(:any)'] = 'Api-V1/prize/my_prizes/$1';
    $route['api/v1/prize/collect_prize'] = 'Api-V1/Prize/collect_prize';
    $route['api/v1/prize/bumpers'] = 'Api-V1/Prize/bumpers';
    $route['api/v1/prize/change-status-prize/(:any)'] = 'Api-V1/prize/change_status_prize/$1';
    $route['api/v1/prize'] = 'Api-V1/Prize';

// SECURITY
    $route['api/v1/security/login'] = 'Api-V1/Security/login';
    $route['api/v1/security/validation/change-password-code'] = 'Api-V1/Security/change_password_code';
    $route['api/v1/security/password-change'] = 'Api-V1/Security/password_change';
    $route['api/v1/security/validation/exists-dni'] = 'Api-V1/Security/exists_dni';
    $route['api/v1/security'] = 'Api-V1/Security';
    $route['api/v1/security/validate-divice'] = 'Api-V1/Security/validate_divice';
// PRODUCTS
    $route['api/v1/product/add_product'] = 'Api-V1/Prize/add_product';
    $route['api/v1/product/update_product'] = 'Api-V1/Prize/update_product';

// SHOPPING
    $route['api/v1/shopping/purchase-detail'] = 'Api-V1/shopping/purchase_detail';
    $route['api/v1/shopping/(:any)'] = 'Api-V1/shopping/$1';
    $route['api/v1/shopping/day/(:any)'] = 'Api-V1/shopping/day/$1';

// POINTS
    $route['api/v1/points'] = 'Api-V1/Points';
    $route['api/v1/points/code-commitment'] = 'Api-V1/Points/code_commitment';
    $route['api/v1/(:any)/points'] = 'Api-V1/Points/$1';
    $route['api/v1/(:any)/points/beat-points'] = 'Api-V1/Points/beat_points/$1';
    $route['api/v1/(:any)/points/change-points'] = 'Api-V1/Points/change_points/$1';
    $route['api/v1/(:any)/points/points-x-articule'] = 'Api-V1/Points/points_x_articule/$1';
    
// BALANCE
    $route['api/v1/(:any)/balance'] = 'Api-V1/Balance/$1';
    $route['api/v1/(:any)/balance/my-moves'] = 'Api-V1/balance/my_moves/$1';
    
// USER
    $route['api/v1/user'] = 'Api-V1/User';
    $route['api/v1/user/departaments'] = 'Api-V1/User/departaments';
    $route['api/v1/user/cities/(:any)'] = 'Api-V1/User/cities/$1';
    $route['api/v1/user/active-count'] = 'Api-V1/User/active_count';
    $route['api/v1/user/change-code-request'] = 'Api-V1/User/change_code_request';
    $route['api/v1/user/validate-code'] = 'Api-V1/User/validate_code';
    $route['api/v1/user/change-password'] = 'Api-V1/User/change_password';
    $route['api/v1/user/my-referals/(:any)'] = 'Api-V1/User/my_referals/$1';
    $route['api/v1/user/invite'] = 'Api-V1/User/invite';
    $route['api/v1/user/my-referals-registers/(:any)'] = 'Api-V1/User/my_referals_registers/$1';
    $route['api/v1/user/(:any)'] = 'Api-V1/User/$1';

// EMAIL
    $route['api/v1/email'] = 'Api-V1/Email';
    $route['api/v1/emailbalance'] = 'Api-V1/EmailBalance';

// PROMOTIONAL
    $route['api/v1/promotional'] = 'Api-V1/Promotional';
    $route['api/v1/promotional/change-state-promotional/(:any)'] = 'Api-V1/promotional/change_state_promotional/$1';
    $route['api/v1/promotional/incentives'] = 'Api-V1/Promotional/incentives';
    $route['api/v1/(:any)/promotional'] = 'Api-V1/Promotional/$1';
    $route['api/v1/(:any)/promotional/scratch-result'] = 'Api-V1/Promotional/scratch_result/$1';
    $route['api/v1/(:any)/promotional/change-bond'] = 'Api-V1/Promotional/change_bond/$1';
    $route['api/v1/(:any)/promotional/promotional-request'] = 'Api-V1/Promotional/promotional_request/$1';  
    $route['api/v1/promotional/validate-code-claim/(:any)'] = 'Api-V1/promotional/validate_code_claim/$1';  

// Discounts
    $route['api/v1/discount/substract/(:any)'] = 'Api-V1/Discount/substractCantCodDiscount/$1';
    
// Transactions
    $route['api/v1/transaction/(:any)'] = 'Api-V1/Transaction/$1';
// Draws
    $route['api/v1/draws'] = 'Api-V1/Draws';