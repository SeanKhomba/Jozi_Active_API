<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\UserType;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function index()
    {
        return view('admin.payments.index', ['payments' => Payment::where('deleted_at' , null)->simplePaginate(20)]);
    }

    public function delete($id)
    {
        Payment::where('id' , $id)->delete();
        return redirect('admin/payments')->with('success', 'Payment Deleted!');
    }
  

    public function readOnly($id)
	{
        $payment = Payment::find($id);
		return view('admin.payment.edit', ['payment' => $payment, 'readOnly' => 'readOnly']);
    }
    
}
