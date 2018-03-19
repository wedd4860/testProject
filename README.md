orderAdmin Solution
-------------
* 개인용 파일입니다.
* 주문서 프로그램 입니다.
* LAMP (Linux, Apache, MySQL, PHP) 환경을 추천 드립니다.
* PHP 5.5 or higher

init
-------------> db 설치 확인
```
경로 : include/init.php
루트 폴더를 기입(line 79) : define('_FOLDER_ROOT', '')

암호화 키 변경(line 58) : define("_client_id", "jvh8QiFHp9OXURYhBE_n")
암호화 키 변경(line 59) : define("_client_secret", "FBk5WFJrcO")

개발용 아이피 주소 기입(line 50) : $_SERVER["SERVER_ADDR"] == '127.0.0.1'
개발용 db name 기입(line 52) : define("_DB_NAME", "")
실제 서버용 db name 기입(line 55) : define("_DB_NAME", "")

경로 : include/init.dbPDO.php
db 아이디 비밀번호 기입(line 7 ~ 8) : $user,$pass

경로 : install/order.sql
db 아이디 비밀번호 기입(line 17) : 관리자 정보와 비밀번호 설정
```

install
-------------
* install/index.php 실행 하여 db install
* 또는 order.sql 파일을 에디트로 열어 개별 환경에서 실행
* install이 끝나면 install 폴더를 삭제

permission
-------------
> (참고용)관리 등급에 따른 설정 진행
* 전체 디렉토리 권한 : 755, 766
* templates 디렉토리 권한 : 755, 766 / 파일 권환 : 644
* download 디렉토리 권한 : 755, 766

directory info
-------------
> /action
* (back-end) db의 직접적인 처리를 담당하는 디렉토리
> /bower_components
* (front-end) bower를 통해 설치된 자바스크립트 라이브러리들
> /dist
* (front-end) front의 css, js, img 디렉토리
> /download
* (back-end) 엑셀 다운로드에 필요한 디렉토리
> /include
* (back-end) php 설정 및 utils, class 정의
> /install
* (back-end) db관련 정보를 볼수 있는 디렉토리
> /logs
* (back-end) front의 에러 내용을 텍스트 파일로 저장하는 디렉토리
> /plugins
* (front-end) front의 공통 js 디렉토리
> /templates
* (front-end) templates를 사용하여 view 처리

directory info
-------------
> /include/init.php
* (back-end) 가장 먼저 로드 되며 프로그램 설정 정의
> /include/init.utils.php
* (back-end) php 관련 utils 정의

error info
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

library info
-------------
* (back-end)Smarty-3.1.16
* (back-end)PHPExcel version 2.1
* (front-end)adminLTE