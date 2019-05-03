<?php
/**
 * Created by PhpStorm.
 * User: jrakk
 * Date: 4/8/2019
 * Time: 2:16 PM
 */

    // Start seesion
    session_start();
    // Turn on error reporting
    ini_set('display_error', 1);
    error_reporting(E_ALL);

    //require autoload file
    require_once ('vendor/autoload.php');

    // create an instance of the base class
    $f3 = Base::instance();

    // Turn on Fat-free error reporting
    $f3->set('DEBUG', 3);


    $f3->set('colors', array('pink', 'green', 'blue'));
    $f3->set('breeds', array('Affenpinscher', 'Afghan Hound', 'Airedale Terrier'));

    require_once("model/validation-functions.php");

    // define a default route
    $f3->route('GET /@pet', function($f3, $param)
    {
        $pet = $param['pet'];
        echo "<h1>$pet</h1>";

        switch ($pet)
        {
            case 'dog':
                echo "<h3>Woof</h3>";
                break;
            case 'chicken':
                echo "<h3>Cluck</h3>";
                break;
            case 'cat':
                echo "<h3>Meow</h3>";
                break;
            case 'horse':
                echo "<h3>Neigh</h3>";
                break;
            case 'cow':
                echo "<h3>Moo</h3>";
                break;
            default:
                $f3->error(404);
        }
    });

    $f3->route('GET /', function()
    {
        echo "<h1>My pets</h1>";
        echo "<a href='order'>Order a Pet</a>";
    });

    $f3->route('GET|POST /order', function($f3)
    {
        if(isset($_SESSION))
        {
            session_destroy();
            session_start();
        }

        if(isset($_POST['animal']) && isset($_POST['qty'])) {
            $animal = $_POST['animal'];
            $qty = $_POST['qty'];
            if(validString($animal) && validQty($qty)) {
                $_SESSION['animal'] = $animal;
                $_SESSION['qty'] = $qty;
                $f3->reroute('/order2');
            }
            else {
                if(!validString($animal))
                {
                    $f3->set("errors['animal']", "Please enter an animal.");
                }
                if(!validQty($qty))
                {
                    $f3->set("errors['qty']", "Quantity must be larger than 0");
                }
                $f3->set("previous", $animal);
                $f3->set("previousQty", $qty);
            }
        }

        $view = new Template();
        echo $view->render("views/form1.html");
    });

    $f3->route('POST|GET /order2', function($f3)
    {

        if(isset($_POST['color'])) {
            $color = $_POST['color'];
            $breed = $_POST['breed'];

            $f3->set('breed', $breed);
            $f3->set('color', $color);
            if((validString($color) && $color !== "--Select a color--") && validBreed($breed)) {
                $_SESSION['color'] = $color;
                $_SESSION['breed'] = $breed;
                $f3->reroute('/results');
            }
            else {
                if(!validString($color) || $color === "--Select a color--")
                {
                    $f3->set("errors['color']", "Please select a color.");
                }
                if(!validBreed($breed))
                {
                    $f3->set("errors['breed']", "Please select a breed.");
                }
            }
        }

        $view = new Template();
        echo $view->render("views/form2.html");
    });

    $f3->route('POST|GET /results', function()
    {

        $view = new Template();
        echo $view->render("views/results.html");
    });

    // Run Fat-Free
    $f3->run();
