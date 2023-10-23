@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="/assets/vendors/select2/css/select2.min.css" type="text/css">

@endsection('css')
@section('script')
    <script src="/assets/vendors/select2/js/select2.min.js"></script>
    <script src="/assets/js/examples/select2.js"></script>
    <script src="/js/sweetalert.min.js"></script>
    @include('sweet::alert')
    <script>
        jQuery(document).ready(function () {
            jQuery('#hideshow').on('click', function (event) {
                jQuery('#search').toggle('show');
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
            <h3>لیست</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="#">محصول ها</a></li>
                    <li class="breadcrumb-item active" aria-current="page">لیست</li>
                </ol>
            </nav>
        </div>

    </div>
@endsection('header')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addmaterial">
                <i class="fa fa-plus"></i>
                &nbsp;
            </button>
            <div class="modal fade" id="addmaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">تعریف محصول جدید</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/products" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label>نام</label>
                                        <input class="form-control" name="title" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>قیمت</label>
                                        <input class="form-control" name="price" type="number" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>کد</label>
                                        <input class="form-control" name="code" type="text" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>تصویر</label>
                                        <input class="form-control" name="image" type="file">
                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-block">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <input type='button' class="btn btn-warning" id='hideshow' value='جستجو'>
            <div id='search' style="display: none">
                <form method="get" action="/products">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="p-2">
                            <label>نام محصول</label>
                            <input type="text" autocomplete="off" name="name"
                                   value="{{request()->input('name')}}"
                                   class="form-control">
                        </div>
                        <div class="p-2">
                            <label>کد محصول</label>
                            <input type="text" autocomplete="off" name="code"
                                   value="{{request()->input('code')}}"
                                   class="form-control">
                        </div>

                        <div class="p-2">
                            <br>
                            <button type="submit" class="btn btn-info">جستجوکن</button>
                        </div>
                    </div>

                </form>
            </div>


            <!-- Modal -->
            <div class="">
                <br>
                <table class="table table-bordered table-striped mb-0 table-fixed" id="myTable">
                    <thead>
                    <tr class="success" style="text-align: center">
                        <th>شمارنده</th>
                        <th>تصویر</th>
                        <th>عنوان</th>
                        <th>کد</th>
                        <th>قیمت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php $idn = 1; ?>
                        @foreach($rows as $row)
                            <td style="text-align: center">{{$idn}}</td>
                            <td style="text-align: center">
                                @if($row->image)
                                    <img src="/product/{{$row->image}}" width="50" height="50" class="rounded">
                                @endif
                            </td>
                            <td style="text-align: center">{{$row->title}}</td>
                            <td style="text-align: center">{{$row->code}}</td>
                            <td style="text-align: center">{{$row->price}}</td>
                            <td style="text-align: center">
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#inventory{{$row->id}}">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div class="modal fade" id="inventory{{$row->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalLabel">ثبت متریال
                                                    برای
                                                    {{$row->title}}
                                                </h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/products/material/{{$row->id}}" method="post">
                                                @csrf
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>متریال</label>
                                                            <select name="material_id" class="js-example-basic-single">
                                                                @foreach($materials as $material)
                                                                    <option
                                                                        value="{{$material->id}}">{{$material->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>مقدار</label>
                                                            <input type="number" class="form-control" name="count"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <br>
                                                            <button type="submit" class="btn btn-primary btn-block">
                                                                ذخیره
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <table class="table table-bordered table-striped mb-0 table-fixed"
                                                           id="myTable">
                                                        <thead>
                                                        <tr class="success" style="text-align: center">
                                                            <th>متریال</th>
                                                            <th>مقدار</th>
                                                            <th>حذف</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($row->materials as $material)
                                                            <tr>

                                                                <td>{{$material->material->name}}</td>
                                                                <td>{{$material->count}}</td>
                                                                <td>
                                                                    <button class="btn  btn-danger" type="button"
                                                                            onclick="deleteMaterialData({{$row->id}})">
                                                                        <i
                                                                            class="fa fa-trash"></i></button>
                                                                </td>

                                                            </tr>      @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#edit{{$row->id}}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <div class="modal fade" id="edit{{$row->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalLabel">ویرایش
                                                    {{$row->title}}
                                                </h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/products/{{$row->id}}" method="post"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                {{method_field('PATCH')}}

                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>نام</label>
                                                                <input class="form-control" name="title" required
                                                                       value="{{$row->title}}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>قیمت</label>
                                                                <input class="form-control" name="price" type="number"
                                                                       required value="{{$row->price}}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>کد</label>
                                                                <input class="form-control" name="code" type="text" value="{{$row->code}}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>تصویر</label>
                                                                <input class="form-control" name="image" type="file">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-block">ذخیره
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn  btn-danger" onclick="deleteData({{$row->id}})"><i
                                        class="fa fa-trash"></i></button>
                            </td>
                    </tr>
                    <?php $idn = $idn + 1 ?>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="/js/sweetalert.min.js"></script>

    @include('sweet::alert')

@endsection('content')
<script>
    function deleteData(id) {
        swal({
            title: "آیا از حذف مطمئن هستید؟",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{  url('/products/delete/')  }}" + '/' + id,
                        type: "GET",

                        success: function () {
                            swal({
                                title: "حذف با موفقیت انجام شد!",
                                icon: "success",

                            });
                            window.location.reload(true);
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
    function deleteMaterialData(id) {
        swal({
            title: "آیا از حذف مطمئن هستید؟",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{  url('/products/material/delete/')  }}" + '/' + id,
                        type: "GET",

                        success: function () {
                            swal({
                                title: "حذف با موفقیت انجام شد!",
                                icon: "success",

                            });
                            window.location.reload(true);
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


