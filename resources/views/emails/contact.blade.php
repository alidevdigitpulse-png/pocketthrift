<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #cf5103;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .field-group {
            margin-bottom: 20px;
        }
        .field-label {
            font-weight: bold;
            color: #cf5103;
            margin-bottom: 5px;
            display: block;
        }
        .field-value {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 3px solid #cf5103;
            border-radius: 4px;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #cf5103;
            border-radius: 4px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 New Contact Form Submission</h1>
        </div>
        
        <div class="email-body">
            <p>You have received a new message from the PocketThrift contact form:</p>
            
            <div class="field-group">
                <span class="field-label">Name:</span>
                <div class="field-value">{{ $data['name'] }}</div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Email:</span>
                <div class="field-value">
                    <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
                </div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Subject:</span>
                <div class="field-value">{{ $data['subject'] }}</div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Message:</span>
                <div class="message-box">{{ $data['message'] }}</div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Submitted At:</span>
                <div class="field-value">{{ now()->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
        
        <div class="email-footer">
            <p>This email was sent from the PocketThrift contact form.</p>
            <p>&copy; {{ date('Y') }} PocketThrift. All rights reserved.</p>
        </div>
    </div>
</body>
</html>