<?php
// +----------------------------------------------------------------------
// | Quyun API Suite
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.quyun.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 江林锦 <jianglj@quyun.com>
// +----------------------------------------------------------------------

/**
 * API类库惯例配置文件
 * 该文件请不要修改，如果要覆盖惯例配置的值，可在应用配置文件中设定和惯例不符的配置项
 * 配置名称大小写任意，系统会统一转换成小写
 * 所有配置参数都可以在生效前动态改变
 */
defined('THINK_PATH') or exit();
return  array(
    /* 开发相关配置 */
    'API_DEV_MODE'              => false,   // 是否在开发模式下。如果是则不做签名验证和重攻击验证，并开启assert检测，输出结果自动美化。

    /* 请求相关配置 */
    'API_BIND_PATH'             =>  '/',   // API请求绑定到的路径，支持通配符：*，通配符可通配所有字符（惰性匹配）。
    'API_HTTP_METHOD'           =>  'POST,GET', // 支持的HTTP请求方法，可选值为：GET、POST或二者的组合
    'API_ACTION_SEPARATOR'      =>  '.',    // API接口名使用的字段分隔符，不能使用字母和数字作为分隔符。
    'API_DEFAULT_ACTION'        =>  null,   // 默认API接口名，当接口名不合法时访问该接口，格式为：[模块名].[控制器名].[操作名]。
    'API_ACTION_SUFFIX'         =>  'Action',   // 默认的API操作名对应的ThinkPHP Action方法名后缀。
    'API_ACTION_MAP'            =>  null,   // API接口名映射规则，支持通配符：*和?。
    'API_REQUEST_PARAM_REPLACE' =>  null,   // API公共请求参数名替换，示例：array('api_action' => 'a')

    /* 响应相关配置 */
    'API_DEFAULT_FORMAT'        =>  'json', // 默认返回的数据类型。
    'API_DEFAULT_CALLBACK'      =>  'callback', // 默认的 jsonp 回调函数名。
    'API_RESULT_HAS_REQUESTID'  =>  false,  // 是否在结果中返回REQUESTID。
    'API_RESULT_HAS_ACTION'     =>  true,   // 是否在结果中返回ACTION。
    'API_SUCCESS_CODE'          =>  'ok',   // 默认的成功返回代码。
    'API_ERROR_CODE_CHECK'      =>  true,   // 是否检测错误码的格式，仅在开发模式下有效，在非开发模式下会自动关闭检测。
    'API_RESULT_PARAM_REPLACE'  =>  null,   // API返回参数名替换，示例：array('REQUESTID' => 'rid')

    /* 日志相关配置 */
    'API_LOG_ENABLED'           =>  false,  // 是否启用API日志。
    'API_LOG_CONNECTION'        =>  null,   // API日志数据库连接配置。
    'API_LOG_TABLENAME'         =>  'api_log', // API基础日志表名。
    'API_LOG_DETAIL_ENABLED'    =>  true,   // 是否记录详细日志。
    'API_LOG_DETAIL_TABLENAME'  =>  'api_log_detail',  // API详细日志表名。
    'API_LOGEX_MAP'             =>  null,   // API日志扩展配置，格式如：array('field1' => 'Col1', 'field2' => 'Col2')
    'API_LOG_GEO_ENABLED'       =>  true,   //是否分析API请求者的地理位置。
    'API_LOG_GEO_LIBPATH'       =>  'qqwry.dat',    // IP地址库文件路径。默认放置在与Org\Net\IpLocation类文件同一目录。

    /* 扩展标签配置 */
    'API_REQUEST_INIT_TAG'      =>  'api_request_init', // API请求开始行为标签。
    'API_CONTROLLER_BEFORE_TAG' =>  'api_controller_before',// API控制器实例化之前的行为标签。
    'API_CONTROLLER_INIT_TAG'   =>  'api_controller_init',  // API控制器实例化时的行为标签。
    'API_LOG_BEFORE_ADD_TAG'    =>  'api_log_before_add',   // API日志记录增加前的行为标签。

    /* API签名相关配置 */
    'API_SIGN_ENABLED'          =>  false,  // 是否启用签名验证。
    'API_SIGN_APPID_ENABLED'    =>  false,  // 是否验证参数中的应用ID。值为false时表示不验证应用ID。
    'API_SIGN_KEY'              =>  null,   // API签名使用的密钥。
    'API_SIGN_EXPIRETIME'       =>  600,    // API签名过期时间，单位为秒。
    'API_SIGN_ERRORBAND'        =>  300,    // API时间戳误差时间，单位为秒。
    'API_SIGN_CONNECTION'       =>  null,   // API签名信息数据库连接配置。
    'API_SIGN_TABLENAME'        =>  'api_app', // API签名信息表名。

    /* 网络重攻击防御相关配置 */
    'API_SIGN_NONCE_ENABLED'    =>  false,      // 是否启用防御网络重攻击机制.
    'API_SIGN_NONCE_STORAGE'    =>  'mysql',    // 随机串存储引擎，支持memcache和mysql。
    'API_SIGN_NONCE_MEMCACHE'   =>  array('host'=>'127.0.0.1', 'port'=>'11211', 'persistent'=>false),    // memcache连接信息。
    'API_SIGN_NONCE_KEYPREFIX'  =>  'api_sign_nonce_',  // 随机串存储键名前缀，存储引擎为memcache时有效。
    'API_SIGN_NONCE_CONNECTION' =>  null,   // 随机串存储mysql连接配置。
    'API_SIGN_NONCE_TABLENAME'  =>  'api_sign_nonce',  // 随机串存储表名，存储引擎为mysql时有效。
    'API_SIGN_NONCE_CLEARRATE'  =>  1000,   // 自动清理概率，存储引擎为mysql时有效。
    'API_SIGN_NONCE_EXPIRETIME' =>  900,    // 随机串过期多久后进行自动清理nonce，单位为秒，需大于API_SIGN_EXPIRETIME的设置。

    /* Model相关配置 */
    'API_LIST_DEFAULT_OPTIONS'  =>  null,   // 列表操作默认选项
    'API_DETAIL_DEFAULT_OPTIONS'=>  null,   // 详情操作默认选项
    'API_CREATE_DEFAULT_OPTIONS'=>  null,   // 添加操作默认选项
    'API_UPDATE_DEFAULT_OPTIONS'=>  null,   // 更新操作默认选项
    'API_DELETE_DEFAULT_OPTIONS'=>  null,   // 删除操作默认选项

    /* 多语言相关配置 */
    'API_LANG_PKG_ENABLED'      =>  false,  // 是否启用语言包，启用后将根据api_lang指定的语言输出错误消息。
    'API_LANG_AUTOGEN'          =>  true,  // 是否根据错误码自动生成错误消息，启用后将根据api_lang指定的语言自动生成错误消息。
    'API_LANG_LIST'             =>  'zh-cn,en-us',  // 允许的语言包列表，用逗号分隔。
    'API_DEFAULT_LANG'          =>  'zh-cn',    // 默认语言包。

    /* 测试相关配置 */
    'API_TEST_ENABLED'          =>  false,  // 是否启用API测试。
    'API_TEST_CONFIG'           =>  '', // API测试模式下自动加载的配置文件名，不带.php后缀。
    'API_TEST_MODULE'           =>  'Test', // 接口测试专用模块名。
    'API_TEST_SUFFIX'           =>  'Test', // 接口测试类文件名后缀。

    /* 扩展配置 */
    'API_LOAD_EXT_CONFIG'       =>  '',  // 扩展配置：自动加载的额外的自定义配置文件名，以,分隔，不带.php后缀。
);
