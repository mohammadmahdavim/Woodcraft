<table class="table table-bordered table-striped mb-0 table-fixed" id="myTable">
    <thead>
    <tr class="success" style="text-align: center">
        <th>شمارنده</th>
        <th>متریال</th>
        <th>تعداد/مقدار</th>
        <th>حذف</th>
    </tr>
    </thead>
    <tbody>
    <?php $idn = 1;

    ?>
    @foreach($items as $item)
        <tr>

            <td style="text-align: center">{{$idn}}</td>
            <td style="text-align: center">{{$item->material->name}}</td>
            <td style="text-align: center">{{$item->count}}</td>
            <td>
                <button class="btn  btn-danger" onclick="deleteDataMaterials({{$item->id}})"><i
                        class="fa fa-trash"></i></button>
            </td>
            <?php $idn = $idn + 1 ?>

        </tr>
    @endforeach
    </tbody>
</table>


