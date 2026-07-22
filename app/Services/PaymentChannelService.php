<?php

namespace App\Services;

class PaymentChannelService
{
    public function labels(): array
    {
        return [
            'qris' => 'QRIS',
            'bank_transfer' => 'Bank Transfer',
            'e_wallet' => 'E-Wallet',
            'cash' => 'Cash di Klinik',
        ];
    }

    public function label(string $method): string
    {
        return $this->labels()[$method] ?? ucfirst(str_replace('_', ' ', $method));
    }

    public function bankAccounts(): array
    {
        return [
            [
                'bank' => 'BCA',
                'number' => '1234567890',
                'name' => 'Lumiere Beauty',
            ],
            [
                'bank' => 'Mandiri',
                'number' => '9876543210',
                'name' => 'Lumiere Beauty',
            ],
        ];
    }

    public function eWalletAccounts(): array
    {
        return [
            [
                'provider' => 'DANA',
                'number' => '081234567890',
                'name' => 'Lumiere Beauty',
            ],
            [
                'provider' => 'OVO',
                'number' => '081234567890',
                'name' => 'Lumiere Beauty',
            ],
            [
                'provider' => 'GoPay',
                'number' => '081234567890',
                'name' => 'Lumiere Beauty',
            ],
        ];
    }

    public function qrisImage(): string
    {
        return 'assets/images/qris-lumiere.png';
    }

    public function methods(): array
    {
        return [
            'qris',
            'bank_transfer',
            'e_wallet',
            'cash',
        ];
    }
}