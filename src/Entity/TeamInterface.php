<?php

namespace App\Entity;

interface TeamInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return TeamInterface
     */
    public function setName(string $name): TeamInterface;

    /**
     * @return string
     */
    public function getStrip(): string;

    /**
     * @param string $strip
     *
     * @return TeamInterface
     */
    public function setStrip(string $strip): TeamInterface;
}