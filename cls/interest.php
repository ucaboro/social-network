<?php

/**
 * Created by PhpStorm.
 * User: michaelscott
 * Date: 15/03/2017
 * Time: 15:25
 */
class interest
{
    private $id;
    private $name;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getID(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

}