<?php

use Illuminate\Database\Seeder;

class TranslateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('translate_names')->truncate();
        $market = array(
            array(
                'id' => 1,
                'original' => '11번가',
                'chinese' => '11街',
                'korean' => '11번가',
                'english' => '11st',
            ),
            array(
                'id' => 2,
                'original' => 'Gmarket',
                'chinese' => 'G-市场',
                'korean' => '지마켓',
                'english' => 'Gmarket',
            ),
            array(
                'id' => 3,
                'original' => 'Auction',
                'chinese' => '拍卖',
                'korean' => '옥션',
                'english' => 'Auction',
            ),
            array(
                'id' => 4,
                'origin' => '관리자 미 승인 상품',
                'english' => 'notConfirm',
                'korean' => "관리자 미 승인 상품",
                'chinese' => "我们的产品经理批准",
            ),
            array(
                'id' => 5,
                'origin' => '판매 진행 상품',
                'english' => 'confirm',
                'korean' => "판매 진행 상품",
                'chinese' => "产品销售收入",
            ),
            array(
                'id' => 6,
                'origin' => '판매 종료 상품',
                'english' => 'terminate',
                'korean' => "판매 종료 상품",
                'chinese' => "停产产品",
            ),
            array(
                'id' => 7,
                'origin' => '주문 완료',
                'english' => 'orderComplete',
                'korean' => "주문 완료",
                'chinese' => "为了完成",
            ),
            array(
                'id' => 8,
                'origin' => '주문 취소',
                'english' => 'orderCancel',
                'korean' => "주문 취소",
                'chinese' => "订单取消",
            ),
            array(
                'id' => 9,
                'origin' => '결제 완료',
                'english' => 'paymentComplete',
                'korean' => "결제 완료",
                'chinese' => "完成支付",
            ),
            array(
                'id' => 10,
                'origin' => '결제 취소',
                'english' => 'paymentCancel',
                'korean' => "결제 취소",
                'chinese' => "付款已取消",
            ),
            array(
                'id' => 11,
                'origin' => '배송 준비중',
                'english' => 'shippingPreparation',
                'korean' => "배송 준비중",
                'chinese' => "装运准备",
            ),
            array(
                'id' => 12,
                'origin' => '배송 중',
                'english' => 'shipping',
                'korean' => "배송 중",
                'chinese' => "交付",
            ),
            array(
                'id' => 13,
                'origin' => '배송 완료',
                'english' => 'shippingComplete',
                'korean' => "배송 완료",
                'chinese' => "运",
            ),
            array(
                'id' => 14,
                'origin' => '구매 확정',
                'english' => 'purchaseConfirm',
                'korean' => "구매 확정",
                'chinese' => "购买确认",
            ),
            array(
                'id' => 15,
                'origin' => '환불 신청',
                'english' => 'refundApplication',
                'korean' => "환불 신청",
                'chinese' => "退",
            ),
            array(
                'id' => 16,
                'origin' => '환불 완료',
                'english' => 'refundComplete',
                'korean' => "환불 완료",
                'chinese' => "退款",
            ),
            array(
                'id' => 17,
                'origin' => '남녀공용',
                'english' => 'unisex',
                'korean' => "남녀공용",
                'chinese' => "男女皆宜的",
            ),
            array(
                'id' => 18,
                'origin' => '남성용',
                'english' => 'male',
                'korean' => "남성용",
                'chinese' => "只有男人",
            ),
            array(
                'id' => 19,
                'origin' => '여성용',
                'english' => 'female',
                'korean' => "여성용",
                'chinese' => "只有女人",
            ),
            array(
                'id' => 20,
                'origin' => 'KRW',
                'english' => 'won',
                'korean' => "원",
                'chinese' => "韩元",
            ),
            array(
                'id' => 21,
                'origin' => 'CNY',
                'english' => 'rmb',
                'korean' => "위안",
                'chinese' => "人民幣",
            ),
            array(
                'id' => 22,
                'origin' => 'USD',
                'english' => 'dollar',
                'korean' => "달러",
                'chinese' => "美元",
            ),
        );

        DB::table('translate_names')->insert($market);
    }
}
