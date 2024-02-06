<?php

class Bill
{
    public function getBill($billNumber)
    {
        $sql = "SELECT bill.*, customer.name, customer.family, customer.address, customer.phone FROM callcenter.bill
        INNER JOIN callcenter.customer ON bill.customer_id = customer.id
        WHERE bill_number = '$billNumber' ORDER BY bill_number DESC LIMIT 1";

        $result = CONN->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getBillItems($billNumber)
    {
        $sql = "SELECT * FROM callcenter.bill_details WHERE bill_id = '$billNumber' ORDER BY id DESC LIMIT 1";

        $result = CONN->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getCustomer($customerId)
    {
        $sql = "SELECT * FROM callcenter.customer WHERE id = '$customerId' ORDER BY id DESC LIMIT 1";

        $result = CONN->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}
