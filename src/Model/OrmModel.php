<?php

namespace Orq\Laravel\Starter\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrmModel extends Model
{
    use SoftDeletes;

    /**
     * validate the data which which will store into the database
     *
     * @exception(DomainException)
     */
    public static function validate(array $data): void
    {
        $validator = Validator::make($data, static::$rules, static::$messages);

        $errorMsgs = $validator->errors()->all();
        if (count($errorMsgs) > 0) {
            throw new DomainException($errorMsgs[0], 1573629695);
        }
    }
}
