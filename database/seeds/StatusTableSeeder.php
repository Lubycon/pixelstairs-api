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
                'name' => 'NotConfirm',
                'description' => "관리자 미 승인 상품",
            ),
            array(
                'code' => '0301',
                'name' => 'Running',
                'description' => "판매 진행 상품",
            ),
            array(
                'code' => '0302',
                'name' => 'Turmiante',
                'description' => "판매 종료 상품",
            ),
            array(
                'code' => '0310',
                'name' => 'OrderComplete',
                'description' => "주문 완료",
            ),
            array(
                'code' => '0311',
                'name' => 'OrderCancel',
                'description' => "주문 취소",
            ),
            array(
                'code' => '0312',
                'name' => 'PaymentComplete',
                'description' => "결제 완료",
            ),
            array(
                'code' => '0313',
                'name' => 'PaymentCancel',
                'description' => "결제 취소",
            ),
            array(
                'code' => '0314',
                'name' => 'ShippingPreparation',
                'description' => "배송 준비중",
            ),
            array(
                'code' => '0315',
                'name' => 'Shipping',
                'description' => "배송 중",
            ),
            array(
                'code' => '0316',
                'name' => 'ShippingComplete',
                'description' => "배송 완료",
            ),
            array(
                'code' => '0317',
                'name' => 'PurchaseConfirm',
                'description' => "구매 확정",
            ),
            array(
                'code' => '0318',
                'name' => 'RefundApplication',
                'description' => "환불 신청",
            ),
            array(
                'code' => '0319',
                'name' => 'RefundComplete',
                'description' => "환불 완료",
            ),

        );

        DB::table('statuses')->insert($data);
    }
}
