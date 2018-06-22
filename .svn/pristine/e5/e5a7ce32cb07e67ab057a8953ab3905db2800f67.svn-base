<?php
namespace Home\Controller;
use Think\Controller;
class BlogController extends CommonController
{
	public function index(){
		//分页
		
		$id=I('get.id');
		$total = D('Blog')->where(['series_id' => $id])->count();
		$pagesize = 3;
		$page = new \Think\Page($total,$pagesize);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('first','首页');
		$page->setConfig('last','尾页');
		$page->rollPage = 3;
		$page->lastSuffix = false;
		$page_html = $page->show();
		$this->assign('total',$total);
		$this->assign('page_html',$page_html);
		$name= D('series')->where(['series'=>$id])->find();
		$this->assign('series_name',$name['series_name']);
		$series = D('series')->select();
		$this->assign('series',$series);
		$data=D('Blog')->where(['series_id' => $id])->select();
		// dump($data);die;
		if ($data) {
			$this -> assign('data',$data);
			$this -> display();
		}else{
			$this->error('无效参数');
		}
	}

	//播客详情
	public function detail(){
		$id =I('get.id');
		// dump($id);die;
		$info = D('Blog') -> where( ['id' => $id] ) -> find();
		// dump($info);die;
		$this -> assign('data', $info);
		$series = D('series')->select();
		$this->assign('series',$series);
		 $this -> display();
	}
}



