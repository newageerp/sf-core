<?php
namespace Newageerp\SfXlsx\Styles;

class Colors {
    public const REDTEXT = 'FFB01A1F';
    public const REDBG = 'FFFEF0F0';

    public const BLUETEXT = 'FF1D39A3';
    public const BLUEBG = 'FFEDF5FF';

    public const LIMETEXT = 'FF4E7824';
    public const LIMEBG = 'F6FEE4';

    public const ORANGETEXT = 'FFE74D1C';
    public const ORANGEBG = 'FFFFF6EB';

    public const TEALTEXT = 'FF116B63';
    public const TEALBG = 'FFEEFDF9';

    public const PURPLETEXT = 'FF5121AB';
    public const PURPLEBG = 'FFF3F1FF';

    public const SKYTEXT = 'FF157FC1';
    public const SKYBG = 'FFEEF8FF';

    public const AMBERTEXT = 'FFFAB62F';
    public const AMBERBG = 'FFFFFAE9';

    public const PINKTEXT = 'FFB61853';
    public const PINKBG = 'FFFDF0F7';

    public const SLATETEXT = 'FF1C2533';
    public const SLATEBG = 'FFEFF3F8';
    
    public static function allBgColors() {
        return [
            self::REDBG,
            self::BLUEBG,
            self::LIMEBG,
            self::ORANGEBG,
            self::TEALBG,
            self::PURPLEBG,
            self::SKYBG,
            self::AMBERBG,
            self::PINKBG,
            self::SLATEBG,
        ];
    }

    public static function allTextColors() {
        return [
            self::REDTEXT,
            self::BLUETEXT,
            self::LIMETEXT,
            self::ORANGETEXT,
            self::TEALTEXT,
            self::PURPLETEXT,
            self::SKYTEXT,
            self::AMBERTEXT,
            self::PINKTEXT,
            self::SLATETEXT,
        ];
    }
}