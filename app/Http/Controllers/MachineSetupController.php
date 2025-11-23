<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MachineSetupController extends Controller{
   
    public function index($id){
        $machine = Machine::findOrFail($id);
        return view('machines.machine-setup', compact('machine'));
    }    
    
}
