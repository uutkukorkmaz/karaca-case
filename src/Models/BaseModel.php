<?php

namespace KaracaCase\Models;

class BaseModel
{

    public function __construct(protected mixed $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function __get($name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }

        throw new \Exception('Property '.$name.' does not exists');
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }


}