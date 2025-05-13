<!DOCTYPE html>
<html>
<head>
    <title>Lecturers Report</title>
    <style>
        @media print {
            body { 
                font-family: Arial, sans-serif;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            table { 
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td { 
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th { 
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .print-button {
                display: none;
            }
        }

        /* Screen styles */
        body { 
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th { 
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .print-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button">
        <i class="fas fa-print"></i> Print Report
    </button>

    <div class="header">
        <h1>Lecturers Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Research Group</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lecturers as $lecturer)
            <tr>
                <td>{{ $lecturer->staff_id }}</td>
                <td>{{ $lecturer->name }}</td>
                <td>{{ $lecturer->email }}</td>
                <td>{{ $lecturer->research_group }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: center;">
        <p>Total Lecturers: {{ $lecturers->count() }}</p>
    </div>

    <script>
        // Auto-print when the page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html> 