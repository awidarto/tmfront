<?php

class AdminController extends Controller {

	public $crumb;

	public $model;

	public $heads;

	public $fields;

	public $collection;

	public $controller_name;

	public $form;

	public $form_framework = 'TwitterBootstrap';

	public $form_class = 'form-horizontal';

	public $validator = array();

	public $actions = '';

	public $form_add = 'new';

	public $form_edit = 'edit';

	public $view_object = 'view';

	public $title = '';

    public $ajaxsource = null;

    public $addurl = null;

    public $rowdetail = null;

    public $delurl = null;

    public $newbutton = null;

    public $backlink = '';

    public $makeActions = 'makeActions';


	public function __construct(){

		date_default_timezone_set('Asia/Jakarta');

		Former::framework($this->form_framework);

        $this->crumb = new \Noherczeg\Breadcrumb\Breadcrumb(URL::to('/'));

		$this->beforeFilter('auth', array('on'=>'get', 'only'=>array('getIndex','getAdd','getEdit') ));

        $this->backlink = strtolower($this->controller_name);
	}


	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public function getIndex()
    {
        return $this->pageGenerator();
    }

    public function postIndex()
    {
        return $this->tableResponder();
    }

	public function pageGenerator(){

		//$action_selection = Former::select( Config::get('kickstart.actionselection'))->name('action');

		$heads = $this->heads;

        $this->ajaxsource = (is_null($this->ajaxsource))? strtolower($this->controller_name): $this->ajaxsource;

        $this->addurl = (is_null($this->addurl))? strtolower($this->controller_name).'/add': $this->addurl;

        $this->rowdetail = (is_null($this->rowdetail))? strtolower($this->controller_name).'.rowdetail': $this->rowdetail;

        $this->delurl = (is_null($this->delurl))? strtolower($this->controller_name).'/del': $this->delurl;

        $this->newbutton = (is_null($this->newbutton))? Str::singular($this->controller_name): $this->newbutton;

		$select_all = Former::checkbox()->name('Select All')->check(false)->id('select_all');

		// add selector and sequence columns
		array_unshift($heads, array($select_all,array('search'=>false,'sort'=>false)));
		array_unshift($heads, array('#',array('search'=>false,'sort'=>false)));

		// add action column
		array_push($heads,
			array('Actions',array('search'=>false,'sort'=>false,'clear'=>true))
		);

		$disablesort = array();

		for($s = 0; $s < count($heads);$s++){
			if($heads[$s][1]['sort'] == false){
				$disablesort[] = $s;
			}
		}

		$disablesort = implode(',',$disablesort);

		return View::make('tables.simple')
			->with('title',$this->title )
			->with('newbutton', $this->newbutton )
			->with('disablesort',$disablesort )
			->with('addurl',$this->addurl )
			->with('ajaxsource',URL::to($this->ajaxsource) )
			->with('ajaxdel',URL::to($this->delurl) )
			->with('crumb',$this->crumb )
			->with('heads',$heads )
			->nest('row',$this->rowdetail );


	}


	public function tableResponder($model = null)
	{

		$fields = $this->fields;

		//print_r($fields);

		//array_unshift($fields, array('select',array('kind'=>false)));
		array_unshift($fields, array('seq',array('kind'=>false)));

		$pagestart = Input::get('iDisplayStart');
		$pagelength = Input::get('iDisplayLength');

		$limit = array($pagelength, $pagestart);

		$defsort = 1;
		$defdir = -1;

		$idx = 0;
		$q = array();

		$hilite = array();
		$hilite_replace = array();

		$model = (is_null($model))? $this->model : $model;

		$count_all = $model->count();

		for($i = 0;$i < count($fields);$i++){
			$idx = $i;

			//print_r($fields[$i]);

			$field = $fields[$i][0];
			$type = $fields[$i][1]['kind'];

			$qval = '';

			if(Input::get('sSearch_'.$i))
			{
				if( $type == 'text'){
					if($fields[$i][1]['query'] == 'like'){
						$pos = $fields[$i][1]['pos'];
						if($pos == 'both'){
							$model->whereRegex($field,'/'.Input::get('sSearch_'.$idx).'/i');

							$qval = new MongoRegex('/'.Input::get('sSearch_'.$idx).'/i');
						}else if($pos == 'before'){
							$model->whereRegex($field,'/^'.Input::get('sSearch_'.$idx).'/i');

							$qval = new MongoRegex('/^'.Input::get('sSearch_'.$idx).'/i');
						}else if($pos == 'after'){
							$model->whereRegex($field,'/'.Input::get('sSearch_'.$idx).'$/i');

							$qval = new MongoRegex('/'.Input::get('sSearch_'.$idx).'$/i');
						}
					}else{
						$qval = Input::get('sSearch_'.$idx);

						$model->where($field,$qval);
					}
				}elseif($type == 'numeric' || $type == 'currency'){
					$str = Input::get('sSearch_'.$idx);

					$sign = null;

					$strval = trim(str_replace(array('<','>','='), '', $str));

					$qval = new MongoInt32($strval);

					/*
					if(is_null($sign)){
						$qval = new MongoInt32($strval);
					}else{
						$str = new MongoInt32($str);
						$qval = array($sign=>$str);
					}
					*/


					if(strpos($str, "<=") !== false){
						$sign = '$lte';

						$model->whereLte($field,$qval);

					}elseif(strpos($str, ">=") !== false){
						$sign = '$gte';

						$model->whereGte($field,$qval);

					}elseif(strpos($str, ">") !== false){
						$sign = '$gt';

						$model->whereGt($field,$qval);

					}elseif(stripos($str, "<") !== false){
						$sign = '$lt';

						$model->whereLt($field,$qval);

					}

					//print $sign;

				}elseif($type == 'date'){
					$datestring = Input::get('sSearch_'.$idx);

					if (($timestamp = strtotime($datestring)) === false) {
					} else {
						$daystart = new MongoDate(strtotime($datestring.' 00:00:00'));
						$dayend = new MongoDate(strtotime($datestring.' 23:59:59'));

						$qval = array($field =>array('$gte'=>$daystart,'$lte'=>$dayend));
					    //echo "$str == " . date('l dS \o\f F Y h:i:s A', $timestamp);

						$model->whereBetween($field,$daystart,$dayend);

					}
					$qval = array('$gte'=>$daystart,'$lte'=>$dayend);
					//$qval = Input::get('sSearch_'.$idx);
				}elseif($type == 'datetime'){
					$datestring = Input::get('sSearch_'.$idx);

					$qval = new MongoDate(strtotime($datestring));

					$model->where($field,$qval);

				}


				$q[$field] = $qval;

			}

		}

		//print_r($q);


		/* first column is always sequence number, so must be omitted */

		$fidx = Input::get('iSortCol_0') - 1;

		$fidx = ($fidx == -1 )?0:$fidx;

		$sort_col = $fields[$fidx][0];

		$sort_dir = Input::get('sSortDir_0');

		/*
		if(count($q) > 0){
			$results = $model->skip( $pagestart )->take( $pagelength )->orderBy($sort_col, $sort_dir )->get();
			$count_display_all = $model->count();
		}else{
			$results = $model->find(array(),array(),array($sort_col=>$sort_dir),$limit);
			$count_display_all = $model->count();
		}
		*/


		$model->skip( $pagestart )->take( $pagelength )->orderBy($sort_col, $sort_dir );

		$count_display_all = $model->count();

		$results = $model->get();

		//print_r($results);

		$aadata = array();

		$form = $this->form;

		$counter = 1 + $pagestart;

		foreach ($results as $doc) {

			$extra = $doc;

			//$select = Former::checkbox('sel_'.$doc['_id'])->check(false)->id($doc['_id'])->class('selector');
            $actionMaker = $this->makeActions;
			$actions = $this->$actionMaker($doc);

			$row = array();

			$row[] = $counter;

			//$sel = Former::checkbox('sel_'.$doc['_id'])->check(false)->label(false)->id($doc['_id'])->class('selector')->__toString();
			$sel = '<input type="checkbox" name="sel_'.$doc['_id'].'" id="'.$doc['_id'].'" value="'.$doc['_id'].'" class="selector" />';
			$row[] = $sel;

			foreach($fields as $field){
				if($field[1]['kind'] != false && $field[1]['show'] == true){

					$fieldarray = explode('.',$field[0]);
					if(is_array($fieldarray) && count($fieldarray) > 1){
						$fieldarray = implode('\'][\'',$fieldarray);
						$cstring = '$label = (isset($doc[\''.$fieldarray.'\']))?true:false;';
						eval($cstring);
					}else{
						$label = (isset($doc[$field[0]]))?true:false;
					}


					if($label){

						if( isset($field[1]['callback']) && $field[1]['callback'] != ''){
							$callback = $field[1]['callback'];
							$row[] = $this->$callback($doc);
						}else{
							if($field[1]['kind'] == 'datetime'){
                                if($doc[$field[0]] instanceof MongoDate){
                                    $rowitem = date('d-m-Y H:i:s',$doc[$field[0]]->sec);
                                }elseif ($doc[$field[0]] instanceof Date) {
                                    $rowitem = date('d-m-Y H:i:s',$doc[$field[0]]);
                                }else{
                                    $rowitem = $doc[$field[0]];
                                }
							}elseif($field[1]['kind'] == 'date'){
                                if($doc[$field[0]] instanceof MongoDate){
                                    $rowitem = date('d-m-Y',$doc[$field[0]]->sec);
                                }elseif ($doc[$field[0]] instanceof Date) {
                                    $rowitem = date('d-m-Y',$doc[$field[0]]);
                                }else{
                                    $rowitem = $doc[$field[0]];
                                }
							}elseif($field[1]['kind'] == 'currency'){
								$num = (double) $doc[$field[0]];
								$rowitem = number_format($num,2,',','.');
							}else{
								$rowitem = $doc[$field[0]];
							}

							if(isset($field[1]['attr'])){
								$attr = '';
								foreach ($field[1]['attr'] as $key => $value) {
									$attr .= '"'.$key.'"="'.$value.'" ';
								}
								$row[] = '<span '.$attr.' >'.$rowitem.'</span>';
							}else{
								$row[] = $rowitem;
							}

						}


					}else{
						$row[] = '';
					}
				}
			}

			$row[] = $actions;
			$row['extra'] = $extra;

			$aadata[] = $row;

			$counter++;
		}


		$result = array(
			'sEcho'=> Input::get('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata,
			'qrs'=>$q,
			'sort'=>array($sort_col=>$sort_dir)
		);

		return Response::json($result);
	}

	public function getAdd(){

		$controller_name = strtolower($this->controller_name);

		//$this->crumb->add($controller_name.'/add','New '.Str::singular($this->controller_name));
        $data = $this->beforeAddForm();

		$model = $this->model;

		$form = $this->form;

		return View::make($controller_name.'.'.$this->form_add)
					->with('back',$controller_name)
                    ->with('auxdata',$data)
					->with('form',$form)
					->with('submit',$controller_name.'/add')
					->with('crumb',$this->crumb)
					->with('title','New '.Str::singular($this->controller_name));

	}

	public function postAdd($data = null){

		//print_r(Session::get('permission'));
		if(is_null($data)){
			$data = Input::get();
		}

		//print_r($data);

		$data = $this->beforeValidateAdd($data);

		$controller_name = strtolower($this->controller_name);

	    $validation = Validator::make($input = $data, $this->validator);

	    if($validation->fails()){

	    	return Redirect::to($controller_name.'/add')->withErrors($validation)->withInput(Input::all());

	    }else{

			unset($data['csrf_token']);

			$data['createdDate'] = new MongoDate();
			$data['lastUpdate'] = new MongoDate();


			$model = $this->model;


			$data = $this->beforeSave($data);

			if($obj = $model->insert($data)){

				$obj = $this->afterSave($obj);

				//Event::fire('product.createformadmin',array($obj['_id'],$passwordRandom,$obj['conventionPaymentStatus']));
		    	return Redirect::to($this->backlink)->with('notify_success',ucfirst(Str::singular($controller_name)).' saved successfully');
			}else{
		    	return Redirect::to($this->backlink)->with('notify_success',ucfirst(Str::singular($controller_name)).' saving failed');
			}


	    }

	}

	public function getEdit($id){

		$controller_name = strtolower($this->controller_name);

		//$this->crumb->add(strtolower($this->controller_name).'/edit','Edit',false);

		$model = $this->model;

		$_id = new MongoId($id);

		$population = $model->where('_id',$_id)->first();

		$population = $this->beforeUpdateForm($population);

		foreach ($population as $key=>$val) {
			if($val instanceof MongoDate){
				$population[$key] = date('d-m-Y H:i:s',$val->sec);
			}
		}

		//print_r($population);

		//exit();

		Former::populate($population);

		//$this->crumb->add(strtolower($this->controller_name).'/edit/'.$id,$id,false);

		return View::make(strtolower($this->controller_name).'.'.$this->form_edit)
					->with('back',$controller_name)
					->with('formdata',$population)
					->with('submit',strtolower($this->controller_name).'/edit/'.$id)
					->with('title','Edit '.Str::singular($this->controller_name));

	}


	public function postEdit($id,$data = null){

		$controller_name = strtolower($this->controller_name);
		//print_r(Session::get('permission'));

	    $validation = Validator::make($input = Input::all(), $this->validator);

	    if($validation->fails()){

	    	return Redirect::to($controller_name.'/edit/'.$id)->withInput(Input::all())->withErrors($validation);
	    	//->with_input(Input::all());

	    }else{

	    	if(is_null($data)){
				$data = Input::get();
	    	}

			$id = new MongoId($data['id']);
			$data['lastUpdate'] = new MongoDate();

			unset($data['csrf_token']);
			unset($data['id']);

			//print_r($data);
			//exit();

			$model = $this->model;

			$data = $this->beforeUpdate($id,$data);

			if($obj = $model->where('_id',$id)->update($data)){

				$obj = $this->afterUpdate($id,$data);
				if($obj != false){
			    	return Redirect::to($controller_name)->with('notify_success',ucfirst(Str::singular($controller_name)).' saved successfully');
				}
			}else{
		    	return Redirect::to($controller_name)->with('notify_success',ucfirst(Str::singular($controller_name)).' saving failed');
			}

	    }

	}



	public function postDel(){
		$id = Input::get('id');

		$controller_name = strtolower($this->controller_name);

		$model = $this->model;

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$id = new MongoId($id);

			if($model->where('_id',$id)->delete()){
				Event::fire($controller_name.'.delete',array('id'=>$id,'result'=>'OK'));
				$result = array('status'=>'OK','data'=>'CONTENTDELETED');
			}else{
				Event::fire($controller_name.'.delete',array('id'=>$id,'result'=>'FAILED'));
				$result = array('status'=>'ERR','data'=>'DELETEFAILED');
			}
		}

		return Response::json($result);
	}

	public function beforeSave($data)
	{
		return $data;
	}

	public function afterSave($data)
	{
		return $data;
	}

	public function makeActions($data){
		return '';
	}

	public function beforeUpdate($id,$data)
	{
		return $data;
	}

	public function afterUpdate($id,$data = null)
	{
		return $id;
	}

	public function beforeView($data)
	{
		return $data;
	}

	public function beforeValidateAdd($data)
	{
		return $data;
	}

    public function beforeAddForm()
    {
        return null;
    }

	public function beforeUpdateForm($population)
	{
		if(isset($population['tags']) && is_array($population['tags']))
		{
			$population['tags'] = implode(',', $population['tags'] );
		}
		return $population;
	}

    public function completeHeads($heads){

        $select_all = Former::checkbox()->name('Select All')->check(false)->id('select_all');

        //product head
        array_unshift($heads, array($select_all,array('search'=>false,'sort'=>false)));
        array_unshift($heads, array('#',array('search'=>false,'sort'=>false)));
        array_push($heads,
            array('Actions',array('search'=>false,'sort'=>false,'clear'=>true))
        );

        return $heads;
    }

	public function get_view($id){
		$_id = new MongoId($id);

		$model = $this->model;

		$obj = $model->where('_id',$_id)->get();

		$obj = $this->beforeView($obj);

		$this->crumb->add(strtolower($this->controller_name).'/view/'.$id,'View',false);
		$this->crumb->add(strtolower($this->controller_name).'/view/'.$id,$id,false);

		//return View::make(strtolower($this->controller_name).'.'.$this->view_object)
		return View::make('view')
			->with('obj',$obj);
	}

	public function get_action_sample(){
		\Laravel\CLI\Command::run(array('notify'));
	}

    public function missingMethod($param = array())
    {
        //print_r($param);
    }

}