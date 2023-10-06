<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\AdminUser;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin_user.index');
    }

    public function ssd()
    {
        $admin_users = AdminUser::query();

        return DataTables::of($admin_users)
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
                $edit_btn = '<a href="'.route('admin.admin-user.edit', $each->id).'" class="text-warning"><i class="fas fa-edit"></i></a>';
                $delete_btn = '<a href="" class="text-danger delete-btn" data-id="'.$each->id.'"><i class="fas fa-trash"></i></a>';

                return '<div class="action-icon">'. $edit_btn . $delete_btn .'</div>';
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
        return view('backend.admin_user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserRequest $request)
    {
        $data = $request->validated();

        $admin_user = AdminUser::create($data);

        return redirect()->route('admin.admin-user.index')->with('create', 'Admin Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminUser $admin_user)
    {
        return view('backend.admin_user.edit', ['admin_user' => $admin_user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserRequest $request, AdminUser $admin_user)
    {
        $admin_user->update($request->except(['_token', '_method']));

        return redirect()->route('admin.admin-user.index')->with('update', 'Admin data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminUser $admin_user)
    {
        $admin_user->delete();

        return "success";
    }
}
