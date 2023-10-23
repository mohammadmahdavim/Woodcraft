<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialLog;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;

class MaterialController extends Controller
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
    public function index(Request $request)
    {
        $rows = Material::with('unit')
            ->when($request->get('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($request->get('type_id'), function ($query) use ($request) {
                $query->where('type_id', $request->type_id );
            })
            ->when($request->get('remaining_from'), function ($query) use ($request) {
                $query->where('remaining', '>=', $request->input('remaining_from'));
            })
            ->when($request->get('remaining_to'), function ($query) use ($request) {
                $query->where('remaining', '<=', $request->input('remaining_to'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $units = Unit::all();
        $types = Type::all();
        return view('panel.material.index', ['rows' => $rows, 'units' => $units,'types' => $types]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $row = Material::create([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'type_id' => $request->type_id,
        ]);
//        $this->fileController->getUploadImage($request, $row, 'product');
        alert()->success('متریال جدید با موفقیت افزوده شد', 'عملیات موفق');

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
        $row = Material::where('id', $id)->first();
        $row->update([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'type_id' => $request->type_id,
        ]);
//        $this->fileController->getUploadImage($request, $row, 'product');
        alert()->success('متریال با موفقیت ویرایش گردید.', 'عملیات موفق');

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
        $row = Material::where('id', $id)->first();
        $row->delete();
    }

    public function inventory(Request $request, $id)
    {
        MaterialLog::create([
            'material_id' => $id,
            'count' => $request->count
        ]);
        $material = Material::where('id', $id)->first();
        $material->update([
            'remaining' => $material->remaining + $request->count
        ]);
        alert()->success('متریال با موفقیت به انبار اضافه گردید.', 'عملیات موفق');

        return back();
    }

}
