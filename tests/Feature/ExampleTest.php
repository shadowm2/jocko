<?php

test('the landing page redirects to the dashboard', function () {
    $this->get(route('home'))->assertRedirect(route('dashboard'));
});

test('a guest ends up at the login page', function () {
    $this->followingRedirects()->get(route('home'))->assertSee(route('login'), false);
});
