<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {


	public function __construct()
	{
		# code...
		parent::__construct();

		$this->load->model('Usuarios_model','users');

	}
	



	public function index()
	{
		$this->form_validation->set_rules('usersemail', 'Email', 'trim|required|min_length[10]|valid_email');
		$this->form_validation->set_rules('userssenha', 'Senha', 'trim|required|min_length[5]');
		
		if ($this->form_validation->run() ==  FALSE) {
			$data['erro'] =  validation_errors();	
		} else {
			
			$post = $this->input->post();
			$senha = senha_enc_dec($post['userssenha']);
			$user = $this->users->login($post['usersemail'], $senha);
			//var_dump($user)
			if ($user) {

				$usuario = $user;
				
				$user_session = array(
					'id_usuario' => $usuario->idusers,
					'nome' => $usuario->userssnome,
					'email' => $usuario->usersemail,
					'matricula' => $usuario->usersmatricula,
					'tipo' => $usuario->userstipo,
					'status' => $usuario->usersstatus,
					'created' => $usuario->userscreated,
					'logado' => TRUE
				);

				$this->session->set_userdata($user_session);
				header("location: home");
			}

			$data['erro'] = 'Email invalido ou senha incorreta';

		}
		
		if (!$this->users->getAll()) {
			# code..
			header("location: setup");
		}
		
		if ($this->session->userdata('id_usuario')) {
			# code...
			header("location: home");
		}

		$this->load->view('login',$data);
	}



	public function logOut()
	{
		# code...
		$this->session->sess_destroy();
		
		header('location: page');
		
	}




	public function home()
	{
		$this->load->view('home');
	}
	



	public function setup()
	{	
		$this->form_validation->set_rules('usersname', 'Nome', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('usersemail', 'Email', 'trim|required|min_length[10]|valid_email');
		$this->form_validation->set_rules('usersmatricula', 'Matricula', 'trim|required');
		$this->form_validation->set_rules('userssenha', 'Senha', 'trim|required|min_length[5]');
		
		if ($this->form_validation->run() ==  FALSE) {
			$data['erro'] =  validation_errors();	
		} else {
			
			$dataForm = $this->input->post();

			$dataForm['userssenha'] 	= senha_enc_dec($dataForm['userssenha']);
			$dataForm['userstipo'] 		= 1;
			$dataForm['usersstatus'] 	= 1;
			$dataForm['userscreated'] 	= date("Y-m-d h:m:s"); 

			$user = $this->users->save($dataForm);

			if (is_numeric($user) ) {
				header("location: page");
			}

		}
		# code...
		
		$this->load->view('setup',$data);
	}




	public function all()
	{
		# code...
		$users = $this->users->getAll();
		echo json_encode($users);
	}




	public function get($id)
	{
		$user = $this->users->get($id);
		echo json_encode($user);
	}



	public function newUser()
	{	
		$data['success'] = null;
		$data['erro'] = null;
		$this->form_validation->set_rules('usersname', 'Nome', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('usersemail', 'Email', 'trim|required|min_length[10]|valid_email');
		$this->form_validation->set_rules('userssenha', 'Senha', 'trim|required|min_length[5]');
		
		if ($this->form_validation->run() ==  FALSE) {
			$data['erro'] =  validation_errors();	
		} else {
			
			$dataForm = $this->input->post();

			$dataForm['userssenha'] 	= senha_enc_dec($dataForm['userssenha']);
			$dataForm['usersstatus'] 	= 1;
			$dataForm['userscreated'] 	= date("Y-m-d h:m:s"); 

			$user = $this->users->save($dataForm);

			if (is_numeric($user) ) {
				$data['success'] = "Cadastrado com sucesso";
			}

		}
		# code...
		$this->load->view('novo-usuario',$data);
	}
	



	public function usersEdit($id)
	{
		# code...
		$data['id'] = $id;
		$this->load->view('usuario-edit',$data);
	}



	public function usersEditSenha($id)
	{
		# code...
		$data['id'] = $id;
		$this->load->view('nova-senha-usuario', $data);
	}




	public function usersUpdate($id)
	{
		# code...
		$this->form_validation->set_rules('usersname', 'Nome', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('usersemail', 'Email', 'trim|required|min_length[10]|valid_email');
		
		if ($this->form_validation->run() == FALSE) {
			# code...
			echo json_encode(validation_errors());
		} else {
			# code...
			$post = $this->input->post();
			$user = $this->users->update($id, $post);

			if ($user != 0) {
				# code...
				echo json_encode($user);
			} else {
				echo json_encode('Erro na Atualização');
			}
		}
	}




	public function usersUpdateSenha($id)
	{
		# code...
		$this->form_validation->set_rules('userssenha', 'Senha', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('userspalavra_chave', 'Palavra Chave', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			# code...
			echo json_encode(validation_errors());

		} else {
			# code...
			$post = $this->input->post();
			
			if ($this->users->where('userspalavra_chave',$post['userspalavra_chave'])) {
				# code...
				$post['userssenha'] = senha_enc_dec($post['userssenha']);
				$user = $this->users->update($id, $post);
				echo json_encode($user);
				
			}else {
				$user = false;
				echo json_encode($user);
				
			}
			
		}

	}




	public function esqueciSenha()
	{
		$data['error'] = null;
		$data['success'] = null;
		$mailSenha	= geraSenha();
		$data['senha'] = $mailSenha;
		
		$this->form_validation->set_rules('usersemail', 'Email', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			# code...
			$data['error'] = validation_errors();
		} else {
			# code...
			$post = $this->input->post();
			$validate = $this->users->where('usersemail', $post['usersemail']);
			
			if ($validate) {
				# code...
				$senha = senha_enc_dec($mailSenha);
				$dataForm['userssenha'] = $senha;
				$user = $this->users->update($validate[0]->idusers, $dataForm);
				
				if ($user > 0) {
					# code...
					$config['smtp_host'] = 'ssl://smtp.gmail.com';
					$config['smtp_port'] = '465';
					$config['smtp_user'] = 'grupomastersendemail@gmail.com';
					$config['smtp_pass'] = 'master@qwerty';
					$config['protocol'] = 'smtp';
					$config['validate'] = TRUE;
					$config['mailtype'] = 'html';
					$config['charset'] = 'iso-8859-1';
					$config['newline'] = "\r\n";
	
					$this->load->library('email', $config);
					
					$this->email->from('grupomastersendemail@gmail.com');
					$this->email->to($post['usersemail']);
					$this->email->subject('Recuperar senha');
					$this->email->message($this->load->view('email-template',$data, TRUE));
					if ($this->email->send()) {
						$data['success'] = "Senha alterada verifique sua caixa de e-mails";	
					}
					else {
						print_r($this->email->print_debugger());
					}
	
				}
			} else {
				# code...
				$data['error'] = "Não foi possivel realizar a sua solicitação";
			}
		}
		



	$this->load->view('esqueci-senha', $data);
		


	}

}

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';
	$caracteres .= $lmin;

	if ($maiusculas) $caracteres .= $lmai;
	
	if ($numeros) $caracteres .= $num;
	
	if ($simbolos) $caracteres .= $simb;
	
	$len = strlen($caracteres);
	
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
}
