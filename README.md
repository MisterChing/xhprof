# xhprof + tideways php profiler

## Requirement
- xhprof or tideways

## Installation

### Install xhprof

~~~
git clone https://github.com/Yaoguais/phpng-xhprof.git
cd phpng-xhprof
phpize
./configre --with-php-config=/path/to/php-config
make && make install
~~~

add the configuration into php.ini

~~~
[xhprof]
extension=phpng_xhprof.so
xhprof.output_dir=/path/to/data
;xhprof.count_prefix=
~~~

### install tideways

~~~
git clone https://github.com/tideways/php-profiler-extension.git
cd php-profiler-extension
phpize
./configure --with-php-config=/path/to/php-config
make && make install
~~~

add the configuration into php.ini

~~~
[tideways]
extension=tideways.so
tideways.auto_prepend_library=0
xhprof.output_dir=/path/to/data
~~~

## Usage with xhprof or tideways

### Nginx (recommendatory)

pass the auto_prepend_file to PHP-FPM

~~~
fastcgi_param PHP_VALUE "auto_prepend_file=/path/to/gen.php";
~~~

### PHP-FPM

add the configuration into php-fpm configure file

~~~
php_admin_value[auto_prepend_file]=/path/to/gen.php
~~~

## Documentation

[Tideways Profiler website](https://tideways.io/profiler/docs/setup/profiler-php-pecl-extension)

[Xhprof](https://github.com/Yaoguais/phpng-xhprof)








