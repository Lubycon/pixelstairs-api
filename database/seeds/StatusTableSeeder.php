<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->truncate();
        $data = array(
            array(
                'code' => '0300',
                'english_name' => 'notConfirm',
                'korean_name' => "관리자 미 승인 상품",
                'chinese_name' => "我们的产品经理批准",
            ),
            array(
                'code' => '0301',
                'english_name' => 'confirm',
                'korean_name' => "판매 진행 상품",
                'chinese_name' => "产品销售收入",
            ),
            array(
                'code' => '0302',
                'english_name' => 'terminate',
                'korean_name' => "판매 종료 상품",
                'chinese_name' => "停产产品",
            ),
            array(
                'code' => '0310',
                'english_name' => 'orderComplete',
                'korean_name' => "주문 완료",
                'chinese_name' => "为了完成",
            ),
            array(
                'code' => '0311',
                'english_name' => 'orderCancel',
                'korean_name' => "주문 취소",
                'chinese_name' => "订单取消",
            ),
            array(
                'code' => '0312',
                'english_name' => 'paymentComplete',
                'korean_name' => "결제 완료",
                'chinese_name' => "完成支付",
            ),
            array(
                'code' => '0313',
                'english_name' => 'paymentCancel',
                'korean_name' => "결제 취소",
                'chinese_name' => "付款已取消",
            ),
            array(
                'code' => '0314',
                'english_name' => 'shippingPreparation',
                'korean_name' => "배송 준비중",
                'chinese_name' => "装运准备",
            ),
            array(
                'code' => '0315',
                'english_name' => 'shipping',
                'korean_name' => "배송 중",
                'chinese_name' => "交付",
            ),
            array(
                'code' => '0316',
                'english_name' => 'shippingComplete',
                'korean_name' => "배송 완료",
                'chinese_name' => "运",
            ),
            array(
                'code' => '0317',
                'english_name' => 'purchaseConfirm',
                'korean_name' => "구매 확정",
                'chinese_name' => "购买确认",
            ),
            array(
                'code' => '0318',
                'english_name' => 'refundApplication',
                'korean_name' => "환불 신청",
                'chinese_name' => "退",
            ),
            array(
                'code' => '0319',
                'english_name' => 'refundComplete',
                'korean_name' => "환불 완료",
                'chinese_name' => "退款",
            ),

        );

        DB::table('statuses')->insert($data);
    }
}
