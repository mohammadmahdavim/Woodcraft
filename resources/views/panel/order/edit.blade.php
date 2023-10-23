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
@endsection('script')
@section('navbar')


@endsection('navbar')
@section('sidebar')
@endsection('sidebar')

@section('header')
    <div class="page-header">
        <div>
            <h3> ویرایش {{$row->customer->name}} با کد {{$row->code}}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="#">سفارش</a></li>
                    <li class="breadcrumb-item"><a href="#"> ویرایش {{$row->customer->name}} با کد {{$row->code}}</a>
                    </li>
                </ol>
            </nav>
        </div>

    </div>
@endsection('header')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/orders/{{$row->id}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                {{method_field('PATCH')}}
                @include('include.errors')
                <div class="row">
                    <div class="col-md-3">
                        <label>انتخاب مشتری </label>
                        <select class="js-example-basic-single" name="customer">
                            @foreach($customers as $customer)
                                <option @if($row->customer_id==$customer->id) selected @endif value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>شماره فاکتور</label>
                        <input class="form-control" name="code" value="{{$row->code}}">
                    </div>
                    <div class="col-md-3">
                        <label>تاریخ ثبت سفارش</label>
                        <input class="form-control" autocomplete="off" name="date-picker-shamsi-list" value="{{$row->sabt_date}}">
                    </div>
                    <div class="col-md-3">
                        <label>تاریخ تحویل سفارش</label>
                        <input class="form-control" autocomplete="off" name="date-picker-shamsi-list-1" value="{{$row->delivery_date}}">
                    </div>
                    <div class="col-md-12">
                        <br>
                        <h6><label>متن </label></h6>
                        <textarea id="editor-demo1" name="body"
                        >{!! $row->description !!}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <br>
                    <button class="btn btn-primary" type="submit">ذخیره و ارسال
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection('content')
