<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\Subscriber;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {       
        $subscriptions = [
            'Free Plan',
            'Basic Plan',
            'Pro Plan',
            'Premium Plan',
            'Enterprise Plan',
        ];

        foreach ($subscriptions as $plan) {
            Subscription::create(['name' => $plan]);
        }

        $allSubscriptions = Subscription::all();

        foreach (Subscriber::all() as $subscriber) {
            $subscriber->subscriptions()->attach(
                $allSubscriptions->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
