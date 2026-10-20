<?php

use Modules\Auth\Services\OtpService;
use Modules\Car\Models\Color;
use Modules\Dashboard\Facades\Sms;
use Modules\Dashboard\Models\Country;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;

// Real browser, real clicks: what a person does, which the Feature tests only simulate.
// The app runs in this process, so the Sms facade can be faked and the database read back.

test('an admin signs in with the code texted to their mobile', function () {
    User::factory()->create(['type' => UserType::Admin, 'mobile' => '09121234567']);
    config(['services.kavenegar.otp_template' => null]);

    $sent = [];
    Sms::shouldReceive('send')->once()->andReturnUsing(function (string $to, string $message) use (&$sent) {
        $sent = [$to, $message];

        return [];
    });

    $page = visit(route('login.otp'))
        ->type('[wire\:model="mobile"]', '09121234567')
        ->click('[data-test="send-code-button"]')
        ->waitForText('09121234567');

    expect($sent[0])->toBe('09121234567');
    preg_match('/\d{'.OtpService::LENGTH.'}/', $sent[1], $code);

    $page->type('[wire\:model="code"]', $code[0])
        ->click('[data-test="otp-login-button"]')
        ->assertPathIs('/dashboard');
});

test('a wrong code keeps the visitor on the login page', function () {
    User::factory()->create(['type' => UserType::Admin, 'mobile' => '09121234567']);
    config(['services.kavenegar.otp_template' => null]);
    Sms::shouldReceive('send')->once()->andReturn([]);

    visit(route('login.otp'))
        ->type('[wire\:model="mobile"]', '09121234567')
        ->click('[data-test="send-code-button"]')
        ->waitForText('09121234567')
        ->type('[wire\:model="code"]', '000000')
        ->click('[data-test="otp-login-button"]')
        ->waitForText(__('auth::strings.The code is invalid or has expired.'))
        ->assertPathIs('/login/otp');
});

test('a color made in the form shows up in the list', function () {
    $this->actingAs(User::factory()->create());

    visit(route('colors.create'))
        ->type('[wire\:model="form.name"]', 'Crimson')
        ->click(__('car::strings.Save Color'))
        ->assertPathIs('/colors/list')
        ->assertSee('Crimson');

    expect(Color::where('name', 'Crimson')->exists())->toBeTrue();
});

test('the switch on a country row turns it off', function () {
    $this->actingAs(User::factory()->create());
    Country::forceCreate(['name' => 'Iran', 'code' => 'IR', 'code3' => 'IRN', 'is_active' => true]);

    visit(route('countries.index'))
        ->click('ui-switch[wire\:change="toggleIsActive(\'IR\')"]')
        ->waitForText(__('dashboard::messages.Country Updated Successfully'));

    expect(Country::firstWhere('code', 'IR')->is_active)->toBeFalsy();
});
