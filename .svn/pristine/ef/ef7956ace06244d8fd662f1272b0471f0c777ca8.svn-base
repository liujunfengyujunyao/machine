<?php  
namespace Admin\Controller;
use Think\Controller;
class AuthController extends CommonController{
	public function index(){
		$model = D('Auth');
		$total = $model->count();
		$pagesize = 10;
		$page = new \Think\Page($total,$pagesize);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('first','首页');
		$page->setConfig('last','尾页');
		$page->rollPage = 5;
		$page->lastSuffix = false;
		$page_html = $page->show();
		$this->assign('page_html',$page_html);
		//查询权限数据
		$auth = $model->limit($page->firstRow,$page->listRows)->select();
		$auth = getTree($auth);
		$this->assign('auth',$auth);
		$this->display();
	}

	public function add(){
		if (IS_POST) {
			$data=I('post.');
			$model=D('Auth');
			$create=$model->create($data);
			if (!$create) {
				$error=$this->getError();
				$this->error($error);
			}
			$res=$model->add();
			if ($res) {
				$this->success('创建成功',U('Admin/Auth/index'));
			}else{
				$this->error('创建失败');
			}
		}else{
			//顶级分类top
			$top = D('Auth')->where('pid=0')->select();
			$this->assign('top',$top);
			$this->display();
		}
	}

	public function edit(){
		if (IS_POST) {
			$data=I('post.');
			$model=D('Auth');
			$create=$model->create($data);
			if (!$create) {
				$error=$this->getError();
				$this->error($error);
			}
			$res=$model->where(['id'=>$data['id']])->save();
			if ($res!==false) {
				$this->success('修改成功',U('Admin/Auth/index'));
			}else{
				$this->error('修改失败');
			}

		}else{
			$id=I('get.id');
			$auth=D('Auth')->where(['id'=>$id])->find();
			$this->assign('auth',$auth);
			//查找二级分类的父id是否对应获取到的id,如果对应的话证明得到的id为顶级id
			$second=D('Auth')->where(['pid'=>$id])->find();
			if (empty($second)) {
				//分配顶级权限到模板
				$top=D('Auth')->where('pid=0')->select();
				$this->assign('top',$top);
			}
			$this->display();
		}


	}

	public function del(){
		$id=I('get.id');
		$model=D('Auth');
		$second=$model->where(['pid'=>$id])->select();
		if ($second) {
		     	$this->error('权限下有子类不能删除');
		     }  
		 else{
		 	$res=$model->where(['id'=>$id])->delete();
		 	if ($res!==false) {
		 		$this->success('删除成功',U('Admin/Auth/index'));
		 	}else{
		 		$this->error('删除失败');
		 	}
		 }    
	}
	public function delAll(){
		$ids = I('post.ids');
		$res = D('Auth')->where("pid in ({$ids})")->select();
		if($res){
			$return=array(
				'code'=>10001,
				'msg'=>'下有二级权限不可删除'
				);

		}else{
			$result = D('Auth')->where("id in ({$ids})")->delete();
			if($result!=false){
				$return=array(
					'code'=>10000,
					'msg'=>'success'
					);
			}else{
				$return=array(
					'code'=>10002,
					'msg'=>'删除失败'
					);
			}
		}
		$this->ajaxReturn($return);
     }  

}