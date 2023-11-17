@extends('Admin.layout')

@section('title', 'Procedimientos')

@push('css')
   
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
                    <th style="width:15%">Iso</th>
                    <th style="width:30%">Código</th>
                    <th style="width:35%">Nombre</th>
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
                                    <label for="">Iso:</label>
                                    <input type="text" name="iso" id="iso" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Código:</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Formato:</label>
                                    <input type="text" name="formato" id="formato" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Layout:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="layout" name="layout">
                                        <label class="custom-file-label" for="customFile">Subir archivo</label>
                                    </div>
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Entregable:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="entregable" name="entregable">
                                        <label class="custom-file-label" for="customFile">Subir archivo</label>
                                    </div>
                                    <small id="nombre_err" class="text-warning">Help text</small>
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
            url: "{{route('procedimientos.obtener')}}",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                limpiar();
                $('#codigo').val(res.codigo);
                $('#iso').val(res.iso);
                $('#formato').val(res.formato);
                $('#id').val(res.id)
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
                            <td>${val.iso}</td>
                            <td>${val.codigo}</td>
                            <td>${val.formato}</td>
                            <td>
                                <button class="btn btn-icon btn-warning" onclick="ver(${val.id})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-icon btn-danger" onclick="eliminar(${val.id})"><i class="fa fa-trash"></i></button>   
                                <button class="btn btn-icon btn-secondary" onclick="download('${val.layout}')"><i class="fa fa-file-pdf"></i></button> 
                                <button class="btn btn-icon btn-success" onclick="descarga(${val.id})"><i class="fa fa-file-pdf"></i></button>                
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
        $('#id').val(null)           
        LimpiarValidaciones();     
    }

    function reiniciar(){        
        limpiar();
        listar();
    }

    function LimpiarValidaciones(){
        $('small').text('')
    }  

    function setError(ctrlname, msj){
        $('#'+ctrlname+'_err').text(msj)
    }

    function download(archivo){
        $.ajax({
            type: "POST",
            url: "{{route('procedimientos.download')}}",
            data: {nombreArchivo:archivo},
            dataType: "json",
            success: function (res) {
                   console.log(res) 
            }
        });
    }

    



</script>
@endpush
