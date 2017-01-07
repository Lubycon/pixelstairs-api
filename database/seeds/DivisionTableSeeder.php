<?php

use Illuminate\Database\Seeder;

class DivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->truncate();
        $market = array(
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001295,
                "name" => "브랜드 여성의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001296,
                "name" => "브랜드 남성의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001297,
                "name" => "브랜드 언더웨어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001298,
                "name" => "브랜드 여성신발",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001299,
                "name" => "브랜드 남성신발",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001300,
                "name" => "브랜드 여성가방",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001301,
                "name" => "브랜드 남성가방",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001302,
                "name" => "브랜드 여행가방",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001303,
                "name" => "브랜드 지갑/벨트",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001304,
                "name" => "브랜드 쥬얼리",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001305,
                "name" => "브랜드 시계",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001306,
                "name" => "브랜드 잡화/소품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001307,
                "name" => "수입명품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001308,
                "name" => "디자이너 여성의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001309,
                "name" => "디자이너 남성의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>1001310,
                "name" => "디자이너 잡화",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>67813,
                "name" => "망고 공식스토어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 1,
                "data_number" =>'REVOLVE',
                "name" => "패션직구 리볼브",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 2,
                "data_number" =>1001311,
                "name" => "여성의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 2,
                "data_number" =>1001312,
                "name" => "남성의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 2,
                "data_number" =>1001313,
                "name" => "언더웨어/잠옷",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 2,
                "data_number" =>911792,
                "name" => "디자이너/편집샵",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001314,
                "name" => "여성신발",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001315,
                "name" => "남성신발",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001316,
                "name" => "여성가방",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001317,
                "name" => "남성가방",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001318,
                "name" => "여행가방/소품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001319,
                "name" => "시계",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001320,
                "name" => "쥬얼리",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001321,
                "name" => "지갑/벨트",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001322,
                "name" => "패션잡화",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 3,
                "data_number" =>1001323,
                "name" => "순금/돌반지",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001324,
                "name" => "스킨케어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001325,
                "name" => "메이크업",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001326,
                "name" => "선케어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001327,
                "name" => "남성화장품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001332,
                "name" => "향수",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001328,
                "name" => "클렌징/필링",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001329,
                "name" => "헤어케어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001330,
                "name" => "바디케어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001331,
                "name" => "네일케어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>1001333,
                "name" => "뷰티소품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>'scinicBrandMain',
                "name" => "싸이닉",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>'scinicBrandMain',
                "name" => "싸이닉",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>'beautyMain',
                "name" => "뷰티11번가",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 4,
                "data_number" =>'AMOREPACIFIC',
                "name" => "아모레퍼시픽몰 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001388,
                "name" => "스포츠 의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001389,
                "name" => "스포츠 신발",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001390,
                "name" => "스포츠 잡화",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001391,
                "name" => "등산/아웃도어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001392,
                "name" => "골프",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001393,
                "name" => "캠핑",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001394,
                "name" => "낚시",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001395,
                "name" => "자전거",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001396,
                "name" => "헬스",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001397,
                "name" => "요가/필라테스",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001398,
                "name" => "스키/보드",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001399,
                "name" => "인라인/스케이트보드",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001400,
                "name" => "검도/권투/격투",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001401,
                "name" => "구기스포츠",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001402,
                "name" => "라켓스포츠",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001403,
                "name" => "오토바이/스쿠터",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001404,
                "name" => "수영",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001405,
                "name" => "스킨스쿠버/수상레저",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>1001406,
                "name" => "기타 스포츠",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>67384,
                "name" => "아디다스 공식스토어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>67386,
                "name" => "데카트론 공식스토어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>67629,
                "name" => "리복 공식스토어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>67689,
                "name" => "뉴발란스 공식스토어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>67790,
                "name" => "폴더 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 5,
                "data_number" =>67222,
                "name" => "데상트 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>1001420,
                "name" => "자동차용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>1001421,
                "name" => "자동차기기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>1001422,
                "name" => "공구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>1001423,
                "name" => "전기/산업자재",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>1001424,
                "name" => "안전/보안용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>68315,
                "name" => "전동공구전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 6,
                "data_number" =>'carstreet',
                "name" => "카스트릿",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001334,
                "name" => "농산",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001335,
                "name" => "수산",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001336,
                "name" => "축산",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001337,
                "name" => "김치/반찬/가정식",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001338,
                "name" => "가공식품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001339,
                "name" => "커피/생수/음료",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001340,
                "name" => "과자/간식",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001341,
                "name" => "즉석식품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001342,
                "name" => "건강식품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>1001343,
                "name" => "다이어트식품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>67939,
                "name" => "가락시장몰",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>67821,
                "name" => "NOW배송",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>62272,
                "name" => "홈플러스 당일장보기관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>47039,
                "name" => "GS수퍼마켓",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>61437,
                "name" => "수입식품전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 7,
                "data_number" =>63574,
                "name" => "지역특산물장터",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001344,
                "name" => "기저귀",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001345,
                "name" => "분유",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001346,
                "name" => "물티슈",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001347,
                "name" => "출산용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001348,
                "name" => "이유용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001349,
                "name" => "수유용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001350,
                "name" => "유아목욕/스킨케어",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001351,
                "name" => "유아세제/위생용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001352,
                "name" => "유아안전/실내용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001353,
                "name" => "외출용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001354,
                "name" => "임부복/소품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001355,
                "name" => "신생아 의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001356,
                "name" => "유아의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001357,
                "name" => "아동/주니어 의류",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001358,
                "name" => "유아동신발",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001359,
                "name" => "유아동잡화",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001360,
                "name" => "유아가구/침구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001361,
                "name" => "이유식/영양제",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>1001362,
                "name" => "장난감",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 8,
                "data_number" =>913722,
                "name" => "국민 육아용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001378,
                "name" => "구강/세안/면도",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001379,
                "name" => "세탁용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001380,
                "name" => "주방용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001381,
                "name" => "욕실용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001382,
                "name" => "청소용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001383,
                "name" => "화장지",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001384,
                "name" => "생리대/성인기저귀",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001385,
                "name" => "수납정리용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001386,
                "name" => "세제/방향/살충",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 9,
                "data_number" =>1001387,
                "name" => "생활잡화",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001407,
                "name" => "안마용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001408,
                "name" => "온열/찜질용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001409,
                "name" => "저주파/적외선용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001410,
                "name" => "건강관리용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001411,
                "name" => "건강측정용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001412,
                "name" => "당뇨관리용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001413,
                "name" => "몸매관리용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001414,
                "name" => "눈건강용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001415,
                "name" => "손발건강용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001416,
                "name" => "실버용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001417,
                "name" => "재활운동용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001418,
                "name" => "의약외품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 10,
                "data_number" =>1001419,
                "name" => "병원/의료용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001363,
                "name" => "거실가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001364,
                "name" => "침실가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001365,
                "name" => "아웃도어가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001366,
                "name" => "주방가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001367,
                "name" => "수납가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001368,
                "name" => "유아동가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001369,
                "name" => "서재/사무용가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001370,
                "name" => "DIY자재/용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001371,
                "name" => "리모델링가구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001372,
                "name" => "침구",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001373,
                "name" => "커튼/블라인드",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001374,
                "name" => "카페트/러그",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001375,
                "name" => "홈패브릭/수예",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001376,
                "name" => "조명",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>1001377,
                "name" => "인테리어소품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 11,
                "data_number" =>'KOSNEY',
                "name" => "코즈니 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001430,
                "name" => "TV",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001431,
                "name" => "냉장고",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001432,
                "name" => "세탁기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001433,
                "name" => "생활가전",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001434,
                "name" => "주방가전",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001435,
                "name" => "영상가전",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001436,
                "name" => "음향가전",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001437,
                "name" => "이미용가전",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>1001438,
                "name" => "계절가전",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 12,
                "data_number" =>65491,
                "name" => "삼성공식인증브랜드샵",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001439,
                "name" => "노트북",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001440,
                "name" => "데스크탑",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001441,
                "name" => "모니터",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001442,
                "name" => "프린터/복합기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001443,
                "name" => "저장장치",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001444,
                "name" => "PC부품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 13,
                "data_number" =>1001445,
                "name" => "컴퓨터 주변기기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>1001425,
                "name" => "스마트기기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>1001426,
                "name" => "카메라/주변기기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>1001427,
                "name" => "게임",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>1001428,
                "name" => "태블릿",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>1001429,
                "name" => "휴대폰",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>'tworldDirectMain',
                "name" => "T휴대폰샵",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>'55941',
                "name" => "CANON 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>'67672',
                "name" => "Microsoft 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 14,
                "data_number" =>'916592',
                "name" => "삼성갤럭시공식인증샵",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 15,
                "data_number" =>1001446,
                "name" => "문구/사무용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 15,
                "data_number" =>1001447,
                "name" => "화방용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 15,
                "data_number" =>2967,
                "name" => "도서/음반",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 15,
                "data_number" =>"http://books.11st.co.kr/",
                "name" => "도서11번가",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 15,
                "data_number" =>67983,
                "name" => "디자인팬시 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 16,
                "data_number" =>117025,
                "name" => "e쿠폰/상품권",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 16,
                "data_number" =>2878,
                "name" => "여행/숙박/항공",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 16,
                "data_number" =>1001448,
                "name" => "렌탈/무료신청",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 16,
                "data_number" =>"http://tour.11st.co.kr/html/vertical/tourMain.html",
                "name" => "여행11번가",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 16,
                "data_number" =>"http://ticket.11st.co.kr/11st/Main.asp",
                "name" => "티켓11번가",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 16,
                "data_number" =>"http://www.11st.co.kr/disp/DTAction.tmall?ID=GIFTICON&amp;ctgrNo=60086",
                "name" => "기프티콘 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 17,
                "data_number" =>1001449,
                "name" => "꽃배달",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 17,
                "data_number" =>1001450,
                "name" => "꽃/원예",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 17,
                "data_number" =>1001451,
                "name" => "악기",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 17,
                "data_number" =>1001452,
                "name" => "취미",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 17,
                "data_number" =>67869,
                "name" => "야마하악기 전문관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 18,
                "data_number" =>1001453,
                "name" => "애견용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 18,
                "data_number" =>1001454,
                "name" => "고양이용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 18,
                "data_number" =>1001455,
                "name" => "조류용품",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 18,
                "data_number" =>1001456,
                "name" => "관상어/수족관",
                "is_active" => true
            ),
            array(
                "market_id" => 1,
                "category_id" => 18,
                "data_number" =>1001457,
                "name" => "기타 동물용품",
                "is_active" => true
            ),
        );

        DB::table('divisions')->insert($market);
    }
}
