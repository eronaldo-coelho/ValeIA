<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanSelectionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccessController;
use App\Http\Controllers\Admin\FuncionarioController;
use App\Http\Controllers\Admin\ValeController;
use App\Http\Controllers\Admin\NotaController;
use App\Http\Controllers\Admin\NovaNotaController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\PagamentoController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\PagamentoPlanoController;
use App\Http\Controllers\Admin\ApiCredentialController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/status', [StatusPageController::class, 'index'])->name('status.page');

Route::get('/apresentacao', function () {
    return view('apresentacao');
})->name('apresentacao');


Route::get('/docs', function () {
    return view('docs');
})->name('docs');

Route::get('/ajuda', function () {
    return view('ajuda');
})->name('ajuda');

Route::get('/termos-de-uso', function () {
    return view('termos-uso');
})->name('termos-uso');

Route::get('/termos-de-privacidade', function () {
    return view('termos-privacidade');
})->name('termos-privacidade');

Route::post('/webhook', [WebhookController::class, 'handle'])->name('webhook');

Route::get('/auth', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback']);


Route::middleware('guest')->group(function () {
    Route::get('/esqueceu-senha', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/esqueceu-senha', [PasswordResetController::class, 'sendResetCode'])->name('password.email');
    
    Route::get('/verificar-codigo', [PasswordResetController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/verificar-codigo', [PasswordResetController::class, 'verifyCode'])->name('password.verify.store');
    
    Route::get('/redefinir-senha', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/redefinir-senha', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/escolher-plano', [PlanSelectionController::class, 'index'])->name('plan.selection');
    Route::post('/escolher-plano', [PlanSelectionController::class, 'store'])->name('plan.store');
});

Route::middleware(['auth:web,company'])->group(function () {
    Route::get('/completar-cadastro', [AuthController::class, 'showCompleteRegistration'])->name('auth.complete');
    Route::post('/completar-cadastro', [AuthController::class, 'storeCompleteRegistration'])->name('auth.complete.store');

    Route::middleware(['has.plan'])->group(function () {
        Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/admin/acesso-restrito', function() {
            return view('acessorestrito');
        })->name('acessorestrito');

        Route::get('/admin/meu-plano', [PlanController::class, 'index'])->name('admin.planos.index');
        Route::post('/admin/meu-plano/atualizar', [PlanController::class, 'update'])->name('admin.planos.update');
 
        Route::get('/admin/pagamento/{id}', [PagamentoPlanoController::class, 'show'])->name('admin.planos.pagamento');
        Route::post('/admin/pagamento/gerar', [PagamentoPlanoController::class, 'gerarPagamento'])->name('admin.planos.pagamento.gerar');
        Route::get('/admin/pagamento/{id}/status', [PagamentoPlanoController::class, 'checkStatus'])->name('admin.planos.pagamento.status');

        Route::get('/admin/configuracoes', [ConfiguracaoController::class, 'index'])->name('admin.configuracoes.index');
        Route::post('/admin/configuracoes', [ConfiguracaoController::class, 'update'])->name('admin.configuracoes.update');

        Route::get('/admin/api-tokens', [ApiCredentialController::class, 'index'])->name('admin.api.index');
        Route::post('/admin/api-tokens', [ApiCredentialController::class, 'store'])->name('admin.api.store');
        Route::delete('/admin/api-tokens/{id}', [ApiCredentialController::class, 'destroy'])->name('admin.api.destroy');

        Route::get('/admin/auditoria', [AuditoriaController::class, 'index'])->name('admin.auditoria.index');

        Route::prefix('admin/acessos')->name('admin.acessos.')->group(function () {
            Route::get('/', [AccessController::class, 'index'])->name('index');
            Route::get('/novo', [AccessController::class, 'create'])->name('create');
            Route::post('/salvar', [AccessController::class, 'store'])->name('store');
            Route::get('/editar/{id}', [AccessController::class, 'edit'])->name('edit');
            Route::post('/atualizar/{id}', [AccessController::class, 'update'])->name('update');
            Route::delete('/excluir/{id}', [AccessController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('admin/funcionarios')->name('admin.funcionarios.')->group(function () {
            Route::get('/', [FuncionarioController::class, 'index'])->name('index');
            Route::get('/novo', [FuncionarioController::class, 'create'])->name('create');
            Route::post('/salvar', [FuncionarioController::class, 'store'])->name('store');
            Route::get('/editar/{id}', [FuncionarioController::class, 'edit'])->name('edit');
            Route::post('/atualizar/{id}', [FuncionarioController::class, 'update'])->name('update');
            Route::delete('/excluir/{id}', [FuncionarioController::class, 'destroy'])->name('destroy');
            
            Route::post('/vales/adicionar/{funcionario_id}', [FuncionarioController::class, 'storeVale'])->name('vales.store');
            Route::put('/vales/atualizar/{id}', [FuncionarioController::class, 'updateVale'])->name('vales.update');
            Route::delete('/vales/excluir/{id}', [FuncionarioController::class, 'destroyVale'])->name('vales.destroy');

            Route::post('/contas/adicionar/{funcionario_id}', [FuncionarioController::class, 'storeConta'])->name('contas.store');
            Route::delete('/contas/excluir/{id}', [FuncionarioController::class, 'destroyConta'])->name('contas.destroy');
            Route::post('/contas/principal/{id}', [FuncionarioController::class, 'setContaPrincipal'])->name('contas.principal');
        });

        Route::prefix('admin/vales')->name('admin.vales.')->group(function () {
            Route::get('/', [ValeController::class, 'index'])->name('index');
            Route::get('/novo', [ValeController::class, 'create'])->name('create');
            Route::post('/salvar', [ValeController::class, 'store'])->name('store');
            Route::get('/editar/{id}', [ValeController::class, 'edit'])->name('edit');
            Route::post('/atualizar/{id}', [ValeController::class, 'update'])->name('update');
            Route::delete('/excluir/{id}', [ValeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('admin/notas')->name('admin.notas.')->group(function () {
            Route::get('/', [NotaController::class, 'index'])->name('index');
            Route::get('/visualizar/{id}', [NotaController::class, 'show'])->name('show');
            Route::post('/atualizar/{id}', [NotaController::class, 'update'])->name('update');
            Route::get('/editar/{id}', [NotaController::class, 'show'])->name('edit');
        });

        Route::prefix('admin/criar-nota')->name('admin.criar-nota.')->group(function () {
            Route::get('/', [NovaNotaController::class, 'index'])->name('index');
            Route::post('/analisar', [NovaNotaController::class, 'analisar'])->name('analisar');
            Route::post('/salvar', [NovaNotaController::class, 'store'])->name('store');
        });

        Route::prefix('admin/pagamentos')->name('admin.pagamentos.')->group(function () {
            Route::get('/', [PagamentoController::class, 'index'])->name('index');
            Route::get('/novo', [PagamentoController::class, 'create'])->name('create');
            Route::post('/salvar', [PagamentoController::class, 'store'])->name('store');
            Route::post('/analisar', [PagamentoController::class, 'analisar'])->name('analisar');
            
            // Rota NOVA adicionada
            Route::get('/funcionario/{id}', [PagamentoController::class, 'show'])->name('show');
        });
        
        Route::prefix('admin/relatorios')->name('admin.relatorios.')->group(function () {
            Route::get('/', [RelatorioController::class, 'index'])->name('index');
            Route::get('/pdf', [RelatorioController::class, 'exportarPdf'])->name('pdf');
            Route::get('/funcionario/{id}', [RelatorioController::class, 'show'])->name('show');
            Route::get('/funcionario/{id}/pdf', [RelatorioController::class, 'exportarPdfFuncionario'])->name('funcionario.pdf');
        });
    });
});