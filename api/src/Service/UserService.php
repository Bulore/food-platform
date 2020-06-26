<?php

namespace App\Service;

use App\DTO\RegistrationRequest;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    /** @var UserRepository */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function createUserFromRequest(RegistrationRequest $request): User
    {
        $user = new User();
        $user->setEmail($request->getEmail());
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $request->getPassword()
        ));

        $errors = $this->validator->validate($user);
        if($errors->count() !== 0) {
            throw new ValidationException($errors);
        }

        $this->userRepository->save($user);

        return $user;
    }
}
