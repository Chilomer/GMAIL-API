
<?php include('template.php');
	require_once "Models/ApiConection.php";
	$connection = new apiConnection();
	$login = $connection->logIn('webapi@totall.com', 'Totall1');

	if(isset($_GET['page']) || isset($_GET['Articulofilter'])){
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$filter = isset( $_GET['filterInp']) ? $_GET['filterInp'] : '';
		$articulosConnection = $connection->getArticulo($page, $filter);
	} else {
		$articulosConnection = $connection->getArticulo(1);
	}

	if(isset($_GET['setArticulo'])){
		$articulo = $connection->getArticuloById($_GET['setArticulo']);
		$secundaria = $connection->getArticuloSecundaria($_GET['setArticulo']);
	}
		
	// echo json_encode($articulo->data);
	$articulos = isset($articulosConnection->data) ? $articulosConnection->data : array();
	$pagination = $articulosConnection->meta;
	// echo json_encode($articulo);
?>
	<div class="row">
		<div class="col-9">
			<div>
			<form>
				<div class="row">
					<div class="col-3">
						<div class="form-group">
							<label for="exampleFormControlInput1">Clave del producto</label>
							<!-- <input type="text" class="form-control" id="clave" placeholder="Clave"> -->
							<div class="input-group mb-3">
								<input type="text" 
									class="form-control" 
									placeholder="Clave" 
									aria-label="Clave" 
									aria-describedby="basic-addon2" 
									value="<?php print isset($articulo) ? $articulo->clave : '' ?>">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" id="myModal" type="button" data-toggle="modal" data-target="#exampleModalCenter">
										<i class="material-icons">search</i> 
									</button>
								</div>
							</div>
						</div>
					</div>	
					<!-- <div class="col-1">			
						<button type="submit" style="margin-top:32px" class="btn btn-outline-primary set-margin-right" name="goOut" value="goOut">
							<i class="material-icons">search</i>                    
						</button>
					</div> -->
					<div class="col-9">			
						<div class="form-group">
							<label for="exampleFormControlInput1">Descripcion</label>
							<input type="text" class="form-control disabled" id="descripcion" value="<?php print isset($articulo) ? $articulo->descripcion : '' ?>" placeholder="Descripcion">
						</div>
					</div>	
				</div>
				<div class="row">
					<div class="col-3">
						<label for="exampleFormControlSelect1">Presentación</label>
						<select class="form-control" id="exampleFormControlSelect1">
							<option> <?php print isset($articulo) ? $articulo->presentacion1 : '' ?></option>
							<?php
								if(isset($articulo) && trim($articulo->presentacion2) !== '' && trim($articulo->presentacion2) !== null){
							?>
							<option><?php print $articulo->presentacion2 ?></option>
							<?php
								}
								if(isset($articulo) && trim($articulo->presentacion3) !== '' && trim($articulo->presentacion3) !== null){
							?>
							<option><?php print $articulo->presentacion3 ?></option>
								<?php } ?>
						</select>
					</div>	
					<div class="col-2">			
						<div class="form-group">
							<label for="exampleFormControlInput1">Cantidad</label>
							<input type="number" class="form-control" id="cantidad" placeholder="Cantidad" value="1">
						</div>
					</div>	
					<div class="col-2">			
						<div class="form-group">
							<label for="exampleFormControlInput1">Precio</label>
							<input type="number" class="form-control" id="precio" placeholder="Precio"
								value="<?php print isset($articulo) ? precio($articulo) : 0 ?>">
						</div>
					</div>
					<div class="col-2">			
						<div class="form-group">
							<label for="exampleFormControlInput1">Descuento</label>
							<input type="number" class="form-control" id="Descuento" placeholder="Descuento" value="0">
						</div>
					</div>
					<div class="col-1">			
						<button type="submit" style="margin-top:32px" class="btn btn-outline-secondary set-margin-right" name="goOut" value="goOut">
							<i class="material-icons">save</i>                    
						</button>
					</div>
				</div>
			</form>
			</div>
			<div>
				<table class="table table-striped">
					<thead>
						<tr>
						<th scope="col">Clave</th>
						<th scope="col">Cantidad</th>
						<th scope="col">Descripcion</th>
						<th scope="col">Precio</th>
						<th scope="col">Descuento</th>
						<th scope="col">Importe</th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan="6">No hay articulos</td>
					</tr>
						<!-- <tr>
							<td>0004</td>
							<td>2</td>
							<td>Articulo 1</td>
							<td>$100</td>
							<td>25%</td>
							<td>$75</td>
						</tr> -->

					</tbody>
				
				</table>
			</div>
		</div>
		<div class="col-3">
			<div class="row" style="margin-top:50px">
			
				<div class="col-5" style="text-align:end">
					<h3 for="exampleFormControlInput1">Subtotal:</h3> 
					<h3 for="exampleFormControlInput1">Descuento:</h3>
					<h3 for="exampleFormControlInput1">Impuesto:</h3> 
					<h2 for="exampleFormControlInput1">Total:</h2> 
				</div>

				<div class="col-5" style="text-align:end">
					<h3 for="exampleFormControlInput1">$ 2,000</h3>
					<h3 for="exampleFormControlInput1">$ 300</h3> 
					<h3 for="exampleFormControlInput1">$ 250</h3> 
					<h2 for="exampleFormControlInput1">$ 1,950</h2>
				</div>
				<div class="col-2"> </div>
			</div>
		</div>
	</div>


<!-- Modal -->
<div class="modal fade bd-example-modal-lg " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Articulos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      	</div>
      	<div class="modal-body">
		  	<form method="get" name="Articulofilter" role="form">
				<div class="form-group">
					<label for="exampleFormControlInput1">Clave del producto</label>
					<!-- <input type="text" class="form-control" id="clave" placeholder="Clave"> -->
					<div class="input-group mb-3">
						<input type="text" class="form-control" id="filterInp" name="filterInp" placeholder="Clave" aria-label="Clave" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" id="Articulofilter" name="Articulofilter" value="Articulofilter" type="submit">
								<i class="material-icons">search</i> 
							</button>
						</div>
					</div>
				</div>
			</form>
	  	<div class="list-group">
		  	<?php 
				foreach($articulos as $art){ 
			?>
			<a 
			  href="/index.php?setArticulo=<?php print $art->idinterno ?>" 
			  class="list-group-item list-group-item-action" 
			  data-toggle="tooltip" 
			  data-placement="top" 
			  title="Existencia: <?php print $art->existencia !== null ? $art->existencia : 0 ?>">
				<div class="row">
				<div class="col-2 sbtitle"><?php print $art->clave ?></div>
					<div class="col-8"><?php print $art->descripcion ?></div>					
					<div class="col-2">$ <?php print precio($art) ?> </div>
					
				</div>
			</a>
			<?php } ?>
		</div>
		<nav aria-label="Page navigation example" style="margin-top:10px">
			<ul class="pagination justify-content-center">
				<?php if(($pagination->current_page - 1) > 0) {?>
					<li class="page-item">
					<a class="page-link" href="/index.php?page=<?php print $pagination->current_page - 1 ?>" tabindex="-1">Previous</a>
					</li>
					<li class="page-item"><a class="page-link" href="/index.php?page=<?php print $pagination->current_page - 1 ?>"><?php print $pagination->current_page - 1 ?></a></li>
				<?php } ?>
				<li class="page-item active"><a class="page-link" href="#"><?php print $pagination->current_page ?></a></li>
				<?php if(($pagination->current_page) < $pagination->last_page) {?>
					<li class="page-item"><a class="page-link" href="/index.php?page=<?php print $pagination->current_page + 1 ?>"><?php print $pagination->current_page + 1 ?></a></li>
					<li class="page-item">
						<a class="page-link" href="/index.php?page=<?php print $pagination->current_page + 1 ?>">Next</a>
					</li>
				<?php } ?>
			</ul>
		</nav>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</div>

	<script
		src="https://code.jquery.com/jquery-3.3.1.min.js"
		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
		crossorigin="anonymous"></script>
	<script
		src="assets/bootstrap/js/bootstrap.min.js"
		></script>
<?php 
	if(isset($_GET['page']) || isset($_GET['Articulofilter'])){
?>
	<script type="text/javascript">
		$('#exampleModalCenter').modal('show');
		$('#exampleModalCenter').modal('toggle');
	</script>
<?php 
	}
?>
	</body>
</html>
<style>
	td, th{
		text-align:center;
	}
</style>
<?php 
	function precio($arti){
		$ieps = $arti->ieps ? ($arti->ieps->porcentaje / 100) + 1 : 1;
		$iva = $arti->iva ? ($arti->iva->porcentaje / 100) + 1 : 1;
		foreach($arti->precios as $precio){ 
			if(isset($cliente)){
				if($cliente->idlistaprecio === $precio->idlistaprecio){
					$precioIeps = number_format((float)$precio->precio1, 2, '.', '') * $ieps;
					$pr = number_format((float)$precioIeps, 2, '.', '')  * $iva; 
					return number_format((float)$pr, 2, '.', '');
				} else {
					if(trim($precio->descripcion) === 'MENUDEO'){
						$precioIeps = number_format((float)$precio->precio1, 2, '.', '') * $ieps;
						$pr = number_format((float)$precioIeps, 2, '.', '')  * $iva; 
						return number_format((float)$pr, 2, '.', '');
					}
				}
			} else {
				if(trim($precio->descripcion) === 'MENUDEO'){
					$precioIeps = number_format((float)$precio->precio1, 2, '.', '') * $ieps;
					$pr = number_format((float)$precioIeps, 2, '.', '')  * $iva; 
					return number_format((float)$pr, 2, '.', '');
				}
			}
		}
	};
						
?>