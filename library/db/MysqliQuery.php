<?php

/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/4/20
 * Time: 10:14
 * @version 1.0.1
 * @function 不参数化查询，拼装sql语句直接查询
 * @link //link http://www.php.net/manual/en/mysqli-result.fetch-array.php
 */
class MysqliQuery
{
    /**
     * @var mysqli
     */
    private $link;

    /**
     * 初始化数据库连接
     * @throws Exception
     */
    function __construct(){
        $defaultDb= _BASE_PATH.'/library/config/db.config.php';
        $config=$defaultDb['default_db'];
        $conn=new mysqli($config['db_host'],$config['db_user'],$config['db_pwd'],$config['db_name'],$config['port']);
        if($conn->errno){
            throw new Exception('数据连接错误，原因：'.$conn->error);
        }
        $conn->set_charset($config['db_char_set']);
        $this->link=$conn;
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
     * @throws Exception
     */
    public function insert($table,$data){
        if(!is_array($data)||empty($data)){
            throw new Exception('插入数据格式有误');
        }
        $keyArr=array();
        $valueArr=array();
        foreach ($data as $key=>$value){
            $keyArr[]="`".$key."`";
            $valueArr[]="'".$value."'";
        }
        $keys=implode(',', $keyArr);
        $values=implode(',', $valueArr);
        $sql="insert into ".$table." (".$keys.") values (".$values.")";
        $this->link->query($sql);
        $res=$this->link->affected_rows;
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
     * @throws Exception
     */
    public function updateId($table,$id,$data){
        if(!is_array($data)||empty($data)){
            throw new Exception('根据id更新数据格式错误或者为空数组');
        }
        $tmpArr=array();
        foreach ($data as $key=>$value){
            $tmpArr[]="`".$key."`='".$value."'";
        }
        $keyAndValues=implode(",",$tmpArr);
        $sql='update '.$table.' set '.$keyAndValues." where id='{$id}'";
        $this->link->query($sql);
        $res=$this->link->affected_rows;
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
     * @throws Exception
     */
    public function update($table,$data,$where){
        if(!is_array($data)||empty($data)||!is_array($where)||empty($where)){
            throw new Exception('条件数据数据格式错误或者为空数组');
        }
        //拼装需要更新的数据
        $tmpArr=array();
        foreach ($data as $key=>$value){
            $tmpArr[]=$key."='".$value."'";
        }
        $keyAndValues=implode(',',$tmpArr);
        $sql='update '.$table.' set '.$keyAndValues.' where ';
        $whereString=$this->andWhere($where);
        $sql.=$whereString;
        $this->link->query($sql);
        $res=$this->link->affected_rows;
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
        $sql='delete from '.$table." where id='{$id}'";
        $this->link->query($sql);
        $res=$this->link->affected_rows;
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
     * @throws Exception
     */
    public function delete($table,$where){
        if(!is_array($where)||empty($where)){
            throw new Exception('条件删除数据格式错误或者为空数组');
        }
        $sql="delete from ".$table." where ";
        $whereString=$this->andWhere($where);
        $sql.=$whereString;
        $this->link->query($sql);
        $res=$this->link->affected_rows;
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
     * @throws Exception
     */
    public function fetchWhere($table,$where,$order=array(),$offset=0,$fetchNum=0,$getInfo=array('*'),$orWhere=array()){
        if(!is_array($where)||empty($where)||!is_array($order)||!is_array($orWhere)){
            throw new Exception('条件查询数据格式错误，请检查');
        }
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $whereString=$this->andWhere($where);
        $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$whereString;
        if(!empty($orWhere)){
            $orWhereString=$this->orWhere($orWhere);
            $sql.=' or '.$orWhereString;
        }
        if(!empty($order)){
            $orderTmpArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderTmpArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderTmpArr);
        }
        if($fetchNum>0&&$offset>=0){
            $sql.=' limit '.$offset.','.$fetchNum;
        }
        $result=$this->link->query($sql);
        while($resultRow=$result->fetch_assoc()){
            $returnData[]=$resultRow;
        }
        $result->close();
        return isset($returnData)?$returnData:array();
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
     * @throws Exception
     */
    public function fetchAll($table,$order=array(),$offset=0,$fetchNum=0,$getInfo=array('*'),$orWhere=array()){
        if(!is_array($order)||!is_array($orWhere)||!is_array($order)){
            throw new Exception('查询所有数据格式错误，请检查');
        }
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql="select ".implode(',',$getInfo).' from '.$table;
        if(!empty($orWhere)){
            $orWhereString=$this->orWhere($orWhere);
            $sql.=' where '.$orWhereString;
        }
        if(!empty($order)){
            $orderTmpArr=array();
            foreach($order as $orderKey=>$rowOrder){
                $orderTmpArr[]=$orderKey.' '.$rowOrder;
            }
            $sql.=' order by '.implode(',',$orderTmpArr);
        }
        if($fetchNum>0&&$offset>=0){
            $sql.=' limit '.$offset.','.$fetchNum;
        }
        $result=$this->link->query($sql);
        while($resultRow=$result->fetch_assoc()){
            $returnData[]=$resultRow;
        }
        $result->close();
        return isset($returnData)?$returnData:array();
    }

    /**
     * 根据id查询 返回一维数组或空
     * @param string $table 表名
     * @param string $id 获取数据的id
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @return array
     */
    public function fetchId($table,$id,$getInfo=array('*')){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table." where id='{$id}'";
        $result=$this->link->query($sql);
        $returnData=$result->fetch_assoc();
        $result->close();
        return isset($returnData)?$returnData:array();
    }

    /**
     * 根据sql语句查询
     * @param string $sql
     * @return array
     */
    public function fetchSql($sql){
        $result=$this->link->query($sql);
        if(stristr($sql,'select')){
            while($resultRow=$result->fetch_assoc()){
                $returnData[]=$resultRow;
            }
            $result->close();
            return isset($returnData)?$returnData:array();
        }else{
            $res=$this->link->affected_rows;
            if($res>0){
                return true;
            }
            return false;
        }
    }

    /**
     * 获取行的数量
     * @param string $table 表名
     * @param array $where 获取行数的条件 数据格式 数组键值为数据库字段，键值对应的值为满足获的条件 类似 array('id'=>'king','name'=>'test')
     * @param string $columnName 列名
     * @return string|int
     */
    public function getCount($table,$where=array(),$columnName='*'){
        $sql="select count(".$columnName.") as count from ".$table;
        if(!empty($where)&&is_array($where)){
            $whereString=$this->andWhere($where);
            $sql.=" where ".$whereString;
        }
        $result=$this->link->query($sql);
        $returnData=$result->fetch_assoc();
        $result->close();
        return $returnData['count'];
    }

    /**
     * 不等于或者大于说着其他操作 直接是字符串
     * @param string $table 表名
     * @param string $whereString "id>10 or id<3 and name='test'"
     * @param array $getInfo 需要查询出来的字段 无键值的数组 填写需要查询的字段即可 类似 array('id','name')
     * @return array
     */
    public function notEqualAll($table,$whereString,$getInfo=array('*')){
        if(empty($getInfo)||!is_array($getInfo)){
            $getInfo=array('*');
        }
        $sql='select '.implode(',',$getInfo).' from '.$table.' where '.$whereString;
        $result=$this->link->query($sql);
        while($resultRow=$result->fetch_assoc()){
            $returnData[]=$resultRow;
        }
        $result->close();
        return isset($returnData)?$returnData:array();
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
     * and 条件数据拼装
     * @param array $where
     * @return string
     */
    protected function andWhere($where){
        $tmpArr=array();
        foreach($where as $whereKey=>$whereRow){
            $tmpArr[]=$whereKey."= '".$whereRow."'";
        }
        $whereStringTmp='';
        foreach($tmpArr as $row){
            $whereStringTmp.=$row.' and ';
        }
        $whereString=substr($whereStringTmp,0,-4);
        return $whereString;
    }

    /**
     * or 条件数据拼装
     * @param array $orWhere
     * @return string
     */
    protected function orWhere($orWhere){
        $orWhereTmp=array();
        foreach($orWhere as $orWhereKeys=>$orWhereValues){
            $orWhereTmp[]=$orWhereKeys." = '".$orWhereValues."'";
        }
        $orWhereString='';
        foreach($orWhereTmp as $orRow){
            $orWhereString.=' or '.$orRow;
        }
        return $orWhereString;
    }
}