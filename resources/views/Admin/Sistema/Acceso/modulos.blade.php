@extends('Admin.layout')

@section('title', 'Modulos')

@push('css')
   
@endpush


@section('bread')
    <li class="breadcrumb-item"><a href="#">ITTehuacán</a></li>
    <li class="breadcrumb-item active">Sistema</li>
    <li class="breadcrumb-item active">Acceso</li>
    <li class="breadcrumb-item active">Módulos</li>
@endsection

@section('nombreSeccion', 'Módulos')

@section('contenido')

<div class="card bd-primary mg-t-20">
          <div class="card-header bg-primary tx-white">Captura y consulta</div>
          <div class="card-body pd-sm-30">  
            <div class="row">
                <div class="col-12 mb-4">
                    <button onClick="nuevo()" class="btn btn-primary"><i class="fa fa-plus mg-r-10"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Agregar registro</font></font></button>          
                </div>
            </div>
           
            <div class="table-wrapper">
              <table style="width:100%;" id="tb-registros" class="table display responsive nowrap">
                <thead>
                  <tr>
                    <th style="width:5%">#</th>
                    <th style="width:15%">Nombre</th>
                    <th style="width:30%">Ruta</th>
                    <th style="width:15%">Acciones</th>
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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            
                            <form id="frm-registro" enctype="multipart/form-data" >
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <label for="">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Modulos">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Ruta:</label>
                                    <input type="text" name="ruta" id="ruta" class="form-control" placeholder="Ej. /admin/Sistema/Modulos">
                                    <small id="ruta_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Icono:</label>
                                    <input type="text" name="icono" id="icono" class="form-control" placeholder="Ej. fa-screwdriver-wrench">
                                    <small id="icono_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Módulo padre:</label>
                                    <select name="modulo" id="modulo" class="form-control"></select>
                                    <small id="modulo_err" class="text-warning">Help text</small>
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

            save(formData);

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
            url: "{{route('modulos.obtener')}}",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                limpiar();
                $('#modulo').val(res.modulo_padre_id??-1).trigger('change');
                $('#ruta').val(res.ruta);
                $('#icono').val(res.icono);
                $('#nombre').val(res.nombre);
                $('#id').val(res.id)
                $('.modal-title').text('Modificar registro')
                $('#md-registro').modal('toggle')
            }
        });

    }

    function listar(){
        $.ajax({
            type: "get",
            url: "{{route('modulos.listar')}}",
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
            const row = `<tr>
                            <td>${i+1}</td>
                            <td>${val.nombre}</td>
                            <td>${val.ruta??""}</td>
                            <td>
                                <button class="btn btn-icon btn-warning" onclick="ver(${val.id})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-icon btn-danger" onclick="eliminar(${val.id})"><i class="fa fa-trash"></i></button>                                    
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
                url: "{{route('modulos.save')}}",
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

    function eliminar(id){
        let data = null;
        swal.fire({
            icon:"warning",
            title:"Advertencia",
            showDenyButton: true,
            text:"¿Desea eliminar el registro?",
            confirmButtonText:"Si",
            denyButtonText:"No"
        }).then((value)=>{
            if(!value.isConfirmed)return;
            var formData = new FormData();
            formData.append('id',id);
            $.ajax({
                method: "POST",
                url: "{{route('modulos.del')}}",
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
                        msj = "Registro eliminado correctamente."
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
        $('#modulo').val(-1)
        $('#nombre').val(null)
        $('#icono').val(null)
        $('#ruta').val(null)       
        $('#id').val(null)           
        LimpiarValidaciones();     
    }

    function reiniciar(){        
        limpiar();
        listar();
        listarModulos();
    }

    function LimpiarValidaciones(){
        $('small').text('')
    }  

    function setError(ctrlname, msj){
        $('#'+ctrlname+'_err').text(msj)
    }

    function listarModulos(){
        $.ajax({
            type: "GET",
            url: "{{route('modulos.select.listar')}}",
            dataType: "json",
            success: function (res) {
                $('#modulo').empty();
                $('#modulo').append('<option value="-1">Seleccionar módulo</option>');
                $.each(res, function (i, val) { 
                    $("#modulo").append(`<option value="${val.id}">${val.text}</option>}`);
                });

                $("#modulo").val(-1).trigger('change')
            }
        });
    }
  

    



</script>
@endpush
