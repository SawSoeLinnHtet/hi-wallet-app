<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
            ->addColumn('action', function ($each) {
                $edit_btn = '<a href="'.route('admin.admin-user.edit', $each->id).'" class="text-warning"><i class="fas fa-edit"></i></a>';
                $delete_btn = '<a href="" class="text-danger delete-btn" data-id="'.$each->id.'"><i class="fas fa-trash"></i></a>';

                return '<div class="action-icon">'. $edit_btn . $delete_btn .'</div>';
            })
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
