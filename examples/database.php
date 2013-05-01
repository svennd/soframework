<?php
#   Svenn D'Hert
include('../_main/main_frame.php');
 
# initialise frame
$core = new core(array('title' => 'My view page', 'path' => '../'));

# load module database
$core->load_modules('database');

# unlike other modules you don't need to use the full name,
# and a short name is used db to access the module;

# connection : default  --> auto-connect!
# you can if wished create your own connection using: 
	# default setting is to open a connection with given info
	# so you need to close of this one first (you could alter _modules/database/boot.php for alternative behavour)
	$core->db->close_connection();
	
	# create a connection
	$core->db->connect('localhost', 'myUser', 'myPasw', 'myDb');
	
	# you could also check if this worked using :
	# though in most known cases it would fail with a die(__REASON__);
	// if ($core->db->connect(...)) {}
	
# queries
	# a default select 
	# __FILE__ and __LINE__ are build in vars in php so we know if this query should fail what file and what line!
	$my_result = $core->db->sql('select * from my_user_table where user="me" limit 1;', __FILE__, __LINE__);
	
	# in the above example we stored the result in $my_result, this would have a look as : 
	# array('user' => 'me', '0' => 'me', 'pasw' => 'mypassw', '1' => 'mypassw' ...)
	# this is cause we default use MYSQL_BOTH, you can alter this to one of these :
		# $core->db->sql('',__FILE__,__LINE__, 'BOTH'); --> default
		# $core->db->sql('',__FILE__,__LINE__, 'NUM'); --> array(0 => value, 1 => value, ...)
		# $core->db->sql('',__FILE__,__LINE__, 'ASSOC'); --> array( key => value, key => value, ...)
	# you can also don't save $my_result and directly ask the module using $core->db->result,
	# on failure it would contain 'false' (or 0 results!)
	
	
	# a delete would return a bool if it worked or not
	# a insert would return the inserted_id (this could be 0)
	# a update will return the affected lines
	

?>
