# shopee-api

## 介绍

通过shopee提供的api获取店铺订单相关信息，封装了shopee接口！

## 环境要求

- PHP 7.0+
- [Composer](https://getcomposer.org/)


# 安装

推荐的方式是通过composer 进行下载安装[composer](http://getcomposer.org/download/)。

在命令行执行
```
composer require volicano/shopee-api
```
或加入
```
"volicano/shopee-api": "dev-master"
```
到你的`composer.json`文件中的require段。

## 使用

```php
use Shopee\ShopeeApi;

$shopee_api = new ShopeeApi\ShopeeApi();
$list = $shopee_api->getOrderList(SHOP_ID,SHOPEE_PARTNER_ID);
var_dump($list);
```
先通过shopee api获取授权拿到shop_id  参考：[shopee api](https://open.shopee.com/documents?module=63&type=2&id=51)
## License

MIT


