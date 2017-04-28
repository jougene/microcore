<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:25
 */

namespace MicroCore\Enums;


use MyCLabs\Enum\Enum;

/**
 * Class Verb
 * @package MicroCore\Enums
 *
 * @method static Verb GET()
 * @method static Verb DELETE()
 * @method static Verb HEAD()
 * @method static Verb OPTIONS()
 * @method static Verb PATCH()
 * @method static Verb POST()
 * @method static Verb PUT()
 */
class Verb extends Enum
{
    const GET = 'GET';
    const DELETE = 'DELETE';
    const HEAD = 'HEAD';
    const OPTIONS = 'OPTIONS';
    const PATCH = 'PATCH';
    const POST = 'POST';
    const PUT = 'PUT';
}