<?php


namespace application\infrastructure;

use ReflectionMethod;
use ReflectionParameter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class Resolver
{

    /**
     * @var SerializerInterface
     */
    private $serializer;



    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }


    public function getArguments(ReflectionMethod $method, $content) {

        $resolved = [];

        /** @var ReflectionParameter $param */
        foreach ($method->getParameters() as $param) {
            $resolved = $this->resolve($content, $param);

        }

        return $resolved;
    }

    public function resolve($content, ReflectionParameter $parameter)
    {
        $type = (string)$parameter->getType();
        if ($type !== 'string') {
            $item = $this->deserialize($content, $type);
            return $item;
        }
    }

    /**
     * Десериализует и валидирует json данные.
     *
     * @param string $content
     *      Данные пользователя
     * @param $type
     *      Дто класс который будет десериализоваться.
     * @return object
     *      Подготовленный объект
     */
    private function deserialize(string $content, $type)
    {

        $deserialized = $this->serializer->deserialize($content, $type, 'json');
        return $deserialized;
    }
}
