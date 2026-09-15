<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #333;">Ajhuie Book Store & Photostat Services</h2>
        </div>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
            <h3 style="color: #333; margin-bottom: 15px;">OTP Verification</h3>
            <p>Hello,</p>
            <p>Your One Time Password (OTP) for email verification is:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <div style="background: #fff; border: 2px dashed #ddd; padding: 20px; display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #333;">
                    {{ $otp }}
                </div>
            </div>
            
            <p>This OTP is valid for 10 minutes. Please do not share this OTP with anyone.</p>
            <p>If you didn't request this OTP, please ignore this email.</p>
        </div>
        
        <div style="border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; color: #666;">
            <p>Thank you,<br>Ajhuie Book Store & Photostat Services Team</p>
        </div>
    </div>
</body>
</html>