<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HydratesProps
{
    public function hydrate(array $data)
    {
        foreach ($data as $prop => $value) {
            $propName = Str::camel($prop);

            if (property_exists($this, $propName)) {
                method_exists($this, $this->getSetterName($propName))
                    ? $this->{$this->getSetterName($propName)}($value)
                    : $this->{$propName} = $value;
            }
        }

        return  $this;
    }

    public function getSetterName($prop): string
    {
        return 'set' . ucfirst(Str::camel($prop));
    }
}
