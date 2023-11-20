<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Submerchant;
use App\Models\PaymentGateway;
use App\Models\Tenant;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $d = Carbon::now()->subDays(rand(1, 90));
      
        return [
            //
          'consumerid' => $this->faker->ean8(),
          'paymentgateway_id' => Arr::random(PaymentGateway::pluck('id')->toArray()),
          'submerchant_id' => Arr::random(Submerchant::pluck('id')->toArray()),
          'tenant_id' => Arr::random(Tenant::pluck('id')->toArray()),
          'amount' => $this->faker->randomDigitNotNull * $faker->randomDigitNotNull * 2 * 1000 ,
          'status' => $this->faker->randomElement(['success', 'failed', 'pending','success','success','success','success','failed','success']),
          'orderid' => $this->faker->randomNumber(6, true),
          'currency' => '356',
          'mid' => 'UATFORTV2',
          'tid' => Arr::random(['UATFORT1V2','UATFORT2V2']), 
          'productid' => $this->faker->numerify('prod-###'),
          'paymentmethod' => $this->faker->randomElement(['creditcard', 'wallet', 'upi','debitcard','netbanking','emi','creditcard','upi','upi']),
          'externalpaymentreference' => $this->faker->numerify('payref-###'),
          'externaltenantreference' => $this->faker->numerify('tenref-###'),
          'latitude' => $this->faker->latitude($min = 10, $max = 30),
          'longitude' => $this->faker->longitude($min = 70, $max = 90),
          'ipaddress' => $this->faker->ipv4(),
          'useragent' => $this->faker->userAgent(),
          'acceptheader' => '',
          'fingerprintid' => $this->faker->numerify('fpid-###'),
          'browsertz' =>'',
          'browserscreenwidth' => '',
          'browserscreenheight' => '',
          'payment_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),


            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];
    }
}
