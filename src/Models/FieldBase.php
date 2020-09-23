<?php

namespace bachphuc\LaravelCustomFields\Models;

use Illuminate\Database\Eloquent\Model;

use bachphuc\PhpLaravelHelpers\WithModelBase;
use bachphuc\PhpLaravelHelpers\WithImage;

class FieldBase extends Model
{
    use WithModelBase, WithImage;
}