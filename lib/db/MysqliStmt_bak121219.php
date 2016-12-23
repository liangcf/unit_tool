<?php

/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/6/14
 * Time: 22:48
 * @version 1.0.3
 * @function 参数化查询的类
 * 部分代码重复率有点高，还需优化
 */

namespace db;
class MysqliStmt_bak
{
    /**
     * @var \mysqli
     */
    private $link;

    /**
     * @param array $config
     * @throws \Exception
     */
    function __construct($config=array()){
        if(!$this->link){
            if(empty($config)){
                $defaultDb= require __DIR__ . '/../config/config.php';
                $config=$defaultDb['default_db'];
            }
            $conn=new \mysqli($config['db_host'],$config['db_user'],$config['db_pwd'],$config['db_name'],$config['port']);
            if($conn->errno){
                throw new \Exception('database link failed !please configure the run.config.php file under the config folder --'.$conn->error);
            }
            $conn->set_charset($config['db_char_set']);
            $this->link=$conn;
        }
    }

    /**
     * 关闭数据连接的
     */
    function __destruct(){
        if($this->link){
            $this->link->close();
        }
    }

    /**
     * 插入数据库
     * @link https://bugs.php.net/bug.php?id=43568
     * @param string $table 表名
     * @param array $data 数组格式 数据格式为：数据库字段为键值，键值值为需要插入的值的数组，类似 array('id'=>'000','name'=>'test')
     * @return bool
     * @throws \Exception
     */
    public function insert($table,$data){
        if(!is_array($data)||empty($data)){
            throw new \Exception('insert data error!');
        }
        $keyArr=array();
        $tmpArr=array();
        $valueArr=array();
        $bindType='';
        foreach ($data as $key=>$value){
            $keyArr[]=$key;
            $tmpArr[]='?';
            $valueArr[]=&$data[$key];
            $bindType.= $this->_determineType($value);
        }
        $keyValue=implode(',', $keyArr);
        $tempWhy=implode(',',$tmpArr);
        $sql='insert into '.$table.' ('.$keyValue.') values ('.$tempWhy.')';
        $args[]=$bindType;
        $parameter=array_merge($args,$valueArr);
        $stmt=$this->_prepare($sql);
        call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        $stmt->execute();
        $res=$this->link->affected_rows;
        $stmt->close();
        if($res>0){
            return true;
        }
        return false;
    }

    /**
     * 根据id更新
     * @param string $table 表名
     * @param string $id 需要更新的id
     * @param array $data 需要更新的数据 格式：数据库字段为键值，键值值为需要更新的值的数组，类似 array('sex'=>'1','name'=>'test')
     * @return bool
     * @throws \Exception
     */
    public function updateId($table,$id,$data){
        if(!is_array($data)||empty($data)){
            throw new \Exception('updateId function parameter error!');
        }
        $keyArr=array();
        $valueArr=array();
        $bindType='';
        foreach ($data as $key=>$value){
            $keyArr[]=$key.'=? ';
            $valueArr[]=&$data[$key];
            $bindType.=$this->_determineType($value);
        }
        $keyValue=implode(',',$keyArr);
        $sql='update '.$table.' set '.$keyValue.' where id=? ';
        $bindType.=$this->_determineType($id);
        $args[]=$bindType;
        array_push($valueArr,$id);
        $parameter=array_merge($args,$valueArr);
        $stmt=$this->_prepare($sql);
        call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        $stmt->execute();
        $res=$this->link->affected_rows;
        $stmt->close();
        if($res>0){
            return true;
        }
        return false;
    }

    /**
     * 条件更新语句
     * @param string $table 表名
     * @param array $data 需要更新的数据 格式：数据库字段为键值，键值值为需要更新的值的数组，类似 array('sex'=>'1','name'=>'tt')
     * @param array $where 满足需要更新的条件 格式：数据库字段为键值，键值值为满足更新数据的值，类似 array('id'=>'king','name'=>'test')
     * @return bool
     * @throws \Exception
     */
    public function update($table,$data,$where){
        if(!is_array($data)||empty($data)||!is_array($where)||empty($where)){
            throw new \Exception('update function parameter error!');
        }
        //拼装需要更新的数据
        $keyArr=array();
        $valueArr=array();
        $bindType='';
        foreach ($data as $key=>$value){
            $keyArr[]=$key.'=? ';
            $valueArr[]=&$data[$key];
            $bindType.=$this->_determineType($value);
        }
        $keyValue=implode(',',$keyArr);
        //拼装条件更新的数据
        $whereData=$this->_andWhere($where);
        $bindType.=$whereData['bind_type'];
        $whereStr=$whereData['where_string'];
        $whereValueArr=$whereData['where_value_arr'];
        //拼装sql语句
        $sql='update '.$table.' set '.$keyValue.' where '.$whereStr;//print_r($sql);die;
        $args[]=$bindType;
        $bindData=array_merge($valueArr,$whereValueArr);
        $parameter=array_merge($args,$bindData);
        $stmt=$this->_prepare($sql);
        call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        $stmt->execute();
        $res=$this->link->affected_rows;
        $stmt->close();
        if($res>0){
            return true;
        }
        return false;
    }

    /**
     * 根据id删除
     * @param string $table 表名
     * @param string $id 需要删除数据的id
     * @return bool
     */
    public function deleteId($table,$id){
        $sql="delete from ".$table." where id=?";
        $bindType=$this->_determineType($id);
        $stmt=$this->_prepare($sql);
        $stmt->bind_param($bindType,$id);
        $stmt->execute();
        $res=$this->link->affected_rows;
        $stmt->close();
        if($res>0){
            return true;
        }
        return false;
    }

    /**
     * 根据条件删除操作
     * @param string $table 表名
     * @param array $where 满足删除数据的条件 格式：数据库字段为键值，键值值为满足删除数据的值，类似 array('id'=>'king','name'=>'test')
     * @return bool
     * @throws \Exception
     */
    public function delete($table,$where){
        if(!is_array($where)||empty($where)){
            throw new \Exception('delete function parameter error!');
        }
        $bindType='';
        //拼装条件更新的数据
        $whereData=$this->_andWhere($where);
        $bindType.=$whereData['bind_type'];
        $whereStr=$whereData['where_string'];
        $whereValueArr=$whereData['where_value_arr'];
        //sql语句拼装
        $sql="delete from ".$table." where ".$whereStr;

        $args[]=$bindType;
        $parameter=array_merge($args,$whereValueArr);
        $stmt=$this->_prepare($sql);
        call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        $stmt->execute();
        $res=$this->link->affected_rows;
        $stmt->close();
        if($res>0){
            return true;
        }
        return false;
    }

    /**
     * 条件查询
     * @param string $table 表名
     * @param array $where 满足获取数据的条件 格式：数据库字段为键值，键值值为满足获得数据的值，类似 array('id'=>'king','name'=>'test')
     * @param array $order 排序 格式：需要排序的字段为数组键值，降序（desc）或者升序（asc）的为对应键值的值 类似 array('id'=>'desc','name'=>'asc')
     * @param int $offset 跳过的页数
     * @param int $fetchNum 需要查询出来的记录条数 默认全部
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @param array $orWhere 或条件数据  格式与where条件格式一样
     * @return array
     * @throws \Exception
     */
    public function select($table,$where,$order=array(),$offset=0,$fetchNum=0,$getInfo=array('*'),$orWhere=array()){
        if(!is_array($where)||empty($where)||!is_array($order)||!is_array($orWhere)){
            throw new \Exception('select function parameter error!');
        }
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        //拼装where数据
        $bindType='';
        $whereData=$this->_andWhere($where);
        $bindType.=$whereData['bind_type'];
        $whereStr=$whereData['where_string'];
        $whereValueArr=$whereData['where_value_arr'];
        $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$whereStr;
        //拼装orWhere数据
        $whereOrValueArr=array();
        if(!empty($orWhere)){
            $orWhereData=$this->_orWhere($orWhere);
            $bindType.=$orWhereData['bind_type'];
            $whereOrStr=$orWhereData['where_string'];
            $whereOrValueArr=$orWhereData['where_value_arr'];
            $sql.=' or '.$whereOrStr;
        }
        if(!empty($order)){
            $orderArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderArr);
        }
        if($fetchNum>0&&$offset>=0){
            $sql.=' limit '.$offset.','.$fetchNum;
        }
        $args[]=$bindType;
        $bindData=array_merge($whereValueArr,$whereOrValueArr);
        $parameter=array_merge($args,$bindData);
        $stmt=$this->_prepare($sql);
        call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * 如果不传任何参数默认查询所有
     * data format fetchAll('test1',array('id'=>'desc','name'=>'asc'),0,10,array(),array('id'=>'000','name'=>'liang'))
     * @param string $table 表名
     * @param array $order 排序 格式：需要排序的字段为数组键值，降序（desc）或者升序（asc）的为对应键值的值 类似 array('id'=>'desc','name'=>'asc')
     * @param int $offset 跳过的页数
     * @param int $fetchNum 需要查询出来的记录条数 默认全部
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @param array $orWhere or条件的数据 格式：数据库字段为键值，键值值为满足获得数据的值，类似 array('id'=>'king','name'=>'test')
     * @return array
     * @throws \Exception
     */
    public function selectAll($table,$order=array(),$offset=0,$fetchNum=0,$getInfo=array('*'),$orWhere=array()){
        if(!is_array($order)||!is_array($orWhere)){
            throw new \Exception('selectAll function parameter error!');
        }
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $bindType='';
        $sql="select ".implode(',',$getInfo).' from '.$table;
        $whereOrValueArr=array();
        if(!empty($orWhere)){
            $retOrData=$this->_orWhere($orWhere);
            $bindType.=$retOrData['bind_type'];
            $whereOrStr=$retOrData['where_string'];
            $whereOrValueArr=$retOrData['where_value_arr'];
            $sql.=' where '.$whereOrStr;
        }
        if(!empty($order)){
            $orderArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderArr);
        }
        if($fetchNum>0&&$offset>=0){
            $sql.=' limit '.$offset.','.$fetchNum;
        }
        if(!empty($whereOrValueArr)){
            $args[]=$bindType;
            $parameter=array_merge($args,$whereOrValueArr);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            $stmt->execute();
        }else{
            $stmt=$this->_prepare($sql);
            $stmt->execute();
        }
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * 根据id查询 返回一维数组或空
     * @param string $table 表名
     * @param string $id 获取数据的id
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @return array
     */
    public function selectId($table,$id,$getInfo=array('*')){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql="select ".implode(',',$getInfo).' from '.$table." where id=?";
        $bindType=$this->_determineType($id);
        $stmt=$this->_prepare($sql);
        $stmt->bind_param($bindType,$id);
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return isset($returnData[0])?$returnData[0]:'';
    }

    /**
     * 根据sql语句查询
     * @param string $sql
     * @param array $param
     * @return array|bool
     */
    public function query($sql,$param=array()){
        $stmt=$this->_prepare($sql);
        if(!empty($param)&&is_array($param)){
            $paramTmp=array();
            $bindType='';
            foreach($param as $key=>$value){
                $bindType.=$this->_determineType($param[$key]);
                $paramTmp[]=$param[$key];
            }
            $args[]=$bindType;
            $parameter=array_merge($args,$paramTmp);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        }
        $stmt->execute();
        if(stristr($sql,'select')){
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            return $returnData;
        }else{
            $res=$this->link->affected_rows;
            $stmt->close();
            if($res>0){
                return true;
            }
            return false;
        }
    }

    /**
     * 不等于或者大于说着其他操作 直接是字符串
     * @param string $table 表名
     * @param string $whereString "id>10 or id<3 and name='test'"
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @return array
     */
    public function selectNotEqualAll($table,$whereString,$getInfo=array('*')){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$whereString;
        $stmt=$this->_prepare($sql);
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * select 和selectAll的综合函数
     * @param $table
     * @param array $where
     * @param array $getInfo
     * @param array $order
     * @param int $offset
     * @param int $fetchNum
     * @param array $orWhere
     * @return array
     * @throws \Exception
     */
    public function selects($table,$where=array(),$order=array(),$offset=0,$fetchNum=0,$getInfo=array('*'),$orWhere=array()){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table;
        $bindType='';
        $whereValueArr=array();
        if(!empty($where)){
            $whereData=$this->_andWhere($where);
            $bindType.=$whereData['bind_type'];
            $whereStr=$whereData['where_string'];
            $whereValueArr=$whereData['where_value_arr'];
            $sql.=' where '.$whereStr;
        }
        $whereOrValueArr=array();
        if(!empty($orWhere)){
            $orWhereData=$this->_orWhere($orWhere);
            $bindType.=$orWhereData['bind_type'];
            $whereOrStr=$orWhereData['where_string'];
            $whereOrValueArr=$orWhereData['where_value_arr'];
            if(empty($where)){
                $sql.=' where '.$whereOrStr;
            }else{
                $sql.=' or '.$whereOrStr;
            }
        }
        if(!empty($order)){
            $orderArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderArr);
        }
        if($fetchNum>0&&$offset>=0){
            $sql.=' limit '.$offset.','.$fetchNum;
        }
        if(empty($whereValueArr)&&empty($whereOrValueArr)){
            $stmt=$this->_prepare($sql);
        }else{
            $args[]=$bindType;
            $bindData=array_merge($whereValueArr,$whereOrValueArr);
            $parameter=array_merge($args,$bindData);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        }
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * like 搜索
     * @param $table
     * @param $stringName
     * @param $content
     * @param array $where
     * @param array $order
     * @param int $offset
     * @param int $fetchNum
     * @param array $getInfo
     * @param array $orWhere
     * @return array
     * @throws \Exception
     */
    public function like($table,$stringName,$content,$where=array(),$order=array(),$offset=0,$fetchNum=0,$getInfo=array('*'),$orWhere=array()){
        if(stristr($content,'_')){
            $content=str_replace('_', "\\_", $content);
        }
        if(stristr($content, '%')){
            $content=str_replace('%', '', $content);
        }

        $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$stringName." like '%".$content."%' ";
        $bindType='';
        $whereValueArr=array();
        if(!empty($where)){
            $whereData=$this->_andWhere($where);
            $bindType.=$whereData['bind_type'];
            $whereStr=$whereData['where_string'];
            $whereValueArr=$whereData['where_value_arr'];
            $sql.=' and '.$whereStr;
        }
        $whereOrValueArr=array();
        if(!empty($orWhere)){
            $orWhereData=$this->_orWhere($orWhere);
            $bindType.=$orWhereData['bind_type'];
            $whereOrStr=$orWhereData['where_string'];
            $whereOrValueArr=$orWhereData['where_value_arr'];
            $sql.=' or '.$whereOrStr;
        }
        if(!empty($order)){
            $orderArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderArr);
        }
        if($fetchNum>0&&$offset>=0){
            $sql.=' limit '.$offset.','.$fetchNum;
        }
        if(empty($whereValueArr)&&empty($whereOrValueArr)){
            $stmt=$this->_prepare($sql);
        }else{
            $args[]=$bindType;
            $bindData=array_merge($whereValueArr,$whereOrValueArr);
            $parameter=array_merge($args,$bindData);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        }
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * 获取单条数据
     * @param $table
     * @param array $where
     * @param array $order
     * @param array $getInfo
     * @param array $orWhere
     * @return array
     * @throws \Exception
     */
    public function get($table,$where=array(),$order=array(),$getInfo=array('*'),$orWhere=array()){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table;
        $bindType='';
        $whereValueArr=array();
        if(!empty($where)){
            $whereData=$this->_andWhere($where);
            $bindType.=$whereData['bind_type'];
            $whereStr=$whereData['where_string'];
            $whereValueArr=$whereData['where_value_arr'];
            $sql.=' where '.$whereStr;
        }
        $whereOrValueArr=array();
        if(!empty($orWhere)){
            $orWhereData=$this->_orWhere($orWhere);
            $bindType.=$orWhereData['bind_type'];
            $whereOrStr=$orWhereData['where_string'];
            $whereOrValueArr=$orWhereData['where_value_arr'];
            if(empty($where)){
                $sql.=' where '.$whereOrStr;
            }else{
                $sql.=' or '.$whereOrStr;
            }
        }
        if(!empty($order)){
            $orderArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderArr);
        }
        $sql.=' limit 1';
        if(empty($whereValueArr)&&empty($whereOrValueArr)){
            $stmt=$this->_prepare($sql);
        }else{
            $args[]=$bindType;
            $bindData=array_merge($whereValueArr,$whereOrValueArr);
            $parameter=array_merge($args,$bindData);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        }
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return isset($returnData[0])?$returnData[0]:array();
    }

    /**
     * 获取行的数量
     * @param string $table 表名
     * @param array $where 获取行数的条件 数据格式 数组键值为数据库字段，键值对应的值为满足获的条件 类似 array('id'=>'king','name'=>'test')
     * @param string $columnName 列名
     * @param array $orWhere or条件
     * @return string|int
     */
    public function count($table,$where=array(),$columnName='*',$orWhere=array()){
        $sql="select count(".$columnName.") as count from ".$table;
        $returnData=$this->_group($sql,$where,$orWhere);
        return $returnData[0]['count'];
    }

    /**
     * 取最小值
     * @param $table
     * @param $columnName
     * @param array $where
     * @param array $orWhere
     * @return mixed
     * @throws \Exception
     */
    public function min($table,$columnName,$where=array(),$orWhere=array()){
        if(empty($columnName)){
            throw new \Exception('min function parameter error!',1003);
        }
        $sql="select min(".$columnName.") as min from ".$table;
        $returnData=$this->_group($sql,$where,$orWhere);
        return $returnData[0]['min'];
    }

    /**
     * 取最大值
     * @param $table
     * @param $columnName
     * @param array $where
     * @param array $orWhere
     * @return mixed
     * @throws \Exception
     */
    public function max($table,$columnName,$where=array(),$orWhere=array()){
        if(empty($columnName)){
            throw new \Exception('max function parameter error!',1003);
        }
        $sql="select max(".$columnName.") as max from ".$table;
        $returnData=$this->_group($sql,$where,$orWhere);
        return $returnData[0]['max'];
    }

    /**
     * 平均值
     * @param $table
     * @param $columnName
     * @param array $where
     * @param array $orWhere
     * @return mixed
     * @throws \Exception
     */
    public function avg($table,$columnName,$where=array(),$orWhere=array()){
        if(empty($columnName)){
            throw new \Exception('avg function parameter error!',1003);
        }
        $sql="select avg(".$columnName.") as avg from ".$table;
        $returnData=$this->_group($sql,$where,$orWhere);
        return $returnData[0]['avg'];
    }

    /**
     * 求和
     * @param $table
     * @param $columnName
     * @param array $where
     * @param array $orWhere
     * @return mixed
     * @throws \Exception
     */
    public function sum($table,$columnName,$where=array(),$orWhere=array()){
        if(empty($columnName)){
            throw new \Exception('sum function parameter error!',1003);
        }
        $sql="select sum(".$columnName.") as sum from ".$table;
        $returnData=$this->_group($sql,$where,$orWhere);
        return $returnData[0]['sum'];
    }

    /**
     * 分组函数的执行
     * @param $sql
     * @param $where
     * @param $orWhere
     * @return array
     * @throws \Exception
     */
    private function _group($sql,$where,$orWhere=array()){
        $bindType='';
        $whereValueArr=array();
        $whereOrValueArr=array();
        if(!empty($where)&&is_array($where)){
            //拼装where数据
            $whereData=$this->_andWhere($where);
            $bindType.=$whereData['bind_type'];
            $whereStr=$whereData['where_string'];
            $whereValueArr=$whereData['where_value_arr'];
            $sql.=" where ".$whereStr;
        }
        if(!empty($orWhere)){
            $orWhereData=$this->_orWhere($orWhere);
            $bindType.=$orWhereData['bind_type'];
            $whereOrStr=$orWhereData['where_string'];
            $whereOrValueArr=$orWhereData['where_value_arr'];
            if(empty($where)){
                $sql.=' where '.$whereOrStr;
            }else{
                $sql.=' or '.$whereOrStr;
            }
        }
        if(empty($whereValueArr)&&empty($whereOrValueArr)){
            $stmt=$this->_prepare($sql);
        }else{
            $args[]=$bindType;
            $bindData=array_merge($whereValueArr,$whereOrValueArr);
            $parameter=array_merge($args,$bindData);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
        }
        $stmt->execute();
        $returnData=$this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * 开始事务
     * @return bool
     */
    /*public function beginTransaction(){
        return $this->link->autocommit(false);
    }*/

    /**
     * 提交事务
     * @return bool
     */
    /*public function commitTransaction(){
        return $this->link->commit();
    }*/

    /**
     * 事务回滚
     * @return bool
     */
    /*public function rollbackTransaction(){
        return $this->link->rollback();
    }*/

    /**
     * 参数化查询初始化参数
     * @link http://php.net/manual/zh/mysqli-stmt.bind-param.php
     * @param  array $data
     * @return array
     */
     protected static function refValues($data){
        $refs=array();
        foreach($data as $key=>$value){
            $refs[]=&$data[$key];
        }
        return $refs;
    }

    /**
     * 获取参数传递类型
     * @link   https://github.com/joshcam/PHP-MySQLi-Database-Class/tree/v1.1
     * @version   1.1
     * @param $dataType
     * @return string
     * @throws \Exception
     */
    protected function _determineType($dataType)
    {
        switch (gettype($dataType)) {
            case 'string':
                return 's';
                break;
            case 'integer':
                return 'i';
                break;
            case 'blob':
                return 'b';
                break;
            case 'double':
                return 'd';
                break;
        }
        throw new \Exception('parameterized query data error!');
    }

    /**
     * 取得结果集
     * @link   https://github.com/joshcam/PHP-MySQLi-Database-Class/tree/v1.1
     * @version   1.1
     * @param \mysqli_stmt $stmt
     * @return array
     */
    protected function _dynamicBindResults($stmt)
    {
        $parameters = array();
        $results = array();
        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $parameters[] = &$row[$field->name];
        }
        call_user_func_array(array($stmt, 'bind_result'), $parameters);
        while ($stmt->fetch()) {
            $x = array();
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            $results[] = $x;
        }
        return $results;
    }

    /**
     * and 条件数据拼装
     * @param array $where
     * @return array
     */
    protected function _andWhere($where){
        $bindType='';
        $whereKeyArr=array();
        $whereValueArr=array();
        foreach($where as $keys=>$values){
            $bindType.=$this->_determineType($values);
            $whereKeyArr[]=$keys.'=? ';
            $whereValueArr[]=&$where[$keys];
        }
        $whereStrTmp='';
        foreach($whereKeyArr as $row){
            $whereStrTmp.=$row.' and ';
        }
        $whereStr=substr($whereStrTmp,0,-4);
        $returnData=array(
            'bind_type'=>$bindType,
            'where_value_arr'=>$whereValueArr,
            'where_string'=>$whereStr
        );
        return $returnData;
    }

    /**
     * or 条件数据拼装
     * @param array $where
     * @return array
     * @throws \Exception
     */
    protected function _orWhere($where){
        $bindType='';
        $whereKeyArr=array();
        $whereValueArr=array();
        foreach($where as $keys=>$values){
            $bindType.=$this->_determineType($values);
            $whereKeyArr[]=$keys.'=? ';
            $whereValueArr[]=&$where[$keys];
        }
        $whereStrTmp='';
        foreach($whereKeyArr as $row){
            $whereStrTmp.=$row.' or ';
        }
        $whereStr=substr($whereStrTmp,0,-3);
        $returnData=array(
            'bind_type'=>$bindType,
            'where_value_arr'=>$whereValueArr,
            'where_string'=>$whereStr
        );
        return $returnData;
    }

    /**
     * 验证sql与表的正确性
     * @param $sql
     * @return \mysqli_stmt
     * @throws \Exception
     */
    protected function _prepare($sql){
        $stmt=$this->link->prepare($sql);
        if (!$stmt){
            $msg=$this->link->error . " --SQL语句: " . $sql;
            throw new \Exception($msg);
        }
        return $stmt;
    }

    /**
     * 显示sql语句函数
     * @param $sql
     * @param null $parameter
     */
    protected function _printSql($sql,$parameter=null){
        $parameterStr='';
        if(!empty($parameter)&&is_array($parameter)){
            foreach($parameter as $key=>$values){
                if($key==0){
                    $parameterStr.='参数类型为：'.$parameter[$key].';';
                }else{
                    $parameterStr.='   参数 '.$key.' 为：'.$parameter[$key].';';
                }
            }
            echo $sql.'  --'.$parameterStr;
        }else{
            echo $sql;
        }
    }
}