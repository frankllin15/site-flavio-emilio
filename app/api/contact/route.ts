import { NextRequest, NextResponse } from 'next/server';
import { resend } from '@/lib/resend';
import { z } from 'zod';

// Validation schema
const contactSchema = z.object({
  name: z.string().min(2, 'Nome deve ter no mínimo 2 caracteres'),
  email: z.string().email('Email inválido'),
  phone: z.string().optional(),
  company: z.string().optional(),
  message: z.string().min(10, 'Mensagem deve ter no mínimo 10 caracteres'),
  subject: z.enum(['consultoria', 'palestra', 'livros', 'outros']),
});

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();

    // Validate input
    const validatedData = contactSchema.parse(body);

    // Send email via Resend
    const { data, error } = await resend.emails.send({
      from: process.env.CONTACT_EMAIL_FROM || 'onboarding@resend.dev',
      to: process.env.CONTACT_EMAIL_TO || 'contato@flavioemilio.com.br',
      replyTo: validatedData.email,
      subject: `Novo contato: ${validatedData.subject}`,
      html: `
        <!DOCTYPE html>
        <html>
          <head>
            <meta charset="UTF-8">
          </head>
          <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
              <h2 style="color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 10px;">
                Nova mensagem de contato
              </h2>

              <div style="margin: 20px 0;">
                <p><strong>Nome:</strong> ${validatedData.name}</p>
                <p><strong>Email:</strong> <a href="mailto:${validatedData.email}">${validatedData.email}</a></p>
                ${validatedData.phone ? `<p><strong>Telefone:</strong> ${validatedData.phone}</p>` : ''}
                ${validatedData.company ? `<p><strong>Empresa:</strong> ${validatedData.company}</p>` : ''}
                <p><strong>Assunto:</strong> ${validatedData.subject}</p>
              </div>

              <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Mensagem:</strong></p>
                <p style="margin: 10px 0 0 0;">${validatedData.message.replace(/\n/g, '<br>')}</p>
              </div>

              <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280;">
                <p>Esta mensagem foi enviada através do formulário de contato do site Flávio Emílio Cavalcanti.</p>
              </div>
            </div>
          </body>
        </html>
      `,
    });

    if (error) {
      console.error('Resend error:', error);
      return NextResponse.json(
        { error: 'Erro ao enviar mensagem. Por favor, tente novamente.' },
        { status: 500 }
      );
    }

    return NextResponse.json(
      { success: true, message: 'Mensagem enviada com sucesso!', data },
      { status: 200 }
    );
  } catch (error) {
    if (error instanceof z.ZodError) {
      return NextResponse.json(
        {
          error: 'Dados inválidos',        
        },
        { status: 400 }
      );
    }

    console.error('Contact form error:', error);
    return NextResponse.json(
      { error: 'Erro interno do servidor. Por favor, tente novamente mais tarde.' },
      { status: 500 }
    );
  }
}
