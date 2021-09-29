<div class="modal fade" id="login">
    <div class="modal-dialog" style="width: 500px;">
        <div class="modal-content">
            <form>
            <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
                <h4 class="modal-title">Thông tin Đăng nhập</h4>
            </div>
            <!-- Hiện lổi  -->
            <div class="hienloi">
                <div class="dnloi"></div>
                <div class="dnloi2"></div>
                <div class="dnloi3"></div>
            </div>
            <div class="modal-body">
                <!-- Form đăng nhập  -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                    <input type="text" class="form-control dndienthoai" name="phone" placeholder="Số điện thoại">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control dnmatkhau" name="password" placeholder="Mật khẩu">
                </div>
            </div>
            <div class="modal-footer">
                <div class="input-group">
                    <!--Button đăng nhập  -->
                    <span class="input-group-addon"><i class="glyphicon glyphicon-log-in"></i></span>
                    <input type="button" style="color: #FFF; background:rgb(0,64,87);  " class="form-control form-control-success dangnhap" name="login" value="Đăng Nhập">
                </div>
                <br>
                <a href="#" class="btn btn-link">Quên mật khẩu?</a>
                <!-- button close -->
                <button type="button" class="btn btn-danger dongdangnhap" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
