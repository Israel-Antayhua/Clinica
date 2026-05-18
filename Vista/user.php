<?php require_once '../php_action/db_connect.php' ?>
<?php require_once '../includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">

		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Inicio</a></li>		  
		  <li class="active">Usuarios</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Administrar Usuarios</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>

				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" data-toggle="modal" id="addUserModalBtn" data-target="#addUserModal"> 
						<i class="glyphicon glyphicon-plus-sign"></i> Agregar Usuario 
					</button>
				</div> <!-- /div-action -->				
				
				<table class="table" id="manageUserTable">
					<thead>
						<tr>
							<th style="width:10%;">Nombre de Usuario</th>
							<th style="width:15%;">Opciones</th>
						</tr>
					</thead>
				</table>
				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<!-- agregar usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

    	<form class="form-horizontal" id="submitUserForm" action="../php_action/DAO/createUser.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        	<span aria-hidden="true">&times;</span>
	        </button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Usuario</h4>
	      </div>

	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div id="add-user-messages"></div>

	        <div class="form-group">
	        	<label for="userName" class="col-sm-3 control-label">Nombre de Usuario: </label>
	        	<div class="col-sm-8">
			      <input type="text" class="form-control" id="userName" placeholder="Nombre de Usuario" name="userName" autocomplete="off">
			    </div>
	        </div> <!-- /form-group-->	    

	        <div class="form-group">
	        	<label for="upassword" class="col-sm-3 control-label">Contraseña: </label>
	        	<div class="col-sm-8">
			      <input type="password" class="form-control" id="upassword" placeholder="Contraseña" name="upassword" autocomplete="off">
			    </div>
	        </div> <!-- /form-group-->	        	 		

	        <div class="form-group">
	        	<label for="uemail" class="col-sm-3 control-label">Correo: </label>
	        	<div class="col-sm-8">
			      <input type="email" class="form-control" id="uemail" placeholder="Correo" name="uemail" autocomplete="off">
			    </div>
	        </div> <!-- /form-group-->	     	        
	      </div> <!-- /modal-body -->
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> 
	        	<i class="glyphicon glyphicon-remove-sign"></i> Cerrar
	        </button>
	        
	        <button type="submit" class="btn btn-primary" id="createUserBtn" data-loading-text="Loading..." autocomplete="off"> 
	        	<i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios
	        </button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 
<!-- /agregar usuario -->


<!-- editar usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	    	
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        	<span aria-hidden="true">&times;</span>
	        </button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Usuario</h4>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div class="div-loading">
	      		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				<span class="sr-only">Cargando...</span>
	      	</div>

	      	<div class="div-result">

				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active">
				    	<a href="#userInfo" aria-controls="profile" role="tab" data-toggle="tab">Información del Usuario</a>
				    </li>    
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">

				    <div role="tabpanel" class="tab-pane active" id="userInfo">
				    	<form class="form-horizontal" id="editUserForm" action="../php_action/DAO/editUser.php" method="POST">				    
				    	<br />

				    	<div id="edit-user-messages"></div>

				    	<div class="form-group">
			        		<label for="edituserName" class="col-sm-3 control-label">Nombre de Usuario: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="edituserName" placeholder="Nombre de Usuario" name="edituserName" autocomplete="off">
						    </div>
			        	</div> <!-- /form-group-->	    

				        <div class="form-group">
				        	<label for="editPassword" class="col-sm-3 control-label">Contraseña: </label>
						    <div class="col-sm-8">
						      <input type="password" class="form-control" id="editPassword" placeholder="Contraseña" name="editPassword" autocomplete="off">
						    </div>
				        </div> <!-- /form-group-->	        	 	        

			        <div class="modal-footer editUserFooter">
				        <button type="button" class="btn btn-default" data-dismiss="modal"> 
				        	<i class="glyphicon glyphicon-remove-sign"></i> Cerrar
				        </button>
				        
				        <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="Loading..."> 
				        	<i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios
				        </button>
				      </div> <!-- /modal-footer -->				     
			        </form> <!-- /.form -->				     	
				    </div>    
				  </div>

				</div>
	      	
	      </div> <!-- /modal-body -->
	      	    
    </div>
  </div>
</div>
<!-- /editar usuario -->

<!-- eliminar usuario -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeUserModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	<span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Eliminar Usuario</h4>
      </div>
      <div class="modal-body">

      	<div class="removeUserMessages"></div>

        <p>¿Realmente deseas eliminar este usuario?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> 
        	<i class="glyphicon glyphicon-remove-sign"></i> Cerrar
        </button>
        <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Loading..."> 
        	<i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios
        </button>
      </div>
    </div>
  </div>
</div>
<!-- /eliminar usuario -->


<script src="../custom/js/user.js"></script>

<?php require_once '../includes/footer.php'; ?>
