Yii + Angularjs

Зависимости
------------

Я локально установил php7.1, mysql5.6, nginx, node7.7.3 и npm 4.1.3

Установка
-------------

Разворачиваем проект из гита локально
Далее запускаем команду npm install
После в папке protected запускаем php yiic.php migrate

Настройка
-------------

### База данных

Конфигирурем базу, например:

```php
'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=resmi',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
```

Север
-------------

#Рекомендуемая Apache конфигурация:

```php
RewriteEngine on

# не позволять httpd отдавать файлы, начинающиеся с точки (.htaccess, .svn, .git и прочие)
RedirectMatch 403 /\..*$
# если директория или файл существуют, использовать их напрямую
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
# иначе отправлять запрос на файл index.php
RewriteRule . index.php
```

#Рекомендуемая Nginx конфигурация:

```php
server {
    set $host_path "/www/mysite";
    access_log  /www/mysite/log/access.log  main;

    server_name  mysite;
    root   $host_path/htdocs;
    set $yii_bootstrap "index.php";

    charset utf-8;

    location / {
        index  index.html $yii_bootstrap;
        try_files $uri $uri/ /$yii_bootstrap?$args;
    }

    location ~ ^/(protected|framework|themes/\w+/views) {
        deny  all;
    }

    # отключаем обработку запросов фреймворком к несуществующим статичным файлам
    location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
        try_files $uri =404;
    }

    # передаем PHP-скрипт серверу FastCGI, прослушивающему адрес 127.0.0.1:9000
    location ~ \.php {
        fastcgi_split_path_info  ^(.+\.php)(.*)$;

        # позволяем yii перехватывать запросы к несуществующим PHP-файлам
        set $fsn /$yii_bootstrap;
        if (-f $document_root$fastcgi_script_name){
            set $fsn $fastcgi_script_name;
        }

        fastcgi_pass   127.0.0.1:9000;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fsn;

        # PATH_INFO и PATH_TRANSLATED могут быть опущены, но стандарт RFC 3875 определяет для CGI
        fastcgi_param  PATH_INFO        $fastcgi_path_info;
        fastcgi_param  PATH_TRANSLATED  $document_root$fsn;
    }

    # не позволять nginx отдавать файлы, начинающиеся с точки (.htaccess, .svn, .git и прочие)
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
}
```