<!DOCTYPE html>
<html>
<head>
    <title>Password Reset - Ajhuie Book Store & Photostat Services</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background: #f8f9fa; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
        <div style="background: #e40046; padding: 20px; text-align: center;">
            <h2 style="color: white; margin: 0;">Ajhuie Book Store & Photostat Services</h2>
        </div>
        
        <div style="padding: 30px;">
            <h3 style="color: #333; margin-top: 0;">Password Reset Request</h3>
            
            <p>Hello {{ $user->name }},</p>
            
            <p>We received a request to reset your password for your Ajhuie Book Store account.</p>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong style="color: #856404;">⚠️ IMPORTANT:</strong> 
                This password reset link is valid for <strong style="color: #dc3545;">2 minutes only</strong>. 
                After 2 minutes, the link will expire and you'll need to request a new password reset.
            </div>
            
            <p>Click the button below to reset your password:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetLink }}" 
                   style="display: inline-block; padding: 12px 30px; background: #e40046; color: white; 
                          text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Reset Password
                </a>
            </div>
            
            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #0066cc; background: #f8f9fa; padding: 10px; border-radius: 5px;">
                {{ $resetLink }}
            </p>
            
            <p><strong>Didn't request this?</strong> If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.</p>
            
            <p style="margin-top: 30px;">Best regards,<br><strong>Ajhuie Book Store & Photostat Services</strong></p>
        </div>
        
        <div style="text-align: center; padding: 20px; background: #f8f9fa; color: #666; font-size: 12px;">
            <p>© {{ date('Y') }} Ajhuie Book Store & Photostat Services. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>