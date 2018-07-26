<?php
/**
 * UCenter客户端配置文件
 * 注意：该配置文件请使用常量方式定义
 */

define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_AUTH_KEY', 'G%hz1Wy$A&u7MSa6ZViD`x*9)5"U?csn[EIKp/w|'); //加密KEY
define('UC_DB_DSN', 'mysqli://bdm246300528:qwe12358@bdm246300528.my3w.com:3306/bdm246300528_db'); // 数据库连接，使用Model方式调用API必须配置此项
//define('UC_DB_DSN', 'mysqli://root:@127.0.0.1:3306/oth'); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', 'th_'); // 数据表前缀，使用Model方式调用API必须配置此项
