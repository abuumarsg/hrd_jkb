<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Learning extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();

		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			$this->admin = $this->session->userdata('emp')['id'];
		}
		$this->rando = $this->codegenerator->getPin(6,'number');		
		$dtroot['admin']=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$datax['adm'] = array(
			'nama'=>$dtroot['admin']['nama'],
			'email'=>$dtroot['admin']['email'],
			'kelamin'=>$dtroot['admin']['kelamin'],
			'foto'=>$dtroot['admin']['foto'],
			'create'=>$dtroot['admin']['create_date'],
			'update'=>$dtroot['admin']['update_date'],
			'login'=>$dtroot['admin']['last_login'],
			'level'=>$dtroot['admin']['level'],
			'kode_bagian'=>$dtroot['admin']['kode_bagian'],
			'list_bagian'=>$dtroot['admin']['list_bagian'],
			// 'list_bagian'=>(isset($dtroot['admin']['list_bagian']) ? $dtroot['admin']['list_bagian']: null),
		);
		$this->dtroot=$datax;
	}
	function index(){
		redirect('pages/dashboard');
	}
	//======================================= MATERI ==========================================//
	public function getMateriLearning()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_learning->getListMateriLearning();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$bagian = $this->otherfunctions->getDataExplode($d->bagian, ';', 'all');
					$url = base_url('pages/view_file_materi_learning/'.$this->codegenerator->encryptChar($d->kode));
					$fileMateri = (!empty($d->jumlah_materi)?'<a href="'.$url.'">'.$d->jumlah_materi.' File Materi</a>':'<a href="'.$url.'" type="button" class="btn btn-success btn-xs">Tambah File Materi</a>');
					$sp = (!empty($d->jumlah_soal_project)?'<br><label class="label label-success">'.$d->jumlah_soal_project .' Soal Project</label>':null);
					$datax['data'][]=[
						$d->id,
						'<a href="'.base_url('pages/view_soal_learning/'.$this->codegenerator->encryptChar($d->kode)).'">'.$d->kode.'</a>',
						$d->nama,
						(!empty($d->jumlah_soal)?'<label class="label label-info">'.$d->jumlah_soal .' Data Soal</label>':$this->otherfunctions->getMark($d->jumlah_soal)).$sp,
						$fileMateri,
						'<a href="javascript:void(0)" onclick=view_modal_bagian('.$d->id.')>'.count($bagian).' Bagian</a>',
						$d->waktu.' Menit',
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$kode = $this->input->post('kode');
				if(empty($kode)){
					$id = $this->input->post('id');
					$data=$this->model_learning->getListMateriLearning(['a.id'=>$id]);
				}else{					
					$data=$this->model_learning->getListMateriLearning(['a.kode'=>$kode]);
				}
				foreach ($data as $d) {
  					$dataSoal=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$d->kode]);
					$tabel='';
					$tabel.='<hr><h4 align="center"><b>Data Soal Untuk Materi '.$d->nama.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th>No.</th>
									<th>Soal</th>
									<th>Jawaban</th>
								</tr>
							</thead>
							<tbody>';
							if(count($dataSoal) != null){
								$no=1;
								foreach ($dataSoal as $tg) {
									if($tg->correct_answer == 'A'){
										$jawaban = '<b>(A)</b> '.$tg->choice_a;
									}elseif($tg->correct_answer == 'B'){
										$jawaban = '<b>(B)</b> '.$tg->choice_b;
									}elseif($tg->correct_answer == 'C'){
										$jawaban = '<b>(C)</b> '.$tg->choice_c;
									}elseif($tg->correct_answer == 'D'){
										$jawaban = '<b>(D)</b> '.$tg->choice_d;
									}elseif($tg->correct_answer == 'E'){
										$jawaban = '<b>(E)</b> '.$tg->choice_e;
									}else{
										$jawaban = null;
									}
									$tabel.='<tr>
										<td>'.$no.'</td>
										<td>'.$tg->soal.'</td>
										<td>'.$jawaban.'</td>
									</tr>';
									$no++;
								}
							}else{
								$tabel.='<tr>
									<td colspan="3" class="text-center"><b>Tidak Ada Soal</b></td>
								</tr>';
							}
							$tabel.='</tbody>
						</table></div>';
					$bagian_edit = $this->otherfunctions->getDataExplode($d->bagian, ';', 'all');
					$dataBagianEdit = [];
					if(!empty($bagian_edit))
					foreach ($bagian_edit as $dx => $value) {
						$dataBagianEdit[] = $value;
					}
					$datax=[
						'id'=>$d->id,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'waktu'=>$d->waktu,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tabel'=>$tabel,
						'bagian_edit'=>$dataBagianEdit,
						'waktu'=>$d->waktu,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one_bagian') {
				$id = $this->input->post('id');
				$data=$this->model_learning->getListMateriLearning(['a.id'=>$id]);
				foreach ($data as $d) {
					$bagian = $this->otherfunctions->getDataExplode($d->bagian, ';', 'all');
					$tabel='';
					$tabel.='<hr><h4 align="center"><b>Data Soal Untuk Materi '.$d->nama.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th>No.</th>
									<th>Bagian</th>
									<th>Lokasi</th>
								</tr>
							</thead>
							<tbody>';
							if(count($bagian) != null){
								$no=1;
								foreach ($bagian as $key => $value) {
									$bag = $this->model_master->getBagianRow(null, ['a.kode_bagian'=>$value]);
									$tabel.='<tr>
										<td>'.$no.'</td>
										<td>'.$bag['nama'].'</td>
										<td>'.$bag['nama_loker'].'</td>
									</tr>';
									$no++;
								}
							}else{
								$tabel.='<tr>
									<td colspan="2" class="text-center"><b>Tidak Ada Soal</b></td>
								</tr>';
							}
							$tabel.='</tbody>
						</table></div>';
					$datax=[
						'id'=>$d->id,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'tabel_bagian'=>$tabel,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeMateriSelfLearning();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_setting_materi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		$nama=$this->input->post('nama');
		$bagian=$this->input->post('bagian');
		$time=$this->input->post('time');
		if (isset($kode)) {
			$data=array(
				'kode'=>$kode,
				'nama'=>$nama,
				'bagian'=> implode(';', $bagian),
				'waktu'=>$time,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax=$this->model_global->insertQuery($data,'learn_materi');
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_setting_materi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$kode_old=$this->input->post('kode_old');
		$kode=$this->input->post('kode');
		$nama=$this->input->post('nama');
		$bagian=$this->input->post('bagian');
		$time=$this->input->post('time');
		if (isset($kode)) {
			$data=array(
				'kode'=>$kode,
				'nama'=>$nama,
				'bagian'=> implode(';', $bagian),
				'waktu'=>$time,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax=$this->model_global->updateQuery($data,'learn_materi', ['id'=>$id]);
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//===================== DATA SOAL ==============================
	public function getSoal(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kode = $this->input->post('kode');
				$data=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$kode]);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id,
						(empty($d->file) ? '<label class="label label-danger">Tidak Ada File</label>' : '<embed src="'.base_url($d->file).'" width="100%" max-height="120"></embed>'),
						$d->soal,
						$d->choice_a,
						$d->choice_b,
						$d->choice_c,
						$d->choice_d,
						$d->choice_e,
						$d->correct_answer,
						// $d->jenis_soal,
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_learning->getListSoalLearning(['a.id'=>$id], true);				
				$fileView = '<embed src="'.base_url($data['file']).'" width="100%" max-height="1200" height="800"></embed>';
				$datax = [];
				$datax=[
					'id'=>$data['id'],
					'kode'=>$data['kode'],
					'kode_materi'=>$data['kode_materi'],
					'nama'=>$data['nama_materi'],
					'file'=>(empty($data['file']) ? '<label class="label label-danger">Tidak Ada File</label>' : '<embed src="'.base_url($data['file']).'" width="60%"></embed><hr>'),
					'data_file'=>$data['file'],
					'soal'=>$data['soal'],
					'choice_A'=>$data['choice_a'],
					'choice_B'=>$data['choice_b'],
					'choice_C'=>$data['choice_c'],
					'choice_D'=>$data['choice_d'],
					'choice_E'=>$data['choice_e'],
					'correct_answer'=>$data['correct_answer'],
					'jenis_soal'=>$data['jenis_soal'],
					'status'=>$data['status'],
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($data['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($data['update_date']),
					'create_by'=>$data['create_by'],
					'update_by'=>$data['update_by'],
					'nama_buat'=>(!empty($data['nama_buat'])) ? $data['nama_buat']:$this->otherfunctions->getMark($data['nama_buat']),
					'nama_update'=>(!empty($data['nama_update']))?$data['nama_update']:$this->otherfunctions->getMark($data['nama_update']),
				];
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeSoalSelfLearning();
        		echo json_encode($data);
			}elseif ($usage == 'select_correct_answer') {
				$data = $this->otherfunctions->getChoiceList();
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($data as $key => $value) {
					$pack[$key]=$value;
				}
        		echo json_encode($pack);
			}elseif ($usage == 'select_jenis_materi') {
				$data = $this->otherfunctions->getJenisMateriList();
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($data as $key => $value) {
					$pack[$key]=$value;
				}
        		echo json_encode($pack);
			}elseif ($usage == 'select_materi') {
				$data=$this->model_learning->getListMateriLearning();
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($data as $key) {
					$pack[$key->kode]=$key->nama;
				}
        		echo json_encode($pack);
			}elseif ($usage == 'view_all_soal_project') {
				$kode = $this->input->post('kode');
				$data=$this->model_learning->getListSoalProjectLearning(['a.kode_materi'=>$kode]);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id,
						$d->kode,
						$d->soal_project,
						$d->keterangan,
						$properties['status2'],
						$properties['info2'].$properties['delete2'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one_project') {
				$id = $this->input->post('id');
				$data=$this->model_learning->getListSoalProjectLearning(['a.id'=>$id], true);
				$datax = [];
				$datax=[
					'id'=>$data['id'],
					'kode'=>$data['kode'],
					'kode_materi'=>$data['kode_materi'],
					'nama'=>$data['nama_materi'],
					'soal'=>$data['soal_project'],
					'keterangan'=>$data['keterangan'],
					'status'=>$data['status'],
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($data['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($data['update_date']),
					'create_by'=>$data['create_by'],
					'update_by'=>$data['update_by'],
					'nama_buat'=>(!empty($data['nama_buat'])) ? $data['nama_buat']:$this->otherfunctions->getMark($data['nama_buat']),
					'nama_update'=>(!empty($data['nama_update']))?$data['nama_update']:$this->otherfunctions->getMark($data['nama_update']),
				];
				echo json_encode($datax);
			}elseif ($usage == 'kodeSoalProject') {
				$data = $this->codegenerator->kodeSoalProjectSelfLearning();
				echo json_encode($data);
			}
		}
	}
	public function add_soal(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode_materi = $this->input->post('kode_materi');
		$kode_soal = $this->input->post('kode_soal');
		$soal = $this->input->post('soal');
		$choice_a = $this->input->post('choice_a');
		$choice_b = $this->input->post('choice_b');
		$choice_c = $this->input->post('choice_c');
		$choice_d = $this->input->post('choice_d');
		$choice_e = $this->input->post('choice_e');
		$correct_answer = $this->input->post('correct_answer');
		$jenis_soal=$this->input->post('jenis_soal');
		if (isset($kode_soal)) {
			if(empty($_FILES['file']['name'])){
				$data=array(
					'kode'=>$kode_soal,
					'kode_materi'=>$kode_materi,
					'soal'=>$soal,
					'choice_a'=>$choice_a,
					'choice_b'=>$choice_b,
					'choice_c'=>$choice_c,
					'choice_d'=>$choice_d,
					'choice_e'=>$choice_e,
					'correct_answer'=>$correct_answer,
					// 'jenis_soal'=>$jenis_soal,
				);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax=$this->model_global->insertQuery($data,'learn_soal');
			}else{
				$other=array(
					'kode'=>$kode_soal,
					'kode_materi'=>$kode_materi,
					'soal'=>$soal,
					'choice_a'=>$choice_a,
					'choice_b'=>$choice_b,
					'choice_c'=>$choice_c,
					'choice_d'=>$choice_d,
					'choice_e'=>$choice_e,
					'correct_answer'=>$correct_answer,
					'jenis_soal'=>$jenis_soal,
				);
				$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
				$data=[
					'post'=>'file',
					'data_post'=>$this->input->post('file', TRUE),
					'table'=>'learn_soal',
					'column'=>'file', 
					'usage'=>'insert',
					'otherdata'=>$other,
				];
				$newName = $kode_soal.'_'.strtotime($this->date);
				$datax=$this->filehandler->doUpload($data,'self_learning', null, $newName);
			}
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_soal(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if (isset($id)) {
			if(empty($_FILES['file']['name'])){
				$data=array(
					'kode_materi'=>$this->input->post('select_materi'),
					'soal'=>$this->input->post('soal'),
					'choice_a'=>$this->input->post('choice_a'),
					'choice_b'=>$this->input->post('choice_b'),
					'choice_c'=>$this->input->post('choice_c'),
					'choice_d'=>$this->input->post('choice_d'),
					'choice_e'=>$this->input->post('choice_e'),
					'correct_answer'=>$this->input->post('correct_answer'),
				);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax=$this->model_global->updateQuery($data,'learn_soal', ['id'=>$id]);
			}else{
				$other = array(
					// 'id'=>$this->input->post('id'),
					'kode_materi'=>$this->input->post('select_materi'),
					'soal'=>$this->input->post('soal'),
					'choice_a'=>$this->input->post('choice_a'),
					'choice_b'=>$this->input->post('choice_b'),
					'choice_c'=>$this->input->post('choice_c'),
					'choice_d'=>$this->input->post('choice_d'),
					'choice_e'=>$this->input->post('choice_e'),
					'correct_answer'=>$this->input->post('correct_answer'),
					// 'jenis_soal'=>$this->input->post('jenis_soal'),
				);
				$kode_soal = $this->input->post('kode');
				// $fileOld = $this->input->post('file_old');
				$other = array_merge($other,$this->model_global->getCreateProperties($this->admin));
				$data=[
					'post'=>'file',
					'data_post'=>$this->input->post('file', TRUE),
					'table'=>'learn_soal',
					'column'=>'file', 
					'usage'=>'update',
					'otherdata'=>$other,
					'where'=>['id'=>$id],
					'unlink'=>'yes',
				];
				$newName = $kode_soal.'_'.strtotime($this->date);
				$datax=$this->filehandler->doUpload($data,'self_learning', null, $newName);
			}
				// echo '<pre>';
				// print_r($kode_soal);
				// print_r($fileOld);
				// print_r($_FILES);
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function add_soal_project(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode_soal_project = $this->input->post('kode_soal_project');
		$kode_materi = $this->input->post('kode_materi');
		$soal = $this->input->post('soal');
		$ket = $this->input->post('ket');
		if (isset($kode_soal_project)) {
			$data=array(
				'kode'=>$kode_soal_project,
				'kode_materi'=>$kode_materi,
				'soal_project'=>$soal,
				'keterangan'=>$ket,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax=$this->model_global->insertQuery($data,'learn_soal_project');
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_soal_project(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if (isset($id)) {
			$data=array(
				'kode_materi'=>$this->input->post('select_materi'),
				'soal_project'=>$this->input->post('soal'),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax=$this->model_global->updateQuery($data,'learn_soal_project', ['id'=>$id]);
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//===================== DATA FILE ==============================
	public function getFileMateriLearning(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kode = $this->input->post('kode');
				$data=$this->model_learning->getListFileMateriLearning(['a.kode_materi'=>$kode]);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$ext = explode('.', $d->file);
					$viewFile = '<button type="button" class="btn btn-success btn-xs" href="javascript:void(0)" onclick="view_modal_file('.$d->id.', \''.$ext[1].'\')"><i class="fa fa-eye" data-toggle="tooltip" title="Lihat File"></i> Lihat File</button> ';
					$fileView = str_replace('asset/file/self_learning/', '', $d->file);
					$datax['data'][]=[
						$d->id,
						$d->kode,
						$d->nama,
						$viewFile.'<br>'.$fileView,
						$d->keterangan,
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_learning->getListFileMateriLearning(['a.id'=>$id], true);
				$fileView = null;
				if(!empty($data['file'])){
					$fileView = $this->otherfunctions->getPreviewFileLearning($data['file']);
				}
				$datax = [];
				$datax=[
					'id'=>$data['id'],
					'kode'=>$data['kode'],
					'kode_materi'=>$data['kode_materi'],
					'nama'=>$data['nama'],
					'file'=>$data['file'],
					'keterangan'=>$data['keterangan'],
					'fileView'=>$fileView,
					'status'=>$data['status'],
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($data['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($data['update_date']),
					'create_by'=>$data['create_by'],
					'update_by'=>$data['update_by'],
					'nama_buat'=>(!empty($data['nama_buat'])) ? $data['nama_buat']:$this->otherfunctions->getMark($data['nama_buat']),
					'nama_update'=>(!empty($data['nama_update']))?$data['nama_update']:$this->otherfunctions->getMark($data['nama_update']),
				];
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeFileSelfLearning();
        		echo json_encode($data);
			}elseif ($usage == 'select_correct_answer') {
				$data = $this->otherfunctions->getChoiceList();
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($data as $key => $value) {
					$pack[$key]=$value;
				}
        		echo json_encode($pack);
			}elseif ($usage == 'select_jenis_materi') {
				$data = $this->otherfunctions->getJenisMateriList();
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($data as $key => $value) {
					$pack[$key]=$value;
				}
        		echo json_encode($pack);
			}elseif ($usage == 'select_materi') {
				$data=$this->model_learning->getListMateriLearning();
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($data as $key) {
					$pack[$key->kode]=$key->nama;
				}
        		echo json_encode($pack);
			}
		}
	}
	public function add_FileMateriLearning(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode_file');
		if ($kode != "") {
			$other=[
				'kode'=>strtoupper($kode),
				'kode_materi'=>$this->input->post('kode_materi'),
				'nama'=>ucwords($this->input->post('nama')),
				'keterangan'=>$this->input->post('keterangan'),
			];
			$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
			$data=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
				'table'=>'learn_file_materi',
				'column'=>'file', 
				'usage'=>'insert',
				'otherdata'=>$other,
			];
			$newName = $kode.'_'.strtotime($this->date);
			$datax=$this->filehandler->doUpload($data,'self_learning', null, $newName);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_FileMateriLearning(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if (isset($id)) {
			$data=array(
				// 'id'=>$this->input->post('id'),
				'kode_materi'=>$this->input->post('select_materi'),
				'soal'=>$this->input->post('soal'),
				'choice_a'=>$this->input->post('choice_a'),
				'choice_b'=>$this->input->post('choice_b'),
				'choice_c'=>$this->input->post('choice_c'),
				'choice_d'=>$this->input->post('choice_d'),
				'choice_e'=>$this->input->post('choice_e'),
				'correct_answer'=>$this->input->post('correct_answer'),
				'jenis_soal'=>$this->input->post('jenis_soal'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax=$this->model_global->updateQuery($data,'learn_soal', ['id'=>$id]);
		}
		if (isset($datax)) {
			$datax=$datax;	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//======================================= MATERI BAGIAN ==========================================//
	public function getMateriLearningBagian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kodeMateri = [];
				$bagian = explode(';', $this->dtroot['adm']['list_bagian']);
				$dataMateri=$this->model_learning->getListMateriLearning(['a.status'=>1]);
				if(!empty($dataMateri)){
					foreach ($dataMateri as $dm) {
						if(!empty($dm->bagian)){
							$bagianDM = explode(';', $dm->bagian);
							if(!empty($bagian)){
								foreach ($bagian as $k => $value) {
									if(in_array($value, $bagianDM)){
										$kodeMateri[] = $dm->kode;
									}
								}
							}else{
								$kodeMateri[] = $dm->kode;
							}
						}
					}
				}
				if(!empty($kodeMateri)){
					$wherex='';
					$c_lv=1;
					foreach ($kodeMateri as $m => $kode) {
						$wherex.="a.kode='".$kode."'";
						if (count($kodeMateri) > $c_lv) {
							$wherex.=' OR ';
						}
						$c_lv++;
					}
					$dataMateri=$this->model_learning->getListMateriLearning($wherex);
				}else{
					$dataMateri=$this->model_learning->getListMateriLearning(['a.status'=>'23']);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($dataMateri as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$bagian = $this->otherfunctions->getDataExplode($d->bagian, ';', 'all');
					$sp = (!empty($d->jumlah_soal_project)?'<br><label class="label label-success">'.$d->jumlah_soal_project .' Soal Project</label>':null);
					$datax['data'][]=[
						$d->id,
						$d->nama,
						(!empty($d->jumlah_soal)?'<label class="label label-info">'.$d->jumlah_soal .' Data Soal</label>':$this->otherfunctions->getMark($d->jumlah_soal)).$sp,
						'<a href="javascript:void(0)" onclick="view_modal_file(\''.$d->kode.'\')">'.$d->jumlah_materi.' File Materi</a>',
						// '<a href="javascript:void(0)" onclick=view_modal_bagian('.$d->id.')>'.count($bagian).' Bagian</a>',
						$d->waktu.' Menit',
						$properties['info'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$kode = $this->input->post('kode');
				if(empty($kode)){
					$id = $this->input->post('id');
					$data=$this->model_learning->getListMateriLearning(['a.id'=>$id]);
				}else{					
					$data=$this->model_learning->getListMateriLearning(['a.kode'=>$kode]);
				}
				foreach ($data as $d) {
  					$dataSoal=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$d->kode]);
					$dataP=$this->model_learning->getListSoalProjectLearning(['a.kode_materi'=>$d->kode]);
					$tabel='';
					$tabel.='<hr><h4 align="center"><b>Data Soal Untuk Materi '.$d->nama.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
							<table class="table table-bordered table-striped data-table">
								<thead>
									<tr class="bg-blue">
										<th>No.</th>
										<th>Soal</th>
										<th>Jawaban</th>
									</tr>
								</thead>
								<tbody>';
								if(count($dataSoal) != null){
									$no=1;
									foreach ($dataSoal as $tg) {
										if($tg->correct_answer == 'A'){
											$jawaban = '<b>(A)</b> '.$tg->choice_a;
										}elseif($tg->correct_answer == 'B'){
											$jawaban = '<b>(B)</b> '.$tg->choice_b;
										}elseif($tg->correct_answer == 'C'){
											$jawaban = '<b>(C)</b> '.$tg->choice_c;
										}elseif($tg->correct_answer == 'D'){
											$jawaban = '<b>(D)</b> '.$tg->choice_d;
										}elseif($tg->correct_answer == 'E'){
											$jawaban = '<b>(E)</b> '.$tg->choice_e;
										}else{
											$jawaban = null;
										}
										$tabel.='<tr>
											<td>'.$no.'</td>
											<td>'.$tg->soal.'</td>
											<td>'.$jawaban.'</td>
										</tr>';
										$tabel.='<tr>
											<td></td>
											<td>'.(empty($tg->file) ? '<label class="label label-danger">Tidak Ada File</label>' : '<embed src="'.base_url($tg->file).'" width="40%"></embed><hr>').'</td>
											<td></td>
										</tr>';
										$tabel.='<tr>
											<td></td>
											<td><b>(A)</b> '.$tg->choice_a.'</td>
											<td></td>
										</tr>';
										$tabel.='<tr>
											<td></td>
											<td><b>(B)</b> '.$tg->choice_b.'</td>
											<td></td>
										</tr>';
										$tabel.='<tr>
											<td></td>
											<td><b>(C)</b> '.$tg->choice_c.'</td>
											<td></td>
										</tr>';
										$tabel.='<tr>
											<td></td>
											<td><b>(D)</b> '.$tg->choice_d.'</td>
											<td></td>
										</tr>';
										$tabel.='<tr>
											<td></td>
											<td><b>(E)</b> '.$tg->choice_e.'</td>
											<td></td>
										</tr>';
										$no++;
									}
								}else{
									$tabel.='<tr>
										<td colspan="3" class="text-center"><b>Tidak Ada Soal</b></td>
									</tr>';
								}
								$tabel.='</tbody>
							</table>
						</div><hr>
						<h4 align="center"><b>Soal Project</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th>No.</th>
									<th>Soal</th>
								</tr>
							</thead>
							<tbody>';
							if(count($dataP) != null){
								$nox=1;
								foreach ($dataP as $tp) {
									$tabel.='<tr>
										<td>'.$nox.'</td>
										<td>'.$tp->soal_project.'</td>
									</tr>';
									$nox++;
								}
							}else{
								$tabel.='<tr>
									<td colspan="2" class="text-center"><b>Tidak Ada Soal</b></td>
								</tr>';
							}
							$tabel.='</tbody>
						</table></div>';
					$bagian_edit = $this->otherfunctions->getDataExplode($d->bagian, ';', 'all');
					$dataBagianEdit = [];
					if(!empty($bagian_edit))
					foreach ($bagian_edit as $dx => $value) {
						$dataBagianEdit[] = $value;
					}
					$datax=[
						'id'=>$d->id,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'waktu'=>$d->waktu,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tabel'=>$tabel,
						'bagian_edit'=>$dataBagianEdit,
						'waktu'=>$d->waktu,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one_file') {
				$kode = $this->input->post('kode');
				$dm=$this->model_learning->getListMateriLearning(['a.kode'=>$kode],true);
				$data=$this->model_learning->getListFileMateriLearning(['a.kode_materi'=>$kode]);
				$tabel='';
				$tabel.='<h4 align="center"><b>Data File Materi '.$dm['nama'].'</b></h4>
					<div style="max-height: 400px; overflow: auto;">
					<table class="table table-bordered table-striped data-table">
						<thead>
							<tr class="bg-blue">
								<th>No.</th>
								<th>Nama</th>
								<th>File</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>';
						if(!empty($data)){
							$no=1;
							foreach ($data as $d) {
								$fileView = str_replace('asset/file/self_learning/', '', $d->file);
								$tabel.='<tr>
									<td>'.$no.'</td>
									<td>'.$d->nama.'</td>
									<td><a href="'.base_url($d->file).'" target="blank">'.$fileView.'</a></td>
									<td>'.$d->keterangan.'</td>
								</tr>';
								$no++;
							}
						}else{
							$tabel.='<tr>
								<td colspan="4" class="text-center"><b>Tidak Ada File Materi</b></td>
							</tr>';
						}
						$tabel.='</tbody>
					</table></div>';
				$datax=[
					'tabel_file'=>$tabel,
				];
				echo json_encode($datax);
			}elseif ($usage == 'select_materi_bagian'){
				$kodeMateri = [];
				$bagian = explode(';', $this->dtroot['adm']['list_bagian']);
				$dataMateri=$this->model_learning->getListMateriLearning(['a.status'=>1]);
				if(!empty($dataMateri)){
					foreach ($dataMateri as $dm) {
						if(!empty($dm->bagian)){
							$bagianDM = explode(';', $dm->bagian);
							if(!empty($bagian)){
								foreach ($bagian as $k => $value) {
									if(in_array($value, $bagianDM)){
										$kodeMateri[] = $dm->kode;
									}
								}
							}else{
								//all materi;
							}
						}
					}
				}
				if(!empty($kodeMateri)){
					$wherex='';
					$c_lv=1;
					foreach ($kodeMateri as $m => $kode) {
						$wherex.="a.kode='".$kode."'";
						if (count($kodeMateri) > $c_lv) {
							$wherex.=' OR ';
						}
						$c_lv++;
					}
					$dataMateri=$this->model_learning->getListMateriLearning($wherex);
				}else{
					$dataMateri=$this->model_learning->getListMateriLearning(['a.status'=>'23']);
				}
				$pack=[];
				$pack[null]='Pilih Data';
				foreach ($dataMateri as $key) {
					$pack[$key->kode]=$key->nama;
				}
        		echo json_encode($pack);
			}elseif ($usage == 'select_karyawan_bagian'){
				$employee = '';
				$bagian = explode(';', $this->dtroot['adm']['list_bagian']);
				if(!empty($bagian)){
					$wherex='';
					$c_lv=1;
					foreach ($bagian as $m => $kode) {
						$wherex.="jbt.kode_bagian='".$kode."'";
						if (count($bagian) > $c_lv) {
							$wherex.=' OR ';
						}
						$c_lv++;
					}
					$where = 'emp.status_emp = 1 AND ('.$wherex.')';
					$employee=$this->model_karyawan->getEmployeeWhere($where);
				}
				$pack[null]='Pilih Data';
				$pack=[];
				foreach ($employee as $key) {
					$pack[$key->id_karyawan]=$key->nama.' - '.$key->nama_jabatan;
				}
        		echo json_encode($pack);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	//======================================= SETTING MATERI PELATIHAN BAGIAN ==========================================//
	public function getLearningKaryawanBagian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $form);
				$param = $this->input->post('param');
				$materi = (!empty($form['materi']) ? ['a.kode_materi'=>$form['materi']] : []);
				$karyawan = (!empty($form['karyawan']) ? ['a.id_karyawan'=>$form['karyawan']] : []);
				$sStatus = [];
				if(isset($form['status'])){
					if($form['status'] == 'BELUM DIKIRIM'){
						$sStatus = ['a.status_materi'=>'BELUM DIKIRIM'];
					}elseif($form['status'] == 'PROSES'){
						$sStatus = ['a.status_materi'=>'SUDAH DIKIRIM'];
					}elseif($form['status'] == 'BELUM DINILAI'){
						$sStatus = ['a.status_materi'=>'BELUM DINILAI'];
					}elseif($form['status'] == 'SELESAI'){
						$sStatus = ['a.status_materi'=>'SELESAI'];
					}else{
						$sStatus = [];
					}
				}
				$whereArray = array_merge($materi, $karyawan, $sStatus);
				$listEmployee = '';
				$bagian = explode(';', $this->dtroot['adm']['list_bagian']);
				if(!empty($bagian)){
					$wherex='';
					$c_lv=1;
					foreach ($bagian as $m => $kode) {
						$wherex.="jbt.kode_bagian='".$kode."'";
						if (count($bagian) > $c_lv) {
							$wherex.=' OR ';
						}
						$c_lv++;
					}
					$where = 'emp.status_emp = 1 AND ('.$wherex.') AND status_materi != "SELESAI"';
					// $where = 'emp.status_emp = 1 AND ('.$wherex.')';
					$listEmployee=$this->model_learning->getListLearningKaryawan($where, false, 'a.target_tanggal desc', $whereArray);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($listEmployee as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$send = '<button type="button" class="btn btn-success btn-sm"  href="javascript:void(0)" onclick=sendToKaryawan('.$d->id.')><i class="fas fa-paper-plane" data-toggle="tooltip" title="Kirim Pelatihan"></i></button> ';				
					$statusSend =(($d->status_materi == 'BELUM DIKIRIM') ? $send : null );
					$nilai = '<button type="button" class="btn btn-warning btn-sm"  href="javascript:void(0)" onclick=nilaiLearning('.$d->id.')><i class="fas fa-receipt" data-toggle="tooltip" title="Nilai Learning"></i></button> ';				
					$sNilai =(($d->status_materi == 'BELUM DINILAI') ? $nilai : null );
					$datax['data'][]=[
						$d->id,
						$d->nama_karyawan,
						$d->nama_materi,
						$this->formatter->getDateFormatUser($d->target_tanggal),
						((!empty($d->nilai_pretest) || $d->nilai_pretest == '0') ? $d->nilai_pretest : '<label class="label (label-danger">Belum Ada Nilai</label>'),
						((!empty($d->nilai_postest) || $d->nilai_postest == '0') ? $d->nilai_postest : '<label class="label label-danger">Belum Ada Nilai</label>'),
						((!empty($d->nilai_project) || $d->nilai_project == '0') ? $d->nilai_project : '<label class="label label-danger">Belum Ada Nilai</label>'),
						(!empty($d->status_project) ? $d->status_project : '<label class="label label-danger">Belum Ada Nilai</label>'),
						$d->status_materi,
						$statusSend.$properties['info'].$sNilai,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$le=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
				$iProject = $this->model_learning->getListSoalProjectLearning(['a.kode_materi'=>$le['kode_materi']], false, 'a.id desc');
				$tabel='';
				$tabel.='<h4 align="center"><b>Data Soal Project Untuk Materi '.$le['nama_materi'].'</b></h4>
					<div style="max-height: 400px; overflow: auto;">
					<table class="table table-bordered table-striped data-table">
						<thead>
							<tr class="bg-blue">
								<th width="5%">No.</th>
								<th>Project</th>
								<th width="20%">Penilaian Anda</th>
							</tr>
						</thead>
						<tbody>';
						if(count($iProject) != null){
							$no=1;
							foreach ($iProject as $p) {
								$jwbn = $this->model_learning->getJawabanProjectEmp(['a.kode_project'=>$p->kode, 'a.id_karyawan'=>$le['id_karyawan']], true, 'a.id desc');
								$tabel.='<tr>
								<form id="form_nilai'.$p->id.'">
									<td rowspan="3">'.$no.'.</td>
									<td>'.$p->soal_project.'</td>
									<td rowspan="3">
										<input type="hidden" name="id_soal" id="id_soal_'.$p->id.'" value="'.$p->id.'">
										<input type="hidden" name="id_jawaban" id="id_jawaban_'.$p->id.'" value="'.$jwbn['id'].'">
										<input type="hidden" name="kode_materi" id="kode_materi_'.$p->id.'" value="'.$p->kode_materi.'">
										<input type="hidden" name="id_karyawan" id="id_karyawan_'.$p->id.'" value="'.$le['id_karyawan'].'">
										<input type="text" name="inputnilai" id="inputnilai_'.$p->id.'" value="'.$jwbn['nilai'].'" class="form-control"><br>
								</form>
										<button type="button" class="btn btn-success pull-right" onclick="myFunction('.$p->id.')"><i class="fa fa-floppy-o"></i> Simpan</button>
									</td>
								</tr>
								<tr>
									<td><b><u>Jawaban :</u></b><br>'.$jwbn['jawaban'].'</td>
								</tr>
								<tr>
									<td>';
									if(!empty($jwbn['file'])){
										// $tabel.= '<a type="button" href="'.base_url($jwbn['file']).'" target="blank" class="btn btn-success"><i class="fa fa-eye"></i> View File</a>';
										$tabel.= '<button type="button" class="btn btn-success btn-xs" href="javascript:void(0)" onclick="view_modal_file('.$jwbn['id'].')"><i class="fa fa-eye" data-toggle="tooltip" title="Lihat File"></i> View File</button> ';
									}
								$tabel.='</td>
								</tr>';
								$no++;
							}
							// <textarea name="inputnilai" id="inputnilaia_'.$p->id.'" class="form-control input-money" placeholder="Nilai" rows="5"></textarea>
						}else{
							$tabel.='<tr>
								<td colspan="3" class="text-center"><b>Tidak Ada Soal</b></td>
							</tr>';
						}
						$tabel.='</tbody>
					</table></div>';
				$datax=[
					'id'=>$le['id'],
					'kode'=>$le['kode_materi'],
					'id_karyawan'=>$le['id_karyawan'],
					'tanggal_target'=>$this->formatter->getDateFormatUser($le['target_tanggal']),
					'nama'=>$le['nama_materi'],
					'nama_karyawan'=>$le['nama_karyawan'],
					'pretest'=>$le['nilai_pretest'],
					'postest'=>$le['nilai_postest'],
					'project'=>$le['nilai_project'],
					'status_materi'=>$le['status_materi'],
					'tablePenilaianProject'=>$tabel,
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($le['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($le['update_date']),
					'create_by'=>$le['create_by'],
					'update_by'=>$le['update_by'],
					'nama_buat'=>(!empty($le['nama_buat'])) ? $le['nama_buat']:$this->otherfunctions->getMark($le['nama_buat']),
					'nama_update'=>(!empty($le['nama_update']))?$le['nama_update']:$this->otherfunctions->getMark($le['nama_update']),
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_file_jawaban'){
				$id = $this->input->post('id');				
				$jwbn = $this->model_learning->getJawabanProjectEmp(['a.id'=>$id], true, 'a.id desc');
				$viewFile = $this->otherfunctions->getPreviewFileLearning($jwbn['file']);
				$datax=[
					'id'=>$jwbn['id'],
					'fileView'=>$viewFile,
				];
				echo json_encode($datax);
			}
		}
	}
	public function add_setting_pelatihan(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$materi = $this->input->post('materi');
		$karyawan = $this->input->post('karyawan');
		$target_tanggal = $this->input->post('target_tanggal');
		if (!empty($karyawan)) {
			foreach ($karyawan as $key => $idkar) {
				$data = [
					'id_karyawan'=>$idkar,
					'kode_materi'=>$materi,
					'target_tanggal'=>$this->formatter->getDateFormatDb($target_tanggal),
					'status_materi'=>'BELUM DIKIRIM',
				];
				$cek = $this->model_learning->getListLearningKaryawan(['a.id_karyawan'=>$idkar, 'a.kode_materi'=>$materi]);
				if(empty($cek)){
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax=$this->model_global->insertQuery($data,'learn_karyawan');
				}else{
					$datax=$this->messages->customWarning('Ada karyawan yg sudah dapat pelatihan tersebut');
				}
			}
		}else{
			$datax=$this->messages->customWarning('Pilihan Karyawan tidak boleh kosong.');
		}
		echo json_encode($datax);
	}
	public function edit_setting_pelatihan(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id = $this->input->post('id');
		$materi = $this->input->post('materi');
		$target_tanggal = $this->input->post('target_tanggal');
		if (!empty($materi)) {
			$data = [
				'kode_materi'=>$materi,
				'target_tanggal'=>$this->formatter->getDateFormatDb($target_tanggal),
				'status_materi'=>'SUDAH DIKIRIM',
			];
			$cek = $this->model_learning->getListLearningKaryawan(['a.kode_materi'=>$materi]);
			if(empty($cek)){
				$data=array_merge($data, $this->model_global->getUpdateProperties($this->admin));
				$datax=$this->model_global->updateQuery($data, 'learn_karyawan', ['id'=>$id]);
			}else{
				$datax=$this->messages->customWarning('Karyawan tersebut sudah dapat pelatihan yang dipilih');
			}
		}else{
			$datax=$this->messages->customWarning('Pilihan Materi tidak boleh kosong.');
		}
		echo json_encode($datax);
	}
	public function add_penilaian_project(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id_soal=$this->input->post('id_soal');
		$id_jawaban=$this->input->post('id_jawaban');
		$kode_materi=$this->input->post('kode_materi');
		$id_karyawan=$this->input->post('id_karyawan');
		$inputnilai=$this->input->post('inputnilai');
		if (isset($id_soal)) {
			$updtX = $this->model_global->updateQuery([ 'nilai'=>$inputnilai ],'learn_jawaban_project', ['id'=>$id_jawaban]);
			if ($updtX) {
				$jwbn = $this->model_learning->getJawabanProjectEmp(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$id_karyawan], false, 'a.id desc');
				if(!empty($jwbn)){
					$nilai = 0;
					$jumlahP = 1;
					$jumPenilaian = 0;
					foreach ($jwbn as $j) {
						$nilai += $j->nilai;
						$jumlahP = $j->jumlah_soal_project;
						$jumPenilaian += (!empty($j->nilai) ? 1 : 0);
					}
					// print_r($jumPenilaian);
					$statusMateri = ($jumPenilaian < $jumlahP) ? 'BELUM DINILAI' : 'SELESAI';
					$nilaiAkhir = $nilai/$jumlahP;
					$datax = $this->model_global->updateQuery([ 'nilai_project'=>$nilaiAkhir, 'status_materi'=>$statusMateri ],'learn_karyawan', ['id_karyawan'=>$id_karyawan, 'kode_materi'=>$kode_materi]);
				}else{
					$datax = $this->model_global->updateQuery([ 'nilai_project'=>0, 'status_materi'=>'SELESAI' ],'learn_karyawan', ['id_karyawan'=>$id_karyawan, 'kode_materi'=>$kode_materi]);
				}
			}else{
				$datax=$this->messages->notValidParam();
			}
		}
		echo json_encode($datax);
	}
	public function sendMateriToKaryawan(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id = $this->input->post('id');
		$dataUp = [
			'status_materi'=>'SUDAH DIKIRIM',
		];
		$data = array_merge($dataUp, $this->model_global->getUpdateProperties($this->admin));
		$updt = $this->model_global->updateQuery($data,'learn_karyawan', ['id'=>$id]);
		if($updt){
			$dt=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
			if(!empty($dt['no_hp']) || $dt['no_hp'] != 'NULL' || $dt['no_hp'] != 'null'){				
				$pesan = "Diberitahukan kepada ".$dt['nama_karyawan'].", bahwa ada Materi dan Pelatihan baru untuk anda, segera cek di tautan berikut : \n\nhttps://hrd.jkb.co.id/kpages/task_to_follow/ \n\n_(Pastikan Anda Sudah Login di Aplikasi)_";
				// $dt['no_hp'] = '085725951044';
				$send = $this->curl->sendwaapi($dt['no_hp'], $pesan);
				// if(!empty($send)){
				// 	$msg = json_decode($send);
				// 	if(isset($msg->status)){
				// 		if($msg->status == true){
							$dataDB = [
								'id_emp_adm'=>$dt['id_karyawan'],
								'nama'=>$dt['nama_karyawan'],
								'nomor'=>$dt['no_hp'],
								'param'=>'self learning',
								'token'=>$pesan,
							];
							$this->model_global->insertQuery($dataDB,'short_url');
				// 		}
				// 	}
				// }
				$datax = $updt;
			}else{
				$datax=$this->messages->customWarning('Notifikasi tidak bisa dikirim via Whatsapp karena nomor HP yang terdaftar di HSOFT Tidak Valid.');
			}
		}
		echo json_encode($datax);
	}
	//======================================= RIWAYAT PELATIHAN BAGIAN ==========================================//
	public function getHistoryLearningKaryawanBagian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $form);
				$param = $this->input->post('param');
				$materi = (!empty($form['materi']) ? ['a.kode_materi'=>$form['materi']] : []);
				$karyawan = (!empty($form['karyawan']) ? ['a.id_karyawan'=>$form['karyawan']] : []);
				$whereArray = array_merge($materi, $karyawan);
				$listEmployee = '';
				$bagian = explode(';', $this->dtroot['adm']['list_bagian']);
				if(!empty($bagian)){
					$wherex='';
					$c_lv=1;
					foreach ($bagian as $m => $kode) {
						$wherex.="jbt.kode_bagian='".$kode."'";
						if (count($bagian) > $c_lv) {
							$wherex.=' OR ';
						}
						$c_lv++;
					}
					$where = 'emp.status_emp = 1 AND ('.$wherex.') AND status_materi = "SELESAI"';
					$listEmployee=$this->model_learning->getListLearningKaryawan($where, false, 'a.target_tanggal desc', $whereArray);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($listEmployee as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$send = '<button type="button" class="btn btn-success btn-sm"  href="javascript:void(0)" onclick=sendToKaryawan('.$d->id.')><i class="fas fa-paper-plane" data-toggle="tooltip" title="Kirim Pelatihan"></i></button> ';				
					$statusSend =(($d->status_materi == 'BELUM DIKIRIM') ? $send : null );
					$labelIN = ($d->jenis == 'Internal') ? '<br><label class="label label-info">Internal</label>' : '<br><label class="label label-success">Eksternal</label>';
					$datax['data'][]=[
						$d->id,
						$d->nama_karyawan.$labelIN,
						($d->jenis == 'Internal') ? $d->nama_materi : $d->judul,
						($d->jenis == 'Internal') ? $this->formatter->getDateFormatUser($d->target_tanggal) : $this->formatter->getDateFormatUser($d->tanggal),
						((!empty($d->nilai_pretest) || $d->nilai_pretest == '0') ? $d->nilai_pretest : '<label class="label label-danger">Belum Ada Nilai</label>'),
						((!empty($d->nilai_postest) || $d->nilai_postest == '0') ? $d->nilai_postest : '<label class="label label-danger">Belum Ada Nilai</label>'),
						((!empty($d->nilai_project) || $d->nilai_project == '0') ? $d->nilai_project : '<label class="label label-danger">Belum Ada Nilai</label>'),
						$d->status_materi,
						$statusSend.$properties['info'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$le=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
				$iProject = $this->model_learning->getListSoalProjectLearning(['a.kode_materi'=>$le['kode_materi']], false, 'a.id desc');
				$tabel='';
				if($le['jenis'] == 'Internal'){
					$tabel.='<h4 align="center"><b>Data Soal Project Untuk Materi '.$le['nama_materi'].'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th width="5%">No.</th>
									<th>Project</th>
									<th width="20%">Penilaian Atasan</th>
								</tr>
							</thead>
							<tbody>';
							if(count($iProject) != null){
								$no=1;
								foreach ($iProject as $p) {
									$jwbn = $this->model_learning->getJawabanProjectEmp(['a.kode_project'=>$p->kode, 'a.id_karyawan'=>$le['id_karyawan']], true, 'a.id desc');
									$tabel.='<tr>
										<td rowspan="3">'.$no.'.</td>
										<td>'.$p->soal_project.'</td>
										<td rowspan="3" class="text-center">
											<h1><b>'.$jwbn['nilai'].'</b></h1>
										</td>
									</tr>
									<tr>
										<td><b><u>Jawaban :</u></b><br>'.$jwbn['jawaban'].'</td>
									</tr>
									<tr>
										<td>';
										if(!empty($jwbn['file'])){
											// $tabel.= '<a type="button" href="'.base_url($jwbn['file']).'" target="blank" class="btn btn-success"><i class="fa fa-eye"></i> View File</a>';
											$tabel.= '<button type="button" class="btn btn-success btn-xs" href="javascript:void(0)" onclick="view_modal_file('.$jwbn['id'].')"><i class="fa fa-eye" data-toggle="tooltip" title="Lihat File"></i> View File</button> ';
										}
									$tabel.='</td>
									</tr>';
									$no++;
								}
								// <textarea name="inputnilai" id="inputnilaia_'.$p->id.'" class="form-control input-money" placeholder="Nilai" rows="5"></textarea>
							}else{
								$tabel.='<tr>
									<td colspan="3" class="text-center"><b>Tidak Ada Soal</b></td>
								</tr>';
							}
							$tabel.='</tbody>
					</table></div>';
				}else{
					$tabel.='<h4 align="center"><b>File Eksternal</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th width="5%">No.</th>
									<th>File</th>
									<th width="20%">Aksi</th>
								</tr>
							</thead>
							<tbody>';
								$no=1;
								$tabel.='<tr>
									<td>1. </td>
									<td>'.$le['file'].'</td>
									<td class="text-center">
										<a type="button" href="'.base_url($le['file']).'" target="blank" class="btn btn-success"><i class="fa fa-eye"></i> View File</a>
									</td>
								</tr>';
							$tabel.='</tbody>
					</table></div>';
				}
				$labelIN = ($le['jenis'] == 'Internal') ? '<br><label class="label label-info">Internal</label>' : '<br><label class="label label-success">Eksternal</label>';
				$datax=[
					'id'=>$le['id'],
					'kode'=>$le['kode_materi'],
					'id_karyawan'=>$le['id_karyawan'],
					'tanggal_target'=>($le['jenis'] == 'Internal') ? $this->formatter->getDateFormatUser($le['target_tanggal']) : $this->formatter->getDateFormatUser($le['tanggal']),
					'nama'=>($le['jenis'] == 'Internal') ? $le['nama_materi'] : $le['judul'],
					'nama_karyawan'=>$le['nama_karyawan'].$labelIN,
					'jenis'=>$labelIN,
					'pretest'=>$le['nilai_pretest'],
					'postest'=>$le['nilai_postest'],
					'project'=>$le['nilai_project'],
					'keterangan'=>$le['keterangan_kirim'],
					'status_materi'=>$le['status_materi'],
					'tablePenilaianProject'=>$tabel,
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($le['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($le['update_date']),
					'create_by'=>$le['create_by'],
					'update_by'=>$le['update_by'],
					'nama_buat'=>(!empty($le['nama_buat'])) ? $le['nama_buat']:$this->otherfunctions->getMark($le['nama_buat']),
					'nama_update'=>(!empty($le['nama_update']))?$le['nama_update']:$this->otherfunctions->getMark($le['nama_update']),
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_file_jawaban'){
				$id = $this->input->post('id');				
				$jwbn = $this->model_learning->getJawabanProjectEmp(['a.id'=>$id], true, 'a.id desc');
				$viewFile = $this->otherfunctions->getPreviewFileLearning($jwbn['file']);
				$datax=[
					'id'=>$jwbn['id'],
					'fileView'=>$viewFile,
				];
				echo json_encode($datax);
			}
		}
	}
	public function cetakHistoriLearning()
	{
		parse_str($this->input->post('data_filter'), $form);
		$materi = (!empty($form['materi']) ? ['a.kode_materi'=>$form['materi']] : []);
		$karyawan = (!empty($form['karyawan']) ? ['a.id_karyawan'=>$form['karyawan']] : []);
		$whereArray = array_merge($materi, $karyawan);
		$listEmployee = '';
		$bagian = explode(';', $this->dtroot['adm']['list_bagian']);
		if(!empty($bagian)){
			$wherex='';
			$c_lv=1;
			foreach ($bagian as $m => $kode) {
				$wherex.="jbt.kode_bagian='".$kode."'";
				if (count($bagian) > $c_lv) {
					$wherex.=' OR ';
				}
				$c_lv++;
			}
			$where = 'emp.status_emp = 1 AND ('.$wherex.') AND status_materi = "SELESAI"';
			$listEmployee=$this->model_learning->getListLearningKaryawan($where, false, 'a.target_tanggal desc', $whereArray);
		}
		$data['properties']=[
			'title'=>"Cetak Riwayat Learning",
			'subject'=>"Cetak Riwayat Learning",
			'description'=>"Cetak Riwayat Learning, HSOFT JKB",
			'keywords'=>"Export, Rekap, Cetak Riwayat Learning",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if(!empty($listEmployee)){
			foreach ($listEmployee as $d) {
				$body[$row]=[
					($row-1),
					$d->nama_karyawan,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$d->jenis,
					($d->jenis == 'Internal') ? $d->nama_materi : $d->judul,
					($d->jenis == 'Internal') ? $this->formatter->getDateFormatUser($d->target_tanggal) : $this->formatter->getDateFormatUser($d->tanggal),
					$d->nilai_pretest,
					$d->nilai_postest,
					$d->nilai_project,
					$d->status_materi,
				];
				$row++;
			}
		}
		$data_head=['No', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Jenis Learning', 'Materi Learning','Target Tanggal','Nilai Pretest','Nilai Posttest','Nilai Project','Status Materi'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>"Riwayat Learning",
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	
	//======================================= FOR USER ==========================================//
	public function getDataTaskToFollow()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				// $where = ['a.id_karyawan'=>$this->admin, 'a.status_materi'=>'SUDAH DIKIRIM'];
				$where = 'a.id_karyawan = '.$this->admin.' AND (a.status_materi = "SUDAH DIKIRIM" OR a.status_materi = "BELUM DINILAI")';
				$data = $this->model_learning->getListLearningKaryawan($where, false, 'a.target_tanggal desc');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$keikut = $this->model_learning->getMaxIDKeikutsertaan(['a.kode_materi'=>$d->kode_materi, 'a.id_karyawan'=>$d->id_karyawan], false, 'a.id desc');
					if(empty($keikut['flag'])){
						$status = 'Belum Dikerjakan';
					}elseif($keikut['flag'] == 'selesai'){
						$status = 'Selesai Pre Test';
					}elseif($keikut['flag'] == 'hasil_pretest'){
						$status = 'Hasil Pre Test';
					}elseif($keikut['flag'] == 'learn_materi'){
						$status = 'Mempelajari Materi';
					}elseif($keikut['flag'] == 'posttest'){
						$status = 'Mulai Post Test';
					}elseif($keikut['flag'] == 'selesai_posttest'){
						$status = 'Selesai Post Test';
					}elseif($keikut['flag'] == 'hasil_posttest'){
						$status = 'Hasil Post Test';
					}elseif($keikut['flag'] == 'learn_project'){
						$status = 'Soal Project';
					}elseif($keikut['flag'] == 'selesai_learning'){
						$status = 'Selesai Learning';
					}
					if($keikut['flag'] == 'selesai_learning'){
						$aksi = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_hasil(\''.$d->id.'\')"><i class="fa fa-eye"></i> Lihat Hasil</a>';
					}else{
						$aksi = '<a class="btn btn-success btn-sm" href="javascript:void(0)" onclick="view_modal_start(\''.$d->id.'\')"><i class="fas fa-arrow-circle-right"></i> MULAI</a>';
					}
					$sp = (!empty($d->jumlah_soal_project)?'<br><label class="label label-success">'.$d->jumlah_soal_project .' Soal Project</label>':null);
					$datax['data'][]=[
						$d->id,
						$d->nama_materi,
						$this->formatter->getDateMonthFormatUser($d->target_tanggal),
						(!empty($d->jumlah_soal)?'<label class="label label-info">'.$d->jumlah_soal .' Data Soal</label>':$this->otherfunctions->getMark($d->jumlah_soal)).$sp,
						$d->waktu.' Menit',
						$status,
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
				$keikut = $this->model_learning->getMaxIDKeikutsertaan(['a.kode_materi'=>$data['kode_materi'], 'a.id_karyawan'=>$data['id_karyawan']], false, 'a.id desc');
				$iProject = $this->model_learning->getJawabanProjectEmp(['a.kode_materi'=>$data['kode_materi'], 'a.id_karyawan'=>$data['id_karyawan']], false, 'a.id desc');
				$project = '';
				if(!empty($iProject)){
					$project .='<table class="table table-bordered table-striped data-table">
						<thead>
							<tr class="bg-blue">
								<th width="70%">Project</th>
								<th>Nilai</th>
							</tr>
						<thead>';
					foreach ($iProject as $ip) {
						$project .='<tr>';
						$project .= '<td>'.$ip->soal_project.'</td>';
						$project .= '<td>'.$ip->nilai.'</td>';
						$project .='</tr>';
					}
					$project .='</table>';
				}
				$text = '<p>Saat ini Anda akan mengakses materi '.$data['nama_materi'].'</p><br>				
				<div class="tab-content" id="quizTabContent">
					<div class="tab-pane show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
						<ul class="list-group list-group-flush">
							<li class="list-group-item text-center px-0">
								<div class="row">
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Soal</b></small>
										<br>
										<h6>'.$data['jumlah_soal'].' Soal</h6>
										</div>
									</div>
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Tipe Soal</b></small>
										<br>
										<h6> Pilihan Ganda</h6>
										</div>
									</div>
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Waktu</b></small>
										<br>
										<h6>'.$data['waktu'].' menit</h6>
										</div>
									</div>
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Skor Maks</b></small>
										<br>
										<h6>100</h6>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<p>Terdapat beberapa tahapan dalam Tugas Pembelajaran dan Pengembangan ini yaitu :</p>
				<ol>
					<li>Tahap Pretest (untuk mengukur penguasaan awal)</li>
					<li>Belajar dengan mengkases materi/video/suara yang tersedia</li>
					<li>Tahap Postest (Mengukur pemahaman materi) </li>
					<li>Menyusun Project</li>
					<li>Konsultasi project dengan pimpinan dan coach</li>
				</ol><br>
				Waktu <b>'.$data['waktu'].' Menit</b> dimulai saat anda mengklik Tombol MULAI dibawah';
				
				$datax=[
					'id'=>$data['id'],
					'id_karyawan'=>$data['id_karyawan'],
					'kode_materi'=>$data['kode_materi'],
					'nama_materi'=>$data['nama_materi'],
					'text'=>$text,
					'pretest'=>$keikut['nilai'],
					'posttest'=>$keikut['nilai_pos'],
					'project'=>$project,
				];
				echo json_encode($datax);
			}
		}

	}
	public function getDataHistoryUser()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$where = 'a.id_karyawan = '.$this->admin.' AND a.status_materi = "SELESAI"';
				$data = $this->model_learning->getListLearningKaryawan($where, false, 'a.target_tanggal desc');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$keikut = $this->model_learning->getMaxIDKeikutsertaan(['a.kode_materi'=>$d->kode_materi, 'a.id_karyawan'=>$d->id_karyawan], false, 'a.id desc');
					if($d->jenis == 'Eksternal'){
						$aksi = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_hasil(\''.$d->id.'\')"><i class="fa fa-eye"></i> Lihat Hasil</a>';
						$status = 'Selesai';
						$dataSoal = null;
					}else{
						if(empty($keikut['flag'])){
							$status = 'Belum Dikerjakan';
						}elseif($keikut['flag'] == 'selesai'){
							$status = 'Selesai Pre Test';
						}elseif($keikut['flag'] == 'hasil_pretest'){
							$status = 'Hasil Pre Test';
						}elseif($keikut['flag'] == 'learn_materi'){
							$status = 'Mempelajari Materi';
						}elseif($keikut['flag'] == 'posttest'){
							$status = 'Mulai Post Test';
						}elseif($keikut['flag'] == 'selesai_posttest'){
							$status = 'Selesai Post Test';
						}elseif($keikut['flag'] == 'hasil_posttest'){
							$status = 'Hasil Post Test';
						}elseif($keikut['flag'] == 'learn_project'){
							$status = 'Soal Project';
						}elseif($keikut['flag'] == 'selesai_learning'){
							$status = 'Selesai Learning';
						}
						if($keikut['flag'] == 'selesai_learning'){
							$aksi = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_hasil(\''.$d->id.'\')"><i class="fa fa-eye"></i> Lihat Hasil</a>';
						}else{
							$aksi = '<a class="btn btn-success btn-sm" href="javascript:void(0)" onclick="view_modal_start(\''.$d->id.'\')"><i class="fas fa-arrow-circle-right"></i> MULAI</a>';
						}
						$sp = (!empty($d->jumlah_soal_project)?'<br><label class="label label-success">'.$d->jumlah_soal_project .' Soal Project</label>':null);
						$dataSoal = (!empty($d->jumlah_soal)?'<label class="label label-info">'.$d->jumlah_soal .' Data Soal</label>':$this->otherfunctions->getMark($d->jumlah_soal)).$sp;
					}
					$labelIN = ($d->jenis == 'Internal') ? '<br><label class="label label-info">Internal</label>' : '<br><label class="label label-success">Eksternal</label>';
					$datax['data'][]=[
						$d->id,
						// $d->nama_materi,
						(($d->jenis == 'Internal') ? $d->nama_materi : $d->judul).$labelIN,
						($d->jenis == 'Internal') ? $this->formatter->getDateFormatUser($d->target_tanggal) : $this->formatter->getDateFormatUser($d->tanggal),
						// $this->formatter->getDateMonthFormatUser($d->target_tanggal),
						$dataSoal,
						(($d->jenis == 'Internal') ? $d->waktu.' Menit' : null),
						$status,
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
				$keikut = $this->model_learning->getMaxIDKeikutsertaan(['a.kode_materi'=>$data['kode_materi'], 'a.id_karyawan'=>$data['id_karyawan']], false, 'a.id desc');
				$iProject = $this->model_learning->getJawabanProjectEmp(['a.kode_materi'=>$data['kode_materi'], 'a.id_karyawan'=>$data['id_karyawan']], false, 'a.id desc');
				$project = '';
				if($data['jenis'] == 'Internal'){
					if(!empty($iProject)){
						$project .='
						<div style="max-height: 500px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th width="5%">No.</th>
									<th>Project</th>
									<th width="20%">Nilai Anda</th>
								</tr>
							</thead>
							<tbody>';
						$no = 1;
						foreach ($iProject as $ip) {
							$project.='<tr>
								<td rowspan="3">'.$no.'.</td>
								<td>'.$ip->soal_project.'</td>
								<td rowspan="3"><h1><b>'.$ip->nilai.'</b></h1></td>
							</tr>
							<tr>
								<td><b><u>Jawaban :</u></b><br>'.$ip->jawaban.'</td>
							</tr>
							<tr>
								<td>';
								if(!empty($ip->file)){
									// $project.= '<a type="button" href="'.base_url($ip->file).'" target="blank" class="btn btn-success"><i class="fa fa-eye"></i> View File</a>';
									$project.= '<button type="button" class="btn btn-success btn-xs" href="javascript:void(0)" onclick="view_modal_file('.$ip->id.')"><i class="fa fa-eye" data-toggle="tooltip" title="Lihat File"></i> View File</button> ';
								}
							$project.='</td>
							</tr>';
							$no++;
						}
						$project .='</tbody></table>';
					}
				}else{
					$project.='<h4 align="center"><b>File Eksternal</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr class="bg-blue">
									<th width="5%">No.</th>
									<th>File</th>
									<th width="20%">Aksi</th>
								</tr>
							</thead>
							<tbody>';
								$no=1;
								$project.='<tr>
									<td>1. </td>
									<td>'.$data['file'].'</td>
									<td class="text-center">
										<a type="button" href="'.base_url($data['file']).'" target="blank" class="btn btn-success"><i class="fa fa-eye"></i> View File</a>
									</td>
								</tr>';
							$project.='</tbody>
						</table>
					</div>';
				}
				$text = '<p>Saat ini Anda akan mengakses materi '.$data['nama_materi'].'</p><br>				
				<div class="tab-content" id="quizTabContent">
					<div class="tab-pane show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
						<ul class="list-group list-group-flush">
							<li class="list-group-item text-center px-0">
								<div class="row">
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Soal</b></small>
										<br>
										<h6>'.$data['jumlah_soal'].' Soal</h6>
										</div>
									</div>
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Tipe Soal</b></small>
										<br>
										<h6> Pilihan Ganda</h6>
										</div>
									</div>
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Waktu</b></small>
										<br>
										<h6>'.$data['waktu'].' menit</h6>
										</div>
									</div>
									<div class="col-6 col-md-3">
										<div class="text-center">
										<small class="text-uppercase"><b>Skor Maks</b></small>
										<br>
										<h6>100</h6>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<p>Terdapat beberapa tahapan dalam Tugas Pembelajaran dan Pengembangan ini yaitu :</p>
				<ol>
					<li>Tahap Pretest (untuk mengukur penguasaan awal)</li>
					<li>Belajar dengan mengkases materi/video/suara yang tersedia</li>
					<li>Tahap Postest (Mengukur pemahaman materi) </li>
					<li>Menyusun Project</li>
					<li>Konsultasi project dengan pimpinan dan coach</li>
				</ol><br>
				Waktu <b>'.$data['waktu'].' Menit</b> dimulai saat anda mengklik Tombol MULAI dibawah';
				$labelIN = ($data['jenis'] == 'Internal') ? '<label class="label label-info">Internal</label>' : '<label class="label label-success">Eksternal</label>';
				$datax=[
					'id'=>$data['id'],
					'id_karyawan'=>$data['id_karyawan'],
					'kode_materi'=>$data['kode_materi'],
					'text'=>$text,
					'pretest'=>$keikut['nilai'],
					'posttest'=>$keikut['nilai_pos'],
					'project'=>$project,
					'keterangan'=>$data['keterangan_kirim'],
					'tanggal_target'=>($data['jenis'] == 'Internal') ? $this->formatter->getDateFormatUser($data['target_tanggal']) : $this->formatter->getDateFormatUser($data['tanggal']),
					'nama_materi'=>(($data['jenis'] == 'Internal') ? $data['nama_materi'] : $data['judul']),
					'jenis'=>$labelIN,
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_file_jawaban'){
				$id = $this->input->post('id');
				$jwbn = $this->model_learning->getJawabanProjectEmp(['a.id'=>$id], true, 'a.id desc');
				$fileView = $this->otherfunctions->getPreviewFileLearning($jwbn['file']);
				$datax=[
					'id'=>$jwbn['id'],
					'fileView'=>$fileView,
				];
				echo json_encode($datax);
			}
		}

	}
	public function pagesStartPelatihan(){
		$kode_materi = $this->input->post('kode_materi');
		$id_materi = $this->input->post('id');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id_materi], true);
		$data = ['kode_materi'=>$kode_materi, 'id_materi'=>$id_materi, 'waktuTest'=>$materi['waktu']];
		// unset($_SESSION['StartTime']);
		$this->load->view('self_learning/user/headx');
		$this->load->view('self_learning/user/startLearning',$data);
		$this->load->view('self_learning/user/footerx');
	}
	public function startPelatihan()
	{
		$id = $this->input->post('id');
		$kode_materi = $this->input->post('kode_materi');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
		$ikut=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin], true);
		if(empty($ikut)){
			$percobaan = 1;
			$nomor = 1;
			$soal=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$kode_materi], true, 'RAND()', 1);
		}else{
			$ikutMax=$this->model_learning->getMaxIDKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin]);
			if($ikutMax['status'] == 'SELESAI'){
				$kodeJawab = [];
				$jawab=$this->model_learning->getJawabanEmp(['e.kode'=>$kode_materi, 'a.tipe'=>'pretest', 'a.id_karyawan'=>$this->admin], false);
				if(!empty($jawab)){
					foreach ($jawab as $j) {
						$kodeJawab[] = $j->kode_soal;
					}
				}
				$kodeSoal = '';
				for ($kk=1; $kk < 31; $kk++) { 
					$soal = $this->model_learning->getKodeSoalLearning(['kode_materi'=>$kode_materi], true, 'RAND()', 1)['kode'];
					if(!in_array($soal, $kodeJawab)){
						$kodeSoal = $soal;
					}
				}
				$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$kodeSoal], true);
				$percobaan = $ikutMax['percobaan']+1;
				$nomor = (count($jawab)+1);
				$jumlahJawaban = (count($jawab)+1);
			}else{
				$kodeJawab = [];
				$jawab=$this->model_learning->getJawabanEmp(['e.kode'=>$kode_materi, 'a.tipe'=>'pretest', 'a.id_karyawan'=>$this->admin], false);
				if(!empty($jawab)){
					foreach ($jawab as $j) {
						$kodeJawab[] = $j->kode_soal;
					}
				}
				$kodeSoal = '';
				for ($kk=1; $kk < 31; $kk++) { 
					$soal = $this->model_learning->getKodeSoalLearning(['kode_materi'=>$kode_materi], true, 'RAND()', 1)['kode'];
					if(!in_array($soal, $kodeJawab)){
						$kodeSoal = $soal;
					}
				}
				$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$kodeSoal], true);
				$percobaan = $ikutMax['percobaan'];
				$nomor = (count($jawab)+1);
				$jumlahJawaban = (count($jawab)+1);
			}
		}
		$waktu = 60*($materi['waktu']);
		$secondx = (!empty($waktu)?$waktu:0);
		$numberBefore = ($nomor-1);
		$numberAfter = ($nomor+1);
		$footerx = '';
		$footerx .= '
		<div class="btn-group" role="group" aria-label="Prev Next Question">';
			if($nomor > 1){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberBefore.'\')">&larr;</a>';
			}
			$footerx .= '<select name="qid" id="qid" class="custom-select rounded-0">';
			$footerx .='<option value="'.$nomor.'">'.$nomor.'</option>';
			$footerx .= '</select>';
			if($nomor < $materi['jumlah_soal']){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberAfter.'\')">&rarr;</a>';
			}else{
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goViewHasil(\''.$numberBefore.'\')">Selesai</a>';
			}
		$footerx .= '</div>';
		$jumlahJawaban=$this->model_learning->getJawabanEmp(['a.percobaan '=>$percobaan, 'a.tipe'=>'pretest', 'a.id_karyawan'=>$this->admin, 'e.kode'=>$kode_materi], false);
		$statusLearn = 'belum_selesai';
		// echo $materi['jumlah_soal'].' == '.count($jumlahJawaban);
		if($materi['jumlah_soal'] == count($jumlahJawaban)){
			$statusLearn = 'selesai';
			$keikutsertaan=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi,'a.percobaan'=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
			if(!empty($keikutsertaan['flag'])){
				$statusLearn = $keikutsertaan['flag'];
			}
		}
		$datax=[
			'id'=>$id,
			'kode_materi'=>$kode_materi,
			'kode_soal'=>$soal['kode'],
			'nama_materi'=>$materi['nama_materi'],
			'nomor'=>$nomor.'. ',
			'percobaan'=>$percobaan,
			'soal'=>$soal['soal'],
			'file'=>(empty($soal['file']) ? null : '<embed src="'.base_url($soal['file']).'" width="50%" max-height="80"></embed>'),
			'choice_a'=>$soal['choice_a'],
			'choice_b'=>$soal['choice_b'],
			'choice_c'=>$soal['choice_c'],
			'choice_d'=>$soal['choice_d'],
			'choice_e'=>$soal['choice_e'],
			'timeToShow'=>'<progress value="'.$secondx.'" max="'.$secondx.'" id="pageBeginCountdown"></progress>',
			'footer'=>$footerx,
			'jumlah_jawaban'=>count($jumlahJawaban),
			'statusLearn'=>$statusLearn,
		];
		// print_r($datax); echo '<br>';
		echo json_encode($datax);
	}
	public function goAnswerBefore()
	{
		$id = $this->input->post('id');
		$kode_materi = $this->input->post('kode_materi');
		$nomor = $this->input->post('answer');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
		$ikut=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin], true);
		$jawab=$this->model_learning->getJawabanEmp(['e.kode '=>$kode_materi, 'a.id_karyawan'=>$this->admin, 'a.nomor'=>$nomor], true);
		$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$jawab['kode_soal']], true);
		$percobaan = $ikut['percobaan'];

		$waktu = 60*($materi['waktu']);
		$secondx = (!empty($waktu)?$waktu:0);
		$numberBefore = ($nomor-1);
		$numberAfter = ($nomor+1);
		$footerx = '';
		$footerx .= '
		<div class="btn-group" role="group" aria-label="Prev Next Question">';
			if($nomor > 1){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberBefore.'\')">&larr;</a>';
			}
			$footerx .= '<select name="qid" id="qid" class="custom-select rounded-0">';
			$footerx .='<option value="'.$nomor.'">'.$nomor.'</option>';
			// $footerx .='<option value="'.$nomor.'">'.$numberBefore.' = '.$nomor.' = '.$numberAfter.'</option>';
			$footerx .= '</select>';
			if($nomor < $materi['jumlah_soal']){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberAfter.'\')">&rarr;</a>';
			}else{
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goViewHasil(\''.$kode_materi.'\')">Selesai</a>';
			}
		$footerx .= '</div>';
		$jumlahJawaban=$this->model_learning->getJawabanEmp(['a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], false);
		$datax=[
			'id'=>$id,
			'kode_materi'=>$kode_materi,
			'kode_soal'=>$soal['kode'],
			'nama_materi'=>$materi['nama_materi'],
			'nomor'=>$nomor.'. ',
			'percobaan'=>$percobaan,
			'soal'=>$soal['soal'],
			'file'=>(empty($soal['file']) ? null : '<embed src="'.base_url($soal['file']).'" width="50%" max-height="80"></embed>'),
			'choice_a'=>$soal['choice_a'],
			'choice_b'=>$soal['choice_b'],
			'choice_c'=>$soal['choice_c'],
			'choice_d'=>$soal['choice_d'],
			'choice_e'=>$soal['choice_e'],
			'timeToShow'=>'<progress value="'.$secondx.'" max="'.$secondx.'" id="pageBeginCountdown"></progress>',
			'footer'=>$footerx,
			'jawaban'=>$jawab['jawaban'],
			'jumlah_jawaban'=>count($jumlahJawaban),
		];
		// print_r($datax); echo '<br>';
		echo json_encode($datax);
	}
	public function inputAnswer()
	{
		$id_materi = $this->input->post('id_materi');
		$kode_materi = $this->input->post('kode_materi');
		$kode_soal = $this->input->post('kode_soal');
		$answer = $this->input->post('answer');
		$nomor = $this->input->post('nomor');
		$percobaan = $this->input->post('percobaan');
		$kode_pelatihan = $this->codegenerator->kodePelatihan();
		$ikut=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin], true);
		if(empty($ikut)){
			$dataIkut = [
				'id_karyawan'=>$this->admin,
				'kode_materi'=>$kode_materi,
				'percobaan'=>$percobaan,
				'tipe'=>'pretest',
				'kode_pelatihan'=>$kode_pelatihan,
			];
			$this->model_global->insertQueryNoMsg($dataIkut,'learn_keikutsertaan');
			$dataAnswer = [
				'id_karyawan'=>$this->admin,
				'kode_soal'=>$kode_soal,
				'nomor'=>str_replace('.', '', $nomor),
				'jawaban'=>$answer,
				'percobaan'=>$percobaan,
				'tipe'=>'pretest',
				'kode_pelatihan'=>$kode_pelatihan,
			];
			$this->model_global->insertQueryNoMsg($dataAnswer,'learn_jawaban_karyawan');
		}else{
			$ikutMax=$this->model_learning->getMaxIDKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin]);
			if($ikutMax['status'] == 'SELESAI'){
				$dataIkut = [
					'id_karyawan'=>$this->admin,
					'kode_materi'=>$kode_materi,
					'percobaan'=>$percobaan,
					'tipe'=>'pretest',
					'kode_pelatihan'=>$kode_pelatihan,
				];
				$this->model_global->insertQueryNoMsg($dataIkut,'learn_keikutsertaan');
				$dataAnswer = [
					'id_karyawan'=>$this->admin,
					'kode_soal'=>$kode_soal,
					'nomor'=>str_replace('.', '', $nomor),
					'jawaban'=>$answer,
					'percobaan'=>$percobaan,
					'tipe'=>'pretest',
					'kode_pelatihan'=>$kode_pelatihan,
				];
				$this->model_global->insertQueryNoMsg($dataAnswer,'learn_jawaban_karyawan');
			}else{
				$jawab=$this->model_learning->getJawabanEmp(['a.kode_soal'=>$kode_soal, 'a.tipe'=>'pretest', 'a.id_karyawan'=>$this->admin], true);
				if(empty($jawab)){
					$kodeP=$this->model_learning->getJawabanEmp(['a.percobaan'=>$percobaan, 'a.tipe'=>'pretest', 'a.id_karyawan'=>$this->admin], true);
					$dataAnswer = [
						'id_karyawan'=>$this->admin,
						'kode_soal'=>$kode_soal,
						'nomor'=>str_replace('.', '', $nomor),
						'jawaban'=>$answer,
						'percobaan'=>$percobaan,
						'tipe'=>'pretest',
						'kode_pelatihan'=>$kodeP['kode_pelatihan'],
					];
					$this->model_global->insertQueryNoMsg($dataAnswer,'learn_jawaban_karyawan');
				}else{
					$dataAnswer = [
						'jawaban'=>$answer,
					];
					$where = ['kode_soal'=>$kode_soal, 'percobaan'=>$percobaan,'tipe'=>'pretest', 'id_karyawan'=>$this->admin];
					$this->model_global->updateQueryNoMsg($dataAnswer,'learn_jawaban_karyawan', $where);
					// $this->model_global->updateQueryNoMsg($dataAnswer,'learn_jawaban_karyawan', ['kode_soal'=>$kode_soal]);
				}
			}
		}
		echo json_encode(true);
	}
	public function pagesViewRingkasan(){
		unset($_SESSION['StartTime']);
		$id_materi = $this->input->post('id_materi');
		$percobaan = $this->input->post('percobaan');
		$kode_materi = $this->input->post('kode_materi');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id_materi], true);
		$jumSoal=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$kode_materi], false);
		$hasil=$this->model_learning->getJawabanEmp(['e.kode '=>$kode_materi, 'a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], false);
		$keikutsertaan=$this->model_learning->getDataKeikutsertaan(['a.kode_materi '=>$kode_materi, 'a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
		$jumlahSoal = count($jumSoal);
		$jumlahJawaban = count($hasil);
		$jumlahBenar = 0;
		if(!empty($hasil)){
			foreach ($hasil as $hs) {
				$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$hs->kode_soal], true);
				if($soal['kode'] == $hs->kode_soal){
					if(strtoupper($hs->jawaban) == $soal['correct_answer']){
						$jumlahBenar += 1;
					}
				}
			}
		}
		$nilai = (100/$jumlahSoal)*$jumlahBenar;
		$dataDB = [
			'flag'=>'hasil_pretest',
			'status'=>null,
			'nilai'=>round($nilai),
			'jumlah_soal'=>$jumlahSoal,
			'jumlah_jawaban'=>$jumlahJawaban,
			'jawaban_benar'=>$jumlahBenar,
		];
		$this->model_global->updateQueryNoMsg($dataDB,'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
		$this->model_global->updateQueryNoMsg(['nilai_pretest'=>$nilai],'learn_karyawan', ['kode_materi '=>$kode_materi,'id_karyawan'=>$this->admin]);
		$data = [
			'kode_materi'=>$kode_materi, 
			'nama_materi'=>$keikutsertaan['nama_materi'], 
			'id_materi'=>$id_materi, 
			'waktuTest'=>$materi['waktu'],
			'percobaan'=>$percobaan,
		];
		$datax = array_merge($dataDB, $data);
		// print_r($datax);
		$this->load->view('self_learning/user/headx');
		$this->load->view('self_learning/user/viewRingkasan',$datax);
		$this->load->view('self_learning/user/footerx');
	}
	public function viewMateriPembelajaran(){
		$id_materi = $this->input->post('id_materi');
		$percobaan = $this->input->post('percobaan');
		$kode_materi = $this->input->post('kode_materi');
		$keikutsertaan=$this->model_learning->getDataKeikutsertaan(['a.kode_materi '=>$kode_materi, 'a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
		$materiPembelajaran=$this->model_learning->getListFileMateriLearning(['a.kode_materi'=>$kode_materi, 'a.status'=>'1'], false);
		$dataDB = [ 'flag'=>'learn_materi', ];
		$this->model_global->updateQueryNoMsg($dataDB,'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
		$data = [
			'kode_materi'=>$kode_materi, 
			'nama_materi'=>$keikutsertaan['nama_materi'], 
			'id_materi'=>$id_materi, 
			// 'waktuTest'=>$materi['waktu'],
			'percobaan'=>$percobaan,
			'materiPembelajaran'=>$materiPembelajaran,
		];
		// echo '<pre>';
		// print_r($materiPembelajaran);
		$this->load->view('self_learning/user/headx');
		$this->load->view('self_learning/user/viewMateriPembelajaran', $data);
		$this->load->view('self_learning/user/footerx');
	}
	//====================================== POST TEST =======================================
	
	public function pagePostTest()
	{
		$kode_materi = $this->input->post('kode_materi');
		$id_materi = $this->input->post('id_materi');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id_materi], true);
		$data = ['kode_materi'=>$kode_materi, 'id_materi'=>$id_materi, 'waktuTest'=>$materi['waktu']];
		$this->load->view('self_learning/user/headx');
		$this->load->view('self_learning/user/startPostTest',$data);
		$this->load->view('self_learning/user/footerx');
	}
	public function startPostTest()
	{
		$id = $this->input->post('id');
		$kode_materi = $this->input->post('kode_materi');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
		$ikut=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin], true);
		if(empty($ikut)){
			$percobaan = 1;
			$nomor = 1;
			$soal=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$kode_materi], true, 'RAND()', 1);
		}else{
			$kodeJawab = [];
			$jawab=$this->model_learning->getJawabanEmp(['e.kode '=>$kode_materi,'a.tipe'=>'posttest', 'a.id_karyawan'=>$this->admin], false);
			if(!empty($jawab)){
				foreach ($jawab as $j) {
					$kodeJawab[] = $j->kode_soal;
				}
			}
			$kodeSoal = '';
			for ($kk=1; $kk < 31; $kk++) { 
				$soal = $this->model_learning->getKodeSoalLearning(['kode_materi'=>$kode_materi], true, 'RAND()', 1)['kode'];
				if(!in_array($soal, $kodeJawab)){
					$kodeSoal = $soal;
				}
			}
			$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$kodeSoal], true);
			$percobaan = $ikut['percobaan'];
			$nomor = (count($jawab)+1);
			$jumlahJawaban = (count($jawab)+1);
		}
		$waktu = 60*($materi['waktu']);
		$secondx = (!empty($waktu)?$waktu:0);
		$numberBefore = ($nomor-1);
		$numberAfter = ($nomor+1);
		$footerx = '';
		$footerx .= '
		<div class="btn-group" role="group" aria-label="Prev Next Question">';
			if($nomor > 1){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberBefore.'\')">&larr;</a>';
			}
			$footerx .= '<select name="qid" id="qid" class="custom-select rounded-0">';
			$footerx .='<option value="'.$nomor.'">'.$nomor.'</option>';
			$footerx .= '</select>';
			if($nomor < $materi['jumlah_soal']){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberAfter.'\')">&rarr;</a>';
			}else{
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goViewHasil(\''.$numberBefore.'\')">Selesai</a>';
			}
		$footerx .= '</div>';
		$jumlahJawaban=$this->model_learning->getJawabanEmp(['a.percobaan '=>$percobaan,'a.tipe'=>'posttest', 'a.id_karyawan'=>$this->admin, 'e.kode'=>$kode_materi], false);
		$statusLearn = 'belum_selesai';
		// echo '<pre>';
		// echo $materi['jumlah_soal'].'<br>'; 
		// echo count($jumlahJawaban).'<br>'; 
		if($materi['jumlah_soal'] == count($jumlahJawaban)){
			$keikutsertaan=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi,'a.percobaan'=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
			if(!empty($keikutsertaan['flag'])){
				if($keikutsertaan['flag'] == 'posttest'){
					$this->model_global->updateQueryNoMsg(['flag'=>'selesai_posttest'],'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
					$statusLearn = 'selesai_posttest';
				}else{
					$statusLearn = $keikutsertaan['flag'];
				}
			}else{
				$statusLearn = 'selesai';
			}
		}
		$datax=[
			'id'=>$id,
			'kode_materi'=>$kode_materi,
			'kode_soal'=>$soal['kode'],
			'nama_materi'=>$materi['nama_materi'],
			'nomor'=>$nomor.'. ',
			'percobaan'=>$percobaan,
			'soal'=>$soal['soal'],
			'file'=>(empty($soal['file']) ? null : '<embed src="'.base_url($soal['file']).'" width="50%" max-height="80"></embed>'),
			'choice_a'=>$soal['choice_a'],
			'choice_b'=>$soal['choice_b'],
			'choice_c'=>$soal['choice_c'],
			'choice_d'=>$soal['choice_d'],
			'choice_e'=>$soal['choice_e'],
			'timeToShow'=>'<progress value="'.$secondx.'" max="'.$secondx.'" id="pageBeginCountdown"></progress>',
			'footer'=>$footerx,
			'jumlah_jawaban'=>count($jumlahJawaban),
			'statusLearn'=>$statusLearn,
		];
		// print_r($datax); echo '<br>';
		echo json_encode($datax);
	}
	public function inputAnswerPost()
	{
		$id_materi = $this->input->post('id_materi');
		$kode_materi = $this->input->post('kode_materi');
		$kode_soal = $this->input->post('kode_soal');
		$answer = $this->input->post('answer');
		$nomor = $this->input->post('nomor');
		$percobaan = $this->input->post('percobaan');
		$kode_pelatihan = $this->codegenerator->kodePelatihan();
		$ikut=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.percobaan'=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
		if(empty($ikut)){
			$dataIkut = [
				'id_karyawan'=>$this->admin,
				'kode_materi'=>$kode_materi,
				'percobaan'=>$percobaan,
				'tipe'=>'posttest',
				'kode_pelatihan'=>$kode_pelatihan,
			];
			$this->model_global->insertQueryNoMsg($dataIkut,'learn_keikutsertaan');
			$dataAnswer = [
				'id_karyawan'=>$this->admin,
				'kode_soal'=>$kode_soal,
				'nomor'=>str_replace('.', '', $nomor),
				'jawaban'=>$answer,
				'percobaan'=>$percobaan,
				'tipe'=>'posttest',
				'kode_pelatihan'=>$kode_pelatihan,
			];
			$this->model_global->insertQueryNoMsg($dataAnswer,'learn_jawaban_karyawan');
		}else{
			$jawab=$this->model_learning->getJawabanEmp(['a.kode_soal'=>$kode_soal, 'a.tipe'=>'posttest', 'a.id_karyawan'=>$this->admin], true);
			if(empty($jawab)){
				$kodeP=$this->model_learning->getJawabanEmp(['a.percobaan'=>$percobaan,'a.tipe'=>'posttest', 'a.id_karyawan'=>$this->admin], true);
				$dataAnswer = [
					'id_karyawan'=>$this->admin,
					'kode_soal'=>$kode_soal,
					'nomor'=>str_replace('.', '', $nomor),
					'jawaban'=>$answer,
					'percobaan'=>$percobaan,
					'tipe'=>'posttest',
					'kode_pelatihan'=>$kodeP['kode_pelatihan'],
				];
				$this->model_global->insertQueryNoMsg($dataAnswer,'learn_jawaban_karyawan');
			}else{
				$dataAnswer = [
					'jawaban'=>$answer,
				];
				$where = ['kode_soal'=>$kode_soal, 'percobaan'=>$percobaan,'tipe'=>'posttest', 'id_karyawan'=>$this->admin];
				$this->model_global->updateQueryNoMsg($dataAnswer,'learn_jawaban_karyawan', $where);
			}
			$dataDB = [ 'flag'=>'posttest' ];
			$this->model_global->updateQueryNoMsg($dataDB,'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
		}
		echo json_encode(true);
	}
	public function goAnswerBeforePost()
	{
		$id = $this->input->post('id');
		$kode_materi = $this->input->post('kode_materi');
		$nomor = $this->input->post('answer');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id], true);
		$ikut=$this->model_learning->getDataKeikutsertaan(['a.kode_materi'=>$kode_materi, 'a.id_karyawan'=>$this->admin], true);
		$jawab=$this->model_learning->getJawabanEmp(['e.kode '=>$kode_materi,'a.tipe'=>'posttest', 'a.id_karyawan'=>$this->admin, 'a.nomor'=>$nomor], true);
		$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$jawab['kode_soal']], true);
		$percobaan = $ikut['percobaan'];

		$waktu = 60*($materi['waktu']);
		$secondx = (!empty($waktu)?$waktu:0);
		$numberBefore = ($nomor-1);
		$numberAfter = ($nomor+1);
		$footerx = '';
		$footerx .= '
		<div class="btn-group" role="group" aria-label="Prev Next Question">';
			if($nomor > 1){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberBefore.'\')">&larr;</a>';
			}
			$footerx .= '<select name="qid" id="qid" class="custom-select rounded-0">';
			$footerx .='<option value="'.$nomor.'">'.$nomor.'</option>';
			// $footerx .='<option value="'.$nomor.'">'.$numberBefore.' = '.$nomor.' = '.$numberAfter.'</option>';
			$footerx .= '</select>';
			if($nomor < $materi['jumlah_soal']){
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goAnswer(\''.$numberAfter.'\')">&rarr;</a>';
			}else{
				$footerx .= '<a class="btn btn-outline-secondary btn-post-nav" href="javascript:void(0)" onclick="goViewHasil(\''.$kode_materi.'\')">Selesai</a>';
			}
		$footerx .= '</div>';
		$jumlahJawaban=$this->model_learning->getJawabanEmp(['a.percobaan '=>$percobaan, 'a.tipe'=>'posttest', 'a.id_karyawan'=>$this->admin], false);
		$datax=[
			'id'=>$id,
			'kode_materi'=>$kode_materi,
			'kode_soal'=>$soal['kode'],
			'nama_materi'=>$materi['nama_materi'],
			'nomor'=>$nomor.'. ',
			'percobaan'=>$percobaan,
			'soal'=>$soal['soal'],
			'file'=>(empty($soal['file']) ? null : '<embed src="'.base_url($soal['file']).'" width="50%" max-height="80"></embed>'),
			'choice_a'=>$soal['choice_a'],
			'choice_b'=>$soal['choice_b'],
			'choice_c'=>$soal['choice_c'],
			'choice_d'=>$soal['choice_d'],
			'choice_e'=>$soal['choice_e'],
			'timeToShow'=>'<progress value="'.$secondx.'" max="'.$secondx.'" id="pageBeginCountdown"></progress>',
			'footer'=>$footerx,
			'jawaban'=>$jawab['jawaban'],
			'jumlah_jawaban'=>count($jumlahJawaban),
		];
		// print_r($datax); echo '<br>';
		echo json_encode($datax);
	}
	public function pagesViewRingkasanPost()
	{
		unset($_SESSION['StartTime']);
		$id_materi = $this->input->post('id_materi');
		$percobaan = $this->input->post('percobaan');
		$kode_materi = $this->input->post('kode_materi');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id_materi], true);
		$jumSoal=$this->model_learning->getListSoalLearning(['a.kode_materi'=>$kode_materi], false);
		$hasil=$this->model_learning->getJawabanEmp(['e.kode '=>$kode_materi, 'a.tipe'=>'posttest', 'a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], false);
		$keikutsertaan=$this->model_learning->getDataKeikutsertaan(['a.kode_materi '=>$kode_materi, 'a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
		$jumlahSoal = count($jumSoal);
		$jumlahJawaban = count($hasil);
		$jumlahBenar = 0;
		if(!empty($hasil)){
			foreach ($hasil as $hs) {
				$soal=$this->model_learning->getListSoalLearning(['a.kode'=>$hs->kode_soal], true);
				if($soal['kode'] == $hs->kode_soal){
					if(strtoupper($hs->jawaban) == $soal['correct_answer']){
						$jumlahBenar += 1;
					}
				}
			}
		}
		$nilai = (100/$jumlahSoal)*$jumlahBenar;
		$dataDB = [
			'flag'=>'hasil_posttest',
			'status'=>null,
			'nilai_pos'=>$nilai,
			'jumlah_jawaban_pos'=>$jumlahJawaban,
			'jawaban_benar_pos'=>$jumlahBenar,
		];
		$this->model_global->updateQueryNoMsg($dataDB,'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
		$this->model_global->updateQueryNoMsg(['nilai_postest'=>$nilai],'learn_karyawan', ['kode_materi '=>$kode_materi,'id_karyawan'=>$this->admin]);
		$data = [
			'kode_materi'=>$kode_materi, 
			'nama_materi'=>$keikutsertaan['nama_materi'], 
			'id_materi'=>$id_materi, 
			'waktuTest'=>$materi['waktu'],
			'percobaan'=>$percobaan,
			'nilai'=>round($nilai),
			'jumlah_soal'=>$jumlahSoal,
			'jumlah_jawaban'=>$jumlahJawaban,
			'jawaban_benar'=>$jumlahBenar,
		];
		$datax = array_merge($dataDB, $data);
		// print_r($datax);
		$this->load->view('self_learning/user/headx');
		$this->load->view('self_learning/user/viewRingkasanPost',$datax);
		$this->load->view('self_learning/user/footerx');
	}
	public function viewLearnProject()
	{
		$id_materi = $this->input->post('id_materi');
		$percobaan = $this->input->post('percobaan');
		$kode_materi = $this->input->post('kode_materi');
		$keikutsertaan=$this->model_learning->getDataKeikutsertaan(['a.kode_materi '=>$kode_materi, 'a.percobaan '=>$percobaan, 'a.id_karyawan'=>$this->admin], true);
		// $materiPembelajaran=$this->model_learning->getListFileMateriLearning(['a.kode_materi'=>$kode_materi, 'a.status'=>'1'], false);
		$dataDB = [ 'flag'=>'learn_project', ];
		$this->model_global->updateQueryNoMsg($dataDB,'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
		$project=$this->model_learning->getListSoalProjectLearning(['a.kode_materi '=>$kode_materi], false);
		$data = [
			'kode_materi'=>$kode_materi, 
			'nama_materi'=>$keikutsertaan['nama_materi'], 
			'id_materi'=>$id_materi, 
			// 'waktuTest'=>$materi['waktu'],
			'percobaan'=>$percobaan,
			'project'=>$project,
			'id_karyawan'=>$this->admin,
			// 'jawabanProject'=>$jawabanProject,
		];
		$this->load->view('self_learning/user/headx');
		$this->load->view('self_learning/user/viewLearnProject', $data);
		$this->load->view('self_learning/user/footerx');
	}
	public function addJawabanProject()
	{
		// if (!$this->input->is_ajax_request()) 
		//    redirect('not_found');
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		$kode_materi = $this->input->post('kode_materi');
		$jawaban = $this->input->post('jawaban');
		$file_old = $this->input->post('file_old');
		$dir = "./asset/file/self_learning/jawaban_project/";
		if(!is_dir($dir)){
			mkdir($dir);
		}
		if(empty($file_old)){
			$upload = false;
			$namefile = null;
			if(!empty($_FILES['file']['name'])){
				$targetFile = $dir.date('YmdHis').$_FILES['file']['name'];
				$input_file = $_FILES['file']['tmp_name'];
				$move = move_uploaded_file($input_file, $targetFile);
				$namefile = "asset/file/self_learning/jawaban_project/".date('YmdHis').$_FILES['file']['name'];
				if($move){
					$upload = true;
				}
			}
			if(!empty($namefile)){
				$cek = $this->model_learning->getJawabanProjectEmp(['a.kode_project '=>$kode, 'a.id_karyawan '=>$this->admin], false);
				$data = [
					'kode_project'=>$kode,
					'kode_materi'=>$kode_materi,
					'id_karyawan'=>$this->admin,
					'jawaban'=>$jawaban,
					'file'=>$namefile,
				];
				if(empty($cek)){
					$datax = $this->model_global->insertQuery($data,'learn_jawaban_project');
				}else{
					$datax = $this->model_global->updateQuery($data,'learn_jawaban_project', ['kode_project '=>$kode, 'id_karyawan'=>$this->admin]);
				}
			}else{
				$cek = $this->model_learning->getJawabanProjectEmp(['a.kode_project '=>$kode, 'a.id_karyawan '=>$this->admin], false);
				$data = [
					'kode_project'=>$kode,
					'kode_materi'=>$kode_materi,
					'id_karyawan'=>$this->admin,
					'jawaban'=>$jawaban,
				];
				if(empty($cek)){
					$datax = $this->model_global->insertQuery($data,'learn_jawaban_project');
				}else{
					$datax = $this->model_global->updateQuery($data,'learn_jawaban_project', ['kode_project '=>$kode, 'id_karyawan'=>$this->admin]);
				}
			}
		}else{
			$namefile = "asset/file/self_learning/jawaban_project/".date('YmdHis').$_FILES['file']['name'];
			if(empty($_FILES['file']['name'])){
				$data = [
					'kode_project'=>$kode,
					'kode_materi'=>$kode_materi,
					'id_karyawan'=>$this->admin,
					'jawaban'=>$jawaban,
				];
				$datax = $this->model_global->updateQuery($data,'learn_jawaban_project', ['kode_project '=>$kode, 'id_karyawan'=>$this->admin]);
			}else{
				$upload = false;
				$namefile = null;
				if(!empty($_FILES['file']['name'])){
					$targetFile = $dir.date('YmdHis').$_FILES['file']['name'];
					$input_file = $_FILES['file']['tmp_name'];
					$move = move_uploaded_file($input_file, $targetFile);
					$namefile = "asset/file/self_learning/jawaban_project/".date('YmdHis').$_FILES['file']['name'];
					if($move){
						$upload = true;
						unlink($file_old);
					}
				}
				$data = [
					'kode_project'=>$kode,
					'kode_materi'=>$kode_materi,
					'id_karyawan'=>$this->admin,
					'jawaban'=>$jawaban,
					'file'=>$namefile,
				];
				$datax = $this->model_global->updateQuery($data,'learn_jawaban_project', ['kode_project '=>$kode, 'id_karyawan'=>$this->admin]);
			}
		}
		echo json_encode($datax);
	}
	public function doEndTest()
	{
		$id_materi = $this->input->post('id_materi');
		$percobaan = $this->input->post('percobaan');
		$kode_materi = $this->input->post('kode_materi');
		$materi=$this->model_learning->getListLearningKaryawan(['a.id'=>$id_materi], true);
		$this->model_global->updateQueryNoMsg(['flag'=>'selesai_learning'],'learn_keikutsertaan', ['kode_materi '=>$kode_materi, 'percobaan '=>$percobaan, 'id_karyawan'=>$this->admin]);
		$this->model_global->updateQueryNoMsg(['status_project'=>'SELESAI MENGERJAKAN', 'status_materi'=>'BELUM DINILAI'],'learn_karyawan', ['kode_materi '=>$kode_materi,  'id_karyawan'=>$this->admin]);
		redirect('kpages/task_to_follow');
	}
	//======================================= LEARNING EKSTERNAL ========================================
	public function addLearningEksternal()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$nama=$this->input->post('nama');
		if ($nama != "") {
			$other=[
				'jenis'=>'Eksternal',
				'judul'=>$this->input->post('nama'),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_pelaksanaan')),
				'keterangan_kirim'=>$this->input->post('keterangan'),
				'id_karyawan'=>$this->input->post('karyawan'),
				'status_materi' => "SELESAI",
			];
			$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
			$data=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
				'table'=>'learn_karyawan',
				'column'=>'file', 
				'usage'=>'insert',
				'otherdata'=>$other,
			];
			$newName = 'Eksternal_'.strtotime($this->date);
			$datax=$this->filehandler->doUpload($data,'self_learning', null, $newName);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
}