<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderProduct;
use App\Models\OrderProductMaterial;
use App\Models\Product;
use App\Models\ProductMaterial;
use Illuminate\Http\Request;

class OrderController extends Controller
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
        $rows = Order::when($request->get('customer_id'), function ($query) use ($request) {
            $query->where('customer_id', $request->customer_id);
        })
            ->when($request->get('code'), function ($query) use ($request) {
                $query->where('code', $request->code);
            })
            ->when($request->get('date-picker-shamsi-list'), function ($query) use ($request) {
                $query->where('sabt_date', '>=', $request->input('date-picker-shamsi-list'));
            })
            ->when($request->get('date-picker-shamsi-list-1'), function ($query) use ($request) {
                $query->where('sabt_date', '<=', $request->input('date-picker-shamsi-list-1'));
            })->when($request->get('date-picker-shamsi-list-2'), function ($query) use ($request) {
                $query->where('delivery_date', '>=', $request->input('date-picker-shamsi-list-2'));
            })
            ->when($request->get('date-picker-shamsi-list-3'), function ($query) use ($request) {
                $query->where('delivery_date', '<=', $request->input('date-picker-shamsi-list-3'));
            })->paginate(30);
        $customers = Customer::all();
        return view('panel.order.index', ['rows' => $rows, 'customers' => $customers]);
    }

    public function edit($id)
    {
        $row = Order::where('id', $id)->first();
        $customers = Customer::all();

        return view('panel.order.edit', ['row' => $row, 'customers' => $customers]);
    }

    public function update(Request $request, $id)
    {
        $row = Order::where('id', $id)->first();
        $row->update([
            'customer_id' => $request->customer,
            'code' => $request->code,
            'sabt_date' => $request->input('date-picker-shamsi-list'),
            'delivery_date' => $request->input('date-picker-shamsi-list-1'),
            'description' => $request->description,
        ]);
        return redirect('orders');
    }

    public function past_create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $materials = Material::all();
        return view('panel.order.past_create', ['customers' => $customers, 'products' => $products, 'materials' => $materials]);
    }

    public function past_store(Request $request)
    {

        $order = Order::create([
            'author' => auth()->user()->id,
            'customer_id' => $request->customer,
            'code' => $request->code,
            'sabt_date' => $request->input('date-picker-shamsi-list'),
            'delivery_date' => $request->input('date-picker-shamsi-list-1'),
            'description' => $request->description,
        ]);
        alert()->success('لطفا محصولات را ثبت کنید.', 'مرحله بعد');
        return redirect('/orders/details/' . $order->id);
    }

    public function new_create()
    {
        $products = Product::all();
        $materials = Material::all();
        return view('panel.order.new_create', ['products' => $products, 'materials' => $materials]);
    }

    public function new_store(Request $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
        ]);
        $order = Order::create([
            'author' => auth()->user()->id,
            'customer_id' => $customer->id,
            'code' => $request->code,
            'sabt_date' => $request->input('date-picker-shamsi-list'),
            'delivery_date' => $request->input('date-picker-shamsi-list-1'),
            'description' => $request->description,
        ]);
        alert()->success('لطفا محصولات را ثبت کنید.', 'مرحله بعد');
        return redirect('/orders/details/' . $order->id);
    }

    public function details($id)
    {
        $products = Product::all();
        $materials = Material::with('unit')->get();
        $order = Order::where('id', $id)->with('customer')->first();
        $items = OrderProduct::where('order_id', $order->id)->get();
        $materialsItem = OrderMaterial::where('order_id', $order->id)->with('material')->get();
        $product_items = [];
        return view('panel.order.details', ['product_items' => $product_items, 'order' => $order, 'products' => $products, 'materials' => $materials, 'items' => $items, 'materialsItem' => $materialsItem]);
    }

    public function details_store(Request $request)
    {
        $box = $request->all();
        $myValue = array();
        parse_str($box['form'], $myValue);
        $orderProduct = OrderProduct::create([
            'order_id' => $myValue['order_id'],
            'product_id' => $myValue['product_id'],
            'price' => $myValue['price'],
            'discount' => $myValue['discount'],
//            'final_price' => ($myValue['price'] * (100 - $myValue['discount'])) / 100,
            'final_price' => ($myValue['price'] * (100 - $myValue['discount'])) / 100,
        ]);
        foreach ($myValue['product'] as $key => $product) {
            $row = ProductMaterial::where('id', $key)->first();
            $mat = Material::where('id', $product)->first();
            $mat->update([
                'remaining' => $mat->remaining - $row->count
            ]);
            OrderProductMaterial::create([
                'order_product_id' => $orderProduct->id,
                'material_id' => $mat->id,
                'count' => $row->count
            ]);
        }

//        $product = Product::where('id', $request->product_id)->first();
//        foreach ($product->materials as $material) {
//            $mat = Material::where('id', $material->material->id)->first();
//            $mat->update([
//                'remaining' => $mat->remaining - $material->count
//            ]);
//        }

        alert()->success('محصول جدید اضافه شد.', 'عملیات موفق');

        $items = OrderProduct::where('order_id', $myValue['order_id'])->get();
        return view('panel.order.product_render', compact('items'));
    }

    public function materials_store(Request $request)
    {
        OrderMaterial::create([
            'order_id' => $request->order_id,
            'material_id' => $request->material_id,
            'count' => $request->count,
        ]);

        $mat = Material::where('id', $request->material_id)->first();
        $mat->update([
            'remaining' => $mat->remaining - $request->count
        ]);
        alert()->success('متریال اضافه شد.', 'عملیات موفق');

        $items = OrderMaterial::where('order_id', $request->order_id)->with('material')->get();
        return view('panel.order.material_render', compact('items'));
    }

    public function details_delete($id)
    {
        $row = OrderProduct::where('id', $id)->first();
        $list = OrderProductMaterial::where('order_product_id', $row->id)->get();
        foreach ($list as $material) {
            $mat = Material::where('id', $material->material_id)->first();
            $mat->update([
                'remaining' => $mat->remaining + $material->count
            ]);
            $material->delete();
        }
        $row->delete();
        $items = OrderProduct::where('order_id', $row->order_id)->get();
        return view('panel.order.product_render', compact('items'));
    }

    public function materials_delete($id)
    {
        $row = OrderMaterial::where('id', $id)->first();
        $mat = Material::where('id', $row->material->id)->first();
        $mat->update([
            'remaining' => $mat->remaining + $row->count
        ]);
        $row->delete();
        $items = OrderMaterial::where('order_id', $row->order_id)->get();
        return view('panel.order.material_render', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->first();
        $order->update([
            'final' => 1,
            'price' => $request->final_price,
            'discount' => $request->final_discount,
            'final_price' => ($request->final_price * (100 - $request->final_discount)) / 100,
        ]);
        alert()->success('سفارش با موفقیت نهایی شد', 'عملیات موفق');

        return redirect('orders');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::where('id', $id)
            ->with('products.product.materials')
            ->with('materials')
            ->first();
        foreach ($order->products as $product) {
            foreach ($product->product->materials as $material) {
                $mat = Material::where('id', $material->material->id)->first();
                $mat->update([
                    'remaining' => $mat->remaining + $material->count
                ]);
            }
        }
        foreach ($order->materials as $material) {
            $mat = Material::where('id', $material->material->id)->first();
            $mat->update([
                'remaining' => $mat->remaining + $material->count
            ]);
        }
        $order->delete();
    }

    public function products($id)
    {
        $order = Order::where('id', $id)->first();
        $items = OrderProduct::where('order_id', $id)->get();
        $materialsItem = OrderMaterial::where('order_id', $id)->with('material')->get();
        return view('panel.order.products', ['items' => $items, 'materialsItem' => $materialsItem, 'order' => $order]);

    }

    public function product_price($id)
    {
        return Product::where('id', $id)->pluck('price')->first();
    }

    public function product_details($id)
    {
        $items = ProductMaterial::where('product_id', $id)->get();
        $materials = Material::with('unit')->get();

        return view('panel.order.product_detail_render', compact('items', 'materials'));
    }
}
