<?php

declare(strict_types=1);

it('renders the welcome page for guests', function () {
    $this->get('/')->assertOk();
});
