<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AccountUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\AssistantController;
use App\Http\Controllers\Admin\BillItemCategoryController;
use App\Http\Controllers\Admin\BillItemController;
use App\Http\Controllers\Admin\CashFlowController;
use App\Http\Controllers\Admin\CommissionBatchController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DiscoveryController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FileCategoryController;
use App\Http\Controllers\Admin\GraphController;
use App\Http\Controllers\Admin\IntegrationController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LeadTypeController;
use App\Http\Controllers\Admin\MarketController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OriginController;
use App\Http\Controllers\Admin\PackageBuildController;
use App\Http\Controllers\Admin\PackageSectionController;
use App\Http\Controllers\Admin\PackageSectionQuestionController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuestionLogicController;
use App\Http\Controllers\Admin\QuestionOptionController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\SalesFunnelController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\TagCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaxCollectionController;
use App\Http\Controllers\Admin\TaxLocationController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\VersionController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\Sales\SalesAccountController;
use App\Http\Controllers\Sales\SalesCommissionController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Sales\SalesLeadController;
use App\Http\Controllers\Sales\SalesQuoteController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\GuestCartController;
use App\Http\Controllers\Shop\GuestCheckoutController;
use App\Http\Controllers\Shop\PresalesController;
use App\Http\Controllers\Shop\ShopAccountController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\TfaController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingController::class, 'index']);

Route::get('control/bypass', [LandingController::class, 'bypass']);
Route::get('login', [LandingController::class, 'login'])->name('login');
Route::get('forgot', [LandingController::class, 'forgot']);
Route::post('forgot', [LandingController::class, 'forgotSend']);
Route::get('forgot/{hash}', [LandingController::class, 'forgotAttempt']);

Route::post('login', [LandingController::class, 'attempt']);
Route::get('logout', [LandingController::class, 'logout']);
Route::get('install', [InstallController::class, 'index']);
Route::post('install', [InstallController::class, 'store']);
Route::get('file/{hash}', [FileController::class, 'get']);

// Oauth Callbacks
Route::get('oa/{service}/authorize', [OAuthController::class, 'auth']);
Route::get('oa/{service}/callback', [OAuthController::class, 'callback']);

// Verify Email
Route::get('verify/{hash}', [LandingController::class, 'verify']);

//Route::get('morning', [LandingController::class, 'morning']);

// Credit Card Update
Route::get('payment/{hash}', [ShopAccountController::class, 'paymentForm']);
// Signature Save -- Stores in session
Route::post('signature/save', [ShopController::class, 'saveSignature']);


// Logged in user regardless of ACL
Route::group(['middleware' => ['auth']], function () {
    Route::get('mode/toggle', [UserController::class, 'toggleMode']);
    Route::get('unshadow', [UserController::class, 'unshadow']);
});

// 2fa routes outside 2fa middleware
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('account-verification', [TfaController::class, 'index']);
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin', '2fa']], function () {
    Route::resource('users', UserController::class);
    Route::resource('accounts.users', AccountUserController::class);
    Route::resource('origins', OriginController::class);
    Route::resource('categories.tag_categories', TagCategoryController::class);
    Route::resource('categories.tag_categories.tags', TagController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('file_categories', FileCategoryController::class);
    Route::resource('meetings', MeetingController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('affiliates', AffiliateController::class);
    Route::resource('tax_locations', TaxLocationController::class);
    Route::resource('tax_locations.tax_collections', TaxCollectionController::class);
    Route::get('partners/{partner}/remote/invoice/{id}', [PartnerController::class, 'getRemoteInvoice']);
    Route::get('partners/{partner}/invoice/{id}', [PartnerController::class, 'getLocalInvoice']);


    // Admin Shop Asisstant Routes
    Route::get('cart/{uid}', [AssistantController::class, 'show']);
    Route::get('cart/{uid}/add/{type}', [AssistantController::class, 'addModal']);
    Route::post('cart/{uid}/add/{type}', [AssistantController::class, 'addItem']);

    Route::get('cart/{uid}/item/{id}', [AssistantController::class, 'showItem']);
    Route::post('cart/{uid}/item/{id}', [AssistantController::class, 'updateItem']);
    Route::delete('cart/{uid}/item/{id}', [AssistantController::class, 'removeItem']);

    Route::get('cart/{uid}/prepare/{command}', [AssistantController::class, 'prepare']);
    Route::get('cart/{uid}/command/{command}', [AssistantController::class, 'sendCommand']);
    Route::post('cart/{uid}/command/{command}', [AssistantController::class, 'sendCommand']);

    Route::post('coupons/{coupon}/items', [CouponController::class, 'addItem']);
    Route::get('coupons/{coupon}/items/{item}', [CouponController::class, 'editItem']);
    Route::put('coupons/{coupon}/items/{item}', [CouponController::class, 'updateItem']);
    Route::delete('coupons/{coupon}/items/{item}', [CouponController::class, 'delItem']);
    // Feedback
    Route::get('feedback', [AdminDashboardController::class, 'feedback']);
    Route::get('bug', [AdminDashboardController::class, 'bugs']);
    Route::get('account_item/{item}', [AccountController::class, 'redirectItem']);

    // Account Import
    Route::get('accounts/import/csv', [AccountController::class, 'importModal']);
    Route::post('accounts/import/csv', [AccountController::class, 'import']);

    // Account Section Routes
    Route::get('accounts/{account}/overview', [AccountController::class, 'overview']);

    // Account Services
    Route::get('accounts/{account}/services', [AccountController::class, 'services']);
    Route::get('accounts/{account}/services/{item}', [AccountController::class, 'editItem']);
    Route::get('accounts/{account}/services/add/{item}', [AccountController::class, 'addItem']);
    Route::put('accounts/{account}/services/{item}', [AccountController::class, 'updateItem']);
    Route::delete('accounts/{account}/services/{item}', [AccountController::class, 'delItem']);

    // Account Invoices
    Route::get('accounts/{account}/invoices', [AccountController::class, 'invoices']);

    // Account Users
    Route::get('accounts/{account}/users', [AccountController::class, 'users']);
    Route::get('accounts/{account}/users/{user}/reset', [AccountUserController::class, 'resetUser']);
    Route::get('accounts/{account}/users/{user}/shadow', [AccountUserController::class, 'shadow']);

    // Creating quotes from an Account
    Route::get('accounts/{account}/quotes', [AccountController::class, 'quotes']);

    // Account Events
    Route::get('accounts/{account}/events', [AccountController::class, 'events']);
    // Account Billing
    Route::get('accounts/{account}/billing', [AccountController::class, 'billing']);
    Route::put('accounts/{account}/billing', [AccountController::class, 'billingUpdate']);
    // Account Profile
    Route::get('accounts/{account}/profile', [AccountController::class, 'profile']);

    Route::get('accounts/{account}/pricing', [AccountController::class, 'pricing']);
    Route::get('accounts/{account}/pricing/{type}/add', [AccountController::class, 'pricingModal']);
    Route::get('accounts/{account}/pricing/{item}', [AccountController::class, 'pricingApply']);
    Route::delete('accounts/{account}/pricing/{item}', [AccountController::class, 'pricingRemove']);

    Route::post('accounts/{account}/pricing/{item}/live', [AccountController::class, 'pricingUpdate']);
    Route::get('accounts/{account}/files', [AccountController::class, 'files']);

    Route::get('accounts/{account}/statement', [AccountController::class, 'statement']);
    Route::get('accounts/{account}/paymentRequest', [AccountController::class, 'paymentRequest']);
    Route::post('accounts/{account}/s3', [AccountController::class, 'updateS3']);

    Route::get('accounts/{account}/suspend', [AccountController::class, 'suspendModal']);
    Route::get('accounts/{account}/terminate', [AccountController::class, 'terminateModal']);
    Route::post('accounts/{account}/suspend', [AccountController::class, 'scheduleSuspend']);
    Route::post('accounts/{account}/terminate', [AccountController::class, 'scheduleTerminate']);

    Route::post('accounts/{account}/items/{item}/suspend', [AccountController::class, 'suspendItem']);
    Route::post('accounts/{account}/items/{item}/terminate', [AccountController::class, 'terminateItem']);
    Route::post('accounts/{account}/items/{item}/remove/{type}', [AccountController::class, 'removeNotice']);


    Route::get('accounts/{account}/notifyServices', [AccountController::class, 'notifyServices']);
    Route::get('accounts/{account}/clearServices', [AccountController::class, 'clearServices']);


    // Cancel Account
    Route::get('accounts/{account}/cancel', [AccountController::class, 'cancelAccountModal']);
    Route::post('accounts/{account}/cancel', [AccountController::class, 'cancelAccount']);


    Route::get('users/{user}/reset', [UserController::class, 'resetUser']);
    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile', [ProfileController::class, 'update']);
    Route::get('/', [AdminDashboardController::class, 'index']);
    Route::get('events', [EventController::class, 'all']);
    Route::get('upgrade', [VersionController::class, 'upgrade']);
    Route::get('integrations', [IntegrationController::class, 'index']);
    Route::get('integrations/{int}/enable', [IntegrationController::class, 'enable']);
    Route::get('integrations/{int}/disable', [IntegrationController::class, 'disable']);

    Route::get('integrations/{int}', [IntegrationController::class, 'show']);
    Route::put('integrations/{int}', [IntegrationController::class, 'update']);

    // Master Graph Route
    Route::get('graph/{type}', [GraphController::class, 'show']);

    // Event Editor
    Route::get('events/{event}', [EventController::class, 'show']);
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::get('events/leads/{lead}', [EventController::class, 'getLead']);
    Route::get('events/accounts/{account}', [EventController::class, 'getAccount']);
    Route::get('events', [EventController::class, 'all']);

    // Versions
    Route::get('versions', [VersionController::class, 'index']);

    // Terms of Service
    Route::resource('terms', TermController::class);


    // Discovery Editor
    Route::get('discovery', [DiscoveryController::class, 'index']);

    Route::post('discovery/create/{type}', [DiscoveryController::class, 'store']);
    Route::post('discovery/{discovery}/live', [DiscoveryController::class, 'live']);
    Route::delete('discovery/{discovery}', [DiscoveryController::class, 'destroy']);


    // Settings
    Route::get('settings', [SettingsController::class, 'index']);
    Route::post('settings', [SettingsController::class, 'save']);

    // Email Templates
    Route::get('email_templates', [EmailTemplateController::class, 'index']);
    Route::get('email_templates/{template}', [EmailTemplateController::class, 'show']);
    Route::put('email_templates/{template}', [EmailTemplateController::class, 'update']);

    // Products/Services (Categories)
    Route::get('bill_categories/{type}', [BillItemCategoryController::class, 'index']);
    Route::get('bill_categories/{type}/create', [BillItemCategoryController::class, 'create']);
    Route::get('bill_categories/{type}/{cat}', [BillItemCategoryController::class, 'show']);
    Route::post('bill_categories/{type}', [BillItemCategoryController::class, 'store']);
    Route::put('bill_categories/{type}/{cat}', [BillItemCategoryController::class, 'update']);
    Route::delete('bill_categories/{type}/{cat}', [BillItemCategoryController::class, 'destroy']);

    // Billable Item Editor
    Route::get('category/{cat}/items', [BillItemController::class, 'index']);
    Route::get('category/{cat}/items/create', [BillItemController::class, 'create']);
    Route::post('category/{cat}/items', [BillItemController::class, 'store']);
    Route::get('category/{cat}/items/{item}', [BillItemController::class, 'show']);
    Route::get('category/{cat}/items/{item}/marketing', [BillItemController::class, 'marketing']);

    ## Bill Item Subroutes
    Route::get('category/{cat}/items/{item}/respec', [BillItemController::class, 'respec']);
    Route::get('category/{cat}/items/{item}/specs', [BillItemController::class, 'specs']);
    Route::put('category/{cat}/items/{item}/specs', [BillItemController::class, 'specsUpdate']);
    Route::get('category/{cat}/items/{item}/pricing', [BillItemController::class, 'pricing']);
    Route::put('category/{cat}/items/{item}/pricing', [BillItemController::class, 'pricingUpdate']);
    Route::get('category/{cat}/items/{item}/photos', [BillItemController::class, 'photos']);
    Route::put('category/{cat}/items/{item}/photos', [BillItemController::class, 'photosUpdate']);
    Route::get('category/{cat}/items/{item}/addons', [BillItemController::class, 'addons']);
    Route::get('category/{cat}/items/{item}/tags', [BillItemController::class, 'tags']);
    Route::get('category/{cat}/items/{item}/tags/create', [BillItemController::class, 'addTag']);
    Route::post('category/{cat}/items/{item}/tags', [BillItemController::class, 'saveTag']);

    Route::get('category/{cat}/items/{item}/requirements', [BillItemController::class, 'requirements']);
    Route::get('category/{cat}/items/{item}/reservation', [BillItemController::class, 'reservation']);
    Route::put('category/{cat}/items/{item}/reservation', [BillItemController::class, 'updateReservation']);

    Route::get('category/{cat}/items/{item}/variation', [BillItemController::class, 'variation']);
    Route::put('category/{cat}/items/{item}/variation', [BillItemController::class, 'variationUpdate']);
    Route::get('category/{cat}/items/{item}/variation/create', [BillItemController::class, 'variationModal']);
    Route::post('category/{cat}/items/{item}/variation', [BillItemController::class, 'createVariation']);

    Route::get('category/{cat}/items/{item}/shop', [BillItemController::class, 'shop']);
    Route::put('category/{cat}/items/{item}/shop', [BillItemController::class, 'shopUpdate']);

    Route::get('category/{cat}/items/{item}/faq', [BillItemController::class, 'faq']);

    Route::get('category/{cat}/items/{item}/remove/{tag}', [BillItemController::class, 'removeTag']);
    Route::delete('category/{cat}/items/{item}', [BillItemController::class, 'destroyItem']);
    Route::get('category/{cat}/items/{item}/meta', [BillItemController::class, 'addMeta']);
    Route::post('category/{cat}/items/{item}/meta', [BillItemController::class, 'saveMeta']);
    Route::get('category/{cat}/items/{item}/meta/{meta}', [BillItemController::class, 'editMeta']);
    Route::put('category/{cat}/items/{item}/meta/{meta}', [BillItemController::class, 'updateMeta']);
    Route::delete('category/{cat}/items/{item}/meta/{meta}', [BillItemController::class, 'removeMeta']);

    // Change Category
    Route::get('category/{cat}/items/{item}/category', [BillItemController::class, 'categoryModal']);
    Route::post('category/{cat}/items/{item}/category', [BillItemController::class, 'changeCategory']);





    Route::get('category/{cat}/items/{item}/addons/create', [BillItemController::class, 'createGroupModal']);
    Route::get('category/{cat}/items/{item}/addons/{addon}/add', [BillItemController::class, 'addOptionModal']);
    Route::get('category/{cat}/items/{item}/addons/{addon}', [BillItemController::class, 'updateGroupModal']);
    Route::put('category/{cat}/items/{item}/addons/{addon}', [BillItemController::class, 'updateGroup']);

    // FAQ
    Route::get('category/{cat}/items/{item}/faqs/create', [BillItemController::class, 'createFaqModal']);
    Route::post('category/{cat}/items/{item}/faqs', [BillItemController::class, 'storeFaq']);
    Route::get('category/{cat}/items/{item}/faqs/{faq}', [BillItemController::class, 'showFaqModal']);
    Route::put('category/{cat}/items/{item}/faqs/{faq}', [BillItemController::class, 'updateFaq']);
    Route::delete('category/{cat}/items/{item}/faqs/{faq}', [BillItemController::class, 'deleteFaq']);

    Route::post('category/{cat}/items/{item}/addons/{addon}/options', [BillItemController::class, 'storeOption']);
    Route::delete('category/{cat}/items/{item}/addons/{addon}', [BillItemController::class, 'removeAddon']);
    Route::get('category/{cat}/items/{item}/addons/{addon}/options/{option}',
        [BillItemController::class, 'showOption']);
    Route::put('category/{cat}/items/{item}/addons/{addon}/options/{option}',
        [BillItemController::class, 'updateOption']);
    Route::delete('category/{cat}/items/{item}/addons/{addon}/options/{option}',
        [BillItemController::class, 'deleteOption']);

    // VPricing
    Route::get('category/{cat}/items/{item}/vpricing', [BillItemController::class, 'vPricingModal']);
    Route::post('category/{cat}/items/{item}/vpricing', [BillItemController::class, 'vPricingUpdate']);


    Route::post('category/{cat}/items/{item}/addons', [BillItemController::class, 'createGroup']);


    // Marketplace
    Route::get('market/clear', [MarketController::class, 'clear']);
    Route::get('market', [MarketController::class, 'index']);
    Route::get('market/set/{slug}', [MarketController::class, 'setIndustry']);
    Route::get('market/{ind}', [MarketController::class, 'categories']);
    Route::get('market/{ind}/{category}', [MarketController::class, 'showCategory']);
    Route::get('market/{ind}/{category}/{lid}', [MarketController::class, 'showItem']);
    Route::get('import/{lid}', [BillItemController::class, 'import']);
    Route::post('import/{lid}', [BillItemController::class, 'importProcess']);

    Route::get('vendors', [VendorController::class, 'index']);
    Route::get('vendors/{vendor}', [VendorController::class, 'show']);
    Route::put('vendors/{vendor}', [VendorController::class, 'update']);
    Route::delete('vendors/{vendor}', [VendorController::class, 'destroy']);

    Route::post('vendors', [VendorController::class, 'store']);
    // Accounts
    Route::get('accounts', [AccountController::class, 'index']);
    Route::post('accounts', [AccountController::class, 'store']);

    Route::get('accounts/create', [AccountController::class, 'create']);
    Route::get('accounts/{account}/events', [AccountController::class, 'events']);

    Route::get('accounts/{account}', [AccountController::class, 'show']);
    Route::get('accounts/{account}/partner/enable', [AccountController::class, 'enablePartner']);
    Route::put('accounts/{account}', [AccountController::class, 'update']);
    Route::post('accounts/{account}/logo', [AccountController::class, 'updateLogo']);
    Route::get('accounts/{account}/items/{item}/meta', [AccountController::class, 'showMeta']);
    Route::post('accounts/{account}/items/{item}/meta', [AccountController::class, 'saveMeta']);


    Route::get('accounts/{account}/pbx/assign', [AccountController::class, 'pbxAssignForm']);
    Route::get('accounts/{account}/pbx/refresh', [AccountController::class, 'pbxRefresh']);
    Route::post('accounts/{account}/pbx/assign', [AccountController::class, 'pbxAssign']);
    Route::post('accounts/{account}/files', [AccountController::class, 'uploadFile']);
    Route::delete('accounts/{account}/files/{file}', [AccountController::class, 'deleteFile']);
    Route::get('accounts/{account}/items/{item}/addons', [AccountController::class, 'addons']);
    Route::post('accounts/{account}/items/{item}/addons', [AccountController::class, 'saveAddons']);
    Route::delete('accounts/{account}/items/{item}/addons/{addon}', [AccountController::class, 'removeAddon']);

    Route::get('accounts/{account}/updateACH', [AccountController::class, 'achModal']);
    Route::post('accounts/{account}/updateACH', [AccountController::class, 'saveACH']);



    Route::post('accounts/{account}/invoices', [AccountController::class, 'storeInvoice']);




    Route::post('accounts/{account}/method/add', [AccountController::class, 'addPaymentMethod']);

    // Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::get('orders/{order}/verify', [OrderController::class, 'verify']);
    Route::get('orders/{order}/progress', [OrderController::class, 'progress']);

    Route::get('orders/{order}/close', [OrderController::class, 'close']);
    Route::get('orders/{order}/send', [OrderController::class, 'send']);
    Route::get('orders/{order}/items/{item}/assign', [OrderController::class, 'assignForm']);
    Route::post('orders/{order}/items/{item}/assign', [OrderController::class, 'assign']);
    Route::get('orders/{order}/items/{item}/shipment', [OrderController::class, 'shipModal']);
    Route::post('orders/{order}/items/{item}/shipment', [OrderController::class, 'setShipment']);
    Route::get('orders/{order}/items/{item}/notes', [OrderController::class, 'noteForm']);
    Route::post('orders/{order}/items/{item}/notes', [OrderController::class, 'addNote']);
    Route::post('orders/{order}/items/{item}/close', [OrderController::class, 'completeItem']);

    // Shipments
    // Hardware Orders
    Route::get('shipments', [ShipmentController::class, 'index']);
    Route::get('shipments/create', [ShipmentController::class, 'create']);
    Route::get('shipments/{shipment}', [ShipmentController::class, 'show']);
    Route::delete('shipments/{shipment}', [ShipmentController::class, 'destroy']);

    Route::get('shipments/{shipment}/send', [ShipmentController::class, 'show']);

    Route::get('shipments/{shipment}/download', [ShipmentController::class, 'download']);
    Route::get('shipments/{shipment}/close', [ShipmentController::class, 'close']);

    Route::get('shipments/{shipment}/submit', [ShipmentController::class, 'submit']);

    Route::get('shipments/{shipment}/del/{item}', [ShipmentController::class, 'delItem']);

    Route::put('shipments/{shipment}', [ShipmentController::class, 'update']);
    Route::put('shipments/{shipment}/tracking', [ShipmentController::class, 'updateTracking']);

    Route::get('shipments/{shipment}/add/{item}', [ShipmentController::class, 'addItem']);
    Route::post('shipments/{shipment}/live/{item}', [ShipmentController::class, 'live']);


    // Invoice Routes
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy']);
    Route::post('invoices/{invoice}/add', [InvoiceController::class, 'addCustomItem']);
    Route::get('invoices/{invoice}/settings', [InvoiceController::class, 'settings']);
    Route::post('invoices/{invoice}/settings', [InvoiceController::class, 'settingsUpdate']);
    Route::get('invoices/{invoice}/add/{item}', [InvoiceController::class, 'addItem']);
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download']);
    Route::get('invoices/{invoice}/send', [InvoiceController::class, 'send']);
    Route::get('invoices/{invoice}/order', [InvoiceController::class, 'createOrder']);
    Route::get('invoices/{invoice}/due', [InvoiceController::class, 'dueModal']);
    Route::post('invoices/{invoice}/due', [InvoiceController::class, 'dueUpdate']);
    Route::get('invoices/{invoice}/item/{item}', [InvoiceController::class, 'showItem']);
    Route::put('invoices/{invoice}/item/{item}', [InvoiceController::class, 'updateInvoiceItem']);
    Route::delete('invoices/{invoice}/rem/{item}', [InvoiceController::class, 'remItem']);
    Route::post('invoices/{invoice}/auth', [InvoiceController::class, 'authPayment']);
    // Sales Funnel
    Route::get('sales/funnel', [SalesFunnelController::class, 'index']);

    Route::group(['prefix' => 'finance'], function () {
        Route::get('flow', [CashFlowController::class, 'index']);
        Route::get('invoices', [InvoiceController::class, 'index']);
        Route::get('transactions', [TransactionController::class, 'index']);
        Route::resource('commissions', CommissionController::class);
        Route::resource('commission_batches', CommissionBatchController::class);
    });


    Route::resource('package_builds', PackageBuildController::class);
    Route::resource('package_builds.sections', PackageSectionController::class);
    Route::resource('package_builds.sections.questions', PackageSectionQuestionController::class);
    Route::resource('package_builds.sections.questions.options', QuestionOptionController::class);
    Route::resource('package_builds.sections.questions.logics', QuestionLogicController::class);


    Route::get('quotes', [QuoteController::class, 'index']);
    Route::post('quotes', [QuoteController::class, 'store']);
    Route::get('quotes/create', [QuoteController::class, 'create']);
    Route::get('quotes/{quote}', [QuoteController::class, 'show']);
    Route::get('quotes/{quote}/approve', [QuoteController::class, 'approve']);
    Route::get('quotes/{quote}/items/{item}/addons', [QuoteController::class, 'addons']);
    Route::post('quotes/{quote}/items/{item}/addons', [QuoteController::class, 'saveAddons']);
    Route::get('quotes/{quote}/items/{item}/meta', [QuoteController::class, 'showMeta']);
    Route::post('quotes/{quote}/items/{item}/meta', [QuoteController::class, 'saveMeta']);

    Route::get('quotes/{quote}/items/{item}/move/{direction}', [QuoteController::class, 'move']);


    Route::get('quotes/{quote}/add/{item}', [QuoteController::class, 'addItem']);
    Route::delete('quotes/{quote}/del/{item}', [QuoteController::class, 'delItem']);
    Route::post('quotes/{quote}/live/{item}', [QuoteController::class, 'liveItem']);


    Route::get('quotes/{quote}/download', [QuoteController::class, 'download']);
    Route::get('quotes/{quote}/msa', [QuoteController::class, 'msa']);

    Route::get('quotes/{quote}/send', [QuoteController::class, 'send']);
    Route::delete('quotes/{quote}', [QuoteController::class, 'destroy']);
    Route::get('quotes/{quote}/presentable', [QuoteController::class, 'togglePresentable']);
    Route::get('quotes/{quote}/import/{id}', [QuoteController::class, 'import']);
    Route::get('quotes/{quote}/coterm/execute', [QuoteController::class, 'executeCoterm']);


    Route::get('leads', [LeadController::class, 'index']);
    Route::post('leads', [LeadController::class, 'store']);
    Route::get('leads/create', [LeadController::class, 'create']);
    Route::get('leads/{lead}', [LeadController::class, 'show']);
    Route::post('leads/{lead}/close', [LeadController::class, 'close']);
    Route::post('leads/{lead}/discovery', [LeadController::class, 'saveDiscovery']);

    Route::get('leads/{lead}/partner', [LeadController::class, 'partnerModal']);
    Route::post('leads/{lead}/partner', [LeadController::class, 'setPartner']);

    Route::put('leads/{lead}', [LeadController::class, 'update']);
    Route::post('leads/{lead}/tns', [LeadController::class, 'addTn']);
    Route::delete('leads/{lead}/tns/{tn}', [LeadController::class, 'delTn']);
    Route::post('leads/{lead}/live', [LeadController::class, 'live']);
    Route::post('leads/{lead}/logo', [LeadController::class, 'uploadLogo']);
    Route::post('leads/{lead}/disc', [LeadController::class, 'updateDiscovery']);

    Route::get('leads/{lead}/reopen', [LeadController::class, 'activate']);
    Route::get('leads/import/csv', [LeadController::class, 'importModal']);
    Route::post('leads/import/csv', [LeadController::class, 'import']);
    // Lead Status
    Route::get('leads/{lead}/status', [LeadController::class, 'showStatus']);
    Route::put('leads/{lead}/status', [LeadController::class, 'setStatus']);

    Route::post('leads/{lead}/rating', [LeadController::class, 'rating']);
    Route::get('leads/{lead}/discovery/send', [LeadController::class, 'sendDiscovery']);


// Quotes from Lead Context
    Route::get('leads/{lead}/quotes', [QuoteController::class, 'leadIndex']);

    Route::put('quotes/{quote}', [QuoteController::class, 'update']);
    Route::post('quotes/{quote}/copy', [QuoteController::class, 'copy']);
    Route::get('quotes/{quote}/items/{item}', [QuoteController::class, 'editItem']);
    Route::put('quotes/{quote}/items/{item}', [QuoteController::class, 'updateItem']);

    // Lead Types
    Route::get('lead_types', [LeadTypeController::class, 'index']);
    Route::get('lead_types/create', [LeadTypeController::class, 'create']);
    Route::get('lead_types/{type}', [LeadTypeController::class, 'show']);
    Route::put('lead_types/{type}', [LeadTypeController::class, 'update']);
    Route::post('lead_types', [LeadTypeController::class, 'store']);
    Route::delete('lead_types/{type}', [LeadTypeController::class, 'destroy']);

    // Lead Statuses
    Route::get('lead_statuses/{status}', [LeadTypeController::class, 'showStatus']);
    Route::put('lead_statuses/{status}', [LeadTypeController::class, 'updateStatus']);
    Route::post('lead_statuses', [LeadTypeController::class, 'storeStatus']);
    Route::delete('lead_statuses/{status}', [LeadTypeController::class, 'destroyStatus']);

    // Logs page
    Route::get('logs/{model}/{id}', [LogsController::class, 'show']);
    Route::get('logs/{model}/{id}/{logseverity}', [LogsController::class, 'show']);

});


// Ecommerce Routes (open to public)
Route::get('shop', [ShopController::class, 'index']);
Route::group(['prefix' => 'shop'], function () {

    Route::get('authorize', [ShopController::class, 'auth']);
    Route::group(['middleware' => ['auth']], function () {
        Route::get('account', [ShopAccountController::class, 'index']);
        Route::get('account/services', [ShopAccountController::class, 'services']);
        Route::get('account/services/{item}/term', [ShopAccountController::class, 'termModal']);
        Route::post('account/services/{item}/term', [ShopAccountController::class, 'termSave']);
        Route::get('account/password', [ShopAccountController::class, 'changePassword']);
        Route::post('account/password', [ShopAccountController::class, 'updatePassword']);
        Route::get('account/invoices/{invoice}', [ShopAccountController::class, 'showInvoice']);
        Route::get('account/orders/{hash}', [ShopAccountController::class, 'showOrder']);
        Route::get('account/invoices/{invoice}/pay', [ShopAccountController::class, 'pay']);
        Route::get('account/invoices/{invoice}/download', [ShopAccountController::class, 'downloadInvoice']);

        Route::post('account/method', [ShopAccountController::class, 'saveMethod']);

        Route::get('account/profile', [ShopAccountController::class, 'profile']);
        Route::get('account/invoices', [ShopAccountController::class, 'invoices']);
        Route::get('account/orders', [ShopAccountController::class, 'orders']);
        Route::get('account/quotes', [ShopAccountController::class, 'quotes']);
        Route::get('account/quotes/{qhash}', [ShopAccountController::class, 'showQuote']);


    });

    Route::get('prepared/{hash}', [ShopController::class, 'prepared']);
    Route::get('prepared/{hash}/download', [ShopController::class, 'downloadPrepared']);

    Route::get('logout', [ShopController::class, 'logout']);

    Route::get('presales/{slug}/contact', [PresalesController::class, 'contactModal']);
    Route::post('presales/{slug}/contact', [PresalesController::class, 'saveContact']);

    Route::get('presales/{slug}/questions', [PresalesController::class, 'questionModal']);
    Route::post('presales/{slug}/questions', [PresalesController::class, 'saveQuestions']);

    Route::get('presales/{slug}', [PresalesController::class, 'index']);
    Route::get('presales/{slug}/{qslug}', [PresalesController::class, 'quote']);
    Route::get('presales/{slug}/{qslug}/checkout', [CheckoutController::class, 'quoteCheckout']);

    // Guest Routes
    Route::get('confirm/{item}', [ShopController::class, 'showConfirmation']);
    Route::get('cart', [GuestCartController::class, 'showCart']);
    Route::get('build/{slug}', [GuestCartController::class, 'startBuild']);
    Route::get('checkout', [GuestCheckoutController::class, 'checkout']);
    Route::get('quote', [GuestCartController::class, 'quote']);
    Route::get('{catslug}', [ShopController::class, 'showCategory']);
    Route::get('{catslug}/{itemslug}', [ShopController::class, 'showItem']);
});

Route::group(['prefix' => 'sales', 'middleware' => ['auth']], function () {
    Route::get('/', [SalesController::class, 'index']);
    Route::resource('leads', SalesLeadController::class);
    Route::resource('leads.quotes', SalesQuoteController::class);
    Route::resource('accounts', SalesAccountController::class);
    Route::resource('commissions', SalesCommissionController::class);
    Route::post('leads/{lead}/questions', [SalesLeadController::class, 'saveQuestions']);

    Route::get('leads/{lead}/quotes/{quote}/download', [SalesQuoteController::class, 'download']);
    Route::get('leads/{lead}/quotes/{quote}/apply', [SalesQuoteController::class, 'applyCart']);
    Route::get('leads/{lead}/quotes/{quote}/presentable', [SalesQuoteController::class, 'presentable']);
    Route::get('leads/{lead}/quotes/{quote}/decline', [SalesQuoteController::class, 'declineModal']);
    Route::delete('leads/{lead}/quotes/{quote}', [SalesQuoteController::class, 'decline']);

    Route::get('leads/{lead}/quotes/{quote}/send', [SalesQuoteController::class, 'send']);

    Route::delete('leads/{lead}/quotes/{quote}/item/{item}', [SalesQuoteController::class, 'removeItem']);

});
