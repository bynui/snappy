<?php
    header( "Content-Type: application/json" );
    header( "HTTP/1.1 400 Bad Request" );
    echo json_encode( $utils->response(400) );
?>