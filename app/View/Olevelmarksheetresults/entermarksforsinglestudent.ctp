<h1 class="sectiondefinition1">Enter marks for <?php echo $subjecttoenter." , ".$examtoenter." Examinations - ".date("Y");?></h1>
<?php 

    $studentpicuture = $webcampic;
    
   ?>   
<fieldset class="sectiondefinition1">
    <legend class="sectiondefinition">Student: 
	  <?php 
	    echo $student[0]['Student']['surname']." ".$student[0]['Student']['othernames']."; Registration Number: ".$student[0]['Student']['registrationnumber']; ?></legend><?php
	  ?>
    <table>
    <tr class = "olevelresults">
	<td>
	    <?php

		$picuturepresent = $student[0]['Student']['studenthaspic'];
		$studentpicid = $student[0]['Student']['id'];

		if($picuturepresent == "YES"){
   
		    echo '<img src="/daris/students/displayImage/'.$studentpicid.'" alt="Student\'s Picture" id="shayhowe"/>';
	
		}else{
		
		    echo $this->Html->image('studentpics/person.png', array('alt' => 'Student\'s Picture', 'id' => 'shayhowe'));
    
		}
		
		echo "<br/>"."<br/>";
		$this->Form->create();
	    ?>
	</td>
	<td>
	    <?php echo "<br/>"."<br/>"; ?>	
	</td>
	<td>
	    <?php echo "<br/>"."<br/>"; ?>
	</td>
    </tr>
    
    <tr class = "olevelresults">
	<td>
	    <?php
		echo $this->Form->input('biology', array('label' => false, 'tabindex' => '1', 'autofocus' => 'autofocus'));
	    ?>
	</td>
	<td>
	    <?php echo "<br/>"."<br/>"; ?>
	</td>
	<td>
	    <?php echo "<br/>"."<br/>"; ?>
	</td>
    </tr>
</table>
<?php
    //echo $this->Form->create('Student');
    echo $this->Form->end('Update');
?>