<?php
class siteUser extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'thesis_site_user', null); 
    }
}