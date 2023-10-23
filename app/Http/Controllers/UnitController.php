<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        $this->fileController = new FileController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Unit::all();
        return view('panel.unit.index', ['rows' => $rows]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Unit::create([
            'name' => $request->name,
        ]);
        alert()->success('واحد جدید با موفقیت افزوده شد', 'عملیات موفق');

        return redirect('units');
    }

    public function update(Request $request, $id)
    {
        $row = Unit::where('id', $id)->first();
        $row->update([
            'name' => $request->name,
        ]);
//        $this->fileController->getUploadImage($request, $row, 'product');
        alert()->success('واحد با موفقیت ویرایش گردید.', 'عملیات موفق');

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
        $row = Unit::where('id', $id)->first();
        $row->delete();
    }

}
