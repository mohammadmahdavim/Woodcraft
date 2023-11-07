<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\ProductMaterial;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $rows = Product::with('materials.material')
            ->when($request->get('name'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->name . '%');
            })
            ->when($request->get('code'), function ($query) use ($request) {
                $query->where('code', $request->code);
            })
            ->orderBy('created_at', 'desc')->get();
        $materials = Material::all();
        return view('panel.product.index', ['rows' => $rows, 'materials' => $materials]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $row = Product::create([
            'title' => $request->title,
            'body' => $request->body,
            'price' => $request->price,
            'code' => $request->code,
            'sode' => $request->sode,
            'author' => auth()->user()->id,

        ]);
        $this->fileController->getUploadImage($request, $row, 'product');
        alert()->success('محصول جدید با موفقیت افزوده شد', 'عملیات موفق');

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
        $row = Product::where('id', $id)->first();
        $row->update([
            'title' => $request->title,
            'body' => $request->body,
            'price' => $request->price,
            'code' => $request->code,
            'sode' => $request->sode,

        ]);
        $this->fileController->getUploadImage($request, $row, 'product');
        alert()->success('محصول با موفقت ویرایش شد.', 'عملیات موفق');

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
        $row = Product::where('id', $id)->first();
        $row->delete();
    }

    public function material(Request $request, $id)
    {
        ProductMaterial::create([
            'product_id' => $id,
            'material_id' => $request->material_id,
            'count' => $request->count,
        ]);

        alert()->success('محصول با موفقت ویرایش شد.', 'عملیات موفق');
        $this->price($id);

        return back();
    }

    public function material_delete($id)
    {
        $row = ProductMaterial::where('id', $id)->first();
        $row->delete();
        $this->price($row->product_id);
    }

    public function price($id)
    {
        $row = Product::where('id', $id)->first();
        $price = 0;
        foreach ($row->materials as $material) {
            $mat = Material::where('id', $material->material_id)->first();
            if ($mat->price)
            {
                $price = $price + ($material->count * $mat->price);

            }

        }
        $sode = $row->sode;
        if (!$sode) {
            $sode = 0;
        }
        $row->update(['price' => $price * (1 + $sode)]);
    }

}
