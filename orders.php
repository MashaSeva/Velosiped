<?php

enum PaymentType : int {
    case Card = 1;
    case Cash = 2;

    public static function fromName(string $name) : self
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}

class Order
{
    public int $id_order;
    public int $id_client;
    public int $id_staff;
    public string $adress;
    public PaymentType $payment;
    public DateTime $data_order;
    public string $status;
    public float $sum;
}
?>