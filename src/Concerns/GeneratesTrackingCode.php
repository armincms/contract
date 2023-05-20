<?php

namespace Armincms\Contract\Concerns;

trait GeneratesTrackingCode
{
    /**
     * Handles booting model.
     *
     * @return void
     */
    public static function bootGeneratesTrackingCode()
    {
        static::saving(function ($model) {
            $model->isTrackable() || $model->fillTrackingCode();
        });
    }

    /**
     * Determine if the model has tracking_code.
     */
    public function isTrackable(): bool
    {
        return ! blank($this->trackingCode());
    }

    /**
     * Fill the tracking_code attribute.
     *
     * @return $this
     */
    public function fillTrackingCode()
    {
        $this->forceFill([
            $this->getTrackingCodeColumn() => $this->generateTrackingCode(),
        ]);

        return $this;
    }

    /**
     * Generate unique string tracking_code.
     *
     * @return string
     */
    public function generateTrackingCode()
    {
        while (static::tracking($code = $this->generateRandomCode())->whereKeyNot($this->id)->count());

        return $code;
    }

    /**
     * Generate new random code.
     */
    public function generateRandomCode(): string
    {
        return (string) rand(999999, 9999999);
    }

    /**
     * Query with the given tracking_code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTracking($query, $code)
    {
        return $query->whereIn($this->getQualifiedTrackingCodeColumn(), (array) $code);
    }

    /**
     * Query where the tracking_code is not null.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrackable($query)
    {
        return $query->whereNotNull($this->getQualifiedTrackingCodeColumn());
    }

    /**
     * Get the value of the "tracking_code" column.
     *
     * @return string
     */
    public function trackingCode()
    {
        return $this->getTrackingCode();
    }

    /**
     * Get the value of the "tracking_code" column.
     *
     * @return string
     */
    public function getTrackingCode()
    {
        return $this->getAttribute($this->getTrackingCodeColumn());
    }

    /**
     * Get the name of the "tracking_code" column.
     *
     * @return string
     */
    public function getTrackingCodeColumn()
    {
        return defined('static::TRACKING_CODE') ? static::TRACKING_CODE : 'tracking_code';
    }

    /**
     * Get the fully qualified "tracking_code" column.
     *
     * @return string
     */
    public function getQualifiedTrackingCodeColumn()
    {
        return $this->qualifyColumn($this->getTrackingCodeColumn());
    }
}
