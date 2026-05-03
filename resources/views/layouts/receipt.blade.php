<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Secure Transaction Receipt - {{ $settings->site_name ?? config('app.name') }}" />
    <meta name="author" content="{{ $settings->site_name ?? config('app.name') }}">
    <title>{{ $settings->site_name ?? config('app.name') }} - @yield('title') </title>
    
    <link rel="icon" href="{{ asset('assets/images/' . ($settings->favicon ?? 'default_favicon.png')) }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --success-color: #10b981;
            --text-main: #111827;
            --text-muted: #6b7280;
            --bg-light: #f3f4f6;
            --white: #ffffff;
            --border-color: #e5e7eb;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        .receipt-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        #receipt {
            width: 100%;
            max-width: 500px;
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
        }

        /* Decorative top bar */
        #receipt::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        }

        .receipt-content {
            padding: 2.5rem;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: var(--bg-light);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 800;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #ecfdf5;
            color: var(--success-color);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .receipt-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-main);
        }

        .amount-display {
            text-align: center;
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        .amount-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .info-grid {
            display: grid;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .info-value {
            font-size: 0.9375rem;
            color: var(--text-main);
            font-weight: 600;
            text-align: right;
        }

        .receipt-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--border-color);
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .buttons-container {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            width: 100%;
            max-width: 500px;
        }

        .btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(30, 64, 175, 0.2);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-light);
            transform: translateY(-2px);
        }

        @media print {
            body { background: white; }
            .receipt-wrapper { padding: 0; justify-content: flex-start; }
            #receipt { box-shadow: none; border: 1px solid #eee; }
            .buttons-container { display: none; }
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="receipt-wrapper">
        <div id="receipt">
            <div class="receipt-content">
                @yield('content')
                
                <div class="receipt-footer">
                    <p>Thank you for using {{ $settings->short_name ?? config('app.name') }}</p>
                    <p style="font-size: 0.75rem; margin-top: 0.5rem;">© {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </div>

        <div class="buttons-container">
            <button onclick="printReceipt()" class="btn btn-secondary">
                <i class="bi bi-printer"></i> Print
            </button>
            <button id="downloadButton" class="btn btn-primary">
                <i class="bi bi-download"></i> Download
            </button>
            <button id="shareButton" class="btn btn-secondary">
                <i class="bi bi-share"></i> Share
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        const transactionId = '{{ strtoupper($transaction->referenceId ?? $transaction->trx ?? 'TRX-DEFAULT') }}';

        function printReceipt() {
            window.print();
        }

        const shareButton = document.getElementById('shareButton');
        const downloadButton = document.getElementById('downloadButton');
        const receiptElement = document.getElementById('receipt');

        async function generateImage() {
            try {
                const canvas = await html2canvas(receiptElement, {
                    scale: 3,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    borderRadius: 24,
                });
                return canvas.toDataURL('image/png', 1.0);
            } catch (error) {
                console.error('Error generating image:', error);
                return null;
            }
        }

        shareButton.addEventListener('click', async () => {
            const imageData = await generateImage();
            if (!imageData) return;

            const receiptName = `receipt_${transactionId}.png`;
            const blob = await (await fetch(imageData)).blob();
            const file = new File([blob], receiptName, { type: 'image/png' });

            if (navigator.canShare && navigator.canShare({ files: [file] })) {
                await navigator.share({
                    files: [file],
                    title: 'Transaction Receipt',
                    text: `Here is your transaction receipt from {{ $settings->site_name ?? config('app.name') }}`,
                });
            } else {
                alert('Sharing is not supported on this browser. Please download the receipt instead.');
            }
        });

        downloadButton.addEventListener('click', async () => {
            const imageData = await generateImage();
            if (!imageData) return;

            const receiptName = `receipt_${transactionId}.png`;
            const downloadLink = document.createElement('a');
            downloadLink.href = imageData;
            downloadLink.download = receiptName;
            downloadLink.click();
        });
    </script>
</body>

</html>

