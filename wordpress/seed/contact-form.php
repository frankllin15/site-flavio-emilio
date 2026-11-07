<?php
/**
 * Definição do formulário de contato (Contact Form 7), portado de components/client/ContactForm.tsx
 * e do e-mail de app/api/contact/route.ts. Usado por seed/import.php.
 *
 * @return array{title:string, form:string, mail:array, messages:array}
 */

defined( 'ABSPATH' ) || exit;

$fec_field = static function ( string $id, string $label, string $tag ): string {
	return "<div class=\"fec-field\">\n\t<label for=\"{$id}\">{$label}</label>\n\t{$tag}\n</div>";
};

$fec_form = implode(
	"\n\n",
	array(
		'<div class="fec-form-grid">',
		$fec_field( 'fec-nome', 'Nome *', '[text* nome id:fec-nome minlength:2 autocomplete:name]' ),
		$fec_field( 'fec-email', 'Email *', '[email* email id:fec-email autocomplete:email]' ),
		$fec_field( 'fec-telefone', 'Telefone', '[tel telefone id:fec-telefone autocomplete:tel placeholder "(00) 00000-0000"]' ),
		$fec_field( 'fec-empresa', 'Empresa', '[text empresa id:fec-empresa autocomplete:organization]' ),
		'</div>',
		$fec_field( 'fec-assunto', 'Assunto *', '[select* assunto id:fec-assunto "Consultoria" "Palestra" "Livros" "Outros"]' ),
		$fec_field( 'fec-mensagem', 'Mensagem *', '[textarea* mensagem id:fec-mensagem minlength:10 x5]' ),
		'[turnstile]',
		'[submit class:fec-submit "Enviar Mensagem"]',
	)
);

// Mesmo layout do e-mail enviado pela API do Next (route.ts). O CF7 escapa os valores dos campos no modo HTML.
$fec_body = <<<'HTML'
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
	<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
		<h2 style="color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 10px;">Nova mensagem de contato</h2>

		<div style="margin: 20px 0;">
			<p><strong>Nome:</strong> [nome]</p>
			<p><strong>Email:</strong> <a href="mailto:[email]">[email]</a></p>
			<p><strong>Telefone:</strong> [telefone]</p>
			<p><strong>Empresa:</strong> [empresa]</p>
			<p><strong>Assunto:</strong> [assunto]</p>
		</div>

		<div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
			<p style="margin: 0;"><strong>Mensagem:</strong></p>
			<p style="margin: 10px 0 0 0;">[mensagem]</p>
		</div>

		<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280;">
			<p>Esta mensagem foi enviada através do formulário de contato do site [_site_title] ([_site_url]).</p>
		</div>
	</div>
</body>
</html>
HTML;

return array(
	'title'    => 'Contato (site)',
	'form'     => $fec_form,
	'mail'     => array(
		'active'             => true,
		'subject'            => 'Novo contato: [assunto]',
		// O endereço real de envio é forçado por FEC_MAIL_FROM (fec-core/mail.php). Domínio fixo porque
		// [_site_domain] vira "localhost:8080" no Docker, que o CF7 recusa como endereço inválido.
		'sender'             => '[_site_title] <site@flavioemilio.com.br>',
		'recipient'          => '%RECIPIENT%',
		'body'               => $fec_body,
		'additional_headers' => 'Reply-To: [nome] <[email]>',
		'attachments'        => '',
		'use_html'           => true,
		'exclude_blank'      => true, // omite as linhas de Telefone/Empresa quando vazias
	),
	// Como as mensagens aparecem em Flamingo → Mensagens recebidas.
	'additional_settings' => implode(
		"\n",
		array(
			'flamingo_name: "[nome]"',
			'flamingo_email: "[email]"',
			'flamingo_subject: "Novo contato: [assunto]"',
		)
	),
	'messages' => array(
		'mail_sent_ok'     => 'Mensagem enviada com sucesso! Entraremos em contato em breve.',
		'mail_sent_ng'     => 'Erro ao enviar mensagem. Por favor, tente novamente ou fale pelo WhatsApp.',
		'validation_error' => 'Confira os campos destacados e tente novamente.',
		'spam'             => 'Não foi possível enviar. Por favor, tente novamente ou fale pelo WhatsApp.',
		'invalid_required' => 'Preencha este campo.',
		'invalid_too_short' => 'Texto muito curto.',
		'invalid_email'    => 'Email inválido.',
	),
);
