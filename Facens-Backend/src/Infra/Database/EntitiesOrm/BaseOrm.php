<?php

namespace App\Infra\Database\EntitiesOrm;

class BaseOrm
{
    protected $pureClass = NULL;


    public static function fromDomain($domain)
    {
        $d = new static();

        $fields = array_keys(get_class_vars(get_class($d)));

        foreach ($fields as $key) {
            try {
                $d->{$key} = $domain->{"get" . ucfirst($key)}();
            } catch (\Throwable $th) {
                // throw $th;
            }
        }

        return $d;
    }

    public function toDomain()
    {
        $pure = new $this->pureClass;
        $fields = array_keys(get_class_vars(get_class($this)));

        foreach ($fields as $key) {
            try {
                $pure->{"set" . ucfirst($key)}($this->{$key});
            } catch (\Throwable $th) {
                // throw $th;
            }
        }

        return $pure;
    }
}
