<?php

/**
 * Event-Listener Mapping Configuration
 *
 * Defines the relationships between events and their listeners.
 * Listeners are automatically triggered when their corresponding events are dispatched.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Event-Listener Bindings
    |--------------------------------------------------------------------------
    |
    | Structure:
    |   'Fully\Qualified\EventClass' => [
    |       'Fully\Qualified\ListenerClass',
    |       'Another\ListenerClass',
    |   ],
    |
    | Listeners are executed in the order they are declared.
    */

    // Example Bindings (uncomment to use):
    // 'App\Events\UserRegistered' => [
    //     'App\Listeners\SendWelcomeEmail',    // First listener to execute
    //     'App\Listeners\LogRegistration',     // Second listener to execute
    //     'App\Listeners\ProcessUserMetrics',  // Third listener to execute
    // ],

    // 'App\Events\PaymentReceived' => [
    //     'App\Listeners\SendPaymentReceipt',
    //     'App\Listeners\UpdateAccountingLedger',
    //     'App\Listeners\TriggerFulfillment',
    // ],

    /*
    |--------------------------------------------------------------------------
    | Best Practices
    |--------------------------------------------------------------------------
    |
    | 1. Keep listeners small and focused (single responsibility)
    | 2. Consider queueing long-running listeners
    | 3. Use event subscribers for complex relationships
    | 4. Document the expected event payload in listener classes
    */
];
