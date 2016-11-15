<?php
	/* ---------------------------------------------------------------------------------------
		la funcion validar usuario retorna un arreglo con la sigte estructura:
		array(
			valido: booleano;	//	true si es un usuario valido, false caso contrario
			legajo: 'xxxxxx'	//	devuelve el legajo si el usuario es valido, '' en caso contrario
			permisos[] ={}		//	arreglo contenedor de permisos, null si no tiene permisos o el usuario es invalido
			)
	   --------------------------------------------------------------------------------------- */

	// este arreglo contiene las IPs externas al hospital permitidas
	//					  ip SIEMPRE
	$arrExcepciones = ["190.97.54.250","190.211.223.199"];

	function satisface($asignados, $requeridos){
		$key = array_search($requeridos, $asignados);
		if ($key!==false){
				return true;
			}else{
				return false;
		}
	}
	
	function excepciones($ip){
		$esta = false;
		global $arrExcepciones;
		$cant = count($arrExcepciones);
		for ($i=0; $i<$cant; $i++){
			if($arrExcepciones[$i]==$ip){
				$esta = true;
			}
		}
		return $esta;
	}
	
	

	function esIpLocal(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$tuplas = explode(".", $ip);
		if (($ip=="127.0.0.1") || excepciones($ip) || (($tuplas[0]=="192")&&($tuplas[1]=="168"))){
				return true;
			}else{
				return false;
		}
	}

	function validar_usuario(){
		if (esIpLocal()){
				//$permisos[]=0;
				$permisos[]=null;
				if (!isset($_COOKIE['id_sessionIntra'])){
						return array( 
								'valido' =>  false,
								'legajo' => '',
								'permisos' => $permisos
							 );
					}else{
						// verifico que no hagan inyeccion de codigo con la cookie
						$cookie = $_COOKIE['id_sessionIntra'];
						if (!ctype_alnum($cookie)){
								// esta haciendo cosas raras con la cookie, lo pateo :)
								return array( 
									'valido' =>  false,
									'legajo' => '',
									'permisos' => $permisos
								 );
							}else{
								//formato valido, verifico que la cookie que presenta el usuario corresponda con la de la db
								include("conexion.php");
								$link = new mysqli($host,$user,$pass,$db);
								if (!($link -> connect_error)){
									$link ->set_charset("utf8");

									//consulto la db por la cookie
									$query = "SELECT id_session, legajo, id_privilegios FROM sessions_intranet WHERE id_session='".$cookie."' AND expiracion> str_to_date(concat(curdate(),' ',curtime()),'%Y-%m-%d %H:%i:%s')";
									$result = $link -> query($query);

									//si existe una entrada (debe ser unica)
									if ($result -> num_rows > 0){
											//guardo sus privilegios para buscar el arreglo asociado
											$row = $result -> fetch_assoc();
											$id_privilegios = $row['id_privilegios'];
											$legajo = $row['legajo'];

											//como existe, renuevo su sesion
//											$query = "UPDATE sessions SET expiracion = '".date("Y/m/d H:i:s", strtotime("+30 minutes"))."' WHERE id_session='".$cookie."'";
											$query = "UPDATE sessions_intranet SET expiracion = '".date("Y/m/d H:i:s", strtotime("+2 hour"))."' WHERE id_session='".$cookie."'";
											$link -> query($query);
											//le actualizo la cookie al usuario
											setcookie("id_sessionIntra", $cookie, time()+7200,"/");

											// armo el arreglo de privilegios asociado al usuario
/*
											$result = $link -> query("SELECT privilegio FROM `sessions_permisos_intranet` WHERE `id_grupo`= '$id_privilegios' 
																		AND privilegio NOT IN (SELECT privilegio FROM `sessions_intranet_restricciones` WHERE legajo = '$legajo')");
*/
											$result = $link -> query("(
																			SELECT privilegio FROM `sessions_permisos_intranet` WHERE `id_grupo`= '$id_privilegios' 
																			AND privilegio NOT IN (SELECT privilegio FROM `sessions_intranet_restricciones` WHERE legajo = '$legajo')
																		)UNION(
																			SELECT privilegio FROM `sessions_intranet_ampliaciones` WHERE legajo = '$legajo'
																		)");
											if ($result -> num_rows > 0){
												while ($row = $result -> fetch_assoc()){
													$permisos[]=$row['privilegio'];
												}
											}
											$tmp = array( 
													'valido' =>  true,
													'legajo' => $legajo,
													'permisos' => $permisos
													);
											return $tmp;
										}else{
											// si su cookie es invalida, no se corresponde con ninguna entrada reciente de la db.
											return array( 
														'valido' =>  false,
														'legajo' => '',
														'permisos' => $permisos
														 );
									}
								}
						}
				}
			}else{
				//estan ingresando de una IP remota
				return array( 
							'valido' =>  false,
							'legajo' => '',
							'permisos' => $permisos
							 );
		}
	}

	function tiene_permiso($LE_NUMLEGA,$grupo,$cod){
		//$permisos[]=0;
		$permisos[]=null;
		//formato valido, verifico que la cookie que presenta el usuario corresponda con la de la db
		include("conexion.php");
		$link = new mysqli($host,$user,$pass,$db);
		if (!($link -> connect_error)){
			$link ->set_charset("utf8");
			$result = $link -> query("(
											SELECT privilegio FROM `sessions_permisos_intranet` WHERE `id_grupo`= '$grupo' AND `privilegio`='$cod'
											AND (privilegio NOT IN (SELECT privilegio FROM `sessions_intranet_restricciones` WHERE legajo = '$LE_NUMLEGA'))

										)UNION(
											SELECT privilegio FROM `sessions_intranet_ampliaciones` WHERE legajo = '$LE_NUMLEGA'
												AND `privilegio`='$cod'
										)");
			return ($result -> num_rows > 0);
		}
	}
	
?>
