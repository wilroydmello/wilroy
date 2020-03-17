<?php
namespace wilroy\core\QueryBuild;

class Schema{
    public function create($name , closure $table){
        echo $name;
    }
    public function name($name){
        echo "this is name";
    }

}