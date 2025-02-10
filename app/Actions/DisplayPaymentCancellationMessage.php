<?php

namespace App\Actions;

class DisplayPaymentCancellationMessage
{
    public static function display(string $cancellationParty, string $cancellationReason) : array
    {
        $cancellationPartyMessage = '';
        $cancellationReasonMessage = '';
        if ($cancellationParty == 'merchant')
        {
            $cancellationPartyMessage = 'Продавец товара(ов)';
        }
        else if ($cancellationParty == 'yoo_money')
        {
            $cancellationPartyMessage = 'ЮКасса';
        }
        else if ($cancellationParty == 'payment_network')
        {
            $cancellationPartyMessage = 'Сторонние участники процесса платежа';
        }
        if ($cancellationReason == '3d_secure_failed')
        {
            $cancellationReasonMessage = 'Не пройдена аутентификация по 3-D Secure.';
        }
        else if ($cancellationReason == 'call_issuer')
        {
            $cancellationReasonMessage = 'Оплата данным платежным средством отклонена по неизвестным причинам.';
        }
        else if ($cancellationReasonMessage == 'canceled_by_merchant')
        {
            $cancellationReasonMessage = 'Платеж отменен по API при оплате в две стадии.';
        }
        else if ($cancellationReason == 'card_expired')
        {
            $cancellationReasonMessage = 'Истек срок действия банковской карты.';
        }
        else if ($cancellationReasonMessage == 'country_forbidden')
        {
            $cancellationReasonMessage = 'Нельзя заплатить банковской картой, выпущенной в этой стране.';
        }
        else if ($cancellationReasonMessage == 'deal_expired')
        {
            $cancellationReasonMessage = 'Закончился срок жизни сделки. Если вы еще хотите принять оплату, создайте новую сделку и проведите для нее новый платеж.';
        }
        else if ($cancellationReasonMessage == 'expired_on_capture')
        {
            $cancellationReasonMessage = 'Истек срок списания оплаты у двухстадийного платежа.';
        }
        else if ($cancellationReasonMessage == 'expired_on_confirmation')
        {
            $cancellationReasonMessage = 'Истек срок оплаты.';
        }
        else if ($cancellationReasonMessage == 'fraud_suspected')
        {
            $cancellationReasonMessage = 'Платеж заблокирован из-за подозрения в мошенничестве.';
        }
        else if ($cancellationReasonMessage == 'general_decline')
        {
            $cancellationReasonMessage = 'Причина не детализирована. Обратитесь к инициатору платежа за уточнением подробностей.';
        }
        else if ($cancellationReasonMessage == 'identification_required')
        {
            $cancellationReasonMessage = 'Превышены ограничения на платежи для кошелька ЮMoney.';
        }
        else if ($cancellationReasonMessage == 'insufficient_funds')
        {
            $cancellationReasonMessage = 'Недостаточно средств.';
        }
        else if ($cancellationReasonMessage == 'invalid_card_number')
        {
            $cancellationReasonMessage = 'Неправильно указан номер карты.';
        }
        else if ($cancellationReasonMessage == 'invalid_csc')
        {
            $cancellationReasonMessage = 'Неправильно указан CCV карты.';
        }
        else if ($cancellationReasonMessage == 'issuer_unavailable')
        {
            $cancellationReasonMessage = 'Организация, выпустившая платежное средство, недоступна.';
        }
        else if ($cancellationReasonMessage == 'payment_method_limit_exceeded')
        {
            $cancellationReasonMessage = 'Исчерпан лимит платежей для данного платежного средства или вашего магазина.';
        }
        else if ($cancellationReasonMessage == 'payment_method_restricted')
        {
            $cancellationReasonMessage = 'Запрещены операции данным платежным средством.';
        }
        else if ($cancellationReasonMessage == 'permission_revoked')
        {
            $cancellationReasonMessage = 'Нельзя провести безакцептное списание: пользователь отозвал разрешение на автоплатежи.';
        }
        else if ($cancellationReasonMessage == 'unsupported_mobile_operator')
        {
            $cancellationReasonMessage = 'Нельзя заплатить с номера телефона этого мобильного оператора.';
        }
        return ['cancellationParty' => $cancellationPartyMessage, 'cancellationReason' => $cancellationReasonMessage];
    }
}