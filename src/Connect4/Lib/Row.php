<?php
namespace Connect4\Lib;

use Connect4\Lib\Value\BoundedInteger;

class Row extends BoundedInteger
{
    const MAX = 6;
    const MIN = 1;
}
