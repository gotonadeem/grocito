<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
class IndexController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return view('seller.index.index');
    }

}