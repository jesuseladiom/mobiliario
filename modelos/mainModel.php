<?php 
    if ($peticionAjax) {
        require_once "../config/server.php";
    }
    else {
        require_once "./config/server.php";
    }

    class mainModel {
        
        /* ------------ funcion para conectar a la base de datos ------- */
        protected static function conectar() {
            $conexion= new PDO(SGBD, USER, PASS);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        } 

        /* ------ funcion para ejecutar consultas simples ------ */
        protected static function ejecutar_consulta_simple($consulta){
            $sql= self::conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        /* ------ funcion para encriptar ------
        es publica porque se va a llamar de varias vistas */
        public function encryption($string){
			$output=FALSE;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
		}

        /* ------ funcion para desencriptar -----
        es protected, porque debe ser privada solo desde el backend- */
		protected function decryption($string){
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		}

        /* ------ funcion para generar codigos aleatorios ----- */
        protected static function generar_codigo_aleatorio($letra, $longitud, $numero){
            // ejemplo:  P876-1
            for ($i=1; $i<=$longitud; $i++){
                $aleatorio= rand(0, 9);
                $letra= $letra.$aleatorio;
            }
            return $letra."-".$numero;

        }

        /* ------ funcion para limpiar cadenas ----- */
        protected static function limpiar_cadena($cadena) {
            $cadena= trim($cadena);
            // elimina las barras invertidas
            $cadena= stripcslashes($cadena); 
            // elimina el codigo script para evitar ataques maliciosos
            $cadena= str_ireplace("<script>","",$cadena);
            $cadena= str_ireplace("</script>","",$cadena);
            $cadena= str_ireplace("<script src","",$cadena);
            $cadena= str_ireplace("<script type","",$cadena);
            $cadena= str_ireplace("SELECT * FROM","",$cadena);
            $cadena= str_ireplace("DELETE FROM","",$cadena);
            $cadena= str_ireplace("INSERT INTO","",$cadena);
            $cadena= str_ireplace("DROP TABLE","",$cadena);
            $cadena= str_ireplace("DROP DATABASE","",$cadena);
            $cadena= str_ireplace("TRUNCATE TABLE","",$cadena);
            $cadena= str_ireplace("SHOW TABLES","",$cadena);
            $cadena= str_ireplace("SHOW DATABASES","",$cadena);
            $cadena= str_ireplace("--","",$cadena);
            $cadena= str_ireplace(">","",$cadena);
            $cadena= str_ireplace("<","",$cadena);
            $cadena= str_ireplace("[","",$cadena);
            $cadena= str_ireplace("]","",$cadena);
            $cadena= str_ireplace("^","",$cadena);
            $cadena= str_ireplace("?>","",$cadena);
            $cadena= str_ireplace("==","",$cadena);
            $cadena= str_ireplace(";","",$cadena);
            $cadena= str_ireplace("::","",$cadena);

            return $cadena;
        }

        /* ------ funcion para verificar cadenas de acuerdo a su patron regulares ----- */
        protected static function verificar_datos($filtro, $cadena){
            // valida la expresion regular
            if (preg_match("/^".$filtro."$/",$cadena)){
                return false;
            }else {
                return true;
            }
        }

        /* ------ funcion para verificar fechas ----- */
        protected static function verificar_fecha($fecha){
            $valores= explode("-", $fecha);
            if (count($valores)==3 && checkdate($valores[1], $valores[2], $valores[0])){
                return false;
            }else {
                return true;
            }
        }

        /* ------ funcion paginacion tablas ----- */
        protected static function paginador_tablas($pagina, $Npaginas, $url, $botones){
            $tabla= '    <nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
            
            // validar los botones de paginacion primero y anterior
            if($pagina==1) {
                // deshabilita pagina anterior porques es la primera
                $tabla.= '<li class="page-item disabled">
                <a class="page-link"><i class="fa-solid fa-angles-left"></i></a> </li>';

            }else {
                $tabla.= 
                    '<li class="page-item"><a class="page-link" href="'.$url.'1/">
                        <i class="fa-solid fa-angles-left"></i></a> </li>
                    <li class="page-item"><a class="page-link" href="'.$url.($pagina-1).
                        '/">Anterior</a> </li>
                    ';
            }

            // parte central de la paginacion
            $contador=0;
            for($i=$pagina; $i<=$Npaginas;$i++){
                // limite de botones que quiero por pagina
                if ($contador>=$botones) {
                    break;
                }
                if ($pagina==$i){
                    // sombrear el boton que indica la pagina actual
                    $tabla.='
                        <li class="page-item"><a class="page-link active" href="'.$url.$i.'/">'.$i.'</a> </li>
                    ';

                }else {
                    // se le quita la clase active para que no se vea sonbreado
                    $tabla.='
                        <li class="page-item"><a class="page-link" href="'.$url.$i.'/">'.$i.'</a> </li>
                    ';
                }
            }

            // validar los botones de paginacion ultimo y siguiente
            if($pagina==$Npaginas) {
                // deshabilita pagina anterior porques es la primera
                $tabla.= '<li class="page-item disabled">
                <a class="page-link"><i class="fa-solid fa-angles-right"></i></a> </li>';

            }else {
                $tabla.= 
                    '<li class="page-item"><a class="page-link" href="'.$url.($pagina+1).'/">Siguiente</a> </li>
                    <li class="page-item"><a class="page-link" href="'.$url.$Npaginas.'/"><i class="fa-solid fa-angles-right"></i></a> </li>
                        
                    ';
            }


            $tabla.='</ul></nav>';
            return $tabla;
        }
    }