<table class="table table-bordered table-striped mb-0 table-fixed" id="myTable">
    <thead>
    <tr class="success" style="text-align: center">
        <th>شمارنده</th>
        <th>محصول</th>
        <th>قیمت</th>
        <th>تخفیف</th>
        <th>قیمت نهایی</th>
        <th>حذف</th>
    </tr>
    </thead>
    <tbody>
    <?php $idn = 1;

    ?>
    @foreach($items as $item)
        <tr>

            <td style="text-align: center">{{$idn}}</td>
            <td style="text-align: center">{{$item->product->title}}</td>
            <td style="text-align: center">{{number_format($item->price)}}</td>
            <td style="text-align: center">{{$item->discount}}</td>
            <td style="text-align: center">{{number_format($item->final_price)}}</td>
            <td>
                <button class="btn  btn-danger" onclick="deleteDataDetail({{$item->id}})"><i
                        class="fa fa-trash"></i></button>
            </td>
            <?php $idn = $idn + 1 ?>

        </tr>
    @endforeach
    @if($items!='[]')
        <tr style="background-color: #78f378">
            <td style="text-align: center" colspan="2">جمع مبالغ</td>
            <td style="text-align: center">{{number_format($items->sum('price'))}}</td>
            <td style="text-align: center">{{number_format($items->sum('price')-$items->sum('final_price'))}}</td>
            <td style="text-align: center">{{number_format($items->sum('final_price'))}}</td>

        </tr>
    @endif
    </tbody>
</table>

<hr>
<br>
@if($items!='[]')
    <form action="/orders" method="post">
        @csrf
        <input name="order_id" value="{{$items[0]->order_id}}" hidden>
        <input name="final_price" value="{{$items->sum('final_price')}}" hidden>
        <div class="row">
            <div class="col-md-3">
                <h4>
                    قابل پرداخت:
                    {{number_format($items->sum('final_price'))}}
                </h4>
            </div>
            <div class="col-md-3">
                <span>تخفیف نهایی</span>
                <input name="final_discount" value="{{$item->order->discount}}" class="form-control">
            </div>
            <div class="col-md-3">
                <br>
                <button class="btn btn-info btn-block">ثبت نهایی</button>
            </div>
        </div>
    </form>
@endif
