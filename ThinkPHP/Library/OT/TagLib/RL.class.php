<?php
namespace OT\TagLib;
use Think\Template\TagLib;
class RL extends TagLib{
    protected$tags=array('select'=>array('attr'=>'table,where,order,limit,id,page,sql,field,key,mod,debug','level'=>3));
    public function _select($tag,$content){//$attr修改为$tag
        //$tag=$this->parseXmlAttr($attr,'select');//此处注释掉
        $table=!empty($tag['table'])?$tag['table']:'';
        $order=!empty($tag['order'])?$tag['order']:'';
        $limit=!empty($tag['limit'])?intval($tag['limit']):'';
        $id=!empty($tag['id'])?$tag['id']:'r';
        $where=!empty($tag['where'])?$tag['where']:'1';
        $key=!empty($tag['key'])?$tag['key']:'i';
        $mod=!empty($tag['mod'])?$tag['mod']:'2';
        $page=!empty($tag['page'])?$tag['page']:false;
        $sql=!empty($tag['sql'])?$tag['sql']:'';
        $field=!empty($tag['field'])?$tag['field']:'';
        $debug=!empty($tag['debug'])?$tag['debug']:false;
        $this->comparison['noteq']='<>';
        $this->comparison['sqleq']='=';
        $where=$this->parseCondition($where);
        $sql=$this->parseCondition($sql);
        $parsestr.='<?php $m=M("'.$table.'");';

        if($sql){
            if($page){
                $limit=$limit?$limit:10;//如果有page，没有输入limit则默认为10
                //$parsestr.='import("@.ORG.Page");';//此处注释掉
                $parsestr.='$count=count($m->query("'.$sql.'"));';
                $parsestr.='$p=new\Think\Page($count,'.$limit.');';//分页类引用
                $parsestr.='$sql.="'.$sql.'";';
                $parsestr.='$sql.=" limit ".$p->firstRow.",".$p->listRows."";';
                $parsestr.='$ret=$m->query($sql);';
                $parsestr.='$pages=$p->show();';
                //$parsestr.='dump($count);dump($sql);';
            }else{
                $sql.=$limit?(' limit '.$limit):'';
                $parsestr.='$ret=$m->query("'.$sql.'");';
            }
        }else{
            if($page){
                $limit=$limit?$limit:10;//如果有page，没有输入limit则默认为10
                //$parsestr.='import("@.ORG.Page");';//此处注释掉
                $parsestr.='$count=$m->where("'.$where.'")->count();';
                $parsestr.='$p=new\Think\Page($count,'.$limit.');';
                $parsestr.='$ret=$m->field("'.$field.'")->where("'.$where.'")->limit($p->firstRow.",".$p->listRows)->order("'.$order.'")->select();';
                $parsestr.='$pages=$p->show();';
            }else{
                $parsestr.='$ret=$m->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit("'.$limit.'")->select();';
            }
        }
        if($debug!=false){
            $parsestr.='dump($ret);dump($m->getLastSql());';
        }
        $parsestr.='if($ret):$'.$key.'=0;';
        $parsestr.='foreach($ret as $key=>$'.$id.'):';
        $parsestr.='++$'.$key.';$mod=($'.$key.'%'.$mod.');?>';
        $parsestr.=$this->tpl->parse($content);
        $parsestr.='<?php endforeach;endif;?>';
        return$parsestr;
    }
}

