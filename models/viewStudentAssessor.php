<?php
class viewStudentAssessor extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'thesis_view_student_assessor', null); 
    }
}