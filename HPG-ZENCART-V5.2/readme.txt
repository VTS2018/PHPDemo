
安装：
1. 上传 接口文件下所有文件到Zen Cart的相应目录。
2. 将接口文件\includes\modules\payment文件夹内容，放置到zencart\includes\modules\payment目录下
3. 将接口文件\includes\languages\english\modules\payment文件夹下的内容，放置到zencart\includes\languages\english\modules\payment目录下
4. 将接口文件\includes\languages\schinese\modules\payment文件夹下的内容，放置到放置到zencart\includes\languages\schinese\modules\payment目录下
5.将接口文件paymenturl文件夹放到网站根目录
6. 管理页面->模块管理->支付模块->和融通支支付，根据提示设置各项参数,支付页面地址设置为./myorderpayment.php

警告：如果出现 警告的提示，将数据库里的的orders_status表里的orders_status_id里的800到809删除。

