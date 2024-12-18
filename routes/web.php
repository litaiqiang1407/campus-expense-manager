    <?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\MyWalletController;
use App\Http\Controllers\NotFoundController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RecurringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportsController;
use App\Http\Middleware\CheckWallet;
use App\Http\Middleware\HandleInertiaRequests;


Route::get('/welcome', [WelcomeController::class, 'index'])->name('Welcome');

Route::middleware(['auth', CheckWallet::class, HandleInertiaRequests::class])->group(function () {
    Route::group(['prefix' => ''], function () {
        Route::get('/', [HomeController::class, 'index'])->name('Home');
        Route::get('/report', [HomeController::class, 'report'])->name('HomeReport');
    });
    Route::get('/', [HomeController::class, 'index'])->name('Home');
    Route::get('/notification', [NotificationController::class, 'index'])->name('Notification');
    Route::get('/account', [AccountController::class, 'index'])->name('Account');
    Route::get('/categories', [CategoryController::class, 'index'])->name('Categories');
    Route::get('/categories/add', [CategoryController::class, 'create'])->name('AddCategory');
    Route::post('/store/category', [CategoryController::class, 'store'])->name('StoreCategory');

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', [ReportsController::class, 'index'])->name('Reports');
        Route::get('/category', [ReportsController::class, 'category'])->name('CategoryReport');
    });

    Route::group(['prefix' => 'transaction'], function ()
    {
        Route::get('/', [TransactionController::class, 'index'])->name('Transaction');
        Route::get('/recurring', [RecurringController::class, 'index'])->name('TransactionRecurring');
        Route::get('/create', [TransactionController::class, 'create'])->name('CreateTransaction');
        Route::post('/store/recurring', [RecurringController::class, 'store'])->name('CreateRecurringTransaction');
        Route::post('/store', [TransactionController::class, 'store'])->name('StoreTransaction');
        Route::get('/edit/{id}', [TransactionController::class, 'edit'])->name('EditTransaction');
        Route::get('/edit-recurring/{id}', [RecurringController::class, 'edit'])->name('EditRecurringTransaction');
        Route::post('/update/{id}', [TransactionController::class, 'update'])->name('UpdateTransaction');
        Route::post('/update-recurring/{id}', [RecurringController::class, 'update'])->name('UpdateRecurringTransaction');
        Route::get('/transaction-details/{id}', [TransactionController::class, 'transactionDetails'])->name('TransactionDetails');
        Route::get('/recurring/{id}', [RecurringController::class, 'transactionRecurringDetails'])->name('TransactionRecurringDetails');
        Route::post('/delete/{id}', [TransactionController::class, 'delete'])->name('DeleteTransaction');
        Route::post('/delete-recurring/{id}', [RecurringController::class, 'delete'])->name('DeleteRecurringTransaction');
    });
    Route::group(['prefix' => 'my-wallet'], function () {
        Route::get('/', [MyWalletController::class, 'index'])->name('MyWallet');
        Route::get('/create', [MyWalletController::class, 'create'])->name('CreateWallet');
        Route::post('/store', [MyWalletController::class, 'store'])->name('StoreWallet');
        Route::get('/edit/{walletId}', [MyWalletController::class, 'edit'])->name('EditWallet');
        Route::post('/update/{walletId}', [MyWalletController::class, 'update'])->name('UpdateWallet');
        Route::post('/delete/{walletId}', [MyWalletController::class, 'delete'])->name('DeleteWallet');
        Route::get('/search', [MyWalletController::class, 'search'])->name('SearchWallets');
    });

    Route::group(['prefix' => 'budget'], function () {
        Route::get('/', [BudgetController::class, 'index'])->name('Budget');
        Route::post('/store', [BudgetController::class, 'store'])->name('StoreBudget');
    });

    Route::get('/icon', [IconController::class, 'index'])->name('Icon');
    Route::get('/select-wallet', [MyWalletController::class, 'selectWallet'])->name('SelectWallet');
});

require __DIR__ . '/auth.php';

Route::get('/{pathMath}', [NotFoundController::class, 'index'])->where('pathMath', '.*');


