<?php
class studentAssessor extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'thesis_student_assessor', null); 
    }
}