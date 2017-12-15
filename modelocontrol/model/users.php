<?php
class Model_users extends Orm\Model

{

   protected static $_table_name = 'users'; 
   protected static $_primary_key = array('id');
   protected static $_properties = array(
      'id',
      'name' => array( //column name
         'data_type' => 'varchar'
      ),
      'pass' => array(
            'data_type' => 'varchar'   
      )
   );
   

}