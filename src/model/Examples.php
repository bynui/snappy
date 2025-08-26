<?php

namespace Model;

use Core\Model;
use Core\Utils;

class Examples extends Model{

    private $query = "
        select
            id,
            first_name as FirstName,
            last_name as LastName,
            email as Email,
            status as Status
        from
            example
        %s
        order by
            first_name asc
        %s
    ";

    public function getAllExamples(){
        $sql = sprintf($this->query, "", "");
        return $this->execute( $sql );
    }
    
    public function getExampleById( $param ){
        $sql = sprintf($this->query, "where id = :id", "");
        return $this->execute( $sql, $param );
    }

    public function getByStatus( $param, $page ){
        $str = "limit 10 offset %d";
        $limit = sprintf($str, $page);
        $where = "where status = :status";
        $sql = sprintf($this->query, $where, $limit);
        return $this->execute( $sql, $param );
    }

    public function searchExample( $param ){
        $sql = sprintf($this->query, "where (first_name like concat('%',:keyword,'%') or last_name like concat('%',:keyword,'%') or email like concat('%',:keyword,'%')) and status = :status", "");
        return $this->execute( $sql, $param );
    }   

    public function newExample( $param ){
        $sql = "insert into example(first_name, last_name, email) values(:first, :last, :email)";
        return $this->execute( $sql, $param );
    }

    public function updateExample( $param ){
        $sql = "update example set first_name = :first, last_name = :last, email = :email where id = :id";
        return $this->execute( $sql, $param );
    }

    public function deleteExample( $param ){
        $sql = "delete from example where id = :id";
        return $this->execute($sql, $param);
    }
}

?>