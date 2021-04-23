<?php
namespace application\core;

use ReflectionMethod;
use TypeError;

class ParamsResolver
{
    /**
     * Заполнение параметров экшенов
     * @param ReflectionMethod $object
     * @param array $params
     * @param array $methodArgs
     * @throws ExceptionHandler
     */
    public function resolve(ReflectionMethod $object, array $params, array &$methodArgs) {

        foreach ($object->getParameters() as $parameter) {

            //обработка query параметров
            if (isset($params[$parameter->name])) {
                $methodArgs[$parameter->name] = $params[$parameter->name];
                continue;
            }

            //обработка скаляров body параметров
            if (isset($params['body']->{$parameter->name})) {
                $methodArgs[$parameter->name] = $params['body']->{$parameter->name};
                continue;
            }

            //обработка DTO классов
            if (isset($parameter->getClass()->name)) {
                $className = $parameter->getClass()->name;

                $inputClass = new $className;

                if ($parameter->isVariadic()) {
                    $params = current(get_object_vars($params['body']));
                } else {
                    $params = get_object_vars($params['body']);
                }
                foreach ($params as $key => $value) {
                    $methodArgs[$key] = $this->fillParams($inputClass, $key, $value);
                }
            }
        }
    }

    /**
     * Обработка параметров как скаляров так и объектов
     * @param $inputClass
     * @param $key
     * @param $value
     * @return mixed
     * @throws ExceptionHandler
     */
    private function fillParams($inputClass, $key, $value) {
        if (is_object($value)) {
            foreach ($value as $key => $val) {
                $this->fillProperty($inputClass, $key, $val);
            }
        } else {
            $this->fillProperty($inputClass, $key, $value);
        }

        return $inputClass;
    }

    /**
     * Проверка конкретного параметра
     * @param $inputClass
     * @param $key
     * @param $value
     * @return mixed
     * @throws ExceptionHandler
     */
    private function fillProperty($inputClass, $key, $value) {
        if(property_exists($inputClass, $key)) {
            try {
                $inputClass->$key = $value;
                return $inputClass;
            } catch (TypeError $e) {
                throw new ExceptionHandler($e->getCode(), $e->getMessage());
            }
        }
    }
}
