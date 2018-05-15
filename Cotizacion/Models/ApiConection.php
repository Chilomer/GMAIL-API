<?php
    class apiConnection {
        private $url = '74.208.235.128:81';
        private $token;


        public function __construct(){}

        public function setAttribute ($atributo , $contenido){
            $this->$atributo = $contenido;
        }

        public function getAttribute ($atributo){
            return $this->$atributo;
        }
   
        public function methodPost($urlComplement = '/api/v1/', $model, $data = array()){
            if (isset($_SESSION['TOKEN'])){
                $service_url = $this->url . $urlComplement . $model;
                $curl = curl_init($service_url);
                curl_setopt($curl,CURLOPT_HTTPHEADER,array('Authorization: Bearer ' . $_SESSION['TOKEN'])); 
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $curl_response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($curl_response);
            
                return $response;
            } else {
                return 'Token not provided';
            }
        }

        public function methodGet($model, $urlComplement = '/api/v1/', $id = ''){
            if (isset($_SESSION['TOKEN'])){
                $id = $id !== '' ? '/' . $id: '';
                $service_url = $this->url . $urlComplement . $model . $id;
                // echo $service_url;
                $curl = curl_init($service_url);
                curl_setopt($curl,CURLOPT_HTTPHEADER,array('Authorization: Bearer ' . $_SESSION['TOKEN'])); 
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, false);
                $curl_response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($curl_response);
            
                return $response;
            } else {
                return 'Token not provided';
            }
        }
        
        public function methodPut($model, $id, $data = array(), $urlComplement = '/api/v1/'){
            if (isset($_SESSION['TOKEN'])){
                $service_url = $this->url . $urlComplement . $model . '/' . $id;
                $curl = curl_init($service_url);
                curl_setopt($curl,CURLOPT_HTTPHEADER,array('Authorization: Bearer ' . $_SESSION['TOKEN'])); 
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $curl_response = curl_exec($curl);
                // if (curl_error($curl)) {
                //     return curl_error($curl);
                // }
                curl_close($curl);
                $response = json_decode($curl_response);
            
                return $response;
            } else {
                return 'Token not provided';
            }
        }
        
        // LOGIN
     
        public function logIn($email, $password){
            $urlComplement = '/api/auth/';
            $model= 'login';
            $data = array(
                "email" => $email,
                "password" => $password,
                );
            $service_url = $this->url . $urlComplement . $model;
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($curl_response);
            
            $_SESSION['TOKEN'] = $response->access_token;
            $this->token = $response->access_token;
            return $response;
        } 

        public function logOut(){
            $urlComplement = '/api/auth/';
            $model= 'logout';
            
            $response = $this->methodPost($urlComplement, $model);
            unset($_SESSION['TOKEN']);
            $this->token = null;
            return $response;
        } 

        public function refreshToken(){
            $urlComplement = '/api/auth/';
            $model= 'refresh';
            
            $response = $this->methodPost($urlComplement, $model);
            $_SESSION['TOKEN'] = $response->access_token;
            $this->token = $response->access_token;
            return $response;
        } 

        public function meToken(){
            $urlComplement = '/api/auth/';
            $model= 'refresh';
            
            $response = $this->methodPost($urlComplement, $model);
            return $response;
        }
        
        //PERSONAL

        public function getPersonal(){
            $urlComplement = '/api/v1/';
            $model= 'personal';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getPersonalById($id){
            $urlComplement = '/api/v1/';
            $model= 'personal';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putPersonal($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'personal';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postPersonal($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'personal';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

         //Categoria

         public function getCategoria(){
            $urlComplement = '/api/v1/';
            $model= 'categoria';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getCategoriaById($id){
            $urlComplement = '/api/v1/';
            $model= 'categoria';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putCategoria($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'categoria';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postCategoria($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'categoria';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        //TiposProducto

        public function getTiposProducto(){
            $urlComplement = '/api/v1/';
            $model= 'tipos_producto';
            
            $response = $this->methodPost($urlComplement, $model);
            return $response;
        }

        public function getTiposProductoById($id){
            $urlComplement = '/api/v1/';
            $model= 'tipos_producto';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putTiposProducto($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'tipos_producto';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postTiposProducto($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'tipos_producto';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        //Departamentos

        public function getDepartamento(){
            $urlComplement = '/api/v1/';
            $model= 'departamentos';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getDepartamentoById($id){
            $urlComplement = '/api/v1/';
            $model= 'departamentos';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putDepartamento($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'departamentos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postDepartamento($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'departamentos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        //SubDepartamentos

        public function getSubDepartamento(){
            $urlComplement = '/api/v1/';
            $model= 'subdepartamentos';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getSubDepartamentoById($id){
            $urlComplement = '/api/v1/';
            $model= 'subdepartamentos';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putSubDepartamento($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'subdepartamentos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postSubDepartamento($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'subdepartamentos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        //Monedas

        public function getMoneda(){
            $urlComplement = '/api/v1/';
            $model= 'monedas';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getMonedaById($id){
            $urlComplement = '/api/v1/';
            $model= 'monedas';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putMoneda($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'monedas';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postMoneda($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'monedas';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        //Impuestos

        public function getImpuesto(){
            $urlComplement = '/api/v1/';
            $model= 'impuestos';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getImpuestoById($id){
            $urlComplement = '/api/v1/';
            $model= 'impuestos';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putImpuesto($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'impuestos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postImpuesto($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'impuestos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        //TiposDocumento

        public function getTiposDocumento(){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getTiposDocumentoById($id){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putTiposDocumento($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postTiposDocumento($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

        //TiposDocumentosVariables

        public function getTiposDocumentoVariables($idTiposDocumento){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos/' . $idTiposDocumento . '/variables';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getTiposDocumentoVariablesById($idTiposDocumento, $id){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos/' . $idTiposDocumento . '/variables';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putTiposDocumentoVariables($idTiposDocumento, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos/' . $idTiposDocumento . '/variables';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postTiposDocumentoVariables($idTiposDocumento, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'tipos_documentos/' . $idTiposDocumento . '/variables';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  
        

        //Articulos

        public function getArticulo($page = 1, $bySubString = ''){
            $urlComplement = '/api/v1/';
            $model= 'articulos?with=iva,ieps&page=' . $page . '&bySubstring=' . $bySubString;
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getArticuloById($id){
            $urlComplement = '/api/v1/';
            $model= 'articulos/' . $id . '?with=iva,ieps';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function putArticulo($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'articulos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postArticulo($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'articulos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  
        
        public function getArticuloSecundaria($id){
            $urlComplement = '/api/v1/';
            $model= 'articulos/' . $id . 'secundarias';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        //Clientes

         public function getCliente(){
            $urlComplement = '/api/v1/';
            $model= 'clientes';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getClienteById($id){
            $urlComplement = '/api/v1/';
            $model= 'clientes';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putCliente($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'clientes';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postCliente($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'clientes';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

        //ClientesSucursales

        public function getClienteSucursales($idCliente){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/sucursales';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getClienteSucursalesById($idCliente, $id){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/sucursales';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putClienteSucursales($idCliente, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/sucursales';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postClienteSucursales($idCliente, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/sucursales';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

         //ClientesPrecios

         public function getClientePrecios($idCliente){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/precios';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getClientePreciosById($idCliente, $id){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/precios';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putClientePrecios($idCliente, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/precios';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postClientePrecios($idCliente, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/precios';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  
        
        //ClientesCobranza

        public function getClienteCobranzaHistorial($idCliente){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/cobranza/historial';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getClienteCobranzaPendiente($idCliente){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/cobranza/pendiente';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getClienteCobranzaTotales($idCliente){
            $urlComplement = '/api/v1/';
            $model= 'clientes/' . $idCliente . '/cobranza/totales';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        //Pedidos

        public function getPedido(){
            $urlComplement = '/api/v1/';
            $model= 'pedidos';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getPedidoById($id){
            $urlComplement = '/api/v1/';
            $model= 'pedidos';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putPedido($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'pedidos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postPedido($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'pedidos';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

        //PedidosDetalles

        public function getPedidoDetalles($idPedido){
            $urlComplement = '/api/v1/';
            $model= 'pedidos/' . $idPedido . '/detalles';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getPedidoDetallesById($idPedido, $id){
            $urlComplement = '/api/v1/';
            $model= 'pedidos/' . $idPedido . '/detalles';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putPedidoDetalles($idPedido, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'pedidos/' . $idPedido . '/detalles';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postPedidoDetalles($idPedido, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'pedidos/' . $idPedido . '/detalles';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

        //Cotizaciones

        public function getCotizacion(){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getCotizacionById($id){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putCotizacion($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postCotizacion($id, $data){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

        //cotizacionesDetalles

        public function getCotizacionDetalles($idCotizacion){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones/' . $idCotizacion . '/detalles';
            
            $response = $this->methodGet($model, $urlComplement);
            return $response;
        }

        public function getCotizacionDetallesById($idCotizacion, $id){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones/' . $idCotizacion . '/detalles';
            
            $response = $this->methodGet($model, $urlComplement, $id);
            return $response;
        }

        public function putCotizacionDetalles($idCotizacion, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones/' . $idCotizacion . '/detalles';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }

        public function postCotizacionDetalles($idCotizacion, $id, $data){
            $urlComplement = '/api/v1/';
            $model= 'cotizaciones/' . $idPedido . '/detalles';
            
            $response = $this->methodPost($model, $id, $data, $urlComplement);
            return $response;
        }  

    }
?>