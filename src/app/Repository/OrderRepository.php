<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class OrderRepository extends AbstractRepository
{
    public function getOrderById($id)
    {
        $sql = 'SELECT * FROM product_order WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function save($sort, $products)
    {
        $sql = '
        INSERT INTO product_order
        (user_name, user_phone, user_email, user_city, user_adress, user_delivery, user_payment, user_comment, products)
        VALUES 
        (:user_name, :user_phone, :user_email, :user_city, :user_adress, :user_delivery, :user_payment, :user_comment, :products)';

        $products = json_encode($products);

        $result = $this->db->prepare($sql);
        $result->bindParam(':user_name', $sort['name'], PDO::PARAM_STR);
        $result->bindParam(':user_phone', $sort['phone'], PDO::PARAM_STR);
        $result->bindParam(':user_email', $sort['email'], PDO::PARAM_STR);
        $result->bindParam(':user_city', $sort['city'], PDO::PARAM_STR);
        $result->bindParam(':user_adress', $sort['address'], PDO::PARAM_STR);
        $result->bindParam(':user_delivery', $sort['delivery'], PDO::PARAM_STR);
        $result->bindParam(':user_payment', $sort['payment'], PDO::PARAM_STR);
        $result->bindParam(':user_comment', $sort['comment'], PDO::PARAM_STR);
        $result->bindParam(':products', $products, PDO::PARAM_STR);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Возвращает список заказов
     */

    public static function getOrdersList()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Получение и возврат результатов
        $result = $db->query('SELECT id, user_name, user_phone, date, status FROM product_order ORDER BY id DESC');
        $ordersList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $ordersList[$i]['id'] = $row['id'];
            $ordersList[$i]['user_name'] = $row['user_name'];
            $ordersList[$i]['user_phone'] = $row['user_phone'];
            $ordersList[$i]['date'] = $row['date'];
            $ordersList[$i]['status'] = $row['status'];
            $i++;
        }
        return $ordersList;
    }

    /**
     * Возвращает текстое пояснение статуса для заказа :<br/>
     */

    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Новый заказ';
                break;
            case '2':
                return 'В обработке';
                break;
            case '3':
                return 'Доставляется';
                break;
            case '4':
                return 'Закрыт';
                break;
        }
    }

    /*
     * Удаляем заказ с заданным id
     */

    public static function deleteOrderById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM product_order WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Редактируем заказ с заданным id
     */
    public static function updateOrderById($id, $userName, $userPhone, $userCity, $userAdress, $userDelivery, $userPayment, $userComment, $date, $status)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE product_order
            SET 
                user_name = :user_name, 
                user_phone = :user_phone, 
                user_city = :user_city, 
                user_adress = :user_adress, 
                user_delivery = :user_delivery,  
                user_payment = :user_payment,                          
                user_comment = :user_comment, 
                date = :date, 
                status = :status 
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_city', $userCity, PDO::PARAM_STR);
        $result->bindParam(':user_adress', $userAdress, PDO::PARAM_STR);
        $result->bindParam(':user_delivery', $userDelivery, PDO::PARAM_STR);
        $result->bindParam(':user_payment', $userPayment, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':date', $date, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }
}

?>
