<?php
/**
 *  
 *  @author  Mike <m@9026.com>
 *  @version    1.0
 *  @date 2015年10月12日
 *
 */
namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\Admin\Controller;
use App\Services\Base\Tree;
use App\Services\Base\BaseArea;
use App\Services\Admin\Menus;
use App\Services\Admin\Acl;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function index() {
        if($this->_user['is_root']) {
            $obj = new Menus();
            $menus = $obj->search(array('level'=>2,'display'=>1),$orderby=array('sort'=>'desc'),$pagesize = 100000);
            $menus = $menus->toArray();
            $menus = list_to_tree($menus['data']);
        }else{
            $obj = new Acl();
            $data = $obj->getRoleMenu($this->_user['admin_role_id']);
            $menus = list_to_tree($data);
        }
        return view('admin.base.index.index',compact('menus'));
    }
    function welcome() {
        return view('admin.base.index.welcome');
    }
    
    function createAreaDate(){
        //Base-index-createareadate.do
        header("Content-type:text/html;charset=utf-8");
        $areaObj = new BaseArea();
        $data = $areaObj->getLevel();

        $treeObj = new Tree();
        $treeObj -> init($data);
        $info = $treeObj -> getTree();
        $output = array();
        
        foreach($info AS $key => $val){
            if($val['id'] == '100000') continue;
            $val['level'] = $val['level'] - 1;
            unset($val['grade'], $val['spacer']);
            $output[]= $val;
        }
        
        $str = json_encode($output);
        
        $area_path = public_path() . '/base/js/areadata.js';
        file_put_contents($area_path, $str);
        
        echo $str;exit;
    }
}