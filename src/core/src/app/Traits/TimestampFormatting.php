<?php

namespace Mangosteen\Core\Traits;

trait TimestampFormatting
{
    public function getCreatedAtAttribute($value): string
    {
        return \Carbon\Carbon::parse($value)->setTimezone(env('APP_TIMEZONE'))->format(env('APP_TIMEZONE_FORMAT'));
    }

    public function getUpdatedAtAttribute($value): string
    {
        return \Carbon\Carbon::parse($value)->setTimezone(env('APP_TIMEZONE'))->format(env('APP_TIMEZONE_FORMAT'));
    }

}
