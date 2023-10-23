<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $rows = Customer::
        when($request->get('name'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->name . '%');
        })
            ->when($request->get('mobile'), function ($query) use ($request) {
                $query->where('mobile', 'like', '%' . $request->mobile . '%');
            })
            ->orderBy('created_at', 'desc')->get();
        return view('panel.customer.index', ['rows' => $rows]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $row = Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
        ]);
        alert()->success('مشتری جدید با موفقیت افزوده شد', 'عملیات موفق');

        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $row = Customer::where('id', $id)->first();
        $row->update([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,

        ]);
        alert()->success('مشتری با موفقت ویرایش شد.', 'عملیات موفق');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = Customer::where('id', $id)->first();
        $row->delete();
    }

}
