# YXLog - 自定义TP5日志驱动

- 版本：1.0.5 （更新时间：2017-9-19）
- 尽量使用tp5.0.6以上版本，低版本在设置上可能会有不同

### 最新更新
- 修改transid逻辑，添加trans_id生成和设置控制。
- 添加trans_id配置
- 修改README中使用部分

### 安装

- 单独安装  **(推荐)**

下载代码库: `git clone https://git.oschina.net/yx_tech/YXLog.git`
将生成的`YXLog`目录拷贝至tp5项目下的`extend`目录下，形成如下目录结构：

```php
├─app
├─extend                扩展类库目录
│  ├─YXLog              YXLog
│  │  ├─driver          YXLog驱动目录
│  │  └─YXLog.php       YXLog入口文件
├─www
├─runtime
├─vendor
├─thinkphp
├─composer.json
```

- 在已有git项目中使用

使用git的`submodule`命令直接将代码库安装至`extend`目录下。切换至tp5项目的extend

`git submodule add https://git.oschina.net/yx_tech/YXLog.git`

【 **注意事项** 】

【坑1】 如果是初始化含有submodule的项目，在`git clone`完成后，请记得在此项目根目录使用` git submodule update --init --recursive `来下载和更新submodule内容。

【坑2】 在更新含有submodule的项目时，也应该注意在` git pull `之后，用` git status `查看一下是否submodule有更新，从而使用` git submodule foreach git submodule update `去批量更新这些submodule（submodule嵌套submodule），当然也可以一个个进入submodule用` git submodule update `来更新。

【坑3】 在含有submodule项目代码管理中，尽量避免使用` git commit -a `来一次性添加和提交修改，而应用`git status`查看下submodule的状态，避免将提交覆盖submodule，如果发现submodule有更新而应该执行一次` git submodule update `后再提交。

【坑4】 原则上尽量不要再包含submodule的项目中修改submodule中的内容，如果头脑发热给修改了，请记住submodule中对应的版本库初始化时默认的HEAD是处于游离状态的(‘detached HEAD’ state)，所以在进入submodule后需要执行` git checkout master `后再提交对submodule的修改提交。

### 使用

1. 配置全局`config.php`文件，主要设置`type、path、prefix`配置

```php
// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
    'log'              => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'         => 'YXLog\driver\YXLog',
        // 日志保存目录
        'path'         => LOG_PATH,
        // 日志记录级别，系统内置['log', 'error', 'info', 'sql', 'notice', 'alert', 'debug'];
        'level'        => [],
        //拆分日志的文件大小(默认2M)
        'file_size'    => 2097152,
        // 将错误、调试、日志单独记录
        'apart_level'  => [],
        // 日志文件前缀 (YXLog定义，如果没有指定或为空，则使用访问地址作为文件前缀)
        'prefix'       => 'yx_',
        //是否保存tp产生的日志info信息 (YXLog定义)
        'save_tp_info' => true,
        //不保存tp信息时需要排除的相关等级
        'save_tp_tag'  => ['error', 'sql'],
    ],
```

2. 引用YXLog命名空间，调用相应方法，如果没有use引用，在YXLog类前加\命名空间

使用举例：

```php
<?php
//use引用方式
use YXLog;

class Index{
	public function index(){
		//在需要记录日志的地方
		YXLog::logError("错误信息");
	}
}
```
```php
<?php
//直接使用方式
class Index{
	public function index(){
		//在需要记录日志的地方
		\YXLog\YXLog::logError("错误信息");
	}
}
```

### 方法

- 通用调用方式：

`XYLog::调用方法名(string $msg,[string $tag])`

参数说明:

**$msg**: 要记录的日志内容，string类型,必填

**$tag**: 自定义的日志标志，用来手工标识相关联的日志记录，string类型，非必须


- 方法列表：

`XYLog::logError($msg,$tag)`，简化方法 `XYLog::error($msg,$tag)` ：记录错误

`XYLog::logInfo($msg,$tag)`，简化方法 `XYLog::info($msg,$tag)`：记录信息

`XYLog::logDebug($msg,$tag)`，简化方法 `XYLog::debug($msg,$tag)`：记录bug

`XYLog::logNotice($msg,$tag)`，简化方法 `XYLog::notice($msg,$tag)`：记录notice

`XYLog::logAlert($msg,$tag)`，简化方法 `XYLog::alert($msg,$tag)`：记录alert

`XYLog::logFatal($msg,$tag)`，简化方法 `YXLog::fatal($msg,$tag)`：记录fatal

2017-9-19新增：
`XYLog::getTransId()`：获取日志生成的trans_id

`XYLog::setTransId($trans_id)`：设置此后日志trans_id

`XYLog::buildTransId()`：按规则生成trans_id，不写入日志

`XYLog::initTransId($trans_id,$force)`：重新初始化trans_id



### 日志记录

- 日志文件名称会根据`config.php`配置的`path`和`prefix`按照日期进行创建。
例如`path`设置为`"/data/log"`,`prefix`设置为`"yx_"`，生成的日志文件为：`/data/log/yx_20170816.log`。如果日志文件大小大于`file_size`设置，则将当前日志文件重命名后保存，命名规则为：`/data/log/yx_20170816_140540.log`（新建规则为当前时间)。因此，最新的日志均记录在`/data/log/yx_20170816.log`中。

- 日志文件记录格式：

`[时间（精确至毫秒）][错误等级]错误信息[tag:自定义错误标识][调用日志函数所在文件位置][服务器地址 访问主机地址 访问方式 访问域名][runtime:运行时间][seq:来源标识][transid:transid标识]`

记录举例：
`[2017-08-20 17:27:11.456][error] logError? [tag:myerror][D:\Server\WWW\oscar\app_center\app\index\controller\Index.php(25): YXLog\YXLog::logError()] [127.0.0.1 127.0.0.1 GET http://app_center.local.me/][runtime:0.008966s][sessid:d41d8cd98f00b204e9800998ecf8427e][transid:150322123127325577]`

- trans_id使用：
建议使用`YXLog::setTransId()`来自定义trans_id。如果使用think\log的，可以用 `Config::set('log.trans_id',$trans_id) `来自定义trans_id。

### 注意事项

- 由于是tp5的log驱动，所以tp5自带的Log类方法也会受影响。因此也可以用Log::write($msg,$level)来记录，但不推荐，更不要用Log::record()方法。

- 记录中出现'[ ** ]'类似字样的为tp系统信息。

- 如果发现有因文件大小分割的日志文件，最新的日志记录应该在未添加分割标识的日志文件中查找。详见“日志记录”环节。

--未完，待续--