<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bienvenido</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('Admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('Admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('Admin/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Desarrollo Acádemico </b>ITT</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Iniciar sesión</p>
      <strong>{{ $errors->first('unauthorizate') }}</strong> 
      <form action="{{route('admin.auth')}}" method="post">
        @csrf
        <div class="form-group mb-1">
          <label for="email">Correo electrónico:</label>
          <input type="email" name="email" class="form-control">                  
        </div>
        @error('email')
            <small class="form-text text-warning">{{ $errors->first('email') }}</small>
        @enderror
        <div class="form-group mt-3 mb-4">
          <label for="password">Contraseña:</label>
          <input type="password" name="password" class="form-control" placeholder="Contraseña">
          @error('password')
            <small class="form-text text-warning">{{ $errors->first('password') }}</small>
          @enderror        
        </div>
      
        <div class="row">       
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Acceder</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('Admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('Admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('Admin/dist/js/adminlte.min.js')}}"></script>
</body>
</html>
