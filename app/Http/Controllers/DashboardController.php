<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    public function index()
    {
        return view('dashboard.index');
    }
}
