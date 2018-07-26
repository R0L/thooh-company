<?php
namespace Admin\Controller;

/**
 * 模型数据管理控制器
 *
 */
class ThinkController extends AdminController {

    public function lists($model = null, $p = 0){
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据

        //获取模型信息
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');

        //解析列表规则
        $fields = array();
        $grids  = preg_split('/[;\r\n]+/s', $model['list_grid']);
        foreach ($grids as &$value) {
            // 字段:标题:链接
            $val      = explode(':', $value);
            // 支持多个字段显示
            if(strpos($val[0], "=")){//处理函数
                $field[0] = $val[0];
            }else{
                $field   = explode(',', $val[0]);
            }
            $value    = array('field' => $field, 'title' => $val[1]);
            if(isset($val[2])){
                // 链接信息
                $value['href']	=	$val[2];
                // 搜索链接信息中的字段信息
                preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
            }
            if(strpos($val[1],'|')){
                // 显示格式定义
                list($value['title'],$value['format'])    =   explode('|',$val[1]);
            }
            foreach($field as $val){
                $array	=	explode('|',$val);
                $fields[] = $array[0];
            }
        }
        
        // 过滤重复字段信息
        $fields =   array_unique($fields);
        // 关键字搜索
        $map	=	array();
        $key	=	$model['search_key']?$model['search_key']:'title';
        if(isset($_REQUEST[$key])){
            $map[$key]	=	array('like','%'.$_REQUEST[$key].'%');
            unset($_REQUEST[$key]);
        }
        // 条件搜索
        foreach($_REQUEST as $name=>$val){
            if(in_array($name,$fields)){
                $map[$name]	=	$val;
            }
        }
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];

        //读取模型数据列表
        if($model['extend']){
            $name   = get_table_name($model['id']);
            $parent = get_table_name($model['extend']);
            $fix    = C("DB_PREFIX");

            $key = array_search('id', $fields);
            if(false === $key){
                array_push($fields, "{$fix}{$parent}.id as id");
            } else {
                $fields[$key] = "{$fix}{$parent}.id as id";
            }

            /* 查询记录数 */
            $count = M($parent)->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")->where($map)->count();

            // 查询数据
            $data   = M($parent)
                ->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order("{$fix}{$parent}.id DESC")
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();
                
        } else {
            in_array('id', $fields) || array_push($fields, 'id');
            $name = parse_name(get_table_name($model['id']), true);
            $data = M($name)
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order('id DESC')
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();
            /* 查询记录总数 */
            $count = M($name)->where($map)->count();
        }

        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        
        $this->assign('model', $model);
        $this->assign('list_grids', $grids);
        $this->assign('list_data', $data);
        $this->meta_title = $model['title'].'列表';
        $this->display($model['template_list']?$model['template_list']:'Think/lists');
    }
    
    public function group($model = null, $p = 0){
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据

        //获取模型信息
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');

        //解析列表规则
        $fields = array();
        $grids  = preg_split('/[;\r\n]+/s', $model['list_grid_group']);
        foreach ($grids as &$value) {
            // 字段:标题:链接
            $val      = explode(':', $value);
            // 支持多个字段显示
            if(strpos($val[0], "=")){//处理函数
                $field[0] = $val[0];
            }else{
                $field   = explode(',', $val[0]);
            }
            $value    = array('field' => $field, 'title' => $val[1]);
            if(isset($val[2])){
                // 链接信息
                $value['href']	=	$val[2];
                // 搜索链接信息中的字段信息
                preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
            }
            if(strpos($val[1],'|')){
                // 显示格式定义
                list($value['title'],$value['format'])    =   explode('|',$val[1]);
            }
            foreach($field as $val){
                $array	=	explode('|',$val);
                $fields[] = $array[0];
            }
        }
        
        // 过滤重复字段信息
        $fields =   array_unique($fields);
        // 关键字搜索
        $map	=	array();
        $key	=	$model['search_key']?$model['search_key']:'title';
        if(isset($_REQUEST[$key])){
            $map[$key]	=	array('like','%'.$_REQUEST[$key].'%');
            unset($_REQUEST[$key]);
        }
        // 条件搜索
        foreach($_REQUEST as $name=>$val){
            if(in_array($name,$fields)){
                $map[$name]	=	$val;
            }
        }
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];

        $group = $model['group'];
        $groupby = null;
        $sum = null;
        if(strpos($group, ":")){
            $explode_group = explode(":", $group);
            $explode_group[0]&&$groupby=$explode_group[0];
            $explode_group[1]&&$sum=$explode_group[1];
        }
        
        
        //读取模型数据列表
        if($model['extend']){
            $name   = get_table_name($model['id']);
            $parent = get_table_name($model['extend']);
            $fix    = C("DB_PREFIX");

            $key = array_search('id', $fields);
            if(false === $key){
                array_push($fields, "{$fix}{$parent}.id as id");
            } else {
                $fields[$key] = "{$fix}{$parent}.id as id";
            }
            
            $fixtable =  "{$fix}{$parent}";
            $explode = explode(",", $sum);
            for($i=0;$i<count($explode);$i++){
                $search = $explode[$i];
                if(strpos($search, "|")){
                    $explode_search = explode("|", $search);
                    $explode_search[0]&&$search = $explode_search[0];
                    if($explode_search[1]){
                        $fixtable = "{$fix}{$name}";
                    }
                    if($explode_search[2]){
                        $alias= $explode_search[2];
                    }
                }

                $alias = $alias?$alias:$search;
                $key = array_search($explode[$i], $fields);
                if(false === $key){
                    array_push($fields, "sum({$fixtable}.{$search}) as {$alias}");
                } else {
                    $fields[$key] = "sum({$fixtable}.{$search}) as {$alias}";
                }
            }
            

            /* 查询记录数 */
            $count = M($parent)->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")->where($map)->group($groupby)->count();

            // 查询数据
            $data   = M($parent)
                ->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order("{$fix}{$parent}.id DESC")
                /*默认按照标示*/
                ->group($groupby)
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();
                
        } else {
            in_array('id', $fields) || array_push($fields, 'id');
            $explode = explode(",", $sum);
            for($i=0;$i<count($explode);$i++){
                $explode[$i] = current(explode("|", $explode[$i]));
                $key = array_search($explode[$i], $fields);
                if(false === $key){
                    array_push($fields, "sum({$explode[$i]}) as {$explode[$i]}");
                } else {
                    $fields[$key] = "sum({$explode[$i]}) as {$explode[$i]}";
                }
            }
            $name = parse_name(get_table_name($model['id']), true);
            $data = M($name)
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order('id DESC')
                /*默认按照标示*/
                ->group($groupby)
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();
             var_dump(M()->_sql());
        exit();
            /* 查询记录总数 */
            $count = M($name)->where($map)->group($groupby)->count();
        }

        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        
        $this->assign('model', $model);
        $this->assign('list_grids', $grids);
        $this->assign('list_data', $data);
        $this->meta_title = $model['title'].'统计';
        $this->display($model['template_list_group']?$model['template_list_group']:'Think/group');
    }

    public function del($model = null, $ids=null){
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');

        $ids = array_unique((array)I('ids',0));

        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $Model = M(get_table_name($model['id']));
        $map = array('id' => array('in', $ids) );
        if($Model->where($map)->delete()){
             if($model['extend']!=0){
                $Model_extend  =   D(parse_name(get_table_name($model['extend']),1));
                if($Model_extend->where($map)->delete()){
                    $this->success('删除成功');
                }else{
                    $this->error('删除失败！');
                }
             }
        } else {
            $this->error('删除失败！');
        }
    }

    public function edit($model = null, $id = 0){
        //获取模型信息
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');

        if(IS_POST){
            if($model['extend']!=0){
                $Model_extend  =   D(parse_name(get_table_name($model['extend']),1));
                // 获取模型的字段信息
                $Model_extend  =   $this->checkAttr($Model_extend,$model['extend']);
                if(!$Model_extend->create() || !$Model_extend->save()){
                    $this->error($Model_extend->getError());
                }
            }
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息 
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->save()){
                $this->success('保存'.$model['title'].'成功！', U('lists?model='.$model['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $fields     = get_model_attribute($model['id']);
            
            //获取数据
            $data       = M(get_table_name($model['id']))->find($id);
            if($model['extend']!=0){
                $Model_extend  =   D(parse_name(get_table_name($model['extend']),1));
                $data_extend = $Model_extend->find($id);
                $data = array_merge($data,$data_extend);
            }
            $data || $this->error('数据不存在！');

            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->meta_title = '编辑'.$model['title'];
            $this->display($model['template_edit']?$model['template_edit']:'Think/edit');
        }
    }

    public function add($model = null){
        //获取模型信息
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            if($model['extend']!=0){
                $Model_extend  =   D(parse_name(get_table_name($model['extend']),1));
                // 获取模型的字段信息
                $Model_extend  =   $this->checkAttr($Model_extend,$model['extend']);
                if(!$Model_extend->create() || !($mode_extend_status = $Model_extend->add())){
                    $this->error($Model_extend->getError());
                }
            }
            if($mode_extend_status)$_POST['id']=$mode_extend_status;
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息 
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->add()){
                $this->success('添加'.$model['title'].'成功！', U('lists?model='.$model['name']));
            } else {
                $Model_extend->delete(array("id"=>$mode_extend_status));
                $this->error($Model->getError());
            }
        } else {

            $fields = get_model_attribute($model['id']);

            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->meta_title = '新增'.$model['title'];
            $this->display($model['template_add']?$model['template_add']:'Think/add');
        }
    }

    protected function checkAttr($Model,$model_id){
        $fields     =   get_model_attribute($model_id,false);    
        $validate   =   $auto   =   array();
        foreach($fields as $key=>$attr){
            if($attr['is_must']){// 必填字段
                $validate[]  =  array($attr['name'],'require',$attr['title'].'必须!');
            }            
            // 自动验证规则
            if(!empty($attr['validate_rule'])) {
                $validate[]  =  array($attr['name'],$attr['validate_rule'],$attr['error_info']?$attr['error_info']:$attr['title'].'验证错误',0,$attr['validate_type'],$attr['validate_time']);
            }
            // 自动完成规则
            if(!empty($attr['auto_rule'])) {
                $auto[]  =  array($attr['name'],$attr['auto_rule'],$attr['auto_time'],$attr['auto_type']);
            }elseif('date' == $attr['type']){ // 日期型
                $auto[] =   array($attr['name'],'get_date_time',3,'function');
            }elseif('time' == $attr['type']){ // 时钟型
                $auto[] =   array($attr['name'],'get_time_time',3,'function');
            }elseif('datetime' == $attr['type']){ // 时间型
                $auto[] =   array($attr['name'],'strtotime',3,'function');
            }
        }
        return $Model->validate($validate)->auto($auto);
    }
    
}