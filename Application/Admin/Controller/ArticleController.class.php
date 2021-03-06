<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends CommonController{
	//新增内容;
	public function add(){
		if(IS_POST){
			$data = I('post.');
			// dump($data);die;
			if(empty($data['article_content'])){
				$this -> error('文章内容不能为空');
			}
			$res = D('Blog') -> add($data);
			if($res){
			 //添加成功;
			 $this -> success('添加成功',U('Admin/Article/index'));
			}else{
				//添加失败;
				$this -> error('添加失败');
			}

		}else{
			// $series_name =I('post.series_name');
			$info =D('Series') -> select();
			// dump($info);die;
			$this -> assign('info',$info);
			$this ->display();
		}
		
	}
	//播客展示列表;
	public function index(){
		$article = D('Manager') -> select();
		$this->assign('article',$article);
		$this->display();
	}
//编辑;
public function edit(){
	if(IS_POST){
	     $data =I('post.');
	     if(empty($data['article_title'])){
	     	$this -> error('必填项不能为空');
	     }
	     $res =M('Blog') -> save($data);
	     if($res !=false){
	     	$this-> success('操作成功',U('Admin/Article/index'));
	     }else{
	     	$this -> error('操作失败');
	     }
	}else{
	  		$id = I('get.id');
	  		$article = D('Blog')->where(['id'=>$id])->find();
	  		$this->assign('article',$article);
			$info = D('Series') -> select();
			$this -> assign('info',$info);
			$this -> display();
	}
}
public function del(){
	$id = I('get.id');
	$res = D('Blog')->where(['id'=>$id])->delete();
	if($res !== false){
		$this->success('删除成功',U('Admin/Article/index'));
	}else{
		$this->error('删除失败');
	}
}
public function delAll(){
		$ids = I('post.ids');
		$res = D('Article')->where("id in ({$ids})")->delete();
		if($res!=false){
			$return=array(
				'code'=>10000,
				'msg'=>'success'
				);
		}else{
			$return=array(
				'code'=>10001,
				'msg'=>'删除失败'
				);
		}
		$this->ajaxReturn($return);
	}

	
}