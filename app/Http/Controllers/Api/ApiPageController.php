<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\UserApiResource;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TransactionApiResource;
use App\Http\Resources\NotificationApiResource;
use App\Http\Resources\TransactionDetailsApiResource;
use App\Http\Resources\NotificationDetailsApiResource;

class ApiPageController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        $data = new UserApiResource($user);

        return ResponseHelper::success('Successfully', $data);
    }

    public function transaction(Request $request)
    {
        $user = Auth::user();
        $transactions = Transaction::with('User', 'Source')->orderBy('created_at', 'DESC')->where('user_id', $user->id);

        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        $transactions = $transactions->paginate(5);

        $data = TransactionApiResource::collection($transactions)->additional(['result' => 1, 'message' => 'success']);

        return $data;
    }

    public function transactionDetails($trx_id)
    {
        $transaction = Transaction::where('trx_id', $trx_id)->where('user_id', auth()->user()->id)->with('User', 'Source')->firstOrFail();

        $data = new TransactionDetailsApiResource($transaction);

        return ResponseHelper::success('Successfully', $data);
    }

    public function notification()
    {
        $user = Auth::User();

        $notifications = $user->notifications()->paginate(5);

        return NotificationApiResource::collection($notifications)->additional(['result' => 1, 'message' => 'success']);
    }

    public function notificationDetails($id)
    {
        $notification = Auth::User()->notifications->where('id', $id)->firstOrFail();

        $data = new NotificationDetailsApiResource($notification);

        return ResponseHelper::success('Success', $data);
    }

    public function checkAccount(Request $request)
    {
        if ($request->phone) {
            if (auth()->User()->phone != $request->phone) {
                $user = User::where('phone', $request->phone)->first();

                if ($user) {
                    return ResponseHelper::success('success', ['name' => $user->name, 'phone' => $user->phone]);
                }
            }
        }
        return ResponseHelper::fail('Invalid Data', null);
    }

    public function transferConfirm(TransferRequest $request)
    {
        $from_account = auth()->User();
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $request_hash_value = $request->hash_value;

        $str = $to_phone . $amount . $description;
        $hash_value2 = hash_hmac('sha256', $str, 'hazelism!@#plmnko!@#wsxzaq!@#');

        if ($hash_value2 !== $request_hash_value) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if (auth()->User()->phone == $to_phone) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $to_account = User::where('phone', $to_phone)->first();

        if (!$to_account) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if ($amount < 1000) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if ($from_account->wallet->amount < $amount) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        return ResponseHelper::success('Success', [
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,

            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone,

            'amount' => $request->amount,
            'description' => $request->description,
            'hash_value' => $request_hash_value
        ]);
    }

    public function transferComplete(TransferRequest $request)
    {
        if (!$request->password) {
            return ResponseHelper::fail('Please fill your password', null);
        }

        $current_user = auth()->User();

        if (!Hash::check($request->password, $current_user->password)) {
            return ResponseHelper::fail('The password is incorrect', null);
        }
        
        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value = hash_hmac('sha256', $str, 'hazelism!@#plmnko!@#wsxzaq!@#');

        if ($hash_value !== $request->hash_value) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $auth_user = auth()->User();

        if ($auth_user->phone == $request->to_phone) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;

        if ($from_account->wallet->amount < $amount) {
            return ResponseHelper::fail('The amount is not enough.', null);
        }

        if ($amount < 1000) {
            return ResponseHelper::fail('The amount must be at least 1000 MMK.', null);
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        DB::beginTransaction();

        try {
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

            $from_trx = Transaction::create($from_account_data);

            $to_account_data = [
                'ref_no' => $ref_no,
                'trx_id' => UUIDGenerate::trxId(),
                'user_id' => $to_account->id,
                'type' => 'income',
                'amount' => $amount,
                'source_id' => $from_account->id,
                'description' => $description
            ];

            $to_trx = Transaction::create($to_account_data);

            //From Noti
            $title = 'E-Money Transfered!';
            $message = 'Your e-money transfered ' . number_format($amount) . ' MMK to ' . $to_account->name . ' ( ' . $to_account->phone . ' )';
            $sourceable_id = $from_trx->trx_id;
            $sourceable_type = Transaction::class;
            $web_link = route('get-transaction-details', $from_trx->trx_id);
            $deep_link = [
                'target' => 'transaction_details',
                'parameter' => [
                    'trx_id' => $from_trx->trx_id,
                ]
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            //To Noti
            $title = 'E-Money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMK from ' . $from_account->name . ' ( ' . $from_account->phone . ' )';
            $sourceable_id = $to_trx->trx_id;
            $sourceable_type = User::class;
            $web_link = route('get-transaction-details', $to_trx->trx_id);
            $deep_link = [
                'target' => 'transaction_details',
                'parameter' => [
                    'trx_id' => $to_trx->trx_id,
                ]
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            DB::commit();

            return ResponseHelper::success('Successfully Transfered', ['trx_id' => $from_trx->trx_id]);
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseHelper::fail('There is some error', null);
        }
    }

    public function scanAndPayForm(Request $request)
    {
        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return ResponseHelper::fail('QR code is invalid', null);
        }

        return ResponseHelper::success('success', [
            'from_name' => auth()->user()->name,
            'from_phone' => auth()->user()->phone,
            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone
        ]);
    }

    public function scanAndPayConfirm(TransferRequest $request)
    {
        $from_account = auth()->User();
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $request_hash_value = $request->hash_value;

        $str = $to_phone . $amount . $description;
        $hash_value2 = hash_hmac('sha256', $str, 'hazelism!@#plmnko!@#wsxzaq!@#');

        if ($hash_value2 !== $request_hash_value) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if (auth()->User()->phone == $to_phone) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $to_account = User::where('phone', $to_phone)->first();

        if (!$to_account) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if ($amount < 1000) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        if ($from_account->wallet->amount < $amount) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        return ResponseHelper::success('Success', [
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,

            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone,

            'amount' => $request->amount,
            'description' => $request->description,
            'hash_value' => $request_hash_value
        ]);
    }

    public function scanAndPayComplete(TransferRequest $request)
    {
        if (!$request->password) {
            return ResponseHelper::fail('Please fill your password', null);
        }

        $current_user = auth()->User();

        if (!Hash::check($request->password, $current_user->password)) {
            return ResponseHelper::fail('The password is incorrect', null);
        }

        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value = hash_hmac('sha256', $str, 'hazelism!@#plmnko!@#wsxzaq!@#');

        if ($hash_value !== $request->hash_value) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $auth_user = auth()->User();

        if ($auth_user->phone == $request->to_phone) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;

        if ($from_account->wallet->amount < $amount) {
            return ResponseHelper::fail('The amount is not enough.', null);
        }

        if ($amount < 1000) {
            return ResponseHelper::fail('The amount must be at least 1000 MMK.', null);
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return ResponseHelper::fail('The given data is invalid', null);
        }

        DB::beginTransaction();

        try {
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

            $from_trx = Transaction::create($from_account_data);

            $to_account_data = [
                'ref_no' => $ref_no,
                'trx_id' => UUIDGenerate::trxId(),
                'user_id' => $to_account->id,
                'type' => 'income',
                'amount' => $amount,
                'source_id' => $from_account->id,
                'description' => $description
            ];

            $to_trx = Transaction::create($to_account_data);

            //From Noti
            $title = 'E-Money Transfered!';
            $message = 'Your e-money transfered ' . number_format($amount) . ' MMK to ' . $to_account->name . ' ( ' . $to_account->phone . ' )';
            $sourceable_id = $from_trx->trx_id;
            $sourceable_type = Transaction::class;
            $web_link = route('get-transaction-details', $from_trx->trx_id);
            $deep_link = [
                'target' => 'transaction_details',
                'parameter' => [
                    'trx_id' => $from_trx->trx_id,
                ]
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            //To Noti
            $title = 'E-Money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMK from ' . $from_account->name . ' ( ' . $from_account->phone . ' )';
            $sourceable_id = $to_trx->trx_id;
            $sourceable_type = User::class;
            $web_link = route('get-transaction-details', $to_trx->trx_id);
            $deep_link = [
                'target' => 'transaction_details',
                'parameter' => [
                    'trx_id' => $to_trx->trx_id,
                ]
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            DB::commit();

            return ResponseHelper::success('Successfully Transfered', ['trx_id' => $from_trx->trx_id]);
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseHelper::fail('There is some error', null);
        }
    }
}