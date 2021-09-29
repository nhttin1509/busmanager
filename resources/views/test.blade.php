<form action="{{route('login')}}" method="post">
    @csrf
    <input type="text" class="form-control" name="username" placeholder="Tên đăng nhập">
    <input type="password" class="form-control" name="password" placeholder="Mật khẩu">
    <input type="submit" class="form-control" name="login" value="Đăng Nhập">
</form>
