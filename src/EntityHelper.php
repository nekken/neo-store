<?php
namespace NeoStore;

class EntityHelper
{
    public static function setObjectFromArray($object,$data)
    {
        foreach ($data as $key => $value) 
        {
            $convertedkey = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            
            $method = 'set' . $convertedkey;
    
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
        
        return $object;
    }
}