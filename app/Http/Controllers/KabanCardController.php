<?php

namespace App\Http\Controllers;

use App\Models\KabanCard;
use Illuminate\Http\Request;

class KabanCardController extends Controller
{
    // Show all
    public function index(Request $request)
    {
        return KabanCard::loadAll()->get();
    }

    // Create Card
    public function create(Request $request)
    {
        $this->validate($request, [
            'kaban_column_id' => 'required|exists:kaban_columns,id',
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:2000',
            'status' => 'nullable|boolean'
        ]);

        $card = KabanCard::create([
            'kaban_column_id' => $request->kaban_column_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? true
        ]);

        return response()->json(['card' => $card], 200);
    }

    // Update Card
    public function update(Request $request, KabanCard $card)
    {
        $validation_rules = match ($request->query('update')) {
            'move' => [
                'kaban_column_id' => 'required|exists:kaban_columns,id',
            ],
            'title' => ['title' => 'required|string|max:191'],
            'description' => ['description' => 'required|string|max:2000'],
            'status' => ['status' => 'required|boolean'],
            default => [
                'kaban_column_id' => 'required|exists:kaban_columns,id',
                'title' => 'required|string|max:191',
                'description' => 'required|string|max:2000',
                'status' => 'nullable|boolean'
            ]
        };

        $this->validate($request, $validation_rules);

        $card->update([...$request->validated()]);

        return response()->json(['card' => $card], 200);
    }

    // Delete Card
    public function destroy(Request $request, KabanCard $card)
    {
        $card->delete();

        return response()->json([
            'message' => 'Deleted'
        ], 200);
    }
}
