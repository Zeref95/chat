<?php

namespace App\Http\Controllers;

use App\Models\Dialog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DialogController extends Controller
{
    public function index()
    {
        return response()->json(['dialogs' => Auth::user()->dialogsWithUsers]);
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->user_id)) {
                $dialog = Auth::user()->dialogs->where('is_group', false)->first();
                if (!$dialog) {
                    $dialog = Dialog::create();
                    Auth::user()->dialogs()->attach($dialog);
                }
            }
            DB::commit();
            return response()->json(['dialog_id' => $dialog->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }


    }
}
