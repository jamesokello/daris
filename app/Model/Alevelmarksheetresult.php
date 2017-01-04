<?php
App::uses('AppModel', 'Model');
/**
 * Dependantdetail Model
 *
 * @property Staffdetail $Staffdetail
 */
class Alevelmarksheetresult extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	//public $displayField = 'staffdetail_id';

	//public $primaryKey = 'id';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
    public function checkIfMarksheetIsCreated($examname,$thedate,$theclass){
	//load the new marksheet with data
	$new = ClassRegistry::init('Marksheetcriterea');
	
	if($new->findAllByExamnameAndYearAndClass($examname,$thedate,$theclass)){
	    return true;
	}else{
	    return false;
	}
    }
    
    public function checkIfMarksheetNeedsUpdating(){
    
    }
    
    /*
	This function returns true if it succeeds in creating a marksheet
    */
    public function createMarksheet($examname,$class,$examyear){
	$mutexrail = ClassRegistry::init('Mutexrail');
	$student = ClassRegistry::init('Student');
	$marksheetcriterea = ClassRegistry::init('Marksheetcriterea');
	// Check if marksheet for a particular class is being created and wait for 3s to see if it is created
	// if it has now been created break out of the while loop and feed the data passed to the student database
	// otherwise if it not being created set the mutex to being created and start the creation of the marksheet
	// thereafter unset the mutex for that class.
		  
	if($mutexrail->findAllByCreatingsheetAndClassAndExamnameAndYear(1,$class,$examname,date("Y")) != null){
	    while($mutexrail->findAllByCreatingsheetAndClassAndExamnameAndYear(1,$class,$examname,date("Y")) != null){
		sleep(1);
		if(($mutexrail->findAllByCreatingsheetAndClassAndExamnameAndYear(0,$class,$examname,date("Y")) != null) && ($this->Marksheetcriterea->findAllByExamnameAndYearAndClass($examname,date("Y"),$class) != null)){
		    break;
		}else{
		    continue;
		}
	    }
	    
	// compose a data array and feed in the values into the database
	      
	}else{
	    // create mutex and set it to creating mode
	    $mutexrail->create();
	    $data = array(
		'Mutexrail' => array(
		    'creatingsheet' => 1,
		    'class' => $class,
		    'examname' => $examname,
		    'year' => date("Y")
		)
	    );
		      
	    $mutexrail->save($data);
		      
	    // start the creation of the marksheet for the particular class
		      
	    $classtocreate = $student->find('all', array(
		'fields' => array('Student.id','Student.currentstream'),
		'conditions' => array('Student.currentclass =' => $class)
	    ));
		      
	    // create the marksheets using the studentid's, class, examname and year
		      
	    foreach($classtocreate as $studentid){
	    
		Alevelmarksheetresult::create();
		
		$data = array(
		    'Alevelmarksheetresult' => array(
			'student_id' => $studentid['Student']['id'],
			'class' => $class,
			'exam_name' => $examname,
			'stream' => $studentid['Student']['currentstream'],
			'year' => date("Y")
		    )
		);
			  
		Alevelmarksheetresult::save($data);
	    }
		      
	    // Create an entry into the Marksheetcriterea table to tell people that a 
	    // marsheet for that class has now been created
	    $marksheetcriterea->create();
			 
	    $data = array(
		'Marksheetcriterea' => array(
		    'examname' => $examname,
		    'class' => $class,
		    'year' => date("Y")
		)
	    );
			
	    $marksheetcriterea->save($data);
	     
	    // Use the previous mutex that you had created and change its creating mode to zero
	    // so that the next person does not recreate the sheet
		      
	    $createdmutex = $mutexrail->findAllByCreatingsheetAndClassAndExamnameAndYear(1,$class,$examname,date("Y"));
		      
	   $data = array(
		'Mutexrail' => array(
		    'id' => $createdmutex[0]['Mutexrail']['id'],
		    'creatingsheet' => 0,
		    'class' => $class,
		    'examname' => $examname,
		    'year' => date("Y")
		)
	    );
		      
	    $mutexrail->save($data);
		      
		      
	}
  
    }
    
    // this function updates a marksheet of a particular class
    public function updateMarksheet($examname,$class,$examyear){
	$mutexrail = ClassRegistry::init('Mutexrail');
	$student = ClassRegistry::init('Student');
	$marksheetcriterea = ClassRegistry::init('Marksheetcriterea');
	// Check if marksheet for a particular class is being created and wait for 3s to see if it is created
	// if it has now been created break out of the while loop and feed the data passed to the student database
	// otherwise if it not being created set the mutex to being created and start the creation of the marksheet
	// thereafter unset the mutex for that class.
		  
	if($mutexrail->findAllByUpdatingsheetAndClassAndExamnameAndYear(1,$class,$examname,date("Y")) != null){
	    while($mutexrail->findAllByUpdatingsheetAndClassAndExamnameAndYear(1,$class,$examname,date("Y")) != null){
		sleep(1);
		if(($mutexrail->findAllByUpdatingsheetAndClassAndExamnameAndYear(0,$class,$examname,date("Y")) != null) && ($this->Marksheetcriterea->findAllByExamnameAndYearAndClass($examname,date("Y"),$class) != null)){
		    break;
		}else{
		    continue;
		}
	    }
	    
	// compose a data array and feed in the values into the database
	      
	}else{
	    //*************************************************************//
	    
	    // start the updating of the marksheet for the particular class
	    // Pick up the current time stamp of when the marksheet was created
	    $marksheettimestamps = $marksheetcriterea->findAllByClassAndExamnameAndYear($class,$examname,date("Y"));
	    $marksheettimestamp = $marksheettimestamps[0]['Marksheetcriterea']['currenttimestamp'];
	    
	    //search for those records entered after the time the marksheet was created
	    $classtocreate = $student->find('all', array(
		'fields' => array('Student.id','Student.currentstream'),
		'conditions' => array('Student.currenttimestamp >' => $marksheettimestamp)
	    ));
	    
	    // if some records exist that have been created after the time the marksheet was
	    // created,add those records to the marksheet with O-level results
	    if($classtocreate != null){
		
		//******Start updating the class list for the selected class***********//
		
		// use the mutex created for the class list and set its updating mode to 1
		$createdmutex = $mutexrail->findAllByClassAndExamnameAndYear($class,$examname,date("Y"));
	    
		$data = array(
		    'Mutexrail' => array(
			'id' => $createdmutex[0]['Mutexrail']['id'],
			'updatingsheet' => 1,
			'class' => $class,
			'examname' => $examname,
			'year' => date("Y")
		    )
		);
		      
		$mutexrail->save($data);
		
		// update the marksheets using the studentid's, class, examname and year
		      
		foreach($classtocreate as $studentid){
	    
		    Alevelmarksheetresult::create();
		
		    $data = array(
			'Alevelmarksheetresult' => array(
			    'student_id' => $studentid['Student']['id'],
			    'class' => $class,
			    'exam_name' => $examname,
			    'stream' => $studentid['Student']['currentstream'],
			    'year' => date("Y")
			)
		    );
			  
		    Alevelmarksheetresult::save($data);
		}
		
		// update the current marksheetcriterea table's time stamp field to show that you
		// have now updated the marksheet
		$data = array(
		    'Marksheetcriterea' => array(
			'id' => $marksheettimestamps[0]['Marksheetcriterea']['id'],
			'examname' => $examname,
			'class' => $class,
			'year' => date("Y"),
			'currenttimestamp' => time()
		    )
		);
			
		$marksheetcriterea->save($data);	
		
		// use the mutex you created for that class and change its updating mode to 0
		
		$data = array(
		    'Mutexrail' => array(
			'id' => $createdmutex[0]['Mutexrail']['id'],
			'updatingsheet' => 0,
			'class' => $class,
			'examname' => $examname,
			'year' => date("Y")
		    )
		);
		      
		$mutexrail->save($data);
		
		
		
	    }
		      
	}
  	
    }
    
    //check the size of the file being uploaded
    public function checksizeuploadsize($filesize){
    
    }
    
    //check for upload errors
    public function checkforuploaderrors($errors){
    
    }
    
    //check for correct file type
    public function checkfiletype($filetype){
    
    }
    
    
}
