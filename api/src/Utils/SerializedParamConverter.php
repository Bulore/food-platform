<?php


namespace App\Utils;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SerializedParamConverter implements ArgumentValueResolverInterface
{
    private $serializer;

    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }
//
//    public function apply(Request $request, ConfigurationInterface $configuration)
//    {
//        $class = $configuration->getClass();
//
//        try {
//            $object = $this->serializer->deserialize(
//                $request->getContent(),
//                $class,
//                'xml'
//            );
//        }
//        catch (XmlErrorException $e) {
//            throw new NotFoundHttpException(sprintf('Could not deserialize request content to object of type "%s"',
//                $class));
//        }
//
//        // set the object as the request attribute with the given name
//        // (this will later be an argument for the action)
//        $request->attributes->set($configuration->getName(), $object);
//    }



    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return (strpos($this->getClass($argument), 'App\DTO') === 0);
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $class = $this->getClass($argument);
        $object = $this->serializer->deserialize($request->getContent(), $class, 'json');
        $constraints = $this->validator->validate($object);

        if ($constraints->count() !== 0) {
//            dd($constraints->__toString());
            throw new ValidationException($constraints);

        }

        yield $object;
    }

    private function getClass(ArgumentMetadata $argument): ?string
    {
        return $argument->getType();
    }
}
