@extends('layouts.admin')
@section('css')
@endsection('css')
@section('script')
    <script src="/js/sweetalert.min.js"></script>
    @include('sweet::alert')
    <script>
        jQuery(document).ready(function () {
            jQuery('#hideshow').on('click', function (event) {
                jQuery('#search').toggle('show');
            });
        });
    </script>
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
            <h3>لیست</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="#">متریال ها</a></li>
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
                            <h5 class="modal-title" id="exampleModalLabel">تعریف متریال جدید</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/materials" method="post">
                            @csrf
                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>نام</label>
                                        <input class="form-control" name="name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>واحد</label>

                                        <select class="form-control" name="unit_id">
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id}}">{{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>دسته</label>

                                        <select class="form-control" name="type_id">
                                            @foreach($types as $type)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="price-box-product">
                                        <span> قیمت</span>
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
                <form method="get" action="/materials">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="p-2">
                            <label>نام متریال</label>
                            <input type="text" autocomplete="off" name="name"
                                   value="{{request()->input('name')}}"
                                   class="form-control">
                        </div>
                        <div class="p-2">
                            <label>دسته</label>
                            <select class="form-control" name="type_id">
                                <option></option>
                                @foreach($types as $type)
                                    <option value="{{$type->id}}"
                                            @if(request()->type_id==$type->id) selected @endif>{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-2">
                            <label>موجودی از</label>
                            <input type="text" autocomplete="off" name="remaining_from"
                                   value="{{request()->input('remaining_from')}}"
                                   class="form-control">
                        </div>
                        <div class="p-2">
                            <label>موجودی تا</label>
                            <input type="text" autocomplete="off" name="remaining_to"
                                   value="{{request()->input('remaining_to')}}"
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
                        <th>عنوان</th>
                        <th>واحد</th>
                        <th>دسته</th>
                        <th>قیمت</th>
                        <th>موجودی</th>

                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php $idn = 1; ?>
                        @foreach($rows as $row)
                            <td style="text-align: center">{{$idn}}</td>
                            <td style="text-align: center">{{$row->name}}</td>
                            <td style="text-align: center">{{$row->unit->name}}</td>
                            <td style="text-align: center">{{$row->type->name}}</td>
                            <td style="text-align: center">{{number_format($row->price)}}</td>
                            <td style="text-align: center">{{$row->remaining}}</td>
                            <td style="text-align: center">
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#inventory{{$row->id}}">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div class="modal fade" id="inventory{{$row->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog " role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalLabel">ثبت ورودی به انبار
                                                    برای
                                                    {{$row->name}}
                                                </h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/materials/inventory/{{$row->id}}" method="post">
                                                @csrf
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>مقدار</label>
                                                            <input type="number" class="form-control" name="count"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <br>
                                                            <button type="submit" class="btn btn-primary btn-block">
                                                                ذخیره
                                                            </button>
                                                        </div>
                                                    </div>
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
                                    <div class="modal-dialog " role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalLabel">ویرایش
                                                    {{$row->name}}
                                                </h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/materials/{{$row->id}}" method="post">
                                                @csrf
                                                {{method_field('PATCH')}}

                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>نام</label>
                                                            <input class="form-control" name="name" required
                                                                   value="{{$row->name}}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>واحد</label>

                                                            <select class="form-control" name="unit_id">
                                                                @foreach($units as $unit)
                                                                    <option @if($row->unit_id==$unit->id) selected
                                                                            @endif value="{{$unit->id}}">{{$unit->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>دسته</label>

                                                            <select class="form-control" name="type_id">
                                                                @foreach($types as $type)
                                                                    <option @if($row->type_id==$type->id) selected
                                                                            @endif value="{{$type->id}}">{{$type->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="price-box-product">
                                                                <span> قیمت</span>
                                                                <input name="price" class="form-control" id="price" required autocomplete="off" value="{{$row->price}}">
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
            {!! $rows->withQueryString()->links("pagination::bootstrap-4") !!}

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
                        url: "{{  url('/materials/delete/')  }}" + '/' + id,
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


