<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait HydratesProps
 * @package App\Traits
 */
trait HydratesProps
{
    /**
     * @param array $data
     * @return $this
     */
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

        return $this;
    }

    /**
     * @param $prop
     * @return string
     */
    public function getSetterName($prop): string
    {
        return 'set' . ucfirst(Str::camel($prop));
    }
}
