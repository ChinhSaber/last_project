@extends('layouts.master')
@section('titles', "Bệnh án")
@section('content')
    <head>
        <title></title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    </head>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style type="text/css">
        #content{
            position: relative;
        }
        #form_information{
            position: absolute;
            position: fixed;
            width: 60%;
            top: 0%;
            z-index: 9999;
        }
        #textarea-input{
            resize: none;
            height: 40px;
        }
    </style>
    <div id="list">
        <table class="table" id="list">
            <tr>
                <th scope="col" style="text-align: center;">Họ và tên bệnh nhân</th>
                <th scope="col" style="text-align: center;">Tuổi</th>
                <th scope="col" style="text-align: center;">Giới tính</th>
                <th scope="col" style="text-align: center;">Chuyên khoa</th>
                <th scope="col" style="text-align: center;">Ngày nhập viện</th>
                <th scope="col" style="text-align: center;">Ngày xuất viện</th>
                <th scope="col" style="text-align: right;"></th>
            </tr>
            <tbody>
            @foreach ($array_list as $medicalrecords)
                <tr>
                    <td align="center">
                        {{$medicalrecords->patient->full_name}}
                    </td>
                    <td align="center">
                        {{ $age = date_diff(date_create($medicalrecords->patient->birthday), date_create('now'))->y}} tuổi
                    </td>
                    <td align="center">
                        @php
                            if ($medicalrecords->patient->gender==1){
                                echo "Nam";
                            }else{
                                echo "Nữ";
                            }
                        @endphp
                    </td>
                    <td align="center">
                        {{ $medicalrecords->speciallist->speciallist_name }}
                    </td>
                    <td align="center">
                        {{ $medicalrecords->created_at->format('d/m/Y') }}
                    </td>
                    <td align="center">
                        {{ $medicalrecords->updated_at->format('d/m/Y') }}
                    </td>
                    <th scope="col" align="center" style="text-align: right;">
                        <button type="button" class="btn btn-success fas fa-check" style="color: white;" onclick="show()"
                                medicalrecords_id = "{{$medicalrecords->medicalrecords_id}}" title="Tình trạng sức khoẻ"></button>
                    </th>
                </tr>
            @endforeach
            <br>
            </tbody>
        </table>
    </div>

    <div id="form_information" style="display: none ;">
        <div class="card"  >
            <div class="card-header" align="center" style="height: 50px;">
                <div class="row form-group">
                    <div class="col-12 col-md-11"><strong>Bệnh án</strong></div>
                    <div class="col-12 col-md-1"><input type="reset" align="right" value=" X " onclick="hiden()" style="background-color: red;"></div>
                </div>
            </div>
            <div class="card-body card-block" >
                <form action="{{ route('medicalrecords.discharge') }}" id="routes" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Họ và tên bệnh nhân</label></div>
                        <div class="col-12 col-md-9">
                            <input type="hidden" id="medicalrecords_id" name="medicalrecords_id" readonly="readonly" class="form-control">
                            <input type="hidden" id="patient_id" name="patient_id" readonly="readonly" class="form-control">
                            <input type="text" id="patient_name" class="form-control"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Ngày sinh</label></div>
                        <div class="col-12 col-md-9"><input type="text" id="birthday" name="birthday" class="form-control" readonly></div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Giới tính</label></div>
                        <div class="col-12 col-md-9"><input type="text" id="gender" name="gender" class="form-control" readonly></div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Số điện thoại liên hệ</label></div>
                        <div class="col-12 col-md-9"><input type="text" id="contact_phone" name="contact_phone" class="form-control" readonly></div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="select" class=" form-control-label">Chuyên khoa</label></div>
                        <div class="col-12 col-md-9">
                            <select name="speciallist_id" class="form-control" id="select_speciallist">
                                <option selected="selected" value="0" disabled>Chuyên Khoa</option>
                                @foreach ($speciallist as $speciallist)
                                    <option value="{{ $speciallist->speciallist_id }}">
                                        {{ $speciallist->speciallist_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="select" class=" form-control-label">Bác sĩ</label></div>
                        <div class="col-12 col-md-9">
                            <select class="form-control" name="doctor_id" id="select_doctor">
                                <option selected="selected" value="0" disabled>Bác Sĩ Điều trị</option>
                                @foreach ($doctor as $doctor)
                                    <option value="{{ $doctor->doctor_id }}">
                                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Phòng điều trị</label></div>
                        <div class="col-12 col-md-9"><input type="text" id="room" name="room" class="form-control"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Sức khoẻ khi xuất viện</label></div>
                        <div class="col-12 col-md-9"><input type="text" id="status" name="status" class="form-control"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Dặn dò</label></div>
                        <div class="col-12 col-md-9"><input type="text" id="advice" name="advice" class="form-control" placeholder="Tạm thời chưa có dặn dò từ bác sĩ và y tá"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{$array_list->appends(['search' => $search])->links()}}
@endsection

@push('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.3-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript" >
        jQuery(document).ready(function($) {
            $(document).on('click', '.btn.btn-success.fas.fa-check', function (){
                var medicalrecords_id = $(this).attr('medicalrecords_id');
                $.ajax({
                    url: '{{ route('ajax.patient_medicalrecords') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {medicalrecords_id : medicalrecords_id},
                })
                    .done(function(response) {
                        console.log(response);
                        today = new Date();
                        hospitalized_day_date = new Date(response[0]['created_at']);
                        hospitalized_day = new Date(response[0]['created_at']).toLocaleDateString();
                        day_in = parseInt( Number(new Date( today - hospitalized_day_date )) / Number('86400000') );
                        console.log(response[0]['medicalrecords_id']);
                        $("#medicalrecords_id").val(response[0]['medicalrecords_id']);
                        $("#patient_name").val(response[0]['first_name']+' '+response[0]['last_name']).attr('readonly','true');
                        $("#select_speciallist").val(response[0]['speciallist_id']);
                        $("#select_doctor").val(response[0]['doctor_id']);
                        $("#created_at").val(hospitalized_day);
                        $("#room").val(response[0]['room']);
                        $("#status").val(response[0]['status']);
                        $("#advice").val(response[0]['advice']);
                        $("#day_in").val(Number(day_in) + ' ngày'+ ' ('+ '250000 đồng/ngày' +')' );
                        $("#price").val( parseFloat('250000') *  Number(day_in) );
                        $("#price_vnd").val( (parseFloat('250000') *  Number(day_in))+ ' đồng' );
                        $("#hospitalized_day").show();
                        $("#birthday").val(response[0]['birthday']);
                        $("#contact_phone").val(response[0]['contact_phone']);
                        if(response[0]['gender'] == 1){
                            $("#gender").val('Nam');
                        } else {
                            $("#gender").val('Nữ');
                        }
                        $(".far.fa-check-circle").text(" Thanh toán viện phí");
                    })
            });

            $("#select_speciallist").change(function(){
                var speciallist_id = $(this).val();
                $("#select_doctor").html('');
                $.ajax({
                    url: '{{ route('ajax.doctor_speciallist') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {speciallist_id : speciallist_id},
                })
                    .done(function(response) {
                        $(response).each(function()
                        {
                            $("#select_doctor").append(`
                                <option value='${this.doctor_id}'>
                                ${this.first_name} ${this.last_name}
                                </option>
                            `)
                        })
                    })
            });

            $("#nameid").select2({
                placeholder: "Select a Name",
                allowClear: true
            });

        });

        function show() {
            document.getElementById("form_information").style.display = "block";
        }

        function hiden() {
            document.getElementById("form_information").style.display = "none";
        }

    </script>
@endpush

{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>--}}

{{--@extends('layouts.master')--}}
{{--@section('titles',"Phân môn giáo viên")--}}
{{--@section('content')--}}

{{--    <div class="card"  >--}}

{{--        <div class="card-body card-block" >--}}
{{--            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">--}}
{{--                @csrf--}}
{{--                <div class="row form-group">--}}
{{--                    <div class="col col-md-3"><label for="select" class=" form-control-label">Giáo viên</label></div>--}}
{{--                    <div class="col-12 col-md-9">--}}
{{--                        <select name="id_teacher" class="form-control" id="select_course">--}}
{{--                            <option value="0" disabled selected></option>--}}
{{--                            <option value="1">1</option>--}}
{{--                            <option value="2">2</option>--}}
{{--                            <option value="3">3</option>--}}
{{--                            <option value="4">4</option>--}}
{{--                            <option value="5">5</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--                <div class="row form-group">--}}
{{--                    <div class="col col-md-3"><label for="select" class=" form-control-label">Môn</label></div>--}}
{{--                    <div class="col-12 col-md-9">--}}
{{--                        <select id="nameid" style="width: 200px" class="select2" >--}}
{{--                            <option value="0" disabled selected>Bệnh nhân</option>--}}
{{--                            <option value="1">1</option>--}}
{{--                            <option value="2">2</option>--}}
{{--                            <option value="3">3</option>--}}
{{--                            <option value="4">4</option>--}}
{{--                            <option value="5">5</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}


{{--                </div>--}}


{{--                <button type="submit" class="btn btn-primary btn-sm" >--}}
{{--                    <i class="fa fa-dot-circle-o"></i> Submit--}}
{{--                </button>--}}
{{--                <button type="reset" class="btn btn-danger btn-sm">--}}
{{--                    <i class="fa fa-ban"></i> Reset--}}
{{--                </button>--}}

{{--            </form>--}}
{{--        </div>--}}

{{--    </div>--}}


{{--@endsection--}}

{{--@push('js')--}}

{{--    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>--}}
{{--    <script  type="text/javascript" >--}}
{{--        jQuery(document).ready(function($) {--}}
{{--            $('#nameid').select2({--}}
{{--                placeholder:'Nhập tên bệnh nhân',--}}
{{--                allowClear: true--}}
{{--            })--}}
{{--        });--}}
{{--    </script>--}}

{{--@endpush--}}
