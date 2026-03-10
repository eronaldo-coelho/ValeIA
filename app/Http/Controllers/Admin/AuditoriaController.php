<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auditoria;
use App\Models\CompanyUser;

class AuditoriaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $adminId = null;

        if ($user instanceof CompanyUser) {
            $permissions = $user->permissions ?? [];
            if (!in_array('auditoria', $permissions)) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $logs = Auditoria::with('companyUser')
            ->where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.auditoria.index', compact('logs'));
    }
}