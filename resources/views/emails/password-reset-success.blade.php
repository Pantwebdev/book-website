<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Successful - Ajhuie Book Store & Photostat Services</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #fff; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Ajhuie Book Store & Photostat Services</h2>
        </div>
        
        <div class="content">
            <div class="success">
                <h3>✅ Password Reset Successful</h3>
            </div>
            
            <p>Hello {{ $user->name }},</p>
            
            <p>Your password has been successfully reset for your Ajhuie Book Store & Photostat Services account.</p>
            
            <p><strong>Security Note:</strong></p>
            <ul>
                <li>If you did not perform this action, please contact our support immediately</li>
                <li>For security reasons, all previous reset links have been invalidated</li>
                <li>Make sure to use a strong, unique password</li>
            </ul>
            
            <p>You can now login to your account with your new password:</p>
            
            <p><a href="{{ route('customer.login') }}">Click here to login</a></p>
            
            <p>Thank you for securing your account!</p>
            
            <p>Best regards,<br>Ajhuie Book Store & Photostat Services Team</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Ajhuie Book Store & Photostat Services. All rights reserved.</p>
            <p>This is an automated security notification.</p>
        </div>
    </div>
</body>
</html>