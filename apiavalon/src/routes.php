<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of routes
 *
 * @author JPenagos
 */

use FastRoute\RouteParser\Std;
use Slim\Http\UploadedFile;

// get all todos
    /*$app->get('/config', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT * FROM configuracion ORDER BY co_base_url");
        $sth->execute();
        $todos = $sth->fetchObject();
        return $this->response->withJson($todos);
    });*/


    $app->post('/login',function($request,$response){
            $mensaje = new stdClass;

        try {
            $input =  $request->getParsedBody();
            if(empty($input['usuario']))
            {
                return $this->response->withJson('usuario no puede ser vacio',400);
            }elseif (empty($input['password'])){
                return $this->response->withJson('password no puede ser vacio',400);
            }
            $sth = $this->db->prepare("SELECT REPLACE(us_codemp,'\n','') as codigo,REPLACE(us_nombre,'\n','') as usuario, REPLACE(us_passwd,'\n','')  as password FROM usuario where us_codemp=:usuario and us_passwd=:pass");
            $sth->bindParam("usuario", $input['usuario']);
            $sth->bindParam("pass", $input['password']);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_OBJ);
            if(!$result)
            {
                $mensaje->success=false;
                $mensaje->response="";
                $mensaje->error="Datos incorrectos verifique";
                return $this->response->withJson($mensaje,400);
            }else {
                $mensaje->success=true;
                $mensaje->response=$result;
                $mensaje->error="";

                return  $this->response->write(json_encode($mensaje));
            }
            
            
        } catch (Exception $e) {
            $mensaje->success=false;
            $mensaje->response="";
            $mensaje->error=$e->getMessage();
            return $this->response->withJson($mensaje,400);
        }
    });

    $app->post('/uploadFicha', function ($request, $response){
        $directory='./Images/';
try {
        $uploadedFile = $request->getUploadedFiles();
        $input =  $request->getParsedBody();
        $ficha= json_decode($input['ficha'], true);

        $sth = $this->db->prepare("INSERT INTO `ficha`(`zona`, `sector`, `bosque`, `numarbol`, `fecreg`, `nomcomun`, `nomcient`, `familia`, `alturatotal`, `alturapr`, `cap`, `dap`, `diamecopa`, `estadofito`, `tallo`, `hojas`, `flores`, `frutos`, `estadofisico`, `latitud`, `longitud`, `visgeneral`, `visdetalle`, `usuario`, `fecha`, `hora`, `foto`, `observaciones`, `fotoBase64`) VALUES (:zona,:sector,:bosque,:numarbol,:fecreg,:nomcomun,:nomcient,:familia,:alturatotal,:alturapr,:cap,:dap,:diamecopa,:estadofito,:tallo,:hojas,:flores,:frutos,:estadofisico,:latitud,:longitud,:visgeneral,:visdetalle,:usuario,:fecha,:hora,:foto,:observaciones,:fotoBase64)");
        $sth->bindParam("zona", $ficha['zona']);
        $sth->bindParam("sector", $ficha['sector']);
        $sth->bindParam("bosque", $ficha['bosque']);
        $sth->bindParam("numarbol", $ficha['numarbol']);
        $sth->bindParam("fecreg", $ficha['fecreg']);
        $sth->bindParam("nomcomun", $ficha['nomcomun']);
        $sth->bindParam("nomcient", $ficha['nomcient']);
        $sth->bindParam("familia", $ficha['familia']);
        $sth->bindParam("alturatotal", $ficha['alturatotal']);
        $sth->bindParam("alturapr", $ficha['alturapr']);
        $sth->bindParam("cap", $ficha['cap']);
        $sth->bindParam("dap", $ficha['dap']);
        $sth->bindParam("diamecopa", $ficha['diamecopa']);
        $sth->bindParam("estadofito", $ficha['estadofito']);
        $sth->bindParam("tallo", $ficha['tallo']);
        $sth->bindParam("hojas", $ficha['hojas']);
        $sth->bindParam("flores", $ficha['flores']);
        $sth->bindParam("frutos", $ficha['frutos']);
        $sth->bindParam("estadofisico", $ficha['estadofisico']);
        $sth->bindParam("latitud", $ficha['latitud']);
        $sth->bindParam("longitud", $ficha['longitud']);
        $sth->bindParam("visgeneral", $ficha['visgeneral']);
        $sth->bindParam("visdetalle", $ficha['visgeneral']);
        $sth->bindParam("usuario", $ficha['usuario']);
        $sth->bindParam("fecha", $ficha['fecha']);
        $sth->bindParam("hora", $ficha['hora']);
        $sth->bindParam("foto", $ficha['foto']);
        $sth->bindParam("observaciones", $ficha['observaciones']);
        $sth->bindParam("fotoBase64", $ficha['foto']);
        $sth->execute();
        $id = $this->db->lastInsertId();
        $filename = moveUploadedFile($directory, $uploadedFile['Imagen'],$id);
        $tmp = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/Images/".$filename;
        $sth = $this->db->prepare("UPDATE `ficha` SET `fotoBase64`=:fotoBase64 WHERE `id`=:id");
        $sth->bindParam("fotoBase64", $tmp);
        $sth->bindParam("id", $id);
        $sth->execute();
        //$sth = $this->db->prepare("UPDATE `lectura` SET `lc_foto`='".$_SERVER['SERVER_NAME'].'/Images/'.$file.'.'.JPEG."' WHERE `lc_nromat`='".$file."' ");
        
} catch (Exception $e) {
    return $this->response->withJson($e->getMessage(),400);
}


return $this->response
->withJson('OK',200);

});
    


    $app->get('/parametros', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT * FROM residence ORDER BY residence_id");
        //$sth = $this->db->prepare("SELECT ob_descri as descripcion FROM novedad_visita ORDER BY ob_observa");
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });

    $app->post('/wptest', function ($request, $response, $args) {

        require_once 'class-phpass.php';
        $input =  $request->getParsedBody();
        $email= $input['email'];
        $pass= $input['pass'];

        $sth = $this->wp->prepare("SELECT ID,display_name ,user_pass FROM wp_users WHERE user_email=:email");
        $sth->bindParam("email", $email);
        //$sth = $this->db->prepare("SELECT ob_descri as descripcion FROM novedad_visita ORDER BY ob_observa");
        $sth->execute();
        $data = $sth->fetchObject();

        $wp_hasher = new PasswordHash( 8, true );
        $password_hashed = $data->user_pass;
        $plain_password = $pass;
        $resp = new stdClass();    
        if($wp_hasher->CheckPassword($plain_password, $password_hashed)) {
            $resp->success=true;
            $resp->data=json_encode($data);
            $resp->error='';

        }else {
            $resp->success=false;
            $resp->data=null;
            $resp->error='Ivalid Credentials';
        }
        return $this->response->withJson($resp,200);
    });
    
    $app->get('/config',function($request,$response)
    {
        
        try {
            $DatosIni = array();
            $data = array();
            $observaciones = $observaciones2= array();
            $sth = $this->db->prepare("SELECT ob_descri as observacion FROM observa ORDER BY ob_observa");
            $sth->execute();
            $observaciones = $sth->fetchAll();
            $data['observaciones'] = $observaciones;
            $sth = $this->db->prepare("SELECT ob_descri as observacion FROM observa ORDER BY ob_observa");
            $sth->execute();
            $observaciones2 = $sth->fetchAll(PDO::FETCH_OBJ);
            $data['observaciones2'] = $observaciones2;
            $sth = $this->db->prepare("SELECT ta_servicio as servicio,ta_uso as uso, ta_estrato as estrato,ta_cargo as cargo, ta_consumo as consumo,plena_cf,plena_consumo,porcentaje FROM tarifas");
            $sth->execute();
            $tarifas = $sth->fetchAll(PDO::FETCH_OBJ);
            $data['tarifas'] = $tarifas;
            $sth = $this->db->prepare("SELECT pa_descripcion as descripcion,pa_valor as valor FROM parametros WHERE pa_modulo='DUO' ");
            $sth->execute();
            $parametros = $sth->fetchAll(PDO::FETCH_OBJ);
            $data['parametros'] = $parametros;
            $DatosIni['DatosIni'] = $data; 
            return $this->response->withJson($data);
        } catch (Exception $e) {
            return $this->response->withJson($e->getMessage(),400);
        }
    });

    $app->get('/usuarios', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT us_codemp as codigo,us_nombre as usuario, us_passwd as password  FROM usuario ORDER BY us_codemp");
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });

    $app->get('/coordenadas', function ($request, $response, $args) {
        $locations= array();
        $sth = $this->db->prepare("SELECT SUBSTRING_INDEX(`lc_coordenadas`,'-',1) as lat, CONCAT('-', SUBSTRING_INDEX (SUBSTRING_INDEX (`lc_coordenadas`, '-', 2), '-', - 1)) AS lng FROM `lectura` ORDER BY  STR_TO_DATE( CONCAT(`lc_fecreg`,' ',`lc_horreg`),'%Y-%m-%d %h:%i:%s')");
        $sth->execute();
        $todos = $sth->fetchAll();

        foreach ($todos as $key => $value) {
            $coor=array(
                'location'=>
                array('lat'=> floatval($value['lat']),
                       'lng'=> floatval($value['lng'] )),
                'stopover'=>true
            );

            array_push($locations, $coor);
        }

        return $this->response->withJson($locations);
    });
 
    $app->get('/ruta', function ($request, $response, $args) {
        try {
                $input = $request->getQueryParams();
                $sth = $this->db->prepare("SELECT `rt_id` as consecutivo,`rt_nromat` as matricula,`rt_codigo` as codigo,`rt_nombre` as nombre,`rt_direcc` as direccion,`rt_uso` as uso,`rt_estrato` as estrato,`rt_medidor` as medidor,`rt_promedio` as promedio,`rt_lectura` as lectura,`rt_estmed` as estadomedidor,`rt_predio` as predio, 'https://api.antsoftdemo.com.co/Images/100004_2019-09-26.JPEG' as foto_ant FROM `ruta` WHERE `rt_ruta`=:codigo AND`rt_periodo`=:periodo ORDER BY  rt_id");
                $sth->bindParam("codigo", $input['codigo']);
                $sth->bindParam("periodo", $input['periodo']);
                $sth->execute();
                $todos = $sth->fetchAll();
                return $this->response->withJson($todos);
        } catch (Exception $e) {
            return $this->response->withJson($e->getMessage());
        }
        
        
    });
    // Retrieve todo with id 
    //  $app->get('/ruta/{id}', function ($request, $response, $args) {
    //     $sth = $this->db->prepare("SELECT `rt_id`,`rt_nromat`,`rt_codigo`,`rt_nombre`,`rt_direcc`,`rt_uso`,`rt_estrato`,`rt_medidor`,`rt_promedio`,`rt_lectura`,`rt_estmed` FROM `ruta` WHERE `rt_ruta`=:codigo AND`rt_periodo`=:periodo");
    //     $sth->bindParam("codigo", $args['codigo']);
    //     $sth->bindParam("periodo", $args['periodo']);
    //     $sth->execute();
    //     $todos = $sth->fetchObject();
    //     return $this->response->withJson($todos);
    //  });
 
 
    // // Search for todo with given search teram in their name
    // $app->get('/todos/search/[{query}]', function ($request, $response, $args) {
    //      $sth = $this->db->prepare("SELECT * FROM tasks WHERE UPPER(task) LIKE :query ORDER BY task");
    //     $query = "%".$args['query']."%";
    //     $sth->bindParam("query", $query);
    //     $sth->execute();
    //     $todos = $sth->fetchAll();
    //     return $this->response->withJson($todos);
    // });
 
    // $app->post('/foto', function ($request, $response){
    //     //use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
    //     try {
    //         $input = $request->getParsedBody();
    //         $target_dir ='./Images/'; // add the specific path to save the file
    //         $img = str_replace('data:image/png;base64,', '', ($input['foto']));
    //         $img = str_replace(' ', '+', $img);
    //         $decoded_file = base64_decode($img); // decode the file
    //         $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
    //         $extension = 'JPEG';//mime2ext($mime_type); // extract extension from mime type
    //         $file = uniqid() .'.'. $extension; // rename file as a unique name
    //         $file_dir = $target_dir . uniqid() .'.'. 'JPEG';
    // try {
    //     file_put_contents($file_dir, $decoded_file); // save
    //     //database_saving($file);
    //     header('Content-Type: application/json');
    //     echo json_encode("File Uploaded Successfully");
    // } catch (Exception $e) {
    //     header('Content-Type: application/json');
    //     echo json_encode($e->getMessage());
    // }

    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }

    // });

    // Add a new todo
    /*$app->post('/lecturas', function ($request, $response) {
        try {
            $input = $request->getParsedBody();
            $someArray = json_decode($input['lecturas'], true);
            foreach ($someArray as $key => $value) {
                    $target_dir ='./Images/'; // add the specific path to save the file
                    $img = str_replace('data:image/png;base64,', '', ($value['lc_foto']));
                    $img = str_replace(' ', '+', $img);
                    $decoded_file = base64_decode($img); // decode the file
                    $file_dir = $target_dir . $value['lc_nromat'] .'_'.date('Y-m-d') .'.'. 'JPEG';
                    $aux = str_replace('.','',$target_dir);
                    $file = $aux . $value['lc_nromat'] .'_'.date('Y-m-d') .'.'. 'JPEG';
                    try {
                        file_put_contents($file_dir, $decoded_file); // save
                        $sth = $this->db->prepare("SELECT ob_observa FROM observa WHERE ob_descri='".$value['lc_observ']."' ORDER BY ob_observa");
                        $sth->execute();
                        $observacion = $sth->fetchColumn();
                        $sth = $this->db->prepare("INSERT INTO `lectura` (`lc_nromat`, `lc_fecreg`, `lc_horreg`, `lc_lecant`, `lc_lecact`, `lc_observ`, `lc_usuario`, `lc_coordenadas`, `lc_foto`, `lc_nota`) VALUES ('". $value['lc_nromat']."', '".$value['lc_fecreg']."', '".$value['lc_horreg']."', '".$value['lc_lecant']."', '".$value['lc_lecact']."', '".$observacion."', '".$value['lc_usuario']."', '".$value['lc_coordenadas']."', '".$_SERVER['SERVER_NAME'].$file."', '".$value['lc_nota']."')");
                        $sth->execute();
                    } catch (Exception $e) {
                        $mensaje = $e->getMessage();
                        if(strpos( $mensaje,'Duplicate'))
                        {
                            return $this->response
                            ->withJson('OK');
                        }else{
                            return $this->response->withJson('NO');
                        }
                        
                    }
            }
        } catch (PDOException $th) {
            return $this->response
           ->withJson('NO');        }
        


        return $this->response
        ->withJson('OK');
    });*/


    $app->post('/postLectura', function ($request, $response) {
        $mensaje = new stdClass;
        try {
            $input =  json_encode($request->getParsedBody());
            $value= json_decode($input,true);
            $sth = $this->db->prepare("SELECT ob_observa FROM observa WHERE ob_descri='".$value['novedad']."' ORDER BY ob_observa");
            $sth->execute();
            $observacion = $sth->fetchColumn();
            $sth = $this->db->prepare("INSERT INTO `lectura` (`lc_nromat`, `lc_fecreg`, `lc_horreg`, `lc_lecant`, `lc_lecact`, `lc_observ`, `lc_usuario`, `lc_coordenadas`, `lc_foto`, `lc_nota`) VALUES ('". $value['matricula']."', '".$value['fecha']."', '".$value['hora']."', '".$value['lectura']."', '".$value['nuvlectura']."', '".$observacion."', '".$value['usuario']."', '".$value['coordenada']."','".$value['foto']."' , '".$value['nota']."')");
            $sth->execute();
        } catch (Exception $e) {
             
            $mensaje->success=false;
            $mensaje->response="";
            $mensaje->error=$e->getMessage();
            return $this->response->withJson($mensaje,400);
        }
        

        $mensaje->success=true;
        $mensaje->response='OK';
        $mensaje->error="";
        return $this->response
        ->withJson($mensaje);
    });
    
    $app->post('/lecturas', function ($request, $response) {
        try {
            $input = $request->getParsedBody();
            $someArray = json_decode($input['lecturas'], true);
            foreach ($someArray as $key => $value) {
                    try {
                        $sth = $this->db->prepare("SELECT ob_observa FROM observa WHERE ob_descri='".$value['lc_observ']."' ORDER BY ob_observa");
                        $sth->execute();
                        $observacion = $sth->fetchColumn();
                        $sth = $this->db->prepare("INSERT INTO `lectura` (`lc_nromat`, `lc_fecreg`, `lc_horreg`, `lc_lecant`, `lc_lecact`, `lc_observ`, `lc_usuario`, `lc_coordenadas`, `lc_foto`, `lc_nota`) VALUES ('". $value['lc_nromat']."', '".$value['lc_fecreg']."', '".$value['lc_horreg']."', '".$value['lc_lecant']."', '".$value['lc_lecact']."', '".$observacion."', '".$value['lc_usuario']."', '".$value['lc_coordenadas']."','".$value['lc_foto']."' , '".$value['lc_nota']."')");
                        $sth->execute();
                    } catch (Exception $e) {
                        $mensaje = $e->getMessage();
                        if(strpos( $mensaje,'Duplicate'))
                        {
                            continue;
                        }else{
                            continue;
                        }
                        
                    }
            }
        } catch (Exception $e) {
            return $this->response->withJson($e->getMessage(),400);       
        }
        


        return $this->response
        ->withJson('OK');
    });
    
    $app->post('/lecturasDuo', function ($request, $response) {
        try {
            $input = $request->getParsedBody();
            $someArray = json_decode($input['lecturas'], true);
            foreach ($someArray as $key => $value) {
                    try {
                        $sth = $this->db->prepare("SELECT ob_observa FROM observa WHERE ob_descri='".$value['lc_observ']."' ORDER BY ob_observa");
                        $sth->execute();
                        $observacion = $sth->fetchColumn();
                        $sth = $this->db->prepare("SELECT ob_observa FROM observa WHERE ob_descri='".$value['lc_observ2']."' ORDER BY ob_observa");
                        $sth->execute();
                        $observacion2 = $sth->fetchColumn();
                        $sth = $this->db->prepare("INSERT INTO `lectura_duo` (`lc_nromat`, `lc_fecreg`, `lc_horreg`, `lc_lecant`, `lc_lecact`, `lc_observ`, `lc_usuario`, `lc_coordenadas`, `lc_foto`, `lc_nota`, `lc_lecact2`, `lc_observ2`, `lc_nota2`, `lc_foto2`) VALUES ('". $value['lc_nromat']."', '".$value['lc_fecreg']."', '".$value['lc_horreg']."', '".$value['lc_lecant']."', '".$value['lc_lecact']."', '".$observacion."', '".$value['lc_usuario']."', '".$value['lc_coordenadas']."','".$value['lc_foto']."' , '".$value['lc_nota']."', '".$value['lc_lecact2']."', '".$observacion2."', '".$value['lc_nota2']."','".$value['lc_foto2']."')");
                        $sth->execute();
                    } catch (Exception $e) {
                        $mensaje = $e->getMessage();
                        if(strpos( $mensaje,'Duplicate'))
                        {
                            continue;
                        }else{
                            continue;
                        }
                        
                    }
            }
        } catch (Exception $e) {
            return $this->response->withJson($e->getMessage(),400);       
        }
        


        return $this->response
        ->withJson('OK');
    });


    $app->post('/uploadLectura', function ($request, $response){
        $directory='./Images/';
        try {
                $uploadedFile = $request->getUploadedFiles();
                $input = $request->getParsedBody();
                $lectura= json_decode($input['lectura'], true);
                $filename = moveUploadedFile($directory, $uploadedFile['Image'],$lectura['matricula'].'_'.date('Y-m-d') );
                $file = $lectura['matricula'] .'_'.date('Y-m-d') .'.'. 'JPEG';
                $sth = $this->db->prepare("SELECT ob_observa FROM observa WHERE ob_descri='".$lectura['observacion']."' ORDER BY ob_observa");
                $sth->execute();
                $observacion = $sth->fetchColumn();
                $sth = $this->db->prepare("INSERT INTO `lectura` (`lc_nromat`, `lc_fecreg`, `lc_horreg`, `lc_lecant`, `lc_lecact`, `lc_observ`, `lc_usuario`, `lc_coordenadas`, `lc_foto`, `lc_nota`) VALUES ('". $lectura['matricula']."', '".$lectura['fecha']."', '".$lectura['hora']."', '".$lectura['lectura']."', '".$lectura['nuvlectura']."', '".$observacion."', '".$lectura['usuario']."', '".$lectura['coordenada']."', '".$_SERVER['SERVER_NAME']."/Images/".$file."', '".$lectura['nota']."')");
                $sth->execute();
        } catch (Exception $e) {
            return $this->response->withJson($e->getMessage(),400);
        }
        

        return $this->response
        ->withJson('OK');

    });

    $app->post('/uploadLecturas', function ($request, $response){
        $directory='./Images/';
        try {
                $uploadedFile = $request->getUploadedFiles();
                $input = $request->getParsedBody();
                $size = count($uploadedFile['Imagen']);
                $lectura= json_decode($input['lecturas'], true);

                foreach ($lectura as $key => $value) {
                    $file= substr($value['matricula'],9) .'_'.date('Y-m-d');
                    //file_put_contents($directory, $image['foto']);
                    moveUploadedFile($directory, $uploadedFile['Imagen'][$size-1],$file);
                    $sth = $this->db->prepare("UPDATE `lectura` SET `lc_foto`='".$_SERVER['SERVER_NAME'].'/Images/'.$file.'.'.JPEG."' WHERE `lc_nromat`='".$file."' ");
                    $sth->execute();
                    $size = $size-1;
                }
                
        } catch (Exception $e) {
            return $this->response->withJson($e->getMessage(),400);
        }
        

        return $this->response
        ->withJson('OK');

    });
    
    

    $app->post('/uploadFotoLectura', function ($request, $response){
        try {
            $input = $request->getParsedBody();
            $someArray = json_decode($input['lecturas'], true);
            foreach ($someArray as $key => $value) {
                    $target_dir ='./Images/'; // add the specific path to save the file
                    $img = str_replace('data:image/png;base64,', '', ($value['lc_foto']));
                    $img = str_replace(' ', '+', $img);
                    $decoded_file = base64_decode($img); // decode the file
                    $file_dir = $target_dir . $value['lc_nromat'] .'_'.date('Y-m-d') .'.'. 'JPEG';
                    $aux = str_replace('.','',$target_dir);
                    $file = $aux . $value['lc_nromat'] .'_'.date('Y-m-d') .'.'. 'JPEG';
                    try {
                        file_put_contents($file_dir, $decoded_file); // save
                        $sth = $this->db->prepare("UPDATE `lectura` SET `lc_foto`='/Images/".$_SERVER['SERVER_NAME'].$file."' WHERE `lc_nromat`='".$value['lc_nromat']."' ");
                        $sth->execute();
                    } catch (Exception $e) {
                        $mensaje = $e->getMessage();
                        if(strpos( $mensaje,'Duplicate'))
                        {
                            return $this->response
                            ->withJson('OK');
                        }else{
                            return $this->response->withJson('NO');
                        }
                        
                    }
            }
        } catch (PDOException $th) {
            return $this->response
           ->withJson('NO');        }
        


        return $this->response
        ->withJson('OK');

    });

    

        
 
    // DELETE a todo with given id
    $app->delete('/todo/[{id}]', function ($request, $response, $args) {
        $sth = $this->db->prepare("DELETE FROM tasks WHERE id=:id");
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });
 
    // Update todo with given id
    $app->put('/todo/[{id}]', function ($request, $response, $args) {
        $input = $request->getParsedBody();
        $sql = "UPDATE tasks SET task=:task WHERE id=:id";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->bindParam("task", $input['task']);
        $sth->execute();
        $input['id'] = $args['id'];
        return $this->response->withJson($input);
    });

    function moveUploadedFile($directory, Slim\Http\UploadedFile $uploadedFile,$name)
    {
        $extension = strtoupper(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION));
        $filename = sprintf('%s.%0.8s', $name, $extension);
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    function mime2ext($mime){
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
        "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
        "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
        "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
        "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
        "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
        "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
        "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
        "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
        "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
        "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
        "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
        "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
        "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
        "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
        "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
        "pdf":["application\/pdf","application\/octet-stream"],
        "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
        "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
        "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
        "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
        "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
        "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
        "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
        "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
        "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
        "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
        "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
        "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
        "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
        "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
        "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
        "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
        "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
        "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
        "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
        "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
        "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
        "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
        "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
        "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
        "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
        "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
        "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);
        foreach ($all_mimes as $key => $value) {
            if(array_search($mime,$value) !== false) return $key;
        }
        return false;
    }