<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallet.index');
    }

    public function ssd()
    {
        $wallets = Wallet::with('User');

        return DataTables::of($wallets)
            ->addColumn('account_person', function ($each) {
                $user = $each->User;

                if ($user) {
                    return '<p>Name : ' . $user->name . ' </p><p>Email : ' . $user->email . '</p><p>Phone : ' . $user->phone . '</p>';
                }

                return '-';
            })
            ->editColumn('amount', function ($each) {
                return number_format($each->amount, 2);
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
            })
            ->rawColumns(['account_person'])
            ->make(true);
    }

    public function add_form()
    {
        $users = User::select('id', 'name', 'phone')->orderBy('name')->get();

        return view('backend.wallet.add-amount', ['users' => $users]);
    }

    public function add(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'required',
                'amount' => 'required|integer',
                'description' => 'string'
            ],
            [
                'user_id.required' => 'The user field is required.'
            ]
        );

        if($request->amount < 1000){
            return back()->withErrors(['amount' => 'The amount must be at least 1000'])->withInput();
        }

        DB::beginTransaction();

        try {
            $to_account = User::with('Wallet')->where('id', $request->user_id)->firstOrFail();
            
            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $to_account_data = [
                'ref_no' => $ref_no,
                'trx_id' => UUIDGenerate::trxId(),
                'user_id' => $to_account->id,
                'type' => 'income',
                'amount' => $request->amount,
                'source_id' => 0,
                'description' => $request->description
            ];

            $to_trx = Transaction::create($to_account_data);

            DB::commit();

            return redirect()->route('admin.wallet.index')->with('create', 'Successfully added amount!');
        } catch(\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['fail' => 'Something Wrong. ' . $e->getMessage()])->withInput();        }
    }

    public function reduce_form()
    {
        $users = User::select('id', 'name', 'phone')->orderBy('name')->get();

        return view('backend.wallet.reduce-amount', ['users' => $users]);
    }

    public function reduce(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'required',
                'amount' => 'required|integer',
                'description' => 'string'
            ],
            [
                'user_id.required' => 'The user field is required.'
            ]
        );

        if ($request->amount < 1) {
            return back()->withErrors(['amount' => 'The amount must be at least 1'])->withInput();
        }

        DB::beginTransaction();

        try {
            $to_account = User::with('Wallet')->where('id', $request->user_id)->firstOrFail();
            $to_account_wallet = $to_account->wallet;

            if($to_account_wallet->amount < $request->amount){
                throw new Exception('The amount is greater than wallet balance');
            }

            $to_account_wallet->decrement('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $to_account_data = [
                'ref_no' => $ref_no,
                'trx_id' => UUIDGenerate::trxId(),
                'user_id' => $to_account->id,
                'type' => 'expense',
                'amount' => $request->amount,
                'source_id' => 0,
                'description' => $request->description
            ];

            $to_trx = Transaction::create($to_account_data);

            DB::commit();

            return redirect()->route('admin.wallet.index')->with('create', 'Successfully reduced amount!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['fail' => 'Something Wrong. ' . $e->getMessage()])->withInput();
        }
    }
}
