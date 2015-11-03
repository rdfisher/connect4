<?php
namespace Connect4\Lib;

use Connect4\Lib\Value\BoundedInteger;

class Column extends BoundedInteger
{
    const MAX = 7;
    const MIN = 1;
}
