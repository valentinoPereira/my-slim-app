<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../classes/Brand.php';
require_once '../classes/FileUpload.php';

$app->get('/brands', function (Request $request, Response $response, array $args) {

    try {
        $brand = new Brand($this->db);
        $brands = $brand->getAllBrands();

        $resp = array(
            'apiVersion' => API_VERSION,
            'method' => 'GET',
            'data' => array(
                'kind' => 'brands',
                'items' => $brands
            )
        );

        $newResponse = $response->withJson($resp, 200);

    } catch (PDOException $e) {
        $resp = array(
            'apiVersion' => API_VERSION,
            'method' => 'GET',
            'error' => array(
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            )
        );
        $newResponse = $response->withJson($resp, 500);
    }

    return $newResponse;
});

$app->get('/brand/{id}', function (Request $request, Response $response, array $args) {
    $brand_id = $request->getAttribute('id');

    try {

        $brand = new Brand($this->db);
        $singleBrand = $brand->getBrand($brand_id);

        $resp = array(
            'apiVersion' => API_VERSION,
            'method' => 'GET',
            'data' => array(
                'kind' => 'brand',
                'fields' => 'id,organization_id,name,brand_logo,brand_profile_image,active,created_at'
            )
        );

        $resp['data'] = array_merge($resp['data'], $singleBrand);
        
        $newResponse = $response->withJson($resp, 200);

    } catch (PDOException $e) {
        // print_r($e); exit;
        $resp = array(
            'apiVersion' => API_VERSION,
            'method' => 'GET',
            'error' => array(
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            )
        );

        $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
        $newResponse = $response->withJson($resp, $code);
    }

    return $newResponse;
});




$app->post('/brand/{id}', function (Request $request, Response $response, array $args) {
    $brand_id = $args['id'];

    $directory = $this->get('upload_directory');

    $uploadedFiles = $request->getUploadedFiles();

        // handle single input with single file upload
    $uploadedFile = $uploadedFiles['file'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = moveUploadedFile($directory, $uploadedFile);
        $response->getBody()->write('uploaded ' . $filename);
    } else {
        $response->getBody()->write('Could not upload file');
    }

    return $response;
});


?>