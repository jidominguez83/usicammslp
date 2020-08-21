<?php
/*
$query_pagination es la variable para obtener el total de registros de la consulta con un count(). Hay que asignar la consulta dependiendo de los resultados que se vayan a paginar.

$limits_query es la variable para sacar la consulta y se le concatena la variable $limit que determinará la paginación.
*/

$query = mysqli_query($dbase, $query_pagination);
$rowp  = mysqli_fetch_row($query);

$rows  = $rowp[0];

$page_rows = 10;

$last = ceil($rows/$page_rows);

if($last < 1){
    $last = 1;
}

$pagenum = 1;

if(isset($_GET['pn'])){
    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}

if ($pagenum < 1) { 
    $pagenum = 1; 
} 
else if ($pagenum > $last) { 
    $pagenum = $last; 
}

$limit = ' LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

$nquery = mysqli_query($dbase, $limits_query.$limit);

$paginationCtrls = '';
$paginationCtrls2 = '';

if($last != 1){
    
    if ($pagenum > 1) {
        $previous = $pagenum - 1;

        if(isset($_GET['buscar_tipo_eval'])){
            $buscar_tipo_eval = $_GET['buscar_tipo_eval'];
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?buscar_tipo_eval='.$buscar_tipo_eval.'&pn='.$previous.'" class="btn-pagination">Anterior</a>  |  ';
        } else {
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" class="btn-pagination">Anterior</a>  |  ';
        }
        
        for($i = $pagenum-4; $i < $pagenum; $i++){
            if($i > 0){
                if(isset($_GET['buscar_tipo_eval'])){
                    $buscar_tipo_eval = $_GET['buscar_tipo_eval'];
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?buscar_tipo_eval='.$buscar_tipo_eval.'&pn='.$i.'" class="btn-pagination">'.$i.'</a>  |  ';
                } else {
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" class="btn-pagination">'.$i.'</a>  |  ';
                }
            }    
        }
    }

    $paginationCtrls .= "  <span class='actual'>".$pagenum."</span> ";

    for($i = $pagenum+1; $i <= $last; $i++){
        if(isset($_GET['buscar_tipo_eval'])){
            $buscar_tipo_eval = $_GET['buscar_tipo_eval'];
            $paginationCtrls .= '  |  <a href="'.$_SERVER['PHP_SELF'].'?buscar_tipo_eval='.$buscar_tipo_eval.'&pn='.$i.'" class="btn-pagination">'.$i.'</a>';
        } else {
            $paginationCtrls .= '  |  <a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" class="btn-pagination">'.$i.'</a>';
        }    
        if($i >= $pagenum+4){
            break;
        }
    }

    if ($pagenum != $last) {
        $next = $pagenum + 1;
        if(isset($_GET['buscar_tipo_eval'])){
            $buscar_tipo_eval = $_GET['buscar_tipo_eval'];
            $paginationCtrls .= '  |  <a href="'.$_SERVER['PHP_SELF'].'?buscar_tipo_eval='.$buscar_tipo_eval.'&pn='.$next.'" class="btn-pagination">Siguiente</a> ';
        } else {
            $paginationCtrls .= '  |  <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'" class="btn-pagination">Siguiente</a> ';
        }
    }
}

?>