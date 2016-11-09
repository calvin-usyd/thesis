<?php
class project extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'thesis_project', null); 
    }
}