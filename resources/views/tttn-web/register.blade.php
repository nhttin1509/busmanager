<div class="modal fade" id="register">
    <div class="modal-dialog" style="width:500px;">
        <div class="modal-content">
            <form>
                <div class="modal-header"  style="background: rgb(0,64,87); color: #FFF; text-align: center;">
                    <h4 class="modal-title">Thông tin Đăng ký</h4>
                </div>
                <!-- hiện lổi -->
                <div class="hienloi">
                    <div class="loi"></div>
                    <div class="loi2"></div>
                    <div class="loi3"></div>
                    <div class="loi4"></div>
                    <div class="loi5"></div>
                    <div class="loi6"></div>
                    <div class="dangkytc"></div>
                </div>
                <div class="modal-body">
                    <!-- form đăng ký -->
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                        <input type="text" class="form-control dienthoai" name="phone" placeholder="Số điện thoại">
                    </div>
                    <br>
                    <!-- Họ và tên  -->
                     <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control txtname" name="name"  placeholder="Họ và tên">
                     </div>
                     <br>
                     <!-- Ngày sinh  -->
                     <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-hourglass"></span></span>
                        <input type="date" class="form-control txtngaysinh" value="1994-02-20" name="ngaysinh">
                     </div>
                     <br>
                     <!--  Giới tính-->
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-transgender-alt"></i></span>
                         <label class="checkbox-inline">
                                <input type="radio" name="txtgioitinh2" value="1" checked>Nam
                        </label>
                        <label class="checkbox-inline">
                        <input type="radio" name="txtgioitinh2" value="2">Nữ
                        </label>
                    </div>
                     <br>
                     <!--  mật khẩu -->
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control matkhau" name="password" placeholder="Mật khẩu">
                    </div>
                    <br>
                    <!-- repassword -->
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-repeat"></i></span>
                        <input type="password" class="form-control rematkhau" name="repassword" placeholder="Xác nhận Mật khẩu">
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="input-group">
                        <!-- button đăng ký -->
                        <span class="input-group-addon"><i class="glyphicon glyphicon-log-in"></i></span>
                        <input type="button" style="color: #FFF;background: rgb(0,64,87);"  class="form-control form-control-success dangky" name="register" value="Đăng Ký">
                    </div>
                    <br>
                    <!-- đã có tài khoản -->
                    <a href="javascript:void(0)" data-dismiss="modal" onclick="$(body).css('padding-right','0');" data-toggle="modal" data-target="#login" class="btn btn-link">Đã Có Tài Khoản?</a>
                    <!-- button close  -->
                    <button type="button" class="btn btn-danger dongdangky" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- @section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".dangky").click(function(){
                alert("tedsadsadts");
            });
        });
    </script>
@endsection -->

