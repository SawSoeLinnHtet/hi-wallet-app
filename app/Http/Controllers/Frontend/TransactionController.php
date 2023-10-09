<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->with('User', 'Source');

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        $transactions = $transactions->paginate(5);

        return view('frontend.transactions.index', ['transactions' => $transactions]);
    }

    public function details($trx_id)
    {
        $current_trx = Transaction::with('User', 'Source')->where('user_id', auth()->user()->id)->where('trx_id', $trx_id)->first();

        return view('frontend.transactions.details', ['transaction' => $current_trx]);
    }
}
