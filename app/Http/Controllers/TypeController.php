<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Type::all();
        return view('panel.type.index', ['rows' => $rows]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Type::create([
            'name' => $request->name,
        ]);
        alert()->success('دسته بندی جدید با موفقیت افزوده شد', 'عملیات موفق');

        return redirect('types');
    }

    public function update(Request $request, $id)
    {
        $row = Type::where('id', $id)->first();
        $row->update([
            'name' => $request->name,
        ]);
//        $this->fileController->getUploadImage($request, $row, 'product');
        alert()->success('دسته بندی با موفقیت ویرایش گردید.', 'عملیات موفق');

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
        $row = Type::where('id', $id)->first();
        $row->delete();
    }
}
