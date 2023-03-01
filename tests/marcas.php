<?php
require "../app/Models/Marcas.php";
use App\Models\Marcas;

$marcas = new Marcas;
$marcas ->searchForId(17);
$marcas ->setNombre('caballo');
if($marcas ->update()){
    echo 'correcto';

}else{
    echo 'incorrecto';
}
