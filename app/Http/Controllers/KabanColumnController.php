<?php

namespace App\Http\Controllers;

use App\Models\AccessTokens;
use App\Models\KabanCard;
use App\Models\KabanColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KabanColumnController extends Controller
{
    // Get Columns
    public function index(Request $request)
    {
        return KabanColumn::loadAll()->get();
    }

    // Create Column
    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'token' => 'required|string|exists:access_tokens,token'
        ]);

        $token_id = AccessTokens::where('token', $request->token)->value('id');

        $column = KabanColumn::create([
            'title' => $request->title,
            'access_token_id' => $token_id,
        ]);

        return response()->json([
            'column' => $column
        ], 200);
    }

    // Delete Column
    public function destroy(Request $request, $columnId)
    {
        DB::beginTransaction();
        try {
            KabanColumn::where('id', $columnId)->delete();

            KabanCard::where('kaban_column_id', $columnId)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Deleted'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'error' => json_encode($th, true)
            ], 500);
        }
    }
}
