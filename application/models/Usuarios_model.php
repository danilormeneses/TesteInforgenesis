<?php
//defined('BASEPATH') OR exit('No direct script access allowed'); 

class Usuarios_model extends CI_Model {
    
    private $conf =  array('table'=>'usuarios','id'=>'id');

    
    
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
       
    }
 


    public function get($id)
    {
        # code...
        $this->db->where(array($this->conf['id'] => $id));
        $query = $this->db->get($this->conf['table']);
        
        if ($query->num_rows() > 0) {
            # code...
            return $query->result();
        }

        return false;

    }




    public function getAll()
    {
        # code...
        $query = $this->db->get($this->conf['table']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    
    }




    public function getJoin($id = null)
    {

        if (is_null($id)) {
            # code...
            $id_user = $this->session->userdata('id_usuario');
            $this->db->select('tar_descricao, idtarefa, idtarefa_tipo, tip_descricao, usersname, tar_created');
            $this->db->join('users','users.idusers = tarefa.tar_users_id','inner');
            $this->db->join('tarefa_tipo','tarefa_tipo.idtarefa_tipo = tarefa.tar_tarefa_tipo_id','inner');
            $this->db->where(array('tar_users_id' => $id_user ));
            $query = $this->db->get($this->conf['table']);/* ->where($this->conf['id']); */
            
            if ($query->num_rows() > 0) {
                # code...
                foreach ($query->result() as $row) {
                    # code...
                    $response[] = $row;
                }
    
                return $response;
            }
    
            return false;
        } else {
            $id_user = $this->session->userdata('id_usuario');
            $this->db->select('tar_descricao, idtarefa, idtarefa_tipo, tip_descricao, usersname, tar_created');
            $this->db->join('users','users.idusers = tarefa.tar_users_id','inner');
            $this->db->join('tarefa_tipo','tarefa_tipo.idtarefa_tipo = tarefa.tar_tarefa_tipo_id','inner');
            $this->db->where(array($this->conf['id'] => $id));
            $this->db->where(array('tar_users_id' => $id_user ));
            $query = $this->db->get($this->conf['table']);
            
            if ($query->num_rows() > 0) {
                # code...
                foreach ($query->result() as $row) {
                    # code...
                    $response[] = $row;
                }
    
                return $response[0];
            }
    
            return false;
        }

    }



    public function getLike($data)
    {
        # code...
        $id_user = $this->session->userdata('id_usuario');
        $this->db->select('tar_descricao, idtarefa, idtarefa_tipo, tip_descricao, usersname, tar_created');
        $this->db->join('users','users.idusers = tarefa.tar_users_id','inner');
        $this->db->join('tarefa_tipo','tarefa_tipo.idtarefa_tipo = tarefa.tar_tarefa_tipo_id','inner');
        $this->db->like('tar_descricao',$data);
        $this->db->where(array('tar_users_id' => $id_user ));
        $query = $this->db->get($this->conf['table']);
        
        if ($query->num_rows() > 0) {
            # code...
            foreach ($query->result() as $row) {
                # code...
                $response[] = $row;
            }

            return $response;
        }

        return false;

    }





    public function save($data)
    {
        
        try {

            $this->db->insert($this->conf['table'],$data);
            return $this->db->insert_id();
        
        } catch (Exception $e) {

            return $e->getMessage();
        
        }
        
    }



    public function update($id, $data)
    {
        # code...
        try {

            $this->db->where($this->conf['id'], $id);
            $this->db->update($this->conf['table'], $data);
            return $this->db->affected_rows();
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    
    public function delete($id)
    {
        # code...
        try {

            $this->db->delete('mytable', array('id' => $id));
            return $this->db->affected_rows();

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}

