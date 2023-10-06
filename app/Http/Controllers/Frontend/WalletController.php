<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\UUIDGenerate;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $auth_user = Auth::user();

        return view('frontend.wallet.index', ['auth_user' => $auth_user]);
    }

    public function check(Request $request)
    {
        if (auth()->User()->phone != $request->phone) {
            $user = User::where('phone', $request->phone)->first();

            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $user
                ]);
            }
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Invalid data'
        ]);
    }

    public function transfer()
    {
        return view('frontend.wallet.transfer');
    }

    public function transferConfirm(TransferRequest $request)
    {
        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'Account credentials invalid'])->withInput();
        }

        $from_account = auth()->User();
        $amount = $request->amount;
        $description = $request->description;

        return view('frontend.wallet.transfer_confirm', ['from_account' => $from_account, 'to_account' => $to_account, 'amount' => $amount, 'description' => $description]);
    }

    public function transferComplete(TransferRequest $request)
    {
        if($request->amount < 1000){
            return back()->withErrors(['amount' => 'The amount must be at least 1000 MMK'])->withInput();
        }

        $auth_user = auth()->User();

        if($auth_user->phone == $request->to_phone){
            return back()->withErrors(['to_phone' => 'To account is invalid'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if(!$to_account){
            return back()->withErrors(['to_phone' => 'To account is invalid'])->withInput();
        }

        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail' => 'Something wrong. The given data is invalid.'])->withInput();
        }

        DB::beginTransaction();

        try{
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $from_account_data = [
                'ref_no' => $ref_no,
                'trx_id' => UUIDGenerate::trxId(),
                'user_id' => $from_account->id,
                'type' => 'expense',
                'amount' => $amount,
                'source_id' => $to_account->id,
                'description' => $description
            ];

            Transaction::create($from_account_data);

            $to_account_data = [
                'ref_no' => $ref_no,
                'trx_id' => UUIDGenerate::trxId(),
                'user_id' => $to_account->id,
                'type' => 'income',
                'amount' => $amount,
                'source_id' => $from_account->id,
                'description' => $description
            ];

            Transaction::create($to_account_data);

            DB::commit();
            return redirect()->route('home')->with('success', 'Successfully transfer');
        }catch(\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['fail', 'Something wrong.' . $e->getMessage()])->withInput();
        }
    }

    public function passwordCheck(Request $request)
    {
        if(!$request->password){
            return response()->json([
                'status' => 'fail',
                'message' => 'The password is incorrect'
            ]);
        }
        $current_user = auth()->User();
        if (Hash::check($request->password, $current_user->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'The password is correct'
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'The password is incorrect'
        ]);
    }
}
