<?php

namespace Core;

/**
 * Email Service - Send emails via SMTP or built-in PHP mail
 * 
 * Features:
 *   - SMTP and native mail() support
 *   - HTML and plain text emails
 *   - Multiple recipients
 *   - Attachments support
 *   - Template rendering
 * 
 * Usage:
 *   $email = new Email();
 *   $email->to('user@example.com')
 *        ->subject('Welcome')
 *        ->html('<h1>Hello</h1>')
 *        ->send();
 * 
 * Or with template:
 *   $email = new Email();
 *   $email->to('user@example.com')
 *        ->subject('Welcome')
 *        ->template('welcome', ['name' => 'John'])
 *        ->send();
 */
class Email
{
    private string $to = '';
    private array $cc = [];
    private array $bcc = [];
    private string $from = '';
    private string $fromName = '';
    private string $subject = '';
    private string $body = '';
    private string $textBody = '';
    private array $headers = [];
    private array $attachments = [];
    private bool $isHtml = true;

    public function __construct()
    {
        // Load email config from .env
        Config::load();
        $this->from = Config::get('MAIL_FROM', 'noreply@bondmvc.local');
        $this->fromName = Config::get('MAIL_FROM_NAME', 'BondoMVC');
    }

    /**
     * Set recipient
     */
    public function to(string $email, string $name = ''): self
    {
        if (!$this->isValidEmail($email)) {
            Logger::warning('Invalid email address: {email}', ['email' => $email]);
            return $this;
        }

        $this->to = $name ? "$name <$email>" : $email;
        return $this;
    }

    /**
     * Add CC recipient
     */
    public function cc(string $email, string $name = ''): self
    {
        if (!$this->isValidEmail($email)) {
            return $this;
        }

        $this->cc[] = $name ? "$name <$email>" : $email;
        return $this;
    }

    /**
     * Add BCC recipient
     */
    public function bcc(string $email, string $name = ''): self
    {
        if (!$this->isValidEmail($email)) {
            return $this;
        }

        $this->bcc[] = $name ? "$name <$email>" : $email;
        return $this;
    }

    /**
     * Set sender
     */
    public function from(string $email, string $name = ''): self
    {
        if (!$this->isValidEmail($email)) {
            return $this;
        }

        $this->from = $email;
        $this->fromName = $name;
        return $this;
    }

    /**
     * Set subject
     */
    public function subject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set HTML body
     */
    public function html(string $html): self
    {
        $this->body = $html;
        $this->isHtml = true;
        return $this;
    }

    /**
     * Set plain text body
     */
    public function text(string $text): self
    {
        $this->textBody = $text;
        $this->isHtml = false;
        return $this;
    }

    /**
     * Render and send from template
     */
    public function template(string $templateName, array $data = []): self
    {
        $templatePath = __DIR__ . '/../app/views/emails/' . $templateName . '.php';

        if (!file_exists($templatePath)) {
            Logger::error('Email template not found: {template}', ['template' => $templateName]);
            return $this;
        }

        ob_start();
        extract($data);
        include $templatePath;
        $this->body = ob_get_clean();
        $this->isHtml = true;

        Logger::info('Email template loaded: {template}', ['template' => $templateName]);
        return $this;
    }

    /**
     * Attach a file
     */
    public function attach(string $filepath, string $filename = ''): self
    {
        if (!file_exists($filepath)) {
            Logger::warning('Attachment file not found: {file}', ['file' => $filepath]);
            return $this;
        }

        $this->attachments[] = [
            'path' => $filepath,
            'name' => $filename ?: basename($filepath),
        ];

        return $this;
    }

    /**
     * Send the email
     */
    public function send(): bool
    {
        if (empty($this->to)) {
            Logger::error('Email recipient not set');
            return false;
        }

        if (empty($this->subject)) {
            Logger::error('Email subject not set');
            return false;
        }

        if (empty($this->body)) {
            Logger::error('Email body not set');
            return false;
        }

        // Build headers
        $this->buildHeaders();

        // Use SMTP if configured, otherwise use mail()
        $success = $this->sendViaPhp();

        if ($success) {
            Logger::info('Email sent to {to} - {subject}', [
                'to' => $this->to,
                'subject' => $this->subject,
            ]);
        } else {
            Logger::error('Email failed to {to} - {subject}', [
                'to' => $this->to,
                'subject' => $this->subject,
            ]);
        }

        return $success;
    }

    /**
     * Send via PHP mail()
     */
    private function sendViaPhp(): bool
    {
        $headers = implode("\r\n", $this->headers);

        return mail(
            $this->to,
            $this->subject,
            $this->body,
            $headers
        );
    }

    /**
     * Build email headers
     */
    private function buildHeaders(): void
    {
        $this->headers = [];

        // From header
        $this->headers[] = 'From: ' . $this->fromName . ' <' . $this->from . '>';

        // CC header
        if (!empty($this->cc)) {
            $this->headers[] = 'Cc: ' . implode(', ', $this->cc);
        }

        // BCC header
        if (!empty($this->bcc)) {
            $this->headers[] = 'Bcc: ' . implode(', ', $this->bcc);
        }

        // Content type
        $charset = 'UTF-8';
        if ($this->isHtml) {
            $this->headers[] = 'Content-Type: text/html; charset=' . $charset;
        } else {
            $this->headers[] = 'Content-Type: text/plain; charset=' . $charset;
        }

        // Additional headers
        $this->headers[] = 'X-Mailer: BondoMVC-Framework';
        $this->headers[] = 'MIME-Version: 1.0';
    }

    /**
     * Validate email address
     */
    private function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Get email headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get email body
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Preview email (for debugging)
     */
    public function preview(): string
    {
        return "To: {$this->to}\n" .
               "Subject: {$this->subject}\n" .
               "Headers:\n" . implode("\n", $this->getHeaders()) . "\n\n" .
               "Body:\n{$this->body}";
    }

    /**
     * Send test email
     */
    public static function test(string $email): bool
    {
        $mail = new self();
        return $mail->to($email)
            ->subject('Test Email from BondoMVC')
            ->html('<h1>Hello!</h1><p>This is a test email from BondoMVC framework.</p>')
            ->send();
    }
}
