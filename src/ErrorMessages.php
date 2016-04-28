<?php
namespace AsaoKamei\PayJp;

class ErrorMessages
{
    /**
     * @var string[]
     */
    private $messages = [];

    public function __construct()
    {
        $this->makeList();
    }

    /**
     * @param string $code
     * @return bool
     */
    public function has($code)
    {
        return array_key_exists($code, $this->messages);
    }

    /**
     * @param string $code
     * @return null|string
     */
    public function get($code)
    {
        return array_key_exists($code, $this->messages) ? $this->messages[$code] : null;
    }

    /**
     *
     */
    private function makeList()
    {
        $list = <<<END_OF_DATA
invalid_number	不正なカード番号
invalid_cvc	不正なCVC
invalid_expiry_month	不正な有効期限月
invalid_expiry_year	不正な有効期限年
expired_card	有効期限切れ
card_declined	カード会社によって拒否されたカード
processing_error	決済ネットワーク上で生じたエラー
missing_card	顧客がカードを保持していない
invalid_id	不正なID
no_api_key	APIキーがセットされていない
invalid_api_key	不正なAPIキー
invalid_plan	不正なプラン
invalid_expiry_days	不正な失効日数
unnecessary_expiry_days	失効日数が不要なパラメーターである場合
invalid_flexible_id	不正なID指定
invalid_timestamp	不正なUnixタイムスタンプ
invalid_trial_end	不正なトライアル終了日
invalid_string_length	不正な文字列長
invalid_country	不正な国名コード
invalid_currency	不正な通貨コード
invalid_address_zip	不正な郵便番号
invalid_amount	不正な支払い金額
invalid_plan_amount	不正なプラン金額
invalid_card	不正なカード
invalid_customer	不正な顧客
invalid_boolean	不正な論理値
invalid_email	不正なメールアドレス
no_allowed_param	パラメーターが許可されていない場合
no_param	パラメーターが何もセットされていない
invalid_querystring	不正なクエリー文字列
missing_param	必要なパラメーターがセットされていない
invalid_param_key	指定できない不正なパラメーターがある
no_payment_method	支払い手段がセットされていない
payment_method_duplicate	支払い手段が重複してセットされている
payment_method_duplicate_including_customer	支払い手段が重複してセットされている(顧客IDを含む)
failed_payment	指定した支払いが失敗している場合
invalid_refund_amount	不正な返金額
already_refunded	すでに返金済み
cannot_refund_by_amount	返金済みの支払いに対して部分返金ができない
invalid_amount_to_not_captured	確定されていない支払いに対して部分返金ができない
refund_amount_gt_net	返金額が元の支払い額より大きい
capture_amount_gt_net	支払い確定額が元の支払い額より大きい
invalid_refund_reason	不正な返金理由
already_captured	すでに支払いが確定済み
cant_capture_refunded_charge	返金済みの支払いに対して支払い確定ができない
charge_expired	認証が失効している支払い
alerady_exist_id	すでに存在しているID
token_already_used	すでに使用済みのトークン
already_have_card	指定した顧客がすでに保持しているカード
dont_has_this_card	顧客が指定したカードを保持していない
doesnt_have_card	顧客がカードを何も保持していない
invalid_interval	不正な課金周期
invalid_trial_days	不正なトライアル日数
invalid_billing_day	不正な支払い実行日
exist_subscribers	購入者が存在するプランは削除できない
already_subscribed	すでに定期課金済みの顧客
already_canceled	すでにキャンセル済みの定期課金
already_pasued	すでに停止済みの定期課金
subscription_worked	すでに稼働している定期課金
test_card_on_livemode	本番モードのリクエストにテストカードが使用されている
not_activated_account	本番モードが許可されていないアカウント
too_many_test_request	テストモードのリクエストリミットを超過している
invalid_access	不正なアクセス
payjp_wrong	PAY.JPのサーバー側でエラーが発生している
pg_wrong	決済代行会社のサーバー側でエラーが発生している
not_found	リクエスト先が存在しないことを示す
not_allowed_method	許可されていないHTTPメソッド
END_OF_DATA;
        $list = explode("\n", $list);
        foreach($list as $item) {
            $this->messages[$item[0]] = $item[1];
        }
    }
}

;
