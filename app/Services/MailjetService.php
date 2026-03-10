<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MailjetService
{
    private $apiKey = '2752a5c7461dc63025c00aa0f7839d51';
    private $apiSecret = 'a050c82964517dae53f5bb49725f930b';

    public function sendVerificationCode($toEmail, $toName, $code)
    {
        $logoUrl = url('imagens/logo.png');

        $htmlContent = '
        <div style="background-color:#f3f4f6;padding:0;font-family:Helvetica,Arial,sans-serif;">
            <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;">
                
                <div style="background:#10b981;padding:28px;text-align:center;">
                    <img src="'.$logoUrl.'" alt="ValeIA" style="width:130px;margin-bottom:12px;">
                    <h1 style="color:#ffffff;margin:0;font-size:22px;font-weight:700;">Segurança</h1>
                </div>

                <div style="padding:0 0 25px;">
                    <h2 style="color:#111827;margin:30px 0 10px;text-align:center;font-size:20px;font-weight:600;">
                        Código de Verificação
                    </h2>

                    <p style="color:#6b7280;font-size:15px;text-align:center;line-height:1.6;margin:0 25px 25px;">
                        Utilize o código abaixo para continuar sua recuperação de senha.
                    </p>

                    <div style="text-align:center;margin:0;">
                        <div style="background:#10b981;color:#ffffff;font-size:42px;font-weight:800;
                        letter-spacing:12px;padding:22px 0;width:100%;font-family:monospace;">
                            '.$code.'
                        </div>
                    </div>

                    <p style="color:#ef4444;font-size:13px;text-align:center;margin-top:18px;">
                        O código expira em 5 minutos.
                    </p>
                </div>

                <div style="background:#f9fafb;padding:18px;text-align:center;border-top:1px solid #e5e7eb;">
                    <p style="color:#9ca3af;font-size:12px;margin:0;">
                        Se você não solicitou, ignore este e-mail.
                    </p>
                </div>

            </div>
        </div>';

        $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->post('https://api.mailjet.com/v3.1/send', [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => 'seguranca@valeia.com.br',
                            'Name' => 'Segurança ValeIA'
                        ],
                        'To' => [
                            ['Email' => $toEmail, 'Name' => $toName]
                        ],
                        'Subject' => 'Seu código de recuperação: '.$code,
                        'HTMLPart' => $htmlContent,
                        'TextPart' => "Seu código de verificação é: $code"
                    ]
                ]
            ]);

        return $response->successful();
    }
}
