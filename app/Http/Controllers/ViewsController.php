<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewsController extends Controller
{
    public function inicioView(){
        return view('inicio');
    }

    public function dashboard(){
        return view('templates.crud');
    }

    public function contactView(){
        return view('contact');
    }   
}
