# unit_tool
常用的测试工具一下测试内容

/
└config 配置文件夹
    └config.php 配置数据文件
    └MysqliConnect.php 配置表名方便使用
└db 数据库连接函数
    └MysqliAbstract.php 参数化查询的抽象类
    └MysqliQuery.php 直接组装sql使用query函数查询
    └MysqliStmt.php 参数化查询，组装在内部组装

└doc 文档数据等文件夹
    └fr_test_v1.0.2.sql 测试使用的sql脚本文件

└public 工作目录
    └defualt.php 默认例子文件

└libs 第三方库

└util 工具目录
    └HttpUtils.php http请求工具
    └ParameterUtils.php 参数验证工具