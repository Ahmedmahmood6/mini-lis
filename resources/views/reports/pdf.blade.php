<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Report {{ $reportNumber }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            line-height: 1.6;
            padding: 30px 40px;
        }

        /* ── Header ────────────────────────────────── */
        .header {
            width: 100%;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header-left { float: left; }
        .header-right { float: right; text-align: right; }
        .lab-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 0.5px;
        }
        .lab-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .meta-line {
            font-size: 11px;
            margin-bottom: 2px;
        }
        .meta-label { color: #64748b; }
        .meta-value { font-weight: bold; color: #0f172a; }
        .clearfix::after { content: ''; display: table; clear: both; }

        /* ── Patient Info Box ───────────────────────── */
        .patient-box {
            background: #f1f5f9;
            border-left: 4px solid #1e40af;
            padding: 10px 14px;
            margin-bottom: 20px;
        }
        .patient-box table { width: 100%; border-collapse: collapse; }
        .patient-box td { padding: 3px 10px 3px 0; font-size: 11.5px; }
        .pf-label { color: #475569; font-weight: bold; width: 110px; }

        /* ── Section Title ──────────────────────────── */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e40af;
            border-bottom: 1px solid #bfdbfe;
            padding-bottom: 4px;
            margin-bottom: 12px;
        }

        /* ── Results ────────────────────────────────── */
        .test-block {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        .test-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
            background: #dbeafe;
            padding: 5px 10px;
            margin-bottom: 0;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
        }
        .result-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11.5px;
            vertical-align: top;
        }
        .result-table tr:last-child td { border-bottom: none; }
        .rt-label { color: #475569; width: 140px; }
        .rt-value { font-weight: bold; color: #0f172a; }
        .rt-notes {
            color: #64748b;
            font-style: italic;
            font-size: 10.5px;
            padding-top: 2px;
        }

        /* ── Footer ─────────────────────────────────── */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #cbd5e1;
            padding-top: 12px;
        }
        .footer-cols { width: 100%; }
        .footer-cols td { width: 50%; font-size: 11px; vertical-align: top; }
        .sig-title { font-weight: bold; color: #334155; margin-bottom: 28px; }
        .sig-line {
            border-top: 1px solid #94a3b8;
            width: 140px;
            margin-bottom: 4px;
        }
        .disclaimer {
            margin-top: 14px;
            font-size: 9.5px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- ── HEADER ───────────────────────────────────────── --}}
<div class="header clearfix">
    <div class="header-left">
        <div class="lab-name">MINI LIS LABORATORY</div>
        <div class="lab-sub">Clinical Analysis &amp; Pathology Center</div>
    </div>
    <div class="header-right">
        <div class="meta-line">
            <span class="meta-label">Report No: </span>
            <span class="meta-value">{{ $reportNumber }}</span>
        </div>
        <div class="meta-line">
            <span class="meta-label">Order No: </span>
            <span class="meta-value">{{ $order->order_number }}</span>
        </div>
        <div class="meta-line">
            <span class="meta-label">Date: </span>
            <span class="meta-value">{{ $reportDate }}</span>
        </div>
    </div>
</div>

{{-- ── PATIENT INFO ─────────────────────────────────── --}}
<div class="patient-box">
    <table>
        <tr>
            <td class="pf-label">Patient:</td>
            <td>{{ $order->patient->name }}</td>
            <td class="pf-label">Age:</td>
            <td>{{ $order->patient->age }} years</td>
        </tr>
        <tr>
            <td class="pf-label">Gender:</td>
            <td>{{ ucfirst($order->patient->gender) }}</td>
            <td class="pf-label">Phone:</td>
            <td>{{ $order->patient->phone }}</td>
        </tr>
    </table>
</div>

{{-- ── RESULTS ──────────────────────────────────────── --}}
<div class="section-title">Laboratory Test Results</div>

@foreach($order->orderItems as $item)
    <div class="test-block">
        <div class="test-name">{{ $item->test->name }}</div>
        <table class="result-table">
            <tr>
                <td class="rt-label">Result</td>
                <td class="rt-value">
                    {!! nl2br(e($item->result->result_text ?? '—')) !!}
                </td>
            </tr>
            @if($item->result?->notes)
            <tr>
                <td class="rt-label">Notes</td>
                <td class="rt-notes">{{ $item->result->notes }}</td>
            </tr>
            @endif
        </table>
    </div>
@endforeach

{{-- ── FOOTER ───────────────────────────────────────── --}}
<div class="footer">
    <table class="footer-cols">
        <tr>
            <td>
                <div class="sig-title">Medical Technologist</div>
                <div class="sig-line"></div>
                <div>{{ $order->orderItems->first()?->result?->technician?->name ?? 'Lab Technician' }}</div>
            </td>
            <td style="text-align: right;">
                <div class="sig-title">Laboratory Director</div>
                <div style="display:inline-block; text-align:left;">
                    <div class="sig-line"></div>
                    <div>Dr. Laboratory Director</div>
                </div>
            </td>
        </tr>
    </table>
    <div class="disclaimer">
        This report is generated electronically and is valid without a physical signature. &mdash;
        Mini LIS Laboratory &mdash; {{ $reportDate }}
    </div>
</div>

</body>
</html>
