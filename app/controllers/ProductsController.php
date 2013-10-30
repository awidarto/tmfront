<?php

class ProductsController extends AdminController {

	public function __construct()
	{
		parent::__construct();

		$this->controller_name = str_replace('Controller', '', get_class());

		//$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

		$this->model = new Product();

	}


	public function getIndex()
	{

		$this->heads = array(
            array('Product',array('search'=>true,'sort'=>true)),
            array('Images',array('search'=>false,'sort'=>false)),
			array('Brand',array('search'=>true,'sort'=>true)),
			array('Collection',array('search'=>true,'sort'=>true)),
			array('Model No.',array('search'=>true,'sort'=>true)),
			array('Category',array('search'=>true,'sort'=>true,'select'=>Config::get('se.search_product_categories'))),
			array('Tags',array('search'=>true,'sort'=>true)),
			array('Price',array('search'=>true,'sort'=>true)),
			array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
			array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
		);

		return parent::getIndex();

	}

	public function postIndex()
	{

		$this->fields = array(
            array('productName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('productName',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'pics','show'=>true)),
			array('brandName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
			array('collectionName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
			array('modelNo',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
			array('productCategory',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
			array('visibleTags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
			array('priceUSD',array('kind'=>'currency','query'=>'like','pos'=>'both','show'=>true)),
			array('createdDate',array('kind'=>'date','query'=>'like','pos'=>'both','show'=>true)),
			array('lastUpdate',array('kind'=>'date','query'=>'like','pos'=>'both','show'=>true)),
		);

		return parent::postIndex();
	}

	public function postAdd($data = null)
	{

		$this->validator = array(
		    'brandName' => 'required',
		    'productName'=> 'required'
	    );

		return parent::postAdd($data);
	}

	public function postEdit($id,$data = null)
	{
		$this->validator = array(
		    'brandName' => 'required',
		    'productName'=> 'required'
	    );

		return parent::postEdit($id,$data);
	}

	public function makeActions($data)
	{
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('products/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
		return $actions;
	}

	public function namePic($data)
	{
		$name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('id' => $data['_id']));
            return $display.'<br />'.$name;
        }else{
            return $name;
        }
	}

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $display.'<br /><span class="img-more" id="'.$data['_id'].'">more images</span>';
        }else{
            return $name;
        }
    }

    public function getViewpics($id)
    {

    }


}
