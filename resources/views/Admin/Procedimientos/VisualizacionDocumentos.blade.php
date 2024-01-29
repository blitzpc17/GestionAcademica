@extends('Admin.layout')

@section('title', 'Procedimientos')

@push('css')
<link rel="stylesheet" href="{{asset('Admin/dist/css/bootstrap-datepicker.min.css')}}">

@endpush


@section('bread')
    <li class="breadcrumb-item"><a href="#">ITTehuacán</a></li>
    <li class="breadcrumb-item active">Procedimientos</li>
    <li class="breadcrumb-item active">Seguimiento</li>
@endsection

@section('nombreSeccion', 'Procedimientos')

@section('contenido')

<div class="card bd-primary mg-t-20">
          <div class="card-header bg-primary tx-white">Segumiento</div>
          <div class="card-body pd-sm-30">  
           
            <div class="table-wrapper">
              <table style="width:100%;" id="tb-registros" class="table display responsive nowrap">
                <thead>
                  <tr>
                    <th style="width:5%">#</th>
                    <th style="width:10%">Iso</th>
                    <th style="width:10%">Código</th>
                    <th style="width:35%">Nombre</th>
                    <th style="width:10%">Fecha Limite Visualización</th>
                    <th style="width:10%">Estado</th>
                    <th style="width:10%">Acciones</th>
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
                                <input type="hidden" name="op" id="op" value="S">
                                <input type="hidden" name="EnvioId" id="EnvioId">
                                <input type="hidden" name="ProcedimientoId" id="ProcedimientoId">
                               
                                <div class="form-group">
                                    <label for="">Subir entregable:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="layout" name="layout">
                                        <label class="custom-file-label" for="customFile">Subir archivo</label>
                                    </div>
                                    <small id="layout_err" class="text-warning"></small>
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
<script src="{{asset('Admin/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('Admin/dist/js/moment.js')}}"></script>
<script>
    

    $(document).ready(function () {
        Inicializar();


        $('#frm-registro').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(this);
            Save(formData);
        })

    });

   function Inicializar(){
    limpiar();
    ListarDocumentosUsuario("{{$rol->id}}");
   }

   function limpiar(){
        $('#ProcedimientoId').val(null)
        $('#EnvioId').val(null)       
        $('#layout').val(null)
         
        LimpiarValidaciones();     
    }

    function LimpiarValidaciones(){
        $('small').text('')
    }  


   function ListarDocumentosUsuario(rolId){
    $.ajax({
        method: "GET",
        url: "{{route('procedimientos.listar.rol')}}",
        data: {usuarioRol:rolId},
        dataType: "json",
        success: function (res) {
            console.log(res)
            DibujarTabla(res)
        }
    });
   }

   function DibujarTabla(data){
        $('#tb-registros tbody').empty();
        $.each(data, function (i, val) {
            const row = `<tr>
                <td>${i+1}</td>
                <td>${val.Iso}</td>
                <td>${val.Codigo}</td>
                <td>${val.Formato}</td>
                <td>${moment(val.FechaVisualizacion).format("DD/MM/YYYY")}</td>
                <td>${val.Estado}</td>
                <td>
                    <a class="btn btn-icon btn-primary" href="{{route('procedimientos.download')}}?id=${val.ProcedimientoId}&tipo=l" ><i class="fas fa-download"></i></a>
                    <button class="btn btn-icon btn-secondary" onclick="Ver(${val.ProcedimientoId})"><i class="fas fa-upload"></i></button>
                </td>
            </tr>` 
             $('#tb-registros tbody').append(row);
        });
   }

   function Ver(procedimientoId){
    console.log(procedimientoId)
    $('.modal-title').text('Subir archivo')
        $.ajax({
            type: "get",
            url: "{{route('procedimientos.visualizar')}}",
            data: {
                ProcedimientoId:procedimientoId,
                UsuarioId:"{{$user->id}}"
            },
            dataType: "json",
            success: function (res) {
                console.log(res)
                SetData(res);
            }
        });
   }

   function SetData(data){        
        $('#ProcedimientoId').val(data.ProcedimientoId)
        $('#EnvioId').val(data.ProcedimientoEnvioId)
        $('#md-registro').modal('toggle')

   }

   function Save(data){

    $.ajax({
        method: "POST",
        url: "{{route('procedimientos.save.envio')}}",
        data: data,
        contentType: false,
        cache:false,
        processData: false,
        dataType: "json",
        success: function (res) {
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
                                Inicializar();
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
    



</script>
@endpush
