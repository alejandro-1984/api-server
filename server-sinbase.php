<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, X-USERNAME, Origin, X-Requested-With, Content-Type, Accept");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Content-Type: application/json' );


function get_info(){
	$version = "0.0";
	$last_modified = date('Y-m-d H:i:s', filemtime(__FILE__));

	return json_encode(
				[
					'version' => $version,
					'modificado' => $last_modified,

				]
			);
}



$URL = "https://demos.jusmisiones.gov.ar/leu/rest/";


$allowedResourceTypes = [
	'help',
	'dependencias',
];

$resourceType = $_GET['resource_type'];

//comprueba si esta dentro de recurso, sino devuelve error
if ( !in_array( $resourceType, $allowedResourceTypes ) ) {
	header( 'Status-Code: 400' );
	echo json_encode(
		[
			'error' => "Tipo de recurso no registrado",
		]
	);
	

}

//si el recurso viene con un id
$resourceId = array_key_exists('resource_id', $_GET ) ? $_GET['resource_id'] : '';


$resourceType = pg_escape_string($resourceType);
$resourceId = pg_escape_string($resourceId);

//guarda el metodo
$method = $_SERVER['REQUEST_METHOD'];


//switch de metodos
switch ( strtoupper( $method ) ) {
	case 'GET':

		if ( "help" == $resourceType ) {
			echo get_info();
		}elseif ( "dependencias" == $resourceType ){
			echo get_multiuso($resourceType, $resourceId);
		}else{
			header( 'Status-Code: 404' );

				echo json_encode(
					[
						'error' => 'datos: '.$resourceType.' - '.$resourceId.' ... not found :(',
					]
				);
		}

/*
		
				
		

*/
		die;
		
		break;
	// los otros metodos fueron borrados porel momento
	default:
		header( 'Status-Code: 404' );

		echo json_encode(
			[
				'error' => $method.' not yet implemented :(',
			]
		);

		break;
}


//
//
// funciones
//
//

function get_multiuso($resourceType, $resourceId = ''){
	if($resourceType == "help"){
		return '';
	}
	$curl = curl_init();
	if($resourceId){
		$resourceType = $resourceType.'/'.$resourceId;
	}


	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://demos.jusmisiones.gov.ar/leu/rest/".$resourceType,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "Authorization: Basic Y2ZiYWVuYTpQSiMxMTFxcXE="
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;

}