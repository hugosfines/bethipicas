<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Helper {
    public static function MyRole() {
        $roles = auth()->user()->getRoleNames();

        return count($roles) ? $roles[0] : null;
    }

    public static function MyRoles() {
        return auth()->user()->getRoleNames();
    }

    public static function MyPermissions() {
        return auth()->user()->getAllPermissions();
    }

    public static function getBetType($betTypeId)
    {
        return \App\Models\BetType::find($betTypeId);
    }

    public static function getNameBetType($betTypeId, $gps=false)
    {
        $betType = \App\Models\BetType::find($betTypeId);

        if (!$betType) {
            return null;
        }

        if (in_array($betTypeId, [1,2,3])) {
            if ($gps == true) {
                return 'G/P/S';
            }
        }

        return $betType->name;
    }
}