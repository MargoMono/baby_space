<?php

namespace App\Models\Site;

use App\Models\Models;
use App\Controllers\Site\MailController;
use App\Helpers\MailerHelper;
use App\Repository\PriceListOrderRepository;
use App\Repository\PriceListRepository;
use DateTime;
use PHPMailer\PHPMailer\Exception;

class PriceListModel extends Model
{
    public function sendPriceListToClient($data)
    {
        $res['result'] = false;

        $mailModels = new MailController();
        $body = $mailModels->getTemplate('catalogOrder.twig', $data);
        $subject = "Заказ прайс-листа на сайте Кдф-трейдинг.рф";

        $priceListOrderRepository = new PriceListOrderRepository();
        $previousOrder = $priceListOrderRepository->getClientByEmail($data['email']);

//        if($previousOrder){
//            $now = new DateTime();
//            $previousOrderDate = new DateTime($previousOrder['created_at']);
//            $interval= $now->diff($previousOrderDate);
//            if ($interval->h < 1) {
//                $res['errors'][] = 'Прайс-лист можно запрашивать с частотой не более 1 раза в час';
//                return $res;
//            }
//        }

        $priceListRepository = new PriceListRepository();
        $priceList = $priceListRepository->getPriceList();

        if (empty($priceList)) {
            $res['errors'][] = 'Нет подходящих прайс-листов для отправки';
            return $res;
        }

        foreach ($priceList as $key => $price) {
            $priceList[$key]['path'] = 'upload/images/price-list/' . $price['file_alias'];
        }

        $mailer = new MailerHelper($subject, $body, $data['email'], $data['name'], $priceList);

        try {
            $mailer->send();
        } catch (Exception $e) {
            return $res;
        }

        $priceListOrderRepository = new PriceListOrderRepository();
        $newPriceListOrder = $priceListOrderRepository->create($data);

        if (!empty($newPriceListOrder)) {
            $res['result'] = true;
            return $res;
        }

        return $res;
    }
}

