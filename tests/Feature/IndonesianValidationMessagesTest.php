<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndonesianValidationMessagesTest extends TestCase
{
    /**
     * R10: pesan validasi framework harus bahasa Indonesia
     * (APP_LOCALE=id + lang/id/validation.php), bukan default Inggris vendor.
     */
    public function test_framework_validation_messages_are_in_indonesian(): void
    {
        $this->assertSame('id', config('app.locale'));

        $response = $this->from('/login')->post('/login', []);

        $response->assertSessionHasErrors(['nik', 'password']);
        $messages = implode(' ', session('errors')->all());
        $this->assertStringContainsString('wajib diisi', $messages);
        $this->assertStringNotContainsString('required', $messages);
    }
}
