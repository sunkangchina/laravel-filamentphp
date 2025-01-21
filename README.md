
## 安装


- 路由配置

~~~
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
~~~

- 数据库迁移

~~~ 
php artisan migrate
~~~

- 帐号

~~~
php artisan make:filament-user
php artisan shield:generate --all #使权限生效
php artisan  shield:super-admin   #生成超级管理员
~~~

默认帐号及密码

~~~
demo@163.com
111111
~~~

- 访问

API 访问地址：/apidoc


## 依赖
- [laravel 11](https://laravel.com/docs/11.x)
- [laravel-route-attributes](https://github.com/spatie/laravel-route-attributes)
- [apidoc](https://docs.apidoc.icu/use/)
- [locales](https://laravel-lang.com/usage-add-locales.html)
- [easywechat](https://easywechat.com/6.x/)
- [filamentphp](https://filamentphp.com/docs/3.x/panels/installation)

##  Octane

~~~
location / {
    try_files $uri $uri/ @octane;
} 

location @octane {
    set $suffix "";

    if ($uri = /index.php) {
        set $suffix ?$query_string;
    }

    proxy_http_version 1.1;
    proxy_set_header Host $http_host;
    proxy_set_header Scheme $scheme;
    proxy_set_header SERVER_PORT $server_port;
    proxy_set_header REMOTE_ADDR $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection $connection_upgrade;

    proxy_pass http://127.0.0.1:8000$suffix;
}
~~~

