<?php

/*
 * This file is part of the Doctrine\OrientDB package.
 *
 * (c) Alessandro Nadalin <alessandro.nadalin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class RidUpdates
 *
 * @package     Doctrine\OrientDB
 * @subpackage  Formatter
 * @author      Alessandro Nadalin <alessandro.nadalin@gmail.com>
 */

namespace Doctrine\OrientDB\Formatter\Query;

use Doctrine\OrientDB\Formatter\Query;
use Doctrine\OrientDB\Formatter\String;
use Doctrine\OrientDB\Contract\Formatter\Query\Token as TokenFormatter;
use Doctrine\OrientDB\Validator\Rid as RidValidator;

class RidUpdates extends Query implements TokenFormatter
{
    public static function format(array $values)
    {
        $rids = array();
        $validator = new RidValidator;

        foreach ($values as $key => $value) {
            $key = String::filterNonSQLChars($key);
            $rid = $validator->check($value, true);

            if ($key && $rid) {
                $rids[$key] = "$key = $rid";
            }
        }

        if ($rids) {
            return self::implode($rids);
        }

        return null;
    }
}