<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            margin-top: 5px;
            color: #212529;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        .priority {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .priority-low { background-color: #d1ecf1; color: #0c5460; }
        .priority-medium { background-color: #fff3cd; color: #856404; }
        .priority-high { background-color: #f8d7da; color: #721c24; }
        .priority-critical { background-color: #dc3545; color: #ffffff; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; color: #dc3545;">🚨 New Incident Reported</h1>
    </div>

    <div class="content">
        <div class="field">
            <div class="label">Incident Title:</div>
            <div class="value">{{ $incident->title }}</div>
        </div>

        <div class="field">
            <div class="label">Description:</div>
            <div class="value">{{ $incident->description }}</div>
        </div>

        <div class="field">
            <div class="label">Priority:</div>
            <div class="value">
                <span class="priority priority-{{ $incident->priority ?? 'medium' }}">
                    {{ strtoupper($incident->priority ?? 'MEDIUM') }}
                </span>
            </div>
        </div>

        <div class="field">
            <div class="label">Status:</div>
            <div class="value">{{ ucfirst($incident->status ?? 'open') }}</div>
        </div>

        <div class="field">
            <div class="label">Reported By:</div>
            <div class="value">
                {{ $incident->reporter->name ?? 'Unknown' }}
                @if(isset($incident->reporter->email))
                    ({{ $incident->reporter->email }})
                @endif
            </div>
        </div>

        <div class="field">
            <div class="label">Date Reported:</div>
            <div class="value">{{ $incident->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>

        @if(isset($incident->id))
        <div class="field">
            <div class="label">Incident ID:</div>
            <div class="value">#{{ $incident->id }}</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated notification from the Incident Management System.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>