<?php

namespace App\Http\Controllers;

use App\Models\KabanCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:2000',
            'status' => 'nullable|boolean'
        ]);

        $card->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? true
        ]);

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

    // Update Positions
    public function updatePositions(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);
        DB::beginTransaction();
        try {
            foreach ($request->data as $item) {
                if (isset($item['cards']) && count($item['cards']) > 0) {
                    foreach ($item['cards'] as $card) {
                        KabanCard::where('id', $card['id'])
                            ->update([
                                'title' => $card['title'],
                                'description' => $card['description'],
                                'kaban_column_id' => $item['id']
                            ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => $request->data
            ], 200);
        } catch (\Throwable $th) {

            DB::rollback();

            return response()->json([
                'error' => $th
            ], 500);
        }
    }
}
