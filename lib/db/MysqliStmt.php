<?php

/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/6/14
 * Time: 22:48
 * @version 1.1.0
 * @function 参数化查询的类
 * 部分代码重复率有点高，还需优化
 */
namespace db;
class MysqliStmt
{
    /**
     * @var \mysqli
     */
    private $link;
    private $_keys='';
    private $_values='';
    private $_bindType='';
    private $_wheres='';
    private $_orWheres='';
    private $_bindValue=array();
    private $_sql='';
    private $_parameter=array();
    private $_selfErrorNo=6000;//函数本身异常代码
    private $_funErrorNo=6001;//外界函数异常代码
    private $_paraErrorNo=6002;//参数异常代码

    /**
     * @param array $config
     * @throws \Exception
     */
    function __construct($config=array()){
        if(!$this->link){
            if(empty($config)){
                $defaultDb= require __DIR__ . '/../config/run.config.php';
                $config=$defaultDb['default_db'];
            }
            $conn=new \mysqli($config['db_host'],$config['db_user'],$config['db_pwd'],$config['db_name'],$config['port']);
            if($conn->connect_errno){
                throw new \Exception('database link failed !please configure the run.config.php file under the config folder --error:'.$conn->error.' --connect_errno:'.$conn->connect_errno);
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
            throw new \Exception('[insert] parameter error!',$this->_paraErrorNo);
        }
        $sql='insert into '.$table;
        if(!$this->iBand($data)){
            $this->clear();
            throw new \Exception('external function [iBand] exception',$this->_funErrorNo);
        }
        try{
            $sql.=' ('.$this->_keys.') values ('.$this->_values.')';
            $args[]=$this->_bindType;
            $parameter=array_merge($args,$this->_bindValue);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            $stmt->execute();
            $affectedRows=$this->link->affected_rows;
            $stmt->close();
            $this->clear();
            if($affectedRows>0){
                return true;
            }
            return false;
        }catch (\Exception $e){
            throw new \Exception('self function [insert] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
            throw new \Exception('[updateId] parameter error!',$this->_paraErrorNo);
        }
        $sql='update '.$table.' set ';
        if(!$this->uBand($data)){
            $this->clear();
            throw new \Exception('external function [uBand] exception',$this->_funErrorNo);
        }
        try{
            $sql.=' '.$this->_keys.' where id=? ';
            $this->_bindType.=$this->_determineType($id);
            $args[]=$this->_bindType;
            array_push($this->_bindValue,$id);
            $parameter=array_merge($args,$this->_bindValue);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            $stmt->execute();
            $affectedRows=$this->link->affected_rows;
            $stmt->close();
            $this->clear();
            if($affectedRows>0){
                return true;
            }
            return false;
        }catch (\Exception $e){
            throw new \Exception('self function [updateId] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
            throw new \Exception('[update] parameter error!',$this->_paraErrorNo);
        }
        $sql='update '.$table.' set ';
        if(!$this->uBand($data)){
            $this->clear();
            throw new \Exception('external function [uBand] exception',$this->_funErrorNo);
        }
        try{
            $sql.=' '.$this->_keys.' where ';
            if(!$this->_and($where)){
                $this->clear();
                throw new \Exception('external function [_and] exception',$this->_funErrorNo);
            }
            $args[]=$this->_bindType;
            $sql.=' '.$this->_wheres;
            $parameter=array_merge($args,$this->_bindValue);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            $stmt->execute();
            $affectedRows=$this->link->affected_rows;
            $stmt->close();
            $this->clear();
            if($affectedRows>0){
                return true;
            }
            return false;
        }catch (\Exception $e){
            throw new \Exception('self function [update] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 根据id删除
     * @param string $table 表名
     * @param string $id 需要删除数据的id
     * @return bool
     * @throws \Exception
     */
    public function deleteId($table,$id){
        $sql='delete from '.$table.' where id=?';
        try{
            $bindType=$this->_determineType($id);
            $stmt=$this->_prepare($sql);
            $stmt->bind_param($bindType,$id);
            $stmt->execute();
            $affectedRows=$this->link->affected_rows;
            $stmt->close();
            if($affectedRows>0){
                return true;
            }
            return false;
        }catch (\Exception $e){
            throw new \Exception('self function [deleteId] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
            throw new \Exception('[delete] parameter error!',$this->_paraErrorNo);
        }
        $sql='delete from '.$table;
        if(!$this->_and($where)){
            $this->clear();
            throw new \Exception('external function [_and] exception',$this->_funErrorNo);
        }
        try{
            $sql.=' where '.$this->_wheres;
            $args[]=$this->_bindType;
            $parameter=array_merge($args,$this->_bindValue);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            $stmt->execute();
            $affectedRows=$this->link->affected_rows;
            $stmt->close();
            if($affectedRows>0){
                return true;
            }
            return false;
        }catch (\Exception $e){
            throw new \Exception('self function [deleteId] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
            throw new \Exception('[select] parameter error!',$this->_paraErrorNo);
        }
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table;
        if(!$this->_and($where)){
            $this->clear();
            throw new \Exception('external function [_and] exception',$this->_funErrorNo);
        }
        try{
            $sql.=' where '.$this->_wheres;

            if(!empty($orWhere)){
                if(!$this->_or($orWhere)){
                    $this->clear();
                    throw new \Exception('external function [_or] exception',$this->_funErrorNo);
                }
                $sql.=' or '.$this->_orWheres;
            }
            if(!empty($order)){
                $sql.=' order by '.$this->_order($order);
            }
            if($fetchNum>0&&$offset>=0){
                $sql.=' limit '.$offset.','.$fetchNum;
            }
            $args[]=$this->_bindType;
            $parameter=array_merge($args,$this->_bindValue);
            $stmt=$this->_prepare($sql);
            call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            $this->clear();
            return $returnData;
        }catch (\Exception $e){
            throw new \Exception('self function [select] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
            throw new \Exception('[selectAll] parameter error!',$this->_paraErrorNo);
        }
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        try{
            $sql='select '.implode(',',$getInfo).' from '.$table;
            if(!empty($orWhere)){
                if(!$this->_or($orWhere)){
                    $this->clear();
                    throw new \Exception('external function [_or] exception',$this->_funErrorNo);
                }
                $sql.=' where '.$this->_orWheres;
            }
            if(!empty($order)){
                $sql.=' order by '.$this->_order($order);
            }
            if($fetchNum>0&&$offset>=0){
                $sql.=' limit '.$offset.','.$fetchNum;
            }
            if(empty($this->_bindValue)){
                $stmt=$this->_prepare($sql);
            }else{
                $args[]=$this->_bindType;
                $parameter=array_merge($args,$this->_bindValue);
                $stmt=$this->_prepare($sql);
                call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            }
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            $this->clear();
            return $returnData;
        }catch (\Exception $e){
            throw new \Exception('self function [selectAll] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
        try{
            $sql='select '.implode(',',$getInfo).' from '.$table;
            if(!empty($where)){
                if(!$this->_and($where)){
                    $this->clear();
                    throw new \Exception('external function [_and] exception',$this->_funErrorNo);
                }
                $sql.=' where '.$this->_wheres;
            }
            if(!empty($orWhere)){
                if(!$this->_or($orWhere)){
                    $this->clear();
                    throw new \Exception('external function [_or] exception',$this->_funErrorNo);
                }
                if(empty($where)){
                    $sql.=' where '.$this->_orWheres;
                }else{
                    $sql.=' or '.$this->_orWheres;
                }
            }
            if(!empty($order)){
                $sql.=' order by '.$this->_order($order);
            }
            if($fetchNum>0&&$offset>=0){
                $sql.=' limit '.$offset.','.$fetchNum;
            }
            if(empty($this->_bindValue)){
                $stmt=$this->_prepare($sql);
            }else{
                $args[]=$this->_bindType;
                $parameter=array_merge($args,$this->_bindValue);
                $stmt=$this->_prepare($sql);
                call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            }
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            $this->clear();
            return $returnData;
        }catch (\Exception $e){
            throw new \Exception('self function [selects] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 根据id查询 返回一维数组或空
     * @param string $table 表名
     * @param string $id 获取数据的id
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @return array
     * @throws \Exception
     */
    public function selectId($table,$id,$getInfo=array('*')){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table.' where id=?';
        try{
            $bindType=$this->_determineType($id);
            $stmt=$this->_prepare($sql);
            $stmt->bind_param($bindType,$id);
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            return isset($returnData[0])?$returnData[0]:array();
        }catch (\Exception $e){
            throw new \Exception('self function [selectId] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 根据sql语句查询
     * @param string $sql
     * @param array $param
     * @return array|bool
     * @throws \Exception
     */
    public function query($sql,$param=array()){
        try{
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
        }catch (\Exception $e){
            throw new \Exception('self function [query] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 不等于或者大于说着其他操作 直接是字符串
     * @param string $table 表名
     * @param string $whereString "id>10 or id<3 and name='test'"
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @return array
     * @throws \Exception
     */
    public function selectNotEqual($table,$whereString,$getInfo=array('*')){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        try{
            $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$whereString;
            $stmt=$this->_prepare($sql);
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            return $returnData;
        }catch (\Exception $e){
            throw new \Exception('self function [selectNotEqual] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
        try{
            if(stristr($content,'_')){
                $content=str_replace('_', "\\_", $content);
            }
            if(stristr($content, '%')){
                $content=str_replace('%', '', $content);
            }
            $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$stringName." like '%".$content."%' ";
            if(!empty($where)){
                if(!$this->_and($where)){
                    $this->clear();
                    throw new \Exception('external function [_and] exception',$this->_funErrorNo);
                }
                $sql.=' and '.$this->_wheres;
            }
            if(!empty($orWhere)){
                if(!$this->_or($orWhere)){
                    $this->clear();
                    throw new \Exception('external function [_or] exception',$this->_funErrorNo);
                }
                $sql.=' and '.$this->_orWheres;
            }
            if(!empty($order)){
                $sql.=' order by '.$this->_order($order);
            }
            if($fetchNum>0&&$offset>=0){
                $sql.=' limit '.$offset.','.$fetchNum;
            }
            if(empty($this->_bindValue)){
                $stmt=$this->_prepare($sql);
            }else{
                $args[]=$this->_bindType;
                $parameter=array_merge($args,$this->_bindValue);
                $stmt=$this->_prepare($sql);
                call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            }
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            $this->clear();
            return $returnData;
        }catch (\Exception $e){
            throw new \Exception('self function [like] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
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
            throw new \Exception('[min] parameter error!',$this->_paraErrorNo);
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
            throw new \Exception('[max] parameter error!',$this->_paraErrorNo);
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
            throw new \Exception('[avg] parameter error!',$this->_paraErrorNo);
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
            throw new \Exception('[sum] parameter error!',$this->_paraErrorNo);
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
    private function _group($sql,$where=array(),$orWhere=array()){
        try{
            if(!empty($where)){
                if(!$this->_and($where)){
                    $this->clear();
                    throw new \Exception('external function [_and] exception',$this->_funErrorNo);
                }
                $sql.=' where '.$this->_wheres;
            }
            if(!empty($orWhere)){
                if(!$this->_or($orWhere)){
                    $this->clear();
                    throw new \Exception('external function [_or] exception',$this->_funErrorNo);
                }
                if(empty($where)){
                    $sql.=' where '.$this->_orWheres;
                }else{
                    $sql.=' or '.$this->_orWheres;
                }
            }
            if(empty($this->_bindValue)){
                $stmt=$this->_prepare($sql);
            }else{
                $args[]=$this->_bindType;
                $parameter=array_merge($args,$this->_bindValue);
                $stmt=$this->_prepare($sql);
                call_user_func_array(array($stmt,'bind_param'), self::refValues($parameter));
            }
            $stmt->execute();
            $returnData=$this->_dynamicBindResults($stmt);
            $stmt->free_result();
            $stmt->close();
            $this->clear();
            return $returnData;
        }catch (\Exception $e){
            throw new \Exception('self function [_group] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 开始事务
     * @return bool
     */
    public function beginTransaction(){
        return $this->link->autocommit(false);
    }

    /**
     * 提交事务
     * @return bool
     */
    public function commitTransaction(){
        return $this->link->commit();
    }

    /**
     * 事务回滚
     * @return bool
     */
    public function rollbackTransaction(){
        return $this->link->rollback();
    }
    /**
     * 参数化查询初始化参数
     * @link http://php.net/manual/zh/mysqli-stmt.bind-param.php
     * @param  array $data
     * @return array
     */
    private static function refValues($data){
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
    private function _determineType($dataType)
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
        throw new \Exception('self function [_determineType] exception ,message: data type not found!',$this->_selfErrorNo);

    }

    /**
     * 取得结果集
     * @link   https://github.com/joshcam/PHP-MySQLi-Database-Class/tree/v1.1
     * @version   1.1
     * @param \mysqli_stmt $stmt
     * @return array
     */
    private function _dynamicBindResults($stmt)
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
     * 验证sql与表的正确性
     * @param $sql
     * @return \mysqli_stmt
     * @throws \Exception
     */
    private function _prepare($sql){
        try{
            $stmt=$this->link->prepare($sql);
            if (!$stmt){
                $msg=$this->link->error . " --SQL: " . $sql;
                throw new \Exception('self function [_prepare] exception ,message: '.$msg,$this->_selfErrorNo);
            }
            return $stmt;
        }catch (\Exception $e){
            throw new \Exception('self function [_prepare] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 插入数据绑定参数
     * @param $data
     * @return bool
     * @throws \Exception
     */
    private function iBand($data){
        try{
            $keyArr=array();
            $tmpArr=array();
            $valueArr=array();
            foreach ($data as $key=>$value){
                $keyArr[]=$key;
                $tmpArr[]='?';
                $valueArr[]=&$data[$key];
                $this->_bindType.= $this->_determineType($value);
            }
            $this->_keys=implode(',', $keyArr);
            $this->_values=implode(',',$tmpArr);
            $this->_bindValue=$valueArr;
            return true;
        }catch (\Exception $e){
            throw new \Exception('self function [iBand] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 更新数据绑定参数
     * @param $data
     * @return bool
     * @throws \Exception
     */
    private function uBand($data){
        try{
            $keyArr=array();
            $valueArr=array();
            foreach ($data as $key=>$value){
                $keyArr[]=$key.'=? ';
                $valueArr[]=&$data[$key];
                $this->_bindType.=$this->_determineType($value);
            }
            $this->_keys=implode(',',$keyArr);
            $this->_bindValue=$valueArr;
            return true;
        }catch (\Exception $e){
            throw new \Exception('self function [uBand] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 防止数据重复清楚数据
     */
    private function clear(){
        $this->_keys='';
        $this->_values='';
        $this->_bindType='';
        $this->_wheres='';
        $this->_orWheres='';
        $this->_bindValue=array();
        /*unset($this->_keys);
        unset($this->_values);
        unset($this->_bindType);
        unset($this->_bindValue);
        unset($this->_wheres);
        unset($this->_orWheres);*/
//        unset($this->_sql);
//        unset($this->_parameter);
    }

    /**
     * and 条件数据拼装
     * @param $where
     * @return bool
     * @throws \Exception
     */
    private function _and($where){
        try{
            $whereKeyArr=array();
            $whereValueArr=array();
            foreach($where as $keys=>$values){
                $this->_bindType.=$this->_determineType($values);
                $whereKeyArr[]=$keys.'=? ';
                $whereValueArr[]=&$where[$keys];
            }
            $whereStrTmp='';
            foreach($whereKeyArr as $row){
                $whereStrTmp.=$row.' and ';
            }
            $this->_wheres=substr($whereStrTmp,0,-4);
            if(!empty($this->_bindValue)){
                $this->_bindValue=array_merge($this->_bindValue,$whereValueArr);
            }else{
                $this->_bindValue=$whereValueArr;
            }
            return true;
        }catch (\Exception $e){
            throw new \Exception('self function [_and] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * or条件数据拼装
     * @param $orWhere
     * @return bool
     * @throws \Exception
     */
    private function _or($orWhere){
        try{
            $whereKeyArr=array();
            $whereValueArr=array();
            foreach($orWhere as $keys=>$values){
                $this->_bindType.=$this->_determineType($values);
                $whereKeyArr[]=$keys.'=? ';
                $whereValueArr[]=&$orWhere[$keys];
            }
            $whereStrTmp='';
            foreach($whereKeyArr as $row){
                $whereStrTmp.=$row.' or ';
            }
            $this->_orWheres=substr($whereStrTmp,0,-3);
            if(!empty($this->_bindValue)){
                $this->_bindValue=array_merge($this->_bindValue,$whereValueArr);
            }else{
                $this->_bindValue=$whereValueArr;
            }
            return true;
        }catch (\Exception $e){
            throw new \Exception('self function [_or] exception ,message: <  '.$e->getMessage().'  >',$this->_selfErrorNo);
        }
    }

    /**
     * 排序处理
     * @param $order
     * @return string
     */
    private function _order($order){
        $orderArr=array();
        foreach($order as $orderKey=>$rowOrder){
            $orderArr[]=$orderKey.' '.$rowOrder;
        }
        return implode(',',$orderArr);
    }
}