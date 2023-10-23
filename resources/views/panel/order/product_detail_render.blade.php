<link rel="stylesheet" href="/assets/vendors/select2/css/select2.min.css" type="text/css">
<script src="/assets/vendors/select2/js/select2.min.js"></script>
<script src="/assets/js/examples/select2.js"></script>
<table class="table table-bordered table-striped mb-0 table-fixed" id="myTable">
    <thead>
    <tr class="success" style="text-align: center">
        <th>متریال</th>
        <th>مقدار</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            <td style="text-align: center">
                <select class="js-example-basic-single" name="product[{{$item->id}}]" id="product{{$item->id}}"
                >
                    @foreach($materials as $material)
                        <option
                            @if($item->material_id==$material->id) selected @endif
                        value="{{$material->id}}">{{$material->name}} {{$material->unit->name}}</option>
                    @endforeach
                </select>

            </td>
            <td style="text-align: center">{{$item->count}}{{$item->material->unit->name}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<hr>
<br>
