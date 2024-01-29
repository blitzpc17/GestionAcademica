@extends('Admin.layout')

@section('title', 'Procedimientos')

@push('css')
<link rel="stylesheet" href="{{asset('Admin/dist/css/bootstrap-datepicker.min.css')}}">

@endpush


@section('bread')
    <li class="breadcrumb-item"><a href="#">ITTehuacán</a></li>
    <li class="breadcrumb-item active">Procedimientos</li>
    <li class="breadcrumb-item active">Captura y Consulta</li>
@endsection

@section('nombreSeccion', 'Procedimientos')

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
                    <th style="width:10%">Iso</th>
                    <th style="width:10%">Código</th>
                    <th style="width:30%">Nombre</th>
                    <th style="width:15%">Destino</th>
                    <th style="width:15%">Fecha Limite Visualización</th>
                    <th style="width:15%">Acciones</th>
                  </tr>
                </thead>
                <tbody></tbody>

              </table>
            </div><!-- table-wrapper -->
          </div><!-- card-body -->
        </div><!-- card -->



        <!-- modal -->

        <!-- Modal procedimiento -->
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
                                    <label for="">Iso:</label>
                                    <input type="text" name="iso" id="iso" class="form-control" placeholder="">
                                    <small id="iso_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Código:</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" placeholder="">
                                    <small id="codigo_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Formato:</label>
                                    <input type="text" name="formato" id="formato" class="form-control" placeholder="">
                                    <small id="formato_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="roles">Destino:</label>
                                    <select name="roles" id="roles" class="form-control"></select>
                                    <small id="roles_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="fecha">Fecha limite visualización</label>
                                    <input type="text" id="fecha" name="fecha" class="form-control">
                                    <small id="fecha_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Layout:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="layout" name="layout">
                                        <label class="custom-file-label" for="customFile">Subir archivo</label>
                                    </div>
                                    <small id="layout_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Entregable:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="entregable" name="entregable">
                                        <label class="custom-file-label" for="customFile">Subir archivo</label>
                                    </div>
                                    <small id="entregable_err" class="text-warning">Help text</small>
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

        <!-- Modal envio -->
          <!-- Modal -->
          <div class="modal fade" id="md-envio" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <br>
                            <br>
                        <table id="tb-envio" class="table" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <br>
                        <br>
                            

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="RealizarEnvio(procedimientoSeleccionadoId, rolProcedimientoId)" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </div>
        </div>



@endsection


@push('js')
<script src="{{asset('Admin/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('Admin/dist/js/moment.js')}}"></script>
<script>
     let tabla = null;
     let tablaEnvio = null;

     let dataEnvio = null;

     let procedimientoSeleccionadoId;
     let rolProcedimientoId;

    $(document).ready(function () {
        console.log('Ready!')

        reiniciar();


        $('#frm-registro').on('submit', function(e){
            e.preventDefault();

            var formData = new FormData(this);

            save(formData);

        });


        var fechaActual = moment();
        
        // Formatear la fecha si es necesario (opcional)
        var fechaFormateada = fechaActual.format('dd/mm/yyyy');

        // Imprimir la fecha
        console.log("Fecha actual:", fechaFormateada);

        $.fn.datepicker.dates['es'] = {
            days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
            daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            today: "Hoy",
            clear: "Limpiar",
            format: "dd/mm/yyyy",
            titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
            weekStart: 1
        };

        $('#fecha').datepicker({
            language: 'es',
            format:'dd/mm/yyyy',
            autoclose: true,
            startDate: fechaFormateada,
            todayHighlight:true
        })



    });

    function nuevo(){
        limpiar()
        $('.modal-title').text('Nuevo registro')
        $('#md-registro').modal('toggle')
    }

    function ver(id){
        $.ajax({
            type: "get",
            url: "{{route('procedimientos.obtener')}}",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                limpiar();
                $('#codigo').val(res.Codigo);
                $('#iso').val(res.Iso);
                $('#formato').val(res.Formato);
                $('#id').val(res.ProcedimientoId)
                $('#fecha').val( moment(res.FechaVisualizacion).format('DD/MM/YYYY') )
                $('#roles').val(res.RolId)

                $('.modal-title').text('Modificar registro')
                $('#md-registro').modal('toggle')
            }
        });

    }

    function listar(){
        $.ajax({
            type: "get",
            url: "{{route('procedimientos.listar')}}",
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
                            <td>${val.Iso}</td>
                            <td>${val.Codigo}</td>
                            <td>${val.Formato}</td>
                            <td>${val.Rol}</td>
                            <td>${ moment(val.FechaVisualizacion).format('DD-MM-YYYY') }</td>
                            <td>
                                <button class="btn btn-icon btn-warning" onclick="ver(${val.ProcedimientoId})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-icon btn-danger" onclick="eliminar(${val.ProcedimientoId})"><i class="fa fa-trash"></i></button>   
                                <button class="btn btn-icon btn-primary" onclick="VerPreliminarEnvio(${val.RolId}, ${val.ProcedimientoId}, '${val.Iso}')"><i class="fa fa-location-arrow"></i></button>
                                <a class="btn btn-icon btn-secondary" href="{{route('procedimientos.download')}}?id=${val.ProcedimientoId}&tipo=l" ><i class="fa fa-file-pdf"></i></a>
                                <a class="btn btn-icon btn-success" href="{{route('procedimientos.download')}}?id=${val.ProcedimientoId}&tipo=e" ><i class="fa fa-file-pdf"></i></a>            
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
                url: "{{route('procedimientos.save')}}",
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
                url: "{{route('procedimientos.del')}}",
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
        $('#iso').val(null)
        $('#codigo').val(null)
        $('#formato').val(null)
        $('#layout').val(null)
        $('#entregable').val(null)
        $('#id').val(null)   
        $('#roles').val(-1)
        $('#fecha').val(null)        
        LimpiarValidaciones();     
    }

    function reiniciar(){        
        limpiar();
        listar();
        ListarRoles();
    }

    function LimpiarValidaciones(){
        $('small').text('')
    }  

    function setError(ctrlname, msj){
        $('#'+ctrlname+'_err').text(msj)
    }

    function ListarRoles(){
        $.ajax({
            method: "GET",
            url: "{{route('roles.listar')}}",
            data: "data",
            dataType: "json",
            success: function (res) {
                FillSelect('roles', res)
            }
        });
    }

    function FillSelect(ctrl, data){
        $('#'+ctrl).empty();
        $('#'+ctrl).append(`<option value="-1">Seleccione una opción</option>`);
        $.each(data, function (i, val) { 
            $('#'+ctrl).append(`<option value="${val.id}">${val.nombre}</option>`);
        });
        
    }
    
    function VerPreliminarEnvio(rolId,procedimientoId,iso){
        $.ajax({
            type: "get",
            url: "{{route('procedimientos.pre.envio')}}",
            data: {"RolId": rolId},
            dataType: "json",
            success: function (res) {
                procedimientoSeleccionadoId = procedimientoId;
                rolProcedimientoId = rolId;   
                dataEnvio = {
                    "procedimientoId":procedimientoSeleccionadoId,
                    "rolId":rolProcedimientoId,
                    "usuariosId":res.map(obj=>obj.UsuarioId),
                    "op":"I"
                };             
                LlenarTablaEnvios('tb-envio', res);               
                $(".modal-title").text('Preparando envio de la Iso '+iso)
                $("#md-envio").modal('toggle')
            }
        });

    }    
    
    function RealizarEnvio(procedimientoId,rolId){
        $.ajax({
            method: "POST",
            url: "{{route('procedimientos.save.envio')}}",
            contentType:"application/json",
            data: JSON.stringify(dataEnvio),
          //  dataType: "json",
            success: function (res) {
                    let tipo = "";
                    let titulo = "";
                    let msj = "";
                    if(res.status === 200){
                        tipo = "success";
                        titulo = "¡Exito!"
                        msj = "Envio realizado correctamente."
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
                            reiniciar();
                        });
            }
        });
    }

    function VerInvolucradosProcedimiento(procedimientoId, rolId){
        $.ajax({
            type: "method",
            url: "url",
            data: "data",
            dataType: "dataType",
            success: function (response) {
                
            }
        });
    }

    function LlenarTablaEnvios(identificador, data ){
        if(tablaEnvio!= null ){
            tablaEnvio.destroy();
            $('#'+identificador+' tbody').empty();
        }

        $.each(data, function (i, val) { 
            const row = `<tr>
                           <td>${i+1}</td>
                           <td>${val.Nombre}</td>
                           <td>${val.Cargo}</td>
                        </tr>`
            $('#'+identificador+' tbody').append(row);
        });

        tablaEnvio = $('#'+identificador).DataTable({
            "language": {
                "url": "{{asset('Admin/json/DataTables-Spanish.json')}}"
            },
        });
    }
    



</script>
@endpush
