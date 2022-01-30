<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $device_id
 * @property int $user_id
 * @property string $routes
 * @property float $amount
 */
class Invoice extends Model
{
    /**
     * @return int
     */
    public function getDeviceId(): int
    {
        return $this->device_id;
    }

    /**
     * @param int $device_id
     * @return Invoice
     */
    public function setDeviceId(int $device_id): Invoice
    {
        $this->device_id = $device_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return Invoice
     */
    public function setUserId(int $user_id): Invoice
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoutes(): string
    {
        return $this->routes;
    }

    /**
     * @param string $routes
     * @return Invoice
     */
    public function setRoutes(string $routes): Invoice
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Invoice
     */
    public function setAmount(float $amount): Invoice
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'routes' => json_decode($this->routes),
            'user' => $this->user_id,
            'device' => $this->device_id,
            'createdAt' => Carbon::make($this->created_at)->format('c')
        ];
    }
}
