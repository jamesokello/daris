<?php
App::uses('AppModel', 'Model');

/*
 * Staffdetail Model
 *
 * @property Dependantdetail $Dependantdetail
 * @property Previousworkplace $Previousworkplace
 */
class Schooldoneasubject extends AppModel {

/*
 * Display field
 *
 * @var string
 */
	//public $displayField = 'name';

	public $primaryKey = 'id';
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

    public $validate = array(
      'fullsubjectname' => array(
	  'fullsubjectnamerule-1' => array(
	      'rule' => 'isUnique',
	      'message' => 'The subject name already exists, please choose another subject name',
	   )
      ),
      'shortsubjectname' => array(
	  'shortsubjectname-1' => array(
	      'rule' => 'isUnique',
	      'message' => 'The short subject name already exists, please choose another subject name',
	   ),
	   'shortsubjectname-2' => array(
	      'rule' => '/^[a-z]{3,8}$/i',
	      'message' => 'Only alphabetic words allowed, they must be between 3 to 8 letters long',
	   ),
	   'shortsubjectname-3' => array(
	      'on' => 'create',
	      'rule' => array('checkcolumnnameexistence'),
	      'message' => 'The subject name already exists, please choose another subject name',
	   )
      ),
      'subjectcode' => array(
	  'subjectcoderule-1' => array(
	      'rule' => 'isUnique',
	      'message' => 'The subject code already exists, please choose another subject name',
	   )
      ),

    );

public function checkcolumnnameexistence($check)
{
    //pick out the value in the array(The value that was in the post data)
    $uploadData = array_shift($check);

    // load the model with the data
    $new = ClassRegistry::init('Alevelmarksheetresult');

    // perform a query using the model you loaded
    $livequery = $new->query("DESCRIBE alevelmarksheetresults;");

  $newarray = array();

    $arraycouter = 0;  
    foreach($livequery as $livequery2){
	    if($arraycouter >=12){
		   array_push($newarray,$livequery2['COLUMNS']['Field']);
	    }
	    $arraycouter++;
    }

    if(in_array($uploadData,$newarray)){
	return false;
    }else
    {
	/*
	  perform column name addition to the database table once you have found out
	  that the column name does not exist and also that the the field names are as specified
	*/
	//$new->query("ALTER TABLE olevelmarksheetresults ADD ".$uploadData." INT(11) UNSIGNED NULL DEFAULT NULL;");
	
	return true;
    }

}

public function deleteColumninTable($shortsubjectname,$value2){
    // load the marksheet model with the data
    /*
    $new = ClassRegistry::init('Alevelmarksheetresult');
    $grade = "_grade";
    $new->query("ALTER TABLE olevelmarksheetresults DROP ".$shortsubjectname.";");
    $new->query("ALTER TABLE olevelmarksheetresults DROP ".$shortsubjectname.$grade.";");
    $new2 = ClassRegistry::init('Olevelreport');
    $new2->query("ALTER TABLE olevelreports DROP ".$shortsubjectname.";");
    $examsconsidered = "_examsconsidered";
    $new2->query("ALTER TABLE olevelreports DROP ".$shortsubjectname.$examsconsidered.";");
    
    $new2->query("ALTER TABLE olevelreports DROP ".$shortsubjectname.$grade.";");
    $date_created = "_datecreated";
    $new2->query("ALTER TABLE olevelreports DROP ".$shortsubjectname.$date_created.";");
    */
    
    // Start modification of 
    $grade = "_grade";
    $new  = ClassRegistry::init('Alevelmarksheetresult');
    $new2 = ClassRegistry::init('Alevelreport');
    $new->query("ALTER TABLE alevelmarksheetresults DROP ".$shortsubjectname."_finalgrade".";");
    $new2->query("ALTER TABLE alevelreports DROP ".$shortsubjectname."_finalgrade".";");
    
    if( ($value2 != null) && (is_array($value2))){
    
	foreach ($value2 as $avalue) {	
	
	    $avalue = (string)$avalue;
	    
	    if($avalue != ""){
	    
		$new->query("ALTER TABLE alevelmarksheetresults DROP ".$shortsubjectname.$avalue."_mark".";");	    
		$new->query("ALTER TABLE alevelmarksheetresults DROP ".$shortsubjectname.$avalue.$grade.";");	    
	    
		$new2->query("ALTER TABLE alevelreports DROP ".$shortsubjectname.$avalue."_finalaveragemark".";");
		$new2->query("ALTER TABLE alevelreports DROP ".$shortsubjectname.$avalue."_finalaveragemarkgrade".";");
		$examsconsidered = "_examsconsidered";
		$new2->query("ALTER TABLE alevelreports DROP ".$shortsubjectname.$avalue.$examsconsidered.";");    
	    
	    }
	}
    }    
}

public function alterColumninTable($previousshortsubjectname,$newshortsubjectname){
    // load the marksheet model with the data
    $new = ClassRegistry::init('Alevelmarksheetresult');
    $grade = "_grade";
    $new->query("ALTER TABLE olevelmarksheetresults CHANGE ".$previousshortsubjectname." ".$newshortsubjectname." INT(11) UNSIGNED NULL DEFAULT NULL;");
    $new->query("ALTER TABLE olevelmarksheetresults CHANGE ".$previousshortsubjectname.$grade." ".$newshortsubjectname.$grade." INT(11) UNSIGNED NULL DEFAULT NULL;");
    $new2 = ClassRegistry::init('Olevelreport');
    $new2->query("ALTER TABLE olevelreports CHANGE ".$previousshortsubjectname." ".$newshortsubjectname." INT(11) UNSIGNED NULL DEFAULT NULL;");
    $examsconsidered = "_examsconsidered";
    $new2->query("ALTER TABLE olevelreports CHANGE ".$previousshortsubjectname.$examsconsidered." ".$newshortsubjectname.$examsconsidered." VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;");
    
    $new2->query("ALTER TABLE olevelreports CHANGE ".$previousshortsubjectname.$grade." ".$newshortsubjectname.$grade." INT(11) UNSIGNED NULL DEFAULT NULL;");
    $date_created = "_datecreated";
    $new2->query("ALTER TABLE olevelreports CHANGE ".$previousshortsubjectname.$date_created." ".$newshortsubjectname.$date_created."  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;");
    
    //ALTER TABLE `olevelmarksheetresults` CHANGE `klkjlpj` `klkjlpjj` INT(11) UNSIGNED NULL DEFAULT NULL;
}

public function addColumninTable($newcolumnname,$value2){
    // load the marksheet model with the data    
    $grade = "_grade";
    $new  = ClassRegistry::init('Alevelmarksheetresult');
    $new2 = ClassRegistry::init('Alevelreport');
    $new->query("ALTER TABLE alevelmarksheetresults ADD ".$newcolumnname."_finalgrade"." VARCHAR(1) NULL DEFAULT NULL;");
    $new2->query("ALTER TABLE alevelreports ADD ".$newcolumnname."_finalgrade"." VARCHAR(1) NULL DEFAULT NULL;");
    
    if( ($value2 != null) && (is_array($value2))){
    
	foreach ($value2 as $avalue) {	
	
	    $avalue = (string)$avalue;
	    
	    if($avalue != ""){
	    
		$new->query("ALTER TABLE alevelmarksheetresults ADD ".$newcolumnname.$avalue."_mark"." INT(11) UNSIGNED NULL DEFAULT NULL;");	    
		$new->query("ALTER TABLE alevelmarksheetresults ADD ".$newcolumnname.$avalue.$grade." INT(11) UNSIGNED NULL DEFAULT NULL;");	    
	    
		$new2->query("ALTER TABLE alevelreports ADD ".$newcolumnname.$avalue."_finalaveragemark"." INT(11) UNSIGNED NULL DEFAULT NULL;");
		$new2->query("ALTER TABLE alevelreports ADD ".$newcolumnname.$avalue."_finalaveragemarkgrade"." INT(11) UNSIGNED NULL DEFAULT NULL;");
		$examsconsidered = "_examsconsidered";
		$new2->query("ALTER TABLE alevelreports ADD ".$newcolumnname.$avalue.$examsconsidered." VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;");    
	    
	    }
	}
    }
}

public function checkIfResultsAreInColumn($columnname){
    //load the marksheet model with the data
    $new = ClassRegistry::init('Alevelmarksheetresult');
    //$findby = 
    //$found = $new->
}

public $findMethods = array('search' => true);
protected function _findSearch($state, $query, $results = array())
{
  //$new = ClassRegistry::init('Student');
  if ($state === 'before') {
    $searchQuery = Hash::get($query, 'searchQuery');
    $searchConditions = array(
      'or' => array(
        "{$this->alias}.fullsubjectname LIKE" => '%' . $searchQuery . '%',
        "{$this->alias}.shortsubjectname LIKE" => '%' . $searchQuery . '%',
        "{$this->alias}.subjectcode LIKE" => '%' . $searchQuery . '%',
      )
    );
    
    $query['conditions'] = array_merge($searchConditions,(array)$query['conditions']);
    return $query;
  }
  return $results;
}

}
