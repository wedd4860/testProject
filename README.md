orderAdmin Solution
-------------
> This is a blockqute.
* 빨강
* 녹색
* 파랑

Install
-------------

error
-------------
> db 설치 확인
```
rpm -qa libjpeg* libpng* freetype* gd-* gcc gcc-c++ gdbm-devel libtermcap-devel
rpm -qa mariadb mariadb-server
rpm -qa httpd mariadb php
```
> db 한글 깨짐
```
경로 : vi /etc/my.cnf
하단 추가 :
character_set_server=utf8
collation_server=utf8_general_ci
init_connect=set collation_connection=utf8_general_ci
init_connect=set names utf8
character-set-server=utf8
character-set-client-handshake = TRUE
```
> mcrypt 암호화 오류
```
에러코드 내용 : Fatal error: Call to undefined function mcrypt_create_iv()
에러코드 설명 : mcrypt_create_iv 함수를 인식하지 못한다.
확인방법 :
php -m | grep mcrypt
rpm -qa | grep mcrypt
yum list php-mcrypt
해결방법 :
yum install epel-release
yum install php-mcrypt
```
> 그외 내부서버 오류
```
확인방법 : ini_set('display_errors', true);
```