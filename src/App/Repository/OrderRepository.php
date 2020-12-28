<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class OrderRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = '
        SELECT 
            o.*, 
            os.name as status_name, os.id as status_id,
            sm.name as shipping_method_name, sm.id as shipping_method_id,
            pm.name as payment_method_name, pm.id as payment_method_id
        FROM orders o
            JOIN order_status os ON o.status_id = os.id
            JOIN order_shipping_method sm ON o.shipping_method_id = sm.id
            JOIN order_payment_method pm ON o.payment_method_id = pm.id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];


        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {

        $sql = '
        SELECT 
            o.*, 
            os.name as status_name, os.id as status_id,
            sm.name as shipping_method_name, sm.id as shipping_method_id,
            pm.name as payment_method_name, pm.id as payment_method_id
        FROM orders o
            JOIN order_status os ON o.status_id = os.id
            JOIN order_shipping_method sm ON o.shipping_method_id = sm.id
            JOIN order_payment_method pm ON o.payment_method_id = pm.id
            AND o.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function updateById($data)
    {
        $sql = '
UPDATE orders
    SET
    first_name = :first_name,
    last_name = :last_name,
    email = :email,
    telephone = :telephone,
    country = :country,
    city = :city,
    postcode = :postcode,
    address = :address,
    payment_method_id = :payment_method_id,
    shipping_method_id = :shipping_method_id,
    status_id = :status_id
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':first_name', $data['first_name']);
        $result->bindParam(':last_name', $data['last_name']);
        $result->bindParam(':email', $data['email']);
        $result->bindParam(':telephone', $data['telephone']);
        $result->bindParam(':country', $data['country']);
        $result->bindParam(':city', $data['city']);
        $result->bindParam(':postcode', $data['postcode']);
        $result->bindParam(':address', $data['address']);
        $result->bindParam(':payment_method_id', $data['payment_method_id']);
        $result->bindParam(':shipping_method_id', $data['shipping_method_id']);
        $result->bindParam(':status_id', $data['status_id']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update order');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM orders WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Unable to delete order');
        }
    }

    public function getOrderProductsByOrderId($orderId)
    {

        $sql = '
        SELECT 
            *
        FROM order_product 
        WHERE order_id = :order_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order_id', $orderId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }


    public function getFilteredData($data)
    {
        $languageId = Language::DEFAUL_LANGUGE_ID;

        $filter = '';

        if (!empty($data['id'])) {
            $filter .= ' AND o.id = ' . $data['id'];
        }

        if (!empty($data['client'])) {
            $filter .= ' AND (o.first_name like \'%' . $data['client'] . '%\' OR o.last_name like \'%' . $data['client'] . '%\')';
        }

        if (!empty($data['shipping_method_id'])) {
            $filter .= ' AND o.shipping_method_id = ' . $data['shipping_method_id'];
        }

        if (!empty($data['payment_method_id'])) {
            $filter .= ' AND o.payment_method_id = ' . $data['payment_method_id'];
        }

        if (!empty($data['price'])) {
            $filter .= ' AND o.price = ' . $data['price'];
        }

        if (!empty($data['status_id'])) {
            $filter .= ' AND o.status_id = ' . $data['status_id'];
        }

        if (!empty($data['created_at'])) {
            $filter .= ' AND o.created_at = \'' . $data['created_at'] . ' \'';
        }
        

        $sql = "
        SELECT 
            o.*, 
            f.alias AS file_alias, 
            os.name as status_name, os.id as status_id,
            sm.name as shipping_method_name, sm.id as shipping_method_id,
            pm.name as payment_method_name, pm.id as payment_method_id
        FROM orders o
            JOIN order_product op ON o.id = op.order_id
            JOIN product p ON op.product_id = p.id
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
            JOIN order_status os ON o.status_id = os.id
            JOIN order_shipping_method sm ON o.shipping_method_id = sm.id
            JOIN order_payment_method pm ON o.payment_method_id = pm.id
        $filter";

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllStatus($sort = null)
    {
        $sql = '
        SELECT * FROM order_status ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllPaymentMethods($sort = null)
    {
        $sql = '
        SELECT * FROM order_payment_method ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllShippingMethods($sort = null)
    {
        $sql = '
        SELECT * FROM order_shipping_method ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}

