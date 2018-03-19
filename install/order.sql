DROP TABLE IF EXISTS at_member_info;
CREATE TABLE IF NOT EXISTS at_member_info (
    `member_seq`        INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '사용자시퀀스', 
    `member_id`         VARCHAR(20)     NOT NULL     COMMENT '아이디', 
    `member_name`       VARCHAR(20)     NOT NULL     COMMENT '이름', 
    `member_nick`       VARCHAR(20)     NOT NULL     COMMENT '닉네임', 
    `member_pwd`        VARCHAR(255)    NOT NULL     COMMENT '패스워드', 
    `member_pwd_moddt`  DATETIME        NULL         COMMENT '패스워드 수정일', 
    `member_level`      INT(3)          NULL         COMMENT '등급', 
    `member_status`     TINYINT(1)      NOT NULL     COMMENT '0:사용,1:사용중지,9:삭제', 
    `member_moddt`      DATETIME        NULL         COMMENT '수정일', 
    `member_regdt`      DATETIME        NOT NULL     COMMENT '등록일', 
    PRIMARY KEY (member_seq, member_id)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_member_info COMMENT '사용자 정보';
INSERT INTO at_member_info (member_id, member_name, member_nick, member_pwd, member_level, member_status, member_regdt) VALUES ('admin', '관리자', '최고관리자', password('asdf'), 100, 0, NOW());


DROP TABLE IF EXISTS at_fair_info;
CREATE TABLE IF NOT EXISTS at_fair_info
(
    `fair_seq`         INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '전시회시퀀스', 
    `fair_title`       VARCHAR(30)     NULL         COMMENT '전시회이름', 
    `fair_sdt`         VARCHAR(8)      NULL         COMMENT 'yyyymmdd', 
    `fair_edt`         VARCHAR(8)      NULL         COMMENT 'yyyymmdd', 
    `fair_regdt`       DATETIME        NULL         COMMENT '등록일', 
    `fair_moddt`       DATETIME        NULL         COMMENT '수정일', 
    `fair_user_agent`  varchar(255)    NULL         COMMENT '등록환경', 
    `fair_ip`          varchar(15)     NULL         COMMENT '등록아이피', 
    `fair_status`      TINYINT(1)      NULL         COMMENT '상태', 
    PRIMARY KEY (fair_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_fair_info COMMENT '전시회정보';


DROP TABLE IF EXISTS at_category_info;
CREATE TABLE IF NOT EXISTS at_category_info
(
    `category_seq`         INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '카테고리시퀀스', 
    `category_title`       varchar(100)    NULL         COMMENT '카테고리이름', 
    `category_code`        varchar(12)     NULL         COMMENT '카테고리코드', 
    `category_sort`        INT(10)         NULL         COMMENT '정렬', 
    `category_moddt`       DATETIME        NULL         COMMENT '수정일', 
    `category_regdt`       DATETIME        NULL         COMMENT '등록일', 
    `category_user_agent`  varchar(255)    NULL         COMMENT '등록환경', 
    `category_ip`          varchar(15)     NULL         COMMENT '등록아이피', 
    `category_status`      TINYINT(1)      NULL         COMMENT '상태', 
    PRIMARY KEY (category_seq),
    UNIQUE (category_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_category_info COMMENT '상품카테고리 정보';

DROP TABLE IF EXISTS at_goods_info;
CREATE TABLE IF NOT EXISTS at_goods_info
(
    `goods_seq`         INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '상품시퀀스', 
    `goods_title`       VARCHAR(255)    NOT NULL     COMMENT '상품이름', 
    `goods_name_sub`    VARCHAR(255)    NULL         COMMENT '상품보조이름', 
    `goods_code`        VARCHAR(30)     NOT NULL     COMMENT '제품코드', 
    `goods_desc`        MEDIUMTEXT      NULL         COMMENT '상품설명', 
    `goods_model`       VARCHAR(100)    NULL         COMMENT '모델명', 
    `goods_launchdt`    DATE            NULL         COMMENT '출시일', 
    `goods_origin`      VARCHAR(50)     NULL         COMMENT '원산지', 
    `goods_regdt`       DATETIME        NULL         COMMENT '등록일', 
    `goods_moddt`       DATETIME        NULL         COMMENT '수정일', 
    `goods_user_agent`  varchar(255)    NULL         COMMENT '등록환경', 
    `goods_ip`          varchar(15)     NULL         COMMENT '등록아이피', 
    `goods_status`      TINYINT(1)      NULL         COMMENT '0:사용,1:사용중지,9:삭제', 
    PRIMARY KEY (goods_seq),
    UNIQUE (goods_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_goods_info COMMENT '상품 정보';


DROP TABLE IF EXISTS at_goods_link_info;
CREATE TABLE IF NOT EXISTS at_goods_link_info
(
    `link_seq`           INT(10)       NOT NULL    AUTO_INCREMENT COMMENT '링크시퀀스', 
    `link_category_seq`  INT(10)       NOT NULL     COMMENT '카테고리시퀀스', 
    `link_goods_seq`     INT(10)       NOT NULL     COMMENT '상품시퀀스', 
    `sort`               TINYINT(1)    NULL         COMMENT '정렬', 
    `sort1`              TINYINT(1)    NULL         COMMENT '정렬1', 
    `sort2`              TINYINT(1)    NULL         COMMENT '정렬2', 
    PRIMARY KEY (link_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_goods_link_info COMMENT '상품연결';

DROP TABLE IF EXISTS at_goods_price_history;
CREATE TABLE IF NOT EXISTS at_goods_price_history
(
    `price_seq`             INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '상품가격시퀀스', 
    `price_fair_seq`        INT(10)         NULL         COMMENT '전시회시퀀스', 
    `price_goods_seq`       INT(10)         NULL         COMMENT '상품시퀀스', 
    `price_goods_consumer`  INT(10)         NULL         COMMENT '정가', 
    `price_goods_price`     INT(10)         NULL         COMMENT '판매가', 
    `price_goods_supply`    INT(10)         NULL         COMMENT '공급가', 
    `price_regdt`           DATETIME        NULL         COMMENT '등록일', 
    `price_moddt`           DATETIME        NULL         COMMENT '수정일', 
    `price_user_agent`      varchar(255)    NULL         COMMENT '등록환경', 
    `price_ip`              varchar(15)     NULL         COMMENT '등록아이피', 
    `price_status`          TINYINT(1)      NULL         COMMENT '상태', 
    PRIMARY KEY (price_seq, price_fair_seq, price_goods_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_goods_price_history COMMENT '상품 가격';


DROP TABLE IF EXISTS at_goods_set_info;
CREATE TABLE IF NOT EXISTS at_goods_set_info
(
    `set_seq`         INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '세트시퀀스', 
    `set_fair_seq`    INT(10)         NOT NULL     COMMENT '전시회시퀀스', 
    `set_title`       VARCHAR(255)    NOT NULL     COMMENT '세트이름', 
    `set_goods_cnt`   INT(3)          NULL         COMMENT '세트 내 제품수', 
    `set_regdt`       DATETIME        NULL         COMMENT '등록일', 
    `set_moddt`       DATETIME        NULL         COMMENT '수정일', 
    `set_user_agent`  varchar(255)    NULL         COMMENT '등록환경', 
    `set_ip`          varchar(15)     NULL         COMMENT '등록아이피', 
    `set_status`      TINYINT(1)      NULL         COMMENT '상태', 
    PRIMARY KEY (set_seq, set_fair_seq),
    UNIQUE (set_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_goods_set_info COMMENT '상품 세트 정보';


DROP TABLE IF EXISTS at_goods_set_option_info;
CREATE TABLE IF NOT EXISTS at_goods_set_option_info
(
    `set_option_seq`        INT(10)        NOT NULL    AUTO_INCREMENT COMMENT '세트상품시퀀스', 
    `set_option_set_seq`    INT(10)        NOT NULL     COMMENT '세트시퀀스', 
    `set_option_goods_seq`  INT(10)        NOT NULL     COMMENT '상품시퀀스', 
    `set_option_percent`    INT(10)        NULL         COMMENT '가격비율', 
    `set_option_opt1`       VARCHAR(50)    NULL         COMMENT '옵션1', 
    `set_option_status`     TINYINT(1)     NULL         COMMENT '상태', 
    PRIMARY KEY (set_option_seq, set_option_set_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_goods_set_option_info COMMENT '상품 세트 제품 옵션';


DROP TABLE IF EXISTS at_order_trans;
CREATE TABLE IF NOT EXISTS at_order_trans
(
    `order_seq`              INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '주문시퀀스', 
    `order_code`             VARCHAR(20)     NOT NULL     COMMENT '주문번호', 
    `order_fair_seq`         INT(10)         NOT NULL     COMMENT '전시회시퀀스', 
    `order_customer`         VARCHAR(20)     NULL         COMMENT '고객이름', 
    `order_mobile`           VARCHAR(15)     NULL         COMMENT '휴대폰', 
    `order_address`          VARCHAR(100)    NULL         COMMENT '주소', 
    `order_memo`             MEDIUMTEXT      NULL         COMMENT '고객메모', 
    `order_admin_memo`       MEDIUMTEXT      NULL         COMMENT '관리자메모', 
    `order_member_seq`       INT(10)         NOT NULL     COMMENT '담당관리자', 
    `order_regdt`            DATETIME        NOT NULL     COMMENT '주문일', 
    `order_moddt`            DATETIME        NULL         COMMENT '수정일', 
    `order_canceldt`         DATETIME        NULL         COMMENT '취소일', 
    `order_tot_orgprice`     INT(10)         NOT NULL     COMMENT '정가', 
    `order_tot_goodsprice`   INT(10)         NOT NULL     COMMENT '판매가', 
    `order_tot_settleprice`  INT(10)         NOT NULL     COMMENT '결제가', 
    `order_payment_type`     TINYINT(1)      NULL         COMMENT '1:현금, 2:카드, 3:카드+현금', 
    `order_pgAppNo`          VARCHAR(60)     NULL         COMMENT '승인번호', 
    `order_status`           TINYINT(3)      NOT NULL     COMMENT '0:주문,9:주문취소', 
    `order_user_agent`       varchar(255)    NULL         COMMENT '등록환경', 
    `order_ip`               varchar(15)     NULL         COMMENT '등록아이피', 
    `order_seller`           VARCHAR(20)     NULL         COMMENT '판매직원', 
    PRIMARY KEY (order_seq, order_code),
    UNIQUE (order_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_order_trans COMMENT '주문내역';


DROP TABLE IF EXISTS at_order_item;
CREATE TABLE IF NOT EXISTS at_order_item
(
    `item_seq`          INT(10)         NOT NULL    AUTO_INCREMENT COMMENT '주문상품시퀀스', 
    `item_order_seq`    INT(10)         NOT NULL     COMMENT '주문시퀀스', 
    `item_goods_seq`    INT(10)         NOT NULL     COMMENT '상품시퀀스', 
    `item_goods_price`  INT(10)         NOT NULL     COMMENT '판매가', 
    `item_goods_consumer`  INT(10)         NOT NULL     COMMENT '정가', 
    `item_ea`           MEDIUMINT(8)    NOT NULL     COMMENT '상품수량', 
    `item_gubun`        TINYINT(1)      NOT NULL     COMMENT '1:단품,2:세트', 
    `item_set_seq`      INT(10)         NULL         COMMENT '세트 시퀀스', 
    `item_order_type` TINYINT(1)      NOT NULL     COMMENT '1:현장수령,2:예약배송', 
    PRIMARY KEY (item_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_order_item COMMENT '주문상품정보';


DROP TABLE IF EXISTS at_setting_info;
CREATE TABLE IF NOT EXISTS at_setting_info
(
    `setting_seq`       INT(10)     NOT NULL    AUTO_INCREMENT COMMENT '설정시퀀스', 
    `setting_fair_seq`  INT(10)     NULL         COMMENT '페어시퀀스', 
    `setting_moddt`     DATETIME    NULL         COMMENT '수정일', 
    `setting_regdt`     DATETIME    NULL         COMMENT '등록일', 
    PRIMARY KEY (setting_seq)
)
ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE at_setting_info COMMENT '공통설정 정보';