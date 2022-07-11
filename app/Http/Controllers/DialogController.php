<?php

namespace App\Http\Controllers;

use App\Models\Dialog;
use App\Models\User;
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
                $r = $request->user_id;
                $dialog = Auth::user()->dialogsWithUsers->where('is_group', false)->filter(function ($value, $key) use ($r) {
                    $z = $value->users->filter(function ($value, $key) use ($r) {
                        return $value->id == $r;
                    });
                    return $z->count() > 0;
                })->first();
                if (!$dialog) {
                    $dialog = Dialog::create();
                    Auth::user()->dialogs()->attach($dialog);
                    User::find($request->user_id)->dialogs()->attach($dialog);
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
