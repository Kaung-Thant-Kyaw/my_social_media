<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    // like & unlike
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|in:App\Models\Post,App\Models\Comment',
        ]);


        $reaction = Reaction::firstOrNew([
            'user_id' => auth()->id(),
            'reactable_id' => $validated['reactable_id'],
            'reactable_type' => $validated['reactable_type'],
        ]);

        if ($reaction->exists) {
            $reaction->delete();
            $status = 'unliked';
        } else {
            $reaction->save();
            $status = 'liked';
        }

        // Get fresh count from database
        $count = Reaction::where('reactable_id', $validated['reactable_id'])
            ->where('reactable_type', $validated['reactable_type'])
            ->count();

        return response()->json([
            'status' => $status,
            'count' => $count
        ]);
    }
}
