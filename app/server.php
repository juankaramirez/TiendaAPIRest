<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Model\categoria;
use Model\producto;
use Model\canasta;

$href = "https://api.stormpath.com/v1/applications/41rhlQxQqw5hgmShRg1WPP";
$application = \Stormpath\Resource\Application::get($href);

$app = new Silex\Application();

$app->before(function ($request) {
	header('Access-Control-Allow-Origin: *');
	});

$services_json = json_decode(getenv("VCAP_SERVICES"), true);
$mysql_config = $services_json["mysql-5.1"][0]["credentials"];

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => $mysql_config["name"],
        'user' => $mysql_config["username"],
        'password' => $mysql_config["password"],
        'host' => $mysql_config["hostname"],
        'port' => $mysql_config["port"],
        'charset' => 'utf8'
    ),
));

$app->get('/', function () use($app) {
			
            return $app->json(array(
                        'result' => 'Bienvenido! Visita http://docs-tiendaapirest.aws.af.cm para empezar a usar la api'
            ));
        });

$app->get('api/categorias', function () use($app) {

            $categs = $GLOBALS['em']->getRepository('Model\categoria')->findAll();
            return $app->json(array(
                        'categs' => $categs
            ));
        });

$app->post('api/categorias', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                if ($request->get('nomCat') === null) {
                    return $app->json(array(
                                'result' => 'ERROR DATOS'
                    ));
                } else if ($GLOBALS['em']->getRepository('Model\categoria')->findOneBy(array('nombre' => $request->get('nomCat'))) !== null) {
                    return $app->json(array(
                                'result' => 'ERROR REGISTRO EXISTE'
                    ));
                } else {
                    $cat = new categoria();
                    $cat->nombre = $request->get('nomCat');
                    $GLOBALS['em']->persist($cat);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'Registro exitoso'
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'UNAUTHORIZED'
                ));
            }
        });

$app->put('api/categorias', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                if ($request->get('idCat') === null) {
                    return $app->json(array(
                                'result' => 'ERROR DATOS'
                    ));
                } else if ($GLOBALS['em']->getRepository('Model\categoria')->findOneBy(array('id' => $request->get('idCat'))) === null) {
                    return $app->json(array(
                                'result' => 'ERROR REGISTRO NO EXISTE'
                    ));
                } else {
                    $cat = new categoria();
                    $cat->id = $request->get('idCat');
                    $cat->nombre = $request->get('nomCat');
                    $GLOBALS['em']->merge($cat);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'Edicion exitosa'
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'UNAUTHORIZED'
                ));
            }
        });

$app->delete('api/categorias', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                if ($request->get('idCat') === null) {
                    return $app->json(array(
                                'result' => 'ERROR DATOS'
                    ));
                } else if ($GLOBALS['em']->getRepository('Model\categoria')->findOneBy(array('id' => $request->get('idCat'))) === null) {
                    return $app->json(array(
                                'result' => 'ERROR REGISTRO NO EXISTE'
                    ));
                } else {
                    $cat = new categoria();
                    $cat = $GLOBALS['em']->getRepository('Model\categoria')->findOneBy(array('id' => $request->get('idCat')));
                    $GLOBALS['em']->remove($cat);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'Eliminacion exitosa'
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'UNAUTHORIZED'
                ));
            }
        });

$app->get('api/categorias/{id}', function ($id) use($app) {
            $categ = $GLOBALS['em']->getRepository('Model\categoria')->findOneBy(array('id' => $id));
            if ($categ === null) {
                return $app->json(array(
                            'result' => 'REGISTRO NO EXISTE'
                ));
            } else {
                $categs = $GLOBALS['em']->getRepository('Model\categoria')->findAll();
                $prods = $GLOBALS['em']->getRepository('Model\producto')->findBy(array('catId' => $id));
                return $app->json(array(
                            'categ' => $categ->getNombre(),
                            'categs' => $categs,
                            'prods' => $prods,
                            'id' => $id
                ));
            }
        });

$app->get('api/productos', function () use($app) {
            $categs = $GLOBALS['em']->getRepository('Model\categoria')->findAll();
            $prods = $GLOBALS['em']->getRepository('Model\producto')->findAll();
            return $app->json(array(
                        'categs' => $categs,
                        'prods' => $prods
            ));
        });


$app->post('api/productos', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                if ($request->get('nombre') === null || $request->get('idcategoria') === null || $request->get('descripcion') === null || $request->get('codigo') === null || $request->get('precio') === null || $request->get('existencias') === null) {
                    return $app->json(array(
                                'result' => 'ERROR DATOS'
                    ));
                } else if ($GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $request->get('nomCat'))) !== null) {
                    return $app->json(array(
                                'result' => 'ERROR REGISTRO EXISTE'
                    ));
                } else {
                    $prod = new producto();
                    $prod->catId = $request->get('idcategoria');
                    $prod->nombre = $request->get('nombre');
                    $prod->descr = $request->get('descripcion');
                    $prod->codigo = $request->get('codigo');
                    $prod->precio = $request->get('precio');
                    $prod->existencia = $request->get('existencias');
                    $folder = "dbimages/";
                    move_uploaded_file($_FILES["img"]["tmp_name"], $folder . $_FILES["img"]["name"]);
                    $prod->url = $_FILES['img']['name'];
                    $GLOBALS['em']->persist($prod);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'Registro exitoso',
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'UNAUTHORIZED'
                ));
            }
        });

$app->put('api/productos', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                if ($request->get('idProd') === null) {
                    return $app->json(array(
                                'result' => 'ERROR DATOS'
                    ));
                } else if ($GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $request->get('idProd'))) === null) {
                    return $app->json(array(
                                'result' => 'ERROR REGISTRO NO EXISTE'
                    ));
                } else {
                    $prod = new producto();
                    $prod->id = $request->get('idProd');
                    $prod->nombre = $request->get('nombre');
                    $prod->catId = $request->get('idcategoria');
                    $prod->descr = $request->get('descripcion');
                    $prod->codigo = $request->get('codigo');
                    $prod->precio = $request->get('precio');
                    $prod->existencia = $request->get('existencias');
                    $folder = "dbimages/";
                    $prodtmp = $GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $request->get('idProd')));
                    if ($_FILES["img"]["tmp_name"] != "") {
                        move_uploaded_file($_FILES["img"]["tmp_name"], $folder . $_FILES["img"]["name"]);
                        $prod->url = $_FILES['img']['name'];
                    } else {
                        $prod->url = $prodtmp->url;
                    }
                    $GLOBALS['em']->merge($prod);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'Edicion exitosa',
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'UNAUTHORIZED'
                ));
            }
        });

$app->delete('api/productos', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                if ($request->get('idProd') === null) {
                    return $app->json(array(
                                'result' => 'ERROR DATOS'
                    ));
                } else if ($GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $request->get('idProd'))) === null) {
                    return $app->json(array(
                                'result' => 'ERROR REGISTRO NO EXISTE'
                    ));
                } else {
                    $prod = $GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $request->get('idProd')));
                    $GLOBALS['em']->remove($prod);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'Eliminacion exitosa',
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'UNAUTHORIZED'
                ));
            }
        });

$app->post('api/loginadm', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/UkDL1AAl4Qcw3p0XcjLlU';
            $directory = \Stormpath\Resource\Directory::get($href);
            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }
            if ($t->username !== null) {
                try {
                    $authResult = $directory->authenticate($request->get('username'), $request->get('password'));
                    $acc = $authResult->account;
                    return $app->json(array(
                                'result' => 'OK',
                                'username' => $acc->username
                    ));
                } catch (Exception $e) {
                    return $app->json(array(
                                'result' => 'FAIL PASS',
                                'username' => 'Pass incorrecta'
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'FAIL USER',
                            'username' => 'No existe'
                ));
            }
        });

$app->post('api/login', function (Request $request) use($app) {
            global $application;
            $accounts = $application->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }
            if ($t->username !== null) {
                try {
                    $authResult = $application->authenticate($request->get('username'), $request->get('password'));
                    $acc = $authResult->account;
                    return $app->json(array(
                                'result' => 'OK',
                                'username' => $acc->username
                    ));
                } catch (Exception $e) {
                    return $app->json(array(
                                'result' => 'FAIL PASS',
                                'username' => 'Pass incorrecta'
                    ));
                }
            } else {
                return $app->json(array(
                            'result' => 'FAIL USER',
                            'username' => 'No existe'
                ));
            }
        });       

$app->post('api/signup', function(Request $request) use($app) {
            global $application;

            if ($request->get('password') !== $request->get('reppassword')) {
                return $app->json(array(
                            'result' => 'FAIL REPASS'
                ));
            }

            $accounts = $application->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('email', $request->get('email'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            $accounts1 = $application->accounts;
            $search1 = new \Stormpath\Resource\Search();
            $accounts1->search = $search1->addEquals('username', $request->get('username'));
            $t1 = null;
            foreach ($accounts1 as $val) {
                $t1 = $val;
            }

            if ($t1->username === null) {
                if ($t->email === $request->get('email')) {
                    return $app->json(array(
                                'result' => 'FAIL EMAIL'
                    ));
                } else {
                    try {
                        $account = \Stormpath\Resource\Account::instantiate(
                                        array('givenName' => $request->get('nombre'),
                                            'surname' => $request->get('apellido'),
                                            'username' => $request->get('username'),
                                            'email' => $request->get('email'),
                                            'password' => $request->get('password')));
                        $application->createAccount($account);
                        return $app->json(array(
                                    'result' => 'OK',
                                    'username' => $request->get('username')
                        ));
                    } catch (Exception $e) {
                        return $app->json(array(
                                    'result' => 'FAIL PASS'
                        ));
                    }
                }
            } else {
                return $app->json(array(
                            'result' => 'FAIL USER'
                ));
            }
        });


$app->get('api/canasta', function(Request $request) use ($app) {
            $productos = array();
            $href = 'https://api.stormpath.com/v1/directories/41s3NhQLcURkZOZNIV2h0J';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                $canastas = $GLOBALS['em']->getRepository('Model\canasta')->findBy(array('username' => $request->get('username')));


                foreach ($canastas as $key => $value) {
                    array_push($productos, $GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $value->getIdProducto())));
                }

                return $app->json(array(
                            'result' => 'OK',
                            'username' => $request->get('username'),
                            'prods' => $productos
                ));
            }

            return $app->json(array(
                        'result' => 'FAIL',
                        'username' => $request->get('username'),
                        'prods' => $productos
            ));
        });


$app->post('api/canasta', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/41s3NhQLcURkZOZNIV2h0J';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                $prod = $GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $request->get('prodId')));
                if ($prod->getExistencia() > 0) {
                    $can = new canasta();
                    $can->idProducto = $request->get('prodId');
                    $can->username = $request->get('username');
                    $fecha = new DateTime();
                    $can->fecha = $fecha->format("Y-m-d H:i:s");
                    $GLOBALS['em']->persist($can);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'OK',
                    ));
                }
            }

            return $app->json(array(
                        'result' => 'FAIL',
            ));
        });

$app->put('api/canasta', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/41s3NhQLcURkZOZNIV2h0J';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                $canastas = $GLOBALS['em']->getRepository('Model\canasta')->findBy(array('username' => $request->get('username')));
                foreach ($canastas as $key => $value) {
                    $producto = $GLOBALS['em']->getRepository('Model\producto')->findOneBy(array('id' => $value->getIdProducto()));
                    $producto->existencia = $producto->getExistencia() - 1;
                    $GLOBALS['em']->merge($producto);
                    $GLOBALS['em']->flush();
                    $GLOBALS['em']->remove($value);
                    $GLOBALS['em']->flush();
                    return $app->json(array(
                                'result' => 'OK',
                    ));
                }
            }

            return $app->json(array(
                        'result' => 'FAIL',
            ));
        });

$app->delete('api/canasta', function (Request $request) use($app) {
            $href = 'https://api.stormpath.com/v1/directories/41s3NhQLcURkZOZNIV2h0J';
            $directory = \Stormpath\Resource\Directory::get($href);

            $accounts = $directory->accounts;
            $search = new \Stormpath\Resource\Search();
            $accounts->search = $search->addEquals('username', $request->get('username'));
            $t = null;
            foreach ($accounts as $val) {
                $t = $val;
            }

            if ($t->username !== null) {
                $canastas = $GLOBALS['em']->getRepository('Model\canasta')->findBy(array('username' => $request->get('username')));
                foreach ($canastas as $key => $value) {
                    $GLOBALS['em']->remove($value);
                    $GLOBALS['em']->flush();
                }
                return $app->json(array(
                            'result' => 'OK',
                ));
            }

            return $app->json(array(
                        'result' => 'FAIL',
            ));
        });


$app['debug'] = true;
$app->run();
