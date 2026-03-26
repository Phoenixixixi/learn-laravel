<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chirp;

class ChirpController extends Controller
{
    public function index(){
        $chirps = Chirp::with('user')
        ->latest()
        ->take(50)
        ->get();
       
        return view('home', ['chirps' => $chirps]);
    }

    public function store(Request $request){
        $validated = $request->validate(
        ['message' => 'required|string|max:255'],
        ['message.required' => 'please write something'], 
        ['message.max' => 'chirp must less 255 character']);

        Chirp::create([
            'message' => $validated['message'],
            'user_id' => null
        ]);


        return redirect('/')->with('success', 'chirp created');


    }

    //edit method controller 
    public function edit(Chirp $chirp){
        return view('chirps.edit', compact('chirp'));
    }

    public function destroy(Chirp $chirp){
        $chirp->delete();
        return redirect('/')->with('success', 'Chip Deleted !');
    }

    public function update(Request $request ,Chirp $chirp){
        if($request->user()->cannot('update', $chirp)){
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);
        
        $chirp->update($validated);

        return redirect('/')->with('success', 'update success');
    }
}
