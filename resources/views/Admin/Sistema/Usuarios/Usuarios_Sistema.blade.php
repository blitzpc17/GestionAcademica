@extends('Admin.layout')

@section('title', 'Usuarios')

@push('css')
    <style>
        .table button{
            width:32px;
            height:32px;
            border-radius:10px;
            cursor:pointer;
        }




    </style>
@endpush


@section('icon', 'icon ion-grid')
@section('title-padre', 'Sistema')
@section('title-hijo', 'Usuarios')

@section('bread')
    <a class="breadcrumb-item" href="#">Sistema</a>
    <span class="breadcrumb-item active">Usuarios</span>
@endsection

@section('contenido')

<div class="card bd-primary mg-t-20">
          <div class="card-header bg-primary tx-white">Captura y consulta</div>
          <div class="card-body pd-sm-30">  
            <div class="col-sm-6 col-md-3">
                <button onClick="nuevo()" class="btn btn-primary btn-block mg-b-10 mb-4"><i class="fa fa-plus mg-r-10"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Agregar registro</font></font></button>          
            </div>
            <div class="table-wrapper">
              <table style="width:100%;" id="tb-registros" class="table display responsive nowrap">
                <thead>
                  <tr>
                    <th class="wd-5p">#</th>
                    <th class="wd-20p">Correo</th>
                    <th class="wd-20">Rol</th>
                    <th class="wd-20">Estado</th>
                    <th class="wd-15p">Acciones</th>
                  </tr>
                </thead>
                <tbody></tbody>

              </table>
            </div><!-- table-wrapper -->
          </div><!-- card-body -->
        </div><!-- card -->



        <!-- modal -->

        <!-- Modal -->

 <!-- LARGE MODAL -->
 <div id="modaldemo3" class="modal d-block pos-static">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
              <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Message Preview</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body pd-x-20">
                <h4 class=" lh-3 mg-b-20"><a href="" class="tx-inverse hover-primary">Why We Use Electoral College, Not Popular Vote</a></h4>
                <p class="mg-b-5">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. </p>
              </div><!-- modal-body -->
              <div class="modal-footer">
                <button type="button" class="btn btn-success pd-x-20">Save changes</button>
                <button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div><!-- modal-dialog -->
        </div><!-- modal -->

        <div class="modal fade" id="md-registro">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header pd-y-20 pd-x-25">
                                <h5 class="modal-title">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    <div class="modal-body pd-25">
                        <div class="container-fluid">
                            
                            <form id="frm-registro">
                                <input type="hidden" name="id" id="id">

                                <div class="form-group">
                                    <label for="">Nombre(s):</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">A. Paterno:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">A. Materno:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Domicilio:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Matricula:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Cargo:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Fecha ingreso:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="wd-200">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                                        <input type="text" class="form-control fc-datepicker" placeholder="MM/DD/YYYY">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="">Alias:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Correo:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Contraseña:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
                                    <small id="nombre_err" class="text-warning">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="">Rol:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="">
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
            url: "{{route('roles.obtener')}}",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                limpiar();
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
            url: "{{route('roles.listar')}}",
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
                            <td>
                                <button class="btn btn-icon btn-warning" onclick="ver(${val.id})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-icon btn-danger" onclick="eliminar(${val.id})"><i class="fa fa-trash"></i></button>                  
                            </td>
                        </tr>`
            $('#tb-registros tbody').append(row);
        });
        tabla = $('#tb-registros').DataTable({
            "language": {
                "url": "{{asset('Admin/js/json/DataTables-Spanish.json')}}"
            },
        });
    }

    function save(form){
        $.ajax({
                method: "POST",
                url: "{{route('roles.save')}}",
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
                url: "{{route('roles.del')}}",
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
        $('#nombre').val(null)
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

    



</script>
@endpush



