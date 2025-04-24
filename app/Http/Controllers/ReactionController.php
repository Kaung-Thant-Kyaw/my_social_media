<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReactionController extends Controller
{
    // like & unlike
    public function toggleReaction(Request $request)
    {
        $request->validate([
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|string',
        ]);
    }
}
