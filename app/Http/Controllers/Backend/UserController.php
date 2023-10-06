<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\UUIDGenerate;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.user.index');
    }

    public function ssd()
    {
        $users = User::query();

        return DataTables::of($users)
            ->editColumn('ip', function ($each) {
                if ($each->ip) {
                    return $each->ip;
                }
                return '-';
            })
            ->editColumn('user_agent', function ($each) {
                if ($each->user_agent) {
                    $agent = new Agent();
                    $agent->setUserAgent($each->user_agent);
                    $device = $agent->device();
                    $platform = $agent->platform();
                    $broswer = $agent->browser();

                    return '<table class="table table-bordered">
                    <tbody>
                    <tr><td>Device</td><td>' . $device . '</td></tr>
                    <tr><td>Platform</td><td>' . $platform . '</td></tr>
                    <tr><td>Broswer</td><td>' . $broswer . '</td></tr>
                    </tbody>
                    </table>';
                }
                return '-';
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($each) {
                $edit_btn = '<a href="' . route('admin.user.edit', $each->id) . '" class="text-warning"><i class="fas fa-edit"></i></a>';
                $delete_btn = '<a href="" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="fas fa-trash"></i></a>';

                return '<div class="action-icon">' . $edit_btn . $delete_btn . '</div>';
            })
        ->rawColumns(['ip', 'user_agent', 'action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.user.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create($data);

            Wallet::firstOrCreate(
                ['user_id' =>  $user->id],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0,
                ]
            );
            DB::commit();

            return redirect()->route('admin.user.index')->with('create', 'User Create Successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withErrors(['fail' => 'Something wrong. ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(User $user) 
    {
        return view('backend.user.edit')->with('user', $user);
    }

    public function update(UserRequest $request, User $user)
    {
        DB::beginTransaction();

        try{
            $user->update($request->except(['_token', '_method']));

            Wallet::firstOrCreate(
                ['user_id' =>  $user->id],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0,
                ]
            );
            DB::commit();

            return redirect()->route('admin.user.index')->with('update', 'User data updated successfully');
        }catch(Exception $e){
            DB::rollBack();

            return back()->withErrors(['fail' => 'Something wrong. '.$e->getMessage()])->withInput();
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return 'success';
    }
}
