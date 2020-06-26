<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequest
{
    /**
     * @var ?string
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @var ?string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      max = 255
     * )
     */
    private $password;


    public function __construct(string $email = null, string $password = null)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
