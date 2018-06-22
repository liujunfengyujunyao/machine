<?php
namespace Home\Controller;
use Think\controller; 
class ListController extends CommonController{
	public $total = "";
	public $page = "";
	public function index(){
		//查询列表要展示的数据
		$get = I('get.');
		if($get['cate_id']){
			//根据分类查询
			//总行数
			$this->total = D('Goods')->where(['cate_id'=>$get['cate_id']])->count();
			$this->list_page();
			//具体数据
			$goods = D('Goods')->where(['cate_id'=>$get['cate_id']])->limit($this->page->firstRow,$this->page->listRows)->select();
			$cate = D('Category')->where(['id'=>$get['cate_id']])->find();
			$name = $cate['cate_name'];
			$this->assign('cate_id',$get['cate_id']);
		}elseif($get['series_id']){
			//根据系列查询
			
			$this->total = D('Goods')->where(['series_id'=>$get['series_id']])->count();
			$this->_page();
			$goods = D('Goods')->where(['series_id'=>$get['series_id']])->limit($this->page->firstRow,$this->page->listRows)->select();
			$series = D('Series')->where(['id'=>$get['series_id']])->find();
			$name = $series['series_name'];
			$this->assign('series_id',$get['series_id']);
		}else{
			//查询全部
			$this->total = D('Goods')->count();
			$this->list_page();
			$goods = D('Goods')->limit($this->page->firstRow,$this->page->listRows)->select();
			$name = '全部商品';
		}
		$this->assign('name',$name);
		$this->assign('goods',$goods);
		//查询所有分类
		$cate_all = D('Category')->select();
		$this->assign('cate_all',$cate_all);
		//判断是网格列表还是普通列表
		if($get['list_sty']==2){
			$this->display('grid');
		}else{
			$this->display();
		}
		
	}
	//分页
	public function list_page(){
		$pagesize = 3;
		$this->page = new \Think\Page($this->total,$pagesize);
		$this->page->setConfig('prev','上一页');
		$this->page->setConfig('next','下一页');
		$this->page->setConfig('first','首页');
		$this->page->setConfig('last','尾页');
		$this->page->rollPage = 3;
		$this->page->lastSuffix = false;
		$page_html = $this->page->show();
		$this->assign('total',$this->total);
		$this->assign('page_html',$page_html);
	}
	
	
}