<?php 

use Firebase\JWT\JWT;
class Controller_Users extends Controller_Rest

{

    private $key = "ipj7t676dh`0h9g9807gr6d635ws5u6dfgp98i0h8y";

    public function post_create()
    {
        try {
            if ( ! isset($_POST['name']) or ! isset($_POST['pass']) or $_POST['name'] == "" or $_POST['pass'] == "") 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'incorrect parameters'
                ));

                return $json;
            }

            $input = $_POST;
            $user = new Model_users();
            $user->name = $input['name'];
            $user->pass = $input['pass'];
            $user->save();

            $json = $this->response(array(
                'code' => 200,
                'message' => 'user registered succesfully!',
                'data' => $input['name']
            ));

            return $json;

        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
            ));

            return $json;
        }

        
    }

    public function get_users()
    {
      $users = Model_users::find('all');

      return $this->response(Arr::reindex($users));
    }

    public function post_delete()
    {
        $user = Model_users::find($_POST['id']);
        $userName = $user->name;
        $user->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'usuario borrado',
            'name' => $userName
        ));

        return $json;
    }

    public function get_login()
    {
        try {
        $users = Model_users::find('all', array(
            'where' => array(
                array('name', $_GET['name']),
                array('pass', $_GET['pass']),
            ),
        ));
       
        if (empty($users)) {
               $json = $this->response(array(
                    'code' => 400,
                    'data' => []
                ));
               return $json;
            }
        
        foreach ($users as $key => $user) {
            $token = array(
                    'id'  => $user->id,
                    'name' => $user->name,
                    'pass' => $user->pass
                );
                
            }
            
            $token = JWT::encode($users, $key);

            $json = $this->response(array(
                    'code' => 200,
                    'message' => 'user logged',
                    'data' => array(
                        'token' => $token,
                        'username' => $token['name']   
                    )
                ));
            return $json;

            
        }
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'internal server error',
            ));

            return $json;
        }
    }
}