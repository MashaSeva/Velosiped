<?php

enum TitleType : string
{
    case Administrator = 'Administrator';
    case Courier = 'Courier';
    

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

class Staff
{
    public int $id_staff;
    public string $name;
    public string $password;
    public string $tel_staff;
    public TitleType $title;
}
?>