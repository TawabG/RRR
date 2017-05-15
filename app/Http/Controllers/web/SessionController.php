<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Session;

class SessionController extends Controller
{
    public function viewSessions(){
        return view('sessions')->with('sessions', Session::All());
    }

    public function viewSession($id){
        return view('session')->with('session', Session::findOrFail($id));
    }
}
