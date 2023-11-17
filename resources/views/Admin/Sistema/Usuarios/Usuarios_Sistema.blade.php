@extends('Admin.layout')

@section('title', 'Usuarios')

@push('css')
   
@endpush

@section('bread')
    <li class="breadcrumb-item"><a href="#">ITTehuacán</a></li>
    <li class="breadcrumb-item active">Sistema</li>
    <li class="breadcrumb-item active">Acceso</li>
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('nombreSeccion', 'Usuarios')

@section('contenido')

<div class="card bd-primary mg-t-20">
          <div class="card-header bg-primary tx-white">Captura y consulta</div>
          <div class="card-body pd-sm-30">  
            <div class="row">
                <div class="col-12 mb-4">
                    <button onClick="nuevo()" class="btn btn-primary">
                        <i class="fa fa-plus mg-r-10"></i> Nuevo Registro</button>          
                </div>
            </div>
           
            <div class="table-wrapper">
              <table style="width:100%;" id="tb-registros" class="table display responsive nowrap">
                <thead>
                  <tr>
                    <th class="wd-5p">#</th>
                    <th class="wd-25p">Alias</th>
                    <th class="wd-25p">Correo</th>
                    <th class="wd-20p">Rol</th>
                    <th class="wd-15p">Estado</th>
                    <th class="wd-10p">Acciones</th>
                  </tr>
                </thead>
                <tbody></tbody>

              </table>
            </div><!-- table-wrapper -->
          </div><!-- card-body -->
        </div><!-- card -->



        <!-- modal -->

        <!-- Modal -->
        <div class="modal fade" id="md-registro" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg
            " role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    <div class="modal-body">
                        <div class="container-fluid">                           
                            
                            <form id="frm-registro">
                                <input type="hidden" name="id" id="id">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Nombre(s):</label>
                                            <input type="text" name="nombres" id="nombres" class="form-control" placeholder="">
                                            <small id="nombres_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">A. Paterno:</label>
                                            <input type="text" name="apellidoPaterno" id="apellidoPaterno" class="form-control" placeholder="">
                                            <small id="apellidoPaterno_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">A. Materno:</label>
                                            <input type="text" name="apellidoMaterno" id="apellidoMaterno" class="form-control" placeholder="">
                                            <small id="apellidoMaterno_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Télefono:</label>
                                            <input type="text" name="telefono" id="telefono" class="form-control" placeholder="">
                                            <small id="telefono_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Matrícula:</label>
                                            <input type="text" name="matricula" id="matricula" class="form-control" placeholder="">
                                            <small id="matricula_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Domicilio:</label>
                                            <input type="text" name="domicilio" id="domicilio" class="form-control" placeholder="">
                                            <small id="domicilio_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Fecha ingreso:</label>
                                            <div class="input-group date" id="fechaIngresoAux" data-target-input="nearest">
                                                <input id="fechaIngreso" name="fechaIngreso" type="text" class="form-control datetimepicker-input" data-target="#fechaIngresoAux"/>
                                                <div class="input-group-append" data-target="#fechaIngresoAux" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            <small id="fechaIngreso_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Cargo:</label>
                                            <select id="cargosId" name="cargoId" class="form-control select2" style="width: 100%;">
                                                <option value="-1">Seleccione una opción</option>
                                            </select>
                                            <small id="cargoId_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Alias:</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="">
                                            <small id="name_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Correo electronico:</label>
                                            <input type="text" name="email" id="email" class="form-control" placeholder="">
                                            <small id="email_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Contraseña:</label>
                                            <input type="text" name="password" id="password" class="form-control" placeholder="">
                                            <small id="password_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Rol:</label>
                                            <select id="rolId" name="rolId" class="form-control select2" style="width: 100%;">
                                                <option value="-1">Seleccione una opción</option>
                                            </select>
                                            <small id="rolId_err" class="text-warning">Help text</small>
                                        </div>
                                    </div>

                                </div>
                                
                                

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



@endsection


@push('js')
<script>
     let tabla = null;

    $(document).ready(function () {
        console.log('Ready!')

        reiniciar();


        $('#frm-registro').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(this);
            const fechaIngreso = moment($('#fechaIngreso').val(), 'DD-MM-YYYY').format('YYYY-MM-DD');
            formData.append('fechaIngreso', fechaIngreso)
            save(formData);

        });

        $('#fechaIngresoAux').datetimepicker({
            format: 'DD-MM-YYYY'
        });


    });

    function nuevo(){
        console.log('nuevo')
        limpiar()
        $('.modal-title').text('Nuevo registro')
        $('#md-registro').modal('toggle')
    }

    function ver(id){
        $.ajax({
            type: "get",
            url: "{{route('usuarios.obtener')}}",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                limpiar();
                $('#nombres').val(res.recurso.nombres);
                $('#apellidoPaterno').val(res.recurso.apellidoPaterno);
                $('#apellidoMaterno').val(res.recurso.apellidoMaterno);
                $('#matricula').val(res.recurso.matricula);
                $('#telefono').val(res.recurso.telefono);
                $('#domicilio').val(res.recurso.domicilio);
                $('#fechaIngreso').val(res.recurso.fechaIngreso);
                $('#cargosId').val(res.recurso.cargoId).trigger('change');                
                $('#name').val(res.user.name);
                $('#email').val(res.user.email);
                $('#password').val(null);
                $('#rolId').val(res.user.rolesId).trigger('change');
                $('#id').val(res.user.id)
                $('.modal-title').text('Modificar registro')
                $('#md-registro').modal('toggle')
            }
        });

    }

    function listar(){
        $.ajax({
            type: "get",
            url: "{{route('usuarios.listar')}}",
            success: function (res) {
                dibujarData(res)
            }
        });
    }

    function dibujarData(data){
        if(tabla!= null ){
            tabla.destroy();
            $('#tb-registros tbody').empty();
        }
        $.each(data, function (i, val) { 
            console.log(val)
            const row = `<tr>
                            <td>${i+1}</td>
                            <td>${val.alias}</td>
                            <td>${val.correo}</td>
                            <td>${val.rol}</td>
                            <td>${val.estado}</td>
                            <td>                            
                                <button class="btn btn-icon btn-warning" onclick="ver(${val.id})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-icon btn-${val.baja == 1? 'primary':'danger'}" onclick="eliminar(${val.id}, ${val.baja==0? 1:0})"><i class="fa fa-${val.baja == 1? 'thumbs-up':'thumbs-down'}"></i></button>                  
                            </td>
                        </tr>`
            $('#tb-registros tbody').append(row);
        });
        tabla = $('#tb-registros').DataTable({
            "language": {
                "url": "{{asset('Admin/json/DataTables-Spanish.json')}}"
            },
        });
    }

    function save(form){
        $.ajax({
                method: "POST",
                url: "{{route('usuarios.save')}}",
                data: form,
                contentType: false,
                cache:false,
                processData: false,
                dataType: "json",
                success: function (res) {
                    console.log(res)
                    let tipo = "";
                    let titulo = "";
                    let msj = "";

                    if(res.status === 200){
                        tipo = "success";
                        titulo = "¡Exito!"
                        msj = "Registro guardado correctamente."
                    }else if(res.status === 500){
                        tipo = "error";
                        titulo = "¡Oh no!"
                        msj = "Ha ocurrido un error al tratar de realizar la operación. Intentalo nuevamente."
                    }else{
                        tipo = "warning";
                        titulo = "¡Advertencia!"
                        msj = "Verifica tu información e intentalo nuevamente."
                    }

                    swal.fire({
                            icon: tipo,
                            title: titulo,
                            text: msj,
                        }).then(()=>{
                            console.log("dentro: "+res.status)
                            if(res.status === 200){
                                $('#md-registro').modal('toggle')
                                reiniciar();
                            }else if(res.status === 422){
                                LimpiarValidaciones();
                                $.each(res.errors, function (i, val) { 
                                     setError(i, val);
                                });
                            }
                        });
                    
                }
            });

    }

    function eliminar(id, accion){
        let data = null;
        const txt = accion==0?"ACTIVAR":"DESACTIVAR";
        swal.fire({
            icon:"warning",
            title:"Advertencia",
            showDenyButton: true,
            text:"¿Desea "+txt+" el registro?",
            confirmButtonText:"Si",
            denyButtonText:"No"
        }).then((value)=>{
            if(!value.isConfirmed)return;
            var formData = new FormData();
            formData.append('id',id);
            formData.append('activo', accion);
            $.ajax({
                method: "POST",
                url: "{{route('usuarios.del')}}",
                data: formData,
                contentType: false,
                cache:false,
                processData: false,
                dataType: "json",
                success: function (res) {
                    console.log(res)
                    let tipo = "";
                    let titulo = "";
                    let msj = "";
                    if(res.status === 200){
                        tipo = "success";
                        titulo = "¡Exito!"
                        msj = "Registro "+(accion==0?"ACTIVADO":"DESACTIVADO")+" correctamente."
                    }else if(res.status === 500){
                        tipo = "error";
                        titulo = "¡Oh no!"
                        msj = "Ha ocurrido un error al tratar de realizar la operación. Intentalo nuevamente."
                    }else{
                        tipo = "warning";
                        titulo = "¡Advertencia!"
                        msj = "Verifica tu información e intentalo nuevamente."
                    }
                    swal.fire({
                            icon: tipo,
                            title: titulo,
                            text: msj,
                        }).then(()=>{
                            listar();
                        });
                }

            });
        })

        
    }

    function limpiar(){
        $('#nombres').val(null);
        $('#apellidoPaterno').val(null);
        $('#apellidoMaterno').val(null);
        $('#matricula').val(null);
        $('#telefono').val(null);
        $('#domicilio').val(null);
        $('#fechaIngreso').val(null);
        $('#cargosId').val(-1).trigger('change');        
        $('#name').val(null);
        $('#email').val(null);
        $('#password').val(null);
        $('#rolId').val(-1).trigger('change');
        $('#id').val(null)           
        LimpiarValidaciones();     
    }

    function reiniciar(){        
        limpiar();
        listar();
        listarCargos();
        listarRoles();
    }

    function LimpiarValidaciones(){
        $('small').text('')
    }  

    function setError(ctrlname, msj){
        $('#'+ctrlname+'_err').text(msj)
    }

    function listarRoles(){
        $.ajax({
            type: "GET",
            url: "{{route('roles.select.listar')}}",
            dataType: "json",
            success: function (res) {
                $.each(res, function (i, val) { 
                    $('#rolId').append(`<option value="${val.id}" >${val.text}</option>`);
                });
                $('#rolId').val(-1)
            }
        });     
    }

    function listarCargos(){
        $.ajax({
            type: "GET",
            url: "{{route('cargos.select.listar')}}",
            dataType: "json",
            success: function (res) {
                $.each(res, function (i, val) { 
                    $('#cargosId').append(`<option value="${val.id}" >${val.text}</option>`);
                });
                $('#cargosId').val(-1)
            }
        });
      
    }

    



</script>
@endpush
