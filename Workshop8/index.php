<?php
require 'vendor/autoload.php';
require 'C:\xampp\htdocs\Workshop8\app\controllers\empolyee_controller';
use Jenssegers\Blade\Blade;
$blade = new Blade('app/views', 'cache');
$data = handleEmployeeRequest();
echo $blade->render($data['view'], $data);