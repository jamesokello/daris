<?php
App::uses('Folder','Utility');
App::uses('Files','Utility');
App::uses('AppController', 'Controller');
/*
 * Staffdetails Controller
 *
 * @property Staffdetail $Staffdetail
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SchooldoneexamsController extends AppController {

    public $helpers = array('Paginator','Html', 'Form', 'Js');
    public $components = array('Paginator','Session');

    public function index() {
	$this->layout = 'default2';
	$this->Paginator->settings = array(
	  'Schooldoneexam' => array (
	    'paramType' => 'querystring',
	    'limit' => 10,
	    'order' => array(
	      'Schooldoneexam.id' => 'desc'
	    )
	  )
	);
	$this->set('students', $this->Paginator->paginate());
	/*$students = $this->Student->find(
				      'all', 
				      array('order' => 'Student.id DESC', 'group' => 'Student.id')
					);
	$this->set('students', $students);
	*/
    }
    
    public function search() {
      $this->layout = 'default2';
      if ($this->request->is('put') || $this->request->is('post')) {
	// poor man's Post Redirect Get behavior
	return $this->redirect(array(
	  '?' => array(
	    'q' => $this->request->data('Schooldoneexam.searchQuery')
	  )
	));
      }
      $this->Schooldoneexam->recursive = 0;
      $searchQuery = $this->request->query('q');
      $this->Paginator->settings = array(
	'Schooldoneexam' => array(
	'findType' => 'search',
	'searchQuery' => $searchQuery
	)
      );
      $this->set('students', $this->Paginator->paginate());
      $this->set('searchQuery', $searchQuery);
      $this->render('index');
    }


/**
 * Components
 *
 * @var array
 */
	//public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
 
 /*
	public function index() {
		$this->Staffdetail->recursive = 0;
		$this->set('staffdetails', $this->Paginator->paginate());
	}

	
*/

/**
 * add method
 *
 * @return void
 */
    public function add() {
	    $this->layout = 'default2';
	    if (!empty($this->request->data)) {
		if ($this->request->is('post')) {
			//$newsubjectname = $this->request->data['Schooldoneexam']['shortsubjectname'];
			$this->Schooldoneexam->create();
			//unset($this->Staffdetail->Previousworkplace->validate['staffdetail_id']);
			if ($this->Schooldoneexam->save($this->request->data)) {
				//$this->Schooldoneexam->addColumninTable($newsubjectname);
				$this->Session->setFlash(__('The Examination details have been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Session->setFlash(__('The Subject details could not be saved. Please, try again.'));
			}
		}
	    }
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
	$this->layout = 'default2';	
	if (!$id){
	    throw new NotFoundException(__('Invalid Subject'));
	}
	
	
	$staff = $this->Schooldoneexam->findById($id);
	
	if (!$staff){
	    throw new NotFoundException(__('Invalid Subject'));
	}

	//$shortsubjectnametobemodified = $staff['Schooldoneexam']['shortsubjectname'];
	
	if ($this->request->is(array('post', 'put'))){
	    $this->Schooldoneexam->id = $id;
	    
	    //$modifiedshortsubjectname = $this->request->data['Schooldoneexam']['shortsubjectname'];
	    
	    //if($shortsubjectnametobemodified != null && $modifiedshortsubjectname != null){
		if ($this->Schooldoneexam->save($this->request->data)){ 
		    //$this->Schooldoneexam->alterColumninTable($shortsubjectnametobemodified,$modifiedshortsubjectname);
		    $this->Session->setFlash(__('Examination Details have been updated successfully'));
		    return $this->redirect(array('action' => 'index'));
		}
	    //}
	    $this->Session->setFlash(__('Unable to update Examination details.'));
	}

	if (!$this->request->data){
	    $this->request->data = $staff;
	}
	

	$this->set('staffrecords',$staff);
	
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null/*, $shortsubjectname = null*/) {
		$this->layout = 'default2';
		$this->Schooldoneexam->id = $id;
		//$shortsubjectnametobedeleted = $shortsubjectname;
		if (!$this->Schooldoneexam->exists()) {
			throw new NotFoundException(__('Invalid Examination'));
		}
		
		$this->request->allowMethod('post', 'delete');
		//$shortsubjectnamefound = $this->Schooldoneexam->findAllByShortsubjectname($shortsubjectnametobedeleted);
		
		//if($shortsubjectnamefound[0]['Schooldoneexam']['shortsubjectname'] != null){
		    if ($this->Schooldoneexam->delete()) {
			    //$this->Schooldoneexam->deleteColumninTable($shortsubjectnametobedeleted);
			    $this->Session->setFlash(__('Examination details have been deleted.'));
		    } else {
			    $this->Session->setFlash(__('Examination details could not be deleted. Please, try again.'));
		    }
		//}else{
		    //$this->Session->setFlash(__('Subject details could not be deleted because they do not exist. Please, try again.'));
		//}
		return $this->redirect(array('action' => 'index'));
    }

    private function createcolumn($columnname){
	$this->layout = 'default2';
	// load the model with the data
	$new = ClassRegistry::init('Olevelmarksheetresult');

	// perform a query using the model you loaded
	$livequery = $new->query("DESCRIBE olevelmarksheetresults;");
    }
}
