@extends('Frontend.master')
@foreach ($unique_course as $course)

@php
    $chk_course_type = $course["COURSE_TYPE"];
@endphp

    @section('TitleTabName')
    {{ $course["COURSE_NAME_TH"] }}
        @if ($course["COURSE_TYPE"] == "T")
            หลักสูตรเทียบโอน
        @elseif ($course["COURSE_TYPE"] == "R")
            หลักสูตรปกติ
        @endif
    @endsection


    @section('Getbody')
        <section class="about-section text-center" id="about">
        <div class="container">
            <div class="row">
                    <div class="col-lg-12 mx-auto">
                        @php
                            if ($course["IMAGE_UPLOAD_DET"] == "" or $course["IMAGE_UPLOAD_DET"] == null) {
                                $img = "00000-NoImage.jpg";
                            } else {
                                $img = $course["IMAGE_UPLOAD_DET"];
                            }
                        @endphp
                        <h3 class="text-white mb-4">
                            สาขาวิชา {{ $course["COURSE_NAME_TH"] }}
                            (
                                @if ($course["COURSE_TYPE"] == "T")
                                    หลักสูตรเทียบโอน
                                @elseif ($course["COURSE_TYPE"] == "R")
                                    หลักสูตรปกติ
                                @endif
                            )
                            <br>
                            [ {{ $course["COURSE_NAME_EN"] }} ]
                        </h3>
                    </div>
                    <br>
                    <div class="container" style="background-color: white">
                        <br>
                        <img src="{{ url('promote-image/' . $img) }}" style="width: 100%;height: 300px;">
                        <div class="container">
                            <br>
                            <div class="col-sm-12">
                                <div class="text-left">{{ $course["DESCRIPTION_DETAIL"] }}</div>
                            </div>
                            @php
                                $learning = explode("<br>" , $course["LEARNING_LIST"]);
                                $qualification = explode("<br>" , $course["QUALIFICATION_REQ"]);
                            @endphp
                                <br>
                            <div class="col-sm-12 text-left">
                                <b>รายวิชาของหลักสูตร : </b>
                                <br>
                                @foreach ($learning as $learn)
                                    <div class="container mx-auto">{{ $learn }}</div>
                                @endforeach
                            </div>
                                <br>
                            <div class="col-sm-12 text-left">
                                <b>คุณสมบัติของผู้สมัคร : </b>
                                <br>
                                @foreach ($qualification as $qualified)
                                    <div class="container mx-auto">{{ $qualified }}</div>
                                @endforeach
                            </div>
                            <div class="col-sm-12 text-center">
                                <br>
                                <button class="btn-lg btn-success mx-auto" id="ShowVerify">สมัครเรียน</button>
                            </div>
                        </div>
                        <br>
                    </div>
                <br>
            </div>
        </div>
        </section>

        {{-- Verify Modal  --}}
        <div class="modal fade" id="Verify_register">
            <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                <h5 class="modal-title">การตรวจสอบคุณสมบัติผู้สมัคร</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="DataVerify" value="{{ $chk_course_type }}" disabled>
                        <label for="class_level_register">
                            ระดับการศึกษาที่จบ :
                        </label>
                        <select name="class_level_register" id="class_level_register_id" class="form-control" style="width: 100%" required>
                                <option value="" selected="" disabled>--- โปรดเลือกระดับการศึกษาที่จบ ---</option>
                            @foreach ($class_level as $cl)
                                <option value="{{ $cl["CLASS_LEVEL_CODE"] }}">{{ $cl["CLASS_LEVEL_NAME_TH"] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-success" onclick="VerifyRegister()">ตรวจสอบคุณสมบัติ</button>
                    <button type="button" class="btn-sm btn-danger" data-dismiss="modal">ยกเลิกการตรวจสอบคุณสมบัติ</button>
                </div>

            </div>
            </div>
        </div>
        {{-- Verify Modal  --}}

        {{-- Register Modal  --}}
        <div class="modal fade" id="Register_Modal" tabindex="-1" role="dialog" aria-labelledby="app_form" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                <h5 class="modal-title" id="app_form">แบบฟอร์มสมัครโควต้าสำหรับนักศึกษา</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('submit.form') }}" id="ApplicationForm" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="course_code" value="{{ $id }}">
                    {{-- ข้อมูลผู้แนะนำ --}}
                        <div class="col-sm-12 mx-auto text-left"><b>ข้อมูลเจ้าหน้าที่ที่แนะนำ : </b></div>
                        <br>
                        <div class="container">
                            <div class="col-sm-12">
                                <label for="selectPersonnelID">
                                    อาจารย์ / เจ้าหน้าที่ ที่แนะนำ :
                                </label>
                                <select name="selectPersonnel" id="selectPersonnelID" class="form-control" style="width: 100%" required>
                                    <option value="" selected="" disabled>--- เลือกอาจารย์ / เจ้าหน้าที่ ---</option>
                                    @foreach ($person_data as $ps)
                                        <option value="{{ $ps->PERSONNEL_CODE }}">{{ $ps->PERSONNEL_NAME }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    {{-- ข้อมูลผู้สมัคร --}}
                        <br>
                        <div class="col-sm-12 mx-auto text-left"><b>ข้อมูลทั่วไปของผู้สมัคร : </b></div>
                        <br>
                        <div class="container">
                            <div class="col-sm-12">
                                <label for="prefixID">
                                    คำนำหน้าชื่อ :
                                </label>
                                <select name="prefix" id="prefixID" class="form-control" style="width: 100%" required>
                                    <option value="" selected="" disabled>--- เลือกคำนำหน้า ---</option>
                                    @foreach ($prefix as $pf)
                                        <option value="{{ $pf->PREFIX_CODE }}">{{ $pf->PREFIX_NAME_TH }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label for="regist_name_thai">ชื่อ - นามสกุลผู้สมัคร : (ไทย) </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name_t" id="first_name_thai" placeholder="ชื่อผู้สมัคร (ไทย)" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name_t" id="last_name_thai" placeholder="นามสกุล (ไทย)" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="regist_name_eng">ชื่อ - นามสกุลผู้สมัคร :  (อังกฤษ)</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name_e" id="first_name_eng" placeholder="ชื่อผู้สมัคร (อังกฤษ)" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name_e" id="last_name_eng" placeholder="นามสกุล (อังกฤษ)" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="id_card">บัตรประจำตัวประชาชน : </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="id_card" id="id_card_id" placeholder="รหัสบัตรประชาชน 13 หลัก" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="prefixID">
                                    เพศ :
                                </label>
                                <select name="gender" id="genderID" class="form-control" style="width: 100%" required>
                                    <option value="" selected="" disabled>--- เลือกเพศ ---</option>
                                    <option value="F">เพศหญิง</option>
                                    <option value="M">เพศชาย</option>
                                </select>
                            </div>
                        </div>
                    {{-- ข้อมูลการศึกษา --}}
                        <br>
                        <div class="col-sm-12 mx-auto text-left"><b>ข้อมูลการศึกษา : </b></div>
                        <br>
                        <div class="container">
                            <div class="col-sm-12">
                                <label for="learning_level">
                                    ระดับการศึกษา :
                                </label>
                                <div class="form-group">
                                    <select name="learning_level" id="learning_levelID" class="form-control" style="width: 100%" required>
                                        <option value="" selected="" disabled>--- เลือกระดับการศึกษา ---</option>
                                        @foreach ($class_level as $cl)
                                            <option value="{{ $cl["CLASS_LEVEL_CODE"] }}">{{ $cl["CLASS_LEVEL_NAME_TH"] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="school">
                                    สถานศึกษา :
                                </label>
                                <div class="form-group">
                                    <select name="school" id="schoolID" class="form-control" style="width: 100%" required>
                                        <option value="" selected="" disabled>--- เลือกสถานศึกษา ---</option>
                                        @foreach ($school as $sc)
                                            <option value="{{ $sc["SCHOOL_CODE"] }}">{{ $sc["SCHOOL_NAME_TH"] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="gpa">
                                    คะแนนเฉลี่ย :
                                </label>
                                <div class="form-group">
                                    <input type="number" class="form-control" max="4.0" step="0.01" name="gpa" id="gpaID" placeholder="Ex : 4.00" required>
                                </div>
                            </div>
                        </div>
                    {{-- ข้อมูลการติดต่อ --}}
                    <br>
                    <div class="col-sm-12 mx-auto text-left"><b>ข้อมูลการติดต่อ : </b></div>
                    <br>
                    <div class="container">
                        <div class="col-sm-12">
                            <label for="LineID">
                                ไอดีไลน์ :
                            </label>
                            <div class="form-group">
                                <input type="text" name="LineID" id="LineID_id" placeholder="Ex : @RMUTSBSCI" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="telephone">
                                เบอร์โทรศัพท์ :
                            </label>
                            <div class="form-group">
                                <input type="tel" name="telephone" id="telephoneID" placeholder="Ex : 0912345678" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="email">
                                อีเมล์ :
                            </label>
                            <div class="form-group">
                                <input type="email" name="Email" id="EmailID" class="form-control" placeholder="Ex : Example@testDomain.com" required>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-success" onclick="verifyApplication()">ยื่นสมัคร</button>
                    <button type="button" class="btn-sm btn-danger" data-dismiss="modal">ยกเลิกการสมัคร</button>
                </div>

            </div>
            </div>
        </div>
        {{-- Register Modal  --}}
    @endsection

    @section('NewLink')

    @endsection

    @section('FncJs')
        <script>
            $("#ShowVerify").click(function() {
                $("#Verify_register").modal({
                    backdrop: "static"
                }, 'show');
            });

            function VerifyRegister() {
                var chk_type = document.getElementById("DataVerify").value;
                var selectData = document.getElementById("class_level_register_id").value;

                if (selectData == null || selectData == "") {
                    swal("ล้มเหลว!", "โปรดเลือกระดับการศึกษาที่จบ", "error");
                } else {
                    if (chk_type == "T") {
                        if (selectData != "02") {
                            swal("ล้มเหลว!", "ระดับการศึกษาที่จบยังไม่สามารถสมัครสาขานี้ได้ โปรดเลือกใหม่อีกครั้ง", "warning");
                        } else {
                            $("#Register_Modal").modal({
                                backdrop: "static"
                            }, 'show');
                        }
                    } else if(chk_type == "R") {
                        if (selectData != "01") {
                            swal("ล้มเหลว!", "ระดับการศึกษาที่จบยังไม่สามารถสมัครสาขานี้ได้ โปรดเลือกใหม่อีกครั้ง", "warning");
                        } else {
                            $("#Register_Modal").modal({
                                backdrop: "static"
                            }, 'show');
                        }
                    }
                }
            }

            function verifyApplication() {
                var application_formID = document.getElementById("ApplicationForm");
                var validate = {
                    "personnel":false,
                    "regist_info":false,
                    "education":false,
                    "contact":false
                };
            // Declare personnel
                var valid_personnel = {
                    "personnel_code" : application_formID.elements.namedItem("selectPersonnel").value
                };
            // Declare regist_info
                var valid_regist_info = {
                    "prefix_code":application_formID.elements.namedItem("prefix").value,
                    "f_name_th":application_formID.elements.namedItem("f_name_t").value,
                    "l_name_th":application_formID.elements.namedItem("l_name_t").value,
                    "id_card":application_formID.elements.namedItem("id_card").value,
                    "gender":application_formID.elements.namedItem("gender").value
                };
            // Declare education
                var valid_education = {
                    "class_level":application_formID.elements.namedItem("learning_level").value,
                    "school":application_formID.elements.namedItem("school").value,
                    "gpa":application_formID.elements.namedItem("gpa").value
                };
            // Declare contact
                var valid_contact = {
                    "line_id":application_formID.elements.namedItem("LineID").value,
                    "telephone":application_formID.elements.namedItem("telephone").value,
                    "mail":application_formID.elements.namedItem("Email").value
                };

            // Validate *personnel
                if (valid_personnel["personnel_code"] == "" || valid_personnel["personnel_code"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดเลือกเจ้าหน้าที่ที่แนะนำ!" , "error");
                } else {
                    validate["personnel"] = true;
                }
            // Validate *register info
                if (valid_regist_info["prefix_code"] == "" || valid_regist_info["prefix_code"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดเลือกคำนำหน้าชื่อ!" , "error");
                } else if (valid_regist_info["f_name_th"] == "" || valid_regist_info["f_name_th"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดระบุชื่อผู้สมัคร!" , "error");
                } else if (valid_regist_info["l_name_th"] == "" || valid_regist_info["l_name_th"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดระบุนามสกุลผู้สมัคร!" , "error");
                } else if (valid_regist_info["id_card"] == "" || valid_regist_info["id_card"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดระบุเลขบัตรประจำตัวประชาชน!" , "error");
                } else if (valid_regist_info["gender"] == "" || valid_regist_info["gender"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดเลือกเพศ!" , "error");
                } else {
                    validate["regist_info"] = true;
                }
            // Validate *education
                if (valid_education["class_level"] == "" || valid_education["class_level"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดเลือกระดับการศึกษาที่จบ!" , "error");
                } else if (valid_education["school"] == "" || valid_education["school"] == null) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดเลือกสถานศึกษาที่จบ!" , "error");
                } else if (
                    valid_education["gpa"] == "" ||
                    valid_education["gpa"] == null ||
                    valid_education["gpa"] > 4 ||
                    valid_education["gpa"] < 0
                ) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดเลือกสถานศึกษาที่จบ!" , "error");
                } else {
                    validate["education"] = true;
                }
            // Validate *contact
                if (
                    (valid_contact["line_id"] == "" || valid_contact["line_id"] == null) &&
                    (valid_contact["mail"] == "" || valid_contact["mail"] == null) &&
                    (valid_contact["telephone"] == "" || valid_contact["telephone"] == null)
                ) {
                    swal("ไม่สามารถบันทึกข้อมูลได้" , "โปรดระบุช่องทางการติดต่อ!" , "error");
                } else {
                    validate["contact"] = true;
                }
            // Validate flag
                if (
                    validate["personnel"] == true &&
                    validate["regist_info"] == true &&
                    validate["education"] == true &&
                    validate["contact"] == true
                ) {
                    document.getElementById("ApplicationForm").submit();
                }
            }
        </script>
    @endsection
@endforeach
