<?

//initSet에서 _INCLUDE_ROOT 를 정의
include "../../include/init.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";


getRequestVar('dateRange');
$dateStr=explode(" ~ ",$dateRange);
$std_date=$dateStr[0]." 00:00:00";
$end_date=$dateStr[1]." 23:59:59";

//게시글 리스트
$sql = "
	SELECT count(*) as bbs_count
	FROM at_order_trans
	WHERE (order_regdt >= :std_date AND order_regdt <= :end_date) AND order_status = 1
";

$database->prepare($sql);
$database->bind('std_date',$std_date);
$database->bind('end_date',$end_date);

if($row = $database->dataFetch()){
	$totalCount = $row['bbs_count'];
}

if($totalCount > 0){
	//게시글 리스트
	$bbsList = array();
	$sql = "
		SELECT 
			CASE WHEN (COALESCE(orderItem.item_gubun,0)=1) THEN ''
				WHEN (COALESCE(orderItem.item_gubun,0)=2) THEN '1'
				ELSE '' END AS item_gubun
			, goods.goods_code, orderItem.item_order_seq, orderItem.item_ea, orderInfo.order_tot_settleprice, orderItem.item_goods_price, orderInfo.order_customer, orderInfo.order_mobile, orderInfo.order_address
			, CASE WHEN (COALESCE(orderInfo.order_payment_type,0)=1) THEN 'x'
				ELSE '' END AS order_payment_type
			, orderInfo.order_pgAppNo, orderInfo.order_memo, orderInfo.order_admin_memo
			, CASE WHEN (COALESCE(orderItem.item_order_type,0)=1) THEN '현장'
				WHEN (COALESCE(orderItem.item_order_type,0)=2) THEN '예약'
				ELSE '' END AS item_order_type
			FROM at_order_trans orderInfo
				LEFT JOIN at_order_item orderItem ON orderInfo.order_seq = orderItem.item_order_seq
				LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
			WHERE (orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND orderInfo.order_status = 1
	";
	$database->prepare($sql);
	$database->bind('std_date',$std_date);
	$database->bind('end_date',$end_date);
	$bbsList = $database->dataAllFetch();
	

	
	/* Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	/* Include PHPExcel */
	include _CLASS_ROOT."PHPExcel/PHPExcel.php";

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$sheet = $objPHPExcel->getActiveSheet();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("동인기연")
								 ->setLastModifiedBy("동인기연")
								 ->setTitle("타이틀 테스트")
								 ->setSubject("주제 테스트")
								 ->setDescription("설명 테스트")
								 ->setKeywords("키워드 테스트")
								 ->setCategory("라이센스 테스트");
	

	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A1","직매장, 대리점 구분")
			->setCellValue("A2","2")
			->setCellValue("B1","SAP 거래처코드 또는 SALES GROUP")
			->setCellValue("B2","100229")
			->setCellValue("C1","Customer Group 1")
			->setCellValue("C2","")
			->setCellValue("D1","셋트여부")
			->setCellValue("D2","")
			->setCellValue("E1","사은품 변경 FLAG")
			->setCellValue("E2","")
			->setCellValue("F1","SAP 제,상품코드")
			->setCellValue("F2","1010-3-2297")
			->setCellValue("G1","수량")
			->setCellValue("G2","2")
			->setCellValue("H1","판매단가")
			->setCellValue("H2","100000")
			->setCellValue("I1","고객납기일(년월일)")
			->setCellValue("I2","20161231")
			->setCellValue("J1","고객PO번호 또는 영수증번호")
			->setCellValue("J2","A1234567")
			->setCellValue("K1","주문자명")
			->setCellValue("K2","")
			->setCellValue("L1","주문자 연락처")
			->setCellValue("L2","")
			->setCellValue("M1","수취인명")
			->setCellValue("M2","아가방")
			->setCellValue("N1","수취인 연락처")
			->setCellValue("N2","01012345678")
			->setCellValue("O1","수취인 주소")
			->setCellValue("O2","서울특별시 중구 장충단로13길 20 케레스타")
			->setCellValue("P1","할인율(%)")
			->setCellValue("P2","20")
			->setCellValue("Q1","개인구분")
			->setCellValue("Q2","")
			->setCellValue("R1","현금 및 카드 구분")
			->setCellValue("R2","현금일경우x찍기")
			->setCellValue("S1","배송비")
			->setCellValue("S2","")
			->setCellValue("T1","비고")
			->setCellValue("T2","직매장(페어일)경우 20일/하나 이런식으로 기재")
			->setCellValue("U1","영수증승인번호")
			->setCellValue("U2","현금영수증 일 경우만 기재")
			->setCellValue("V1","배송메시지 및 기타 메시지")
			->setCellValue("V2","")
			->setCellValue("W1","판매구분")
			->setCellValue("W2","");


	$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕');

	$sheet->duplicateStyleArray(
		array(
			'font' => array(
				'bold' => true,
				'size' => 10
				)
			,'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			,'fill' => array(
				'type'  => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'bfbfbf')
				)
		),
		'A1:W1'
	);
	$sheet->duplicateStyleArray(
		array(
			'fill' => array(
				'type'  => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'FFFF00')
			)
		),
		'A2:W2'
	);
	
	$endCell;
	$orderSeq=0;
	$setVal=0;
	foreach($bbsList as $key=>$val){
		$cell=$key+3;
		$endCell=$cell;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A$cell","1")
			->setCellValue("B$cell","111")
			->setCellValue("C$cell","A91")
			->setCellValue("E$cell", "")
			->setCellValue("F$cell", $val['goods_code'])
			->setCellValue("G$cell", $val['item_ea'])
			->setCellValue("H$cell", $val['item_goods_price'])
			->setCellValue("K$cell", $val['order_customer'])
			->setCellValue("L$cell", $val['order_mobile'])
			->setCellValue("M$cell", $val['order_customer'])
			->setCellValue("N$cell", $val['order_mobile'])
			->setCellValue("O$cell", $val['order_address'])
			->setCellValue("Q$cell", 'x')
			->setCellValue("R$cell", $val['order_payment_type'])
			->setCellValue("T$cell", $val['order_admin_memo'])
			->setCellValue("U$cell", $val['order_pgAppNo'])
			->setCellValue("V$cell", $val['order_memo'])
			->setCellValue("W$cell", $val['item_order_type']);
		
		
		if($val['item_gubun']=="1"){
			if($orderSeq<$val['item_order_seq']){
				$setVal++;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$cell",$setVal);
			$orderSeq=$val['item_order_seq'];
		}
	}

	// 보더 스타일 지정
	$defaultBorder = array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb'=>'000000')
	);
	$headBorder = array(
		'borders' => array(
			'bottom' => $defaultBorder,
			'left'   => $defaultBorder,
			'top'    => $defaultBorder,
			'right'  => $defaultBorder
		)
	);

	// 다중 셀 보더 스타일 적용
	for($i=1;$i<=$endCell;$i++){
		foreach(range('A','W') as $j => $cell){
			$sheet->getStyle($cell.$i)->applyFromArray( $headBorder );
		}
	}

	/*
	foreach(range('A','W') as $cell){
		$objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
	}
	*/
	
	//파일이름
	$filename = iconv("UTF-8", "EUC-KR", $dateStr[0]."_".$dateStr[1]);

	// 활성시트이름
	$objPHPExcel->getActiveSheet()->setTitle('판매통합');

	//활성 시트 색인을 첫 번째 시트로 설정하면 Excel의 첫 번째 시트로 엽니다.
	//$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}else{
	pageRedirect('../../statistics.php','조회된 결과가 없습니다.');
}
?>