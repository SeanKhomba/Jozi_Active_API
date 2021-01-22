<?php

namespace App\Http\Controllers\Admin;

use App\Guest;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('admin.users.index', ['users' => User::where('deleted_at' , null)->simplePaginate(20)]);
    }

    public function create()
    {
        return view('admin.users.edit', ['create' => 'create', 'user_types' => UserType::whereNull('deleted_at')->get()]);
    }

    public function save()
    {
        $data = \request()->all();

        User::create([
            'user_type_id' => $data['user_type'],
            'first_name' => $data['first_name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'mobile_number' => $data['mobile_number'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect('admin/users')->with('success', 'User Saved!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', ['edit' => 'edit', 'user_types' => UserType::whereNull('deleted_at')->get() , 'user' => $user]);

    }

    public function update()
    {
        $data = \request()->all();

        if ($data['password'] === null) {
            User::where('id' , $data['id'])->update([
                'first_name' => $data['first_name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'mobile_number' => $data['mobile_number'],
            ]);
        } else {
            User::where('id' , $data['id'])->update([
                'first_name' => $data['first_name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'mobile_number' => $data['mobile_number'],
                'password' => bcrypt($data['password']),
            ]);
        }
        return redirect('admin/users')->with('success', 'User Updated!');
    }

    public function search()
    {
        $data = \request()->all();
        unset($data['_token']);

        $this->q = \request()->get('q');
        if ( $this->q== '') {
            return back()->with('warning', 'Please enter a value in the field');
        }

        $users = User::whereNull('deleted_at')->where('first_name', 'LIKE', '%' .  $this->q . '%')->where(function($query){
            return $query->where('user_type_id' , 1)->orWhere('user_type_id' , 2);
            }
            )->simplePaginate(20);


        if ($users->isNotEmpty()) {
            \request()->session()->now('info', count($users) . ' results found');

            return view('admin.users.index', ['users' => $users ,  'input' => $this->q]);
        } else {
            return back()->with('warning', 'No users found. Try again !');
        }
    }



    public function delete($id)
    {
        User::where('id' , $id)->delete();
        return redirect('admin/users')->with('success', 'User Deleted!');
    }
  

    public function readOnly($id)
	{
        $user = User::find($id);
		return view('admin.users.edit', ['user_types' => UserType::whereNull('deleted_at')->get(),'user' => $user, 'readOnly' => 'readOnly']);
	}
}
