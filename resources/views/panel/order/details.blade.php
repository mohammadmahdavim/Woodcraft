@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="/assets/vendors/datepicker-jalali/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/assets/vendors/datepicker/daterangepicker.css">
    <!-- begin::select2 -->
    <link rel="stylesheet" href="/assets/vendors/select2/css/select2.min.css" type="text/css">
    <!-- end::select2 -->
@endsection('css')
@section('script')
    <script src="/assets/vendors/ckeditor/ckeditor.js"></script>
    <script src="/assets/js/examples/ckeditor.js"></script>
    <!-- end::CKEditor -->
    <script src="/assets/vendors/datepicker-jalali/bootstrap-datepicker.min.js"></script>
    <script src="/assets/vendors/datepicker-jalali/bootstrap-datepicker.fa.min.js"></script>
    <script src="/assets/vendors/datepicker/daterangepicker.js"></script>
    <script src="/assets/js/examples/datepicker.js"></script>
    <!-- begin::sweet alert demo -->
    <script src="/js/sweetalert.min.js"></script>
    @include('sweet::alert')
    <script src="/assets/vendors/select2/js/select2.min.js"></script>
    <script src="/assets/js/examples/select2.js"></script>
    <script src="/js/num2persian-min.js"></script>
    <script src="/js/number-divider.min.js"></script>
    <script>

        $(document).on('focus', '.price-box-product input', function () {
            var boxPrice = $(this).siblings('.price-box-product-content');
            boxPrice.fadeIn(100);
            boxPrice.find('.price-box-numbers').html($(this).val())
            boxPrice.find('.price-box-numbers').divide({
                delimiter: ',',
                divideThousand: false
            });
            var e = this;
            this.nextSibling.nextElementSibling.children[3].childNodes[1].nextElementSibling.innerHTML = e.value
                .toPersianLetter()
            e.oninput = myHandler;
            e.onpropertychange = e.oninput; // for IE8
            function myHandler() {
                this.nextSibling.nextElementSibling.children[3].childNodes[1].nextElementSibling.innerHTML = e.value
                    .toPersianLetter();
            }
        });

        $(document).on('click', '.price-box-product-content button.close', function () {
            $(this).parents('.price-box-product-content').fadeOut(100);
        })

        $(document).on('blur', '.price-box-product input', function () {
            $(this).siblings('.price-box-product-content').fadeOut(100);
        });

        $(document).on('keyup', '.price-box-product input', function () {
            var boxPrice = $(this).siblings('.price-box-product-content');
            boxPrice.find('.price-box-numbers').html($(this).val());
            boxPrice.find('.price-box-numbers').divide({
                delimiter: ',',
                divideThousand: false
            });
        });
    </script>
@endsection('script')
@section('navbar')


@endsection('navbar')
@section('sidebar')
@endsection('sidebar')

@section('header')
    <div class="page-header">
        <div>
            <h3>ثبت محصولات</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="#"> سفارش {{$order->customer->name}} با کد {{$order->code}}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">ثبت محصولات</li>
                </ol>
            </nav>
        </div>

    </div>
@endsection('header')
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="formProduct">
                <input hidden value="{{$order->id}}" name="order_id" id="order_id">
                @include('include.errors')
                <div class="form-group">
                    <div class="d-flex flex-row">
                        <div class="p-2">
                            <span>انتخاب محصول</span>
                            <select class="js-example-basic-single" name="product_id" id="product_id"
                                    onchange="product_price()" >
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-2">
                            <div class="price-box-product">
                                <span>ثبت قیمت</span>
                                <input name="price" class="form-control" id="price" required autocomplete="off">
                                <div class="price-box-product-content">
                                    <div
                                        class="price-box-header-product d-flex justify-content-between align-items-center">
                                        <span>وضعیت مبلغ شما</span>
                                        <button class="close"><i
                                                class="ion-android-close"></i></button>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-secondary ml-2">به عدد:</span>
                                        <span class="price-box-numbers ml-2">
                                                                        </span>
                                        <span class="text-dark">ریال</span>
                                    </div>

                                    <hr>
                                    <div class="d-flex align-items-center">
                                                                        <span class="text-secondary ml-2">به
                                                                            حروف:</span>
                                        <span class="price-box-letters ml-2">
                                                                        </span>
                                        <span class="text-dark">ریال</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="p-2">
                            <span>ثبت تخفیف</span>
                            <input name="discount" class="form-control" value="0" id="discount" required>
                        </div>
                        <div class="p-2">
                            <div id="product_details_render">
                            </div>
                        </div>
                        <div class="p-2">
                            <br>
                            <button type="button" class="btn btn-success" onclick="formsubmit()">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                اضافه کن
                            </button>
                        </div>

                        </div>
                </div>
            </form>

            <div id="render">
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
                                <input name="final_discount" value="{{$order->discount}}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <br>
                                <button class="btn btn-info btn-block">ثبت نهایی</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="formMaterial">
                <input hidden value="{{$order->id}}" name="order_id" id="order_id">
                @include('include.errors')
                <div class="form-group">
                    <div class="d-flex flex-row">
                        <div class="p-2">
                            <span>انتخاب متریال</span>
                            <select class="js-example-basic-single" name="material_id" id="material_id">
                                @foreach($materials as $material)
                                    <option
                                        value="{{$material->id}}">{{$material->name}} {{$material->unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-2">
                            <span>تعداد/مقدار</span>
                            <input name="count" class="form-control" value="0" id="count" required>
                        </div>
                        <div class="p-2">
                            <br>
                            <button type="button" class="btn btn-success" onclick="materialFormsubmit()">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                اضافه کن
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="render_material">
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
                    @foreach($materialsItem as $item)
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


            </div>
        </div>
    </div>
@endsection('content')
<script>

    function product_price() {
        var product_id = $("#product_id option:selected").val();
        $.ajax({
            type: "GET",
            url: '/orders/product_price/' + product_id,
            success: function (price) {
                $("#price").val();
                document.getElementById("price").value = price;
            }
        });
        $.ajax({
            type: "GET",
            url: '/orders/product_details/' + product_id,
            success: function (data) {
                $('#product_details_render').html(data);

            }
        });
    }


    function formsubmit() {
        var form=$("#formProduct").serialize();
        var product_id = $("#product_id option:selected").val();
        var order_id = $("#order_id").val();
        var price = $("#price").val();
        var discount = $("#discount").val();
        if (!$.trim(price)) {
            alert('قیمت را وارد کنید.')
            return 'قیمت را وارد کنید.';
        }
        if (!$.trim(discount)) {
            discount = 0;
        }
        $('#render').html('');

        $.ajax({
            type: "POST",
            url: '/orders/details/store',
            data: {
                "_token": "{{ csrf_token() }}",
                form: form,
            },
            success: function (data) {
                swal({
                    title: "محصول با موفقیت اضافه شد",
                    icon: "success",

                });
                $('#render').html(data);
            }
        });
    }
    function changeprice() {
        var form=$("#formProduct").serialize();
        var product_id = $("#product_id option:selected").val();
        var order_id = $("#order_id").val();
        var price = $("#price").val();
        var discount = $("#discount").val();
        if (!$.trim(price)) {
            alert('قیمت را وارد کنید.')
            return 'قیمت را وارد کنید.';
        }
        if (!$.trim(discount)) {
            discount = 0;
        }
        $('#render').html('');

        $.ajax({
            type: "POST",
            url: '/orders/details/change_price',
            data: {
                "_token": "{{ csrf_token() }}",
                form: form,
            },
            success: function (data) {

               document.getElementById('price').value=data
            }
        });
    }

    function materialFormsubmit() {

        var material_id = $("#material_id").val();
        var order_id = $("#order_id").val();
        var count = $("#count").val();
        if (!$.trim(count)) {
            alert('تعداد را وارد کنید.')
            return 'تعداد را وارد کنید.';
        }

        $('#render_material').html('');

        $.ajax({
            type: "POST",
            url: '/orders/materials/store',
            data: {
                "_token": "{{ csrf_token() }}",
                order_id: order_id,
                material_id: material_id,
                count: count,
            },
            success: function (data) {
                swal({
                    title: "متریال با موفقیت اضافه شد",
                    icon: "success",

                });
                $('#render_material').html(data);
            }
        });
    }
</script>
<script>
    function deleteDataDetail(id) {
        swal({
            title: "آیا از حذف مطمئن هستید؟",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $('#render').html('');

                    $.ajax({
                        url: "{{  url('/orders/details/delete/')  }}" + '/' + id,
                        type: "GET",

                        success: function (data) {
                            swal({
                                title: "حذف با موفقیت انجام شد!",
                                icon: "success",

                            });
                            $('#render').html(data);

                        },
                        error: function () {
                            swal({
                                title: "خطا...",
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })

                        }
                    });
                } else {
                    swal("عملیات حذف لغو گردید");
                }
            });

    }

    function deleteDataMaterials(id) {
        swal({
            title: "آیا از حذف مطمئن هستید؟",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $('#render_material').html('');

                    $.ajax({
                        url: "{{  url('/orders/material/delete/')  }}" + '/' + id,
                        type: "GET",

                        success: function (data) {
                            swal({
                                title: "حذف با موفقیت انجام شد!",
                                icon: "success",

                            });
                            $('#render_material').html(data);

                        },
                        error: function () {
                            swal({
                                title: "خطا...",
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })

                        }
                    });
                } else {
                    swal("عملیات حذف لغو گردید");
                }
            });

    }

</script>
