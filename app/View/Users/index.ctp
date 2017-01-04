<?php ?>
<h1 class="sectiondefinition1">Users</h1>
<table>
    <thead>
		<tr>
			<th><?php echo $this->Paginator->sort('username', 'Username');?>  </th>			
			<th><?php echo $this->Paginator->sort('created', 'Created');?></th>
			<th><?php echo $this->Paginator->sort('modified','Last Update');?></th>
			<th><?php echo $this->Paginator->sort('role','Role');?></th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php $numberofusers = sizeof($users);     ?>
		</tr>
		<?php foreach($users as $user): ?>				
			
			<td><?php echo $this->Html->link( $user['User']['username']  ,   array('action'=>'edit', $user['User']['id']),array('escape' => false) );?></td>
			<td><?php echo $this->Time->niceShort($user['User']['created']); ?></td>
			<td><?php echo $this->Time->niceShort($user['User']['modified']); ?></td>
			<td><?php echo $user['User']['role']; ?></td>
			<td>
			<?php echo $this->Html->link("Edit ",   array('action'=>'edit', $user['User']['id']) ); ?>
			<?php	
			    if( $numberofusers >= 2 ){
			    
				echo " | ".$this->Html->link(" Delete", array('action'=>'delete', $user['User']['id']));
			    
			    } else {
			    
				echo "";
			    
			    }
			?>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php unset($user); ?>
	</tbody>
</table>
<?php echo $this->Paginator->prev('<< ' . __('previous ', true), array(), null, array('class'=>'disabled'));?>
<?php echo $this->Paginator->numbers(array(   'class' => 'numbers'     ));?>
<?php echo $this->Paginator->next(__(' next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
