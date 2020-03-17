<?php
namespace wilroy\core\QueryBuild;

class QueryBuild{

    function create($name , closure $table){
        echo $table->name;
    }

    
}
?>