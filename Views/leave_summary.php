<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Details</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/leaveSummary.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<div class="container">
    <div class="leave-details-card">
        <h2>Leave Details</h2>
        
        <?php if ($leave): ?>
            <div class="details-grid">
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="label">Start Date:</span>
                    <span class="value"><?= htmlspecialchars($leave['start_date']); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-calendar-check"></i>
                    <span class="label">End Date:</span>
                    <span class="value"><?= htmlspecialchars($leave['end_date']); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-comment"></i>
                    <span class="label">Reason:</span>
                    <span class="value"><?= htmlspecialchars($leave['reason']); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-tag"></i>
                    <span class="label">Type:</span>
                    <span class="value"><?= htmlspecialchars($leave['leave_type']); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <span class="label">Days:</span>
                    <span class="value"><?= htmlspecialchars($leave['days']); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-calendar-plus"></i>
                    <span class="label">Request Date:</span>
                    <span class="value"><?= htmlspecialchars($leave['request_date']); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-info-circle"></i>
                    <span class="label">Status:</span>
                    <span class="status <?= strtolower($leave['status']); ?>"><?= htmlspecialchars($leave['status']); ?></span>
                </div>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Leave details not found or access denied.</p>
                <a href="<?php echo $base_url; ?>dashboard" class="btn-back">Back to Dashboard</a>
            </div>
        <?php endif; ?>
        
        <div class="actions">
            <a href="<?php echo $base_url; ?>dashboard" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 20px;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.leave-details-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-top: 20px;
}

.leave-summary-card h2 {
    color: #2c3e50;
    margin-bottom: 30px;
    text-align: center;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.detail-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.detail-item i {
    color: #3498db;
    margin-right: 15px;
    font-size: 1.2em;
    width: 20px;
}

.label {
    font-weight: bold;
    margin-right: 10px;
    color: #555;
}

.value {
    color: #333;
    flex: 1;
}

.status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: bold;
    text-transform: uppercase;
}

.status.pending {
    background-color: purple;
    color: white;
}

.status.approved {
    background-color: #27ae60;
    color: white;
}

.status.rejected {
    background-color: #e74c3c;
    color: white;
}

.actions {
    text-align: center;
    margin-top: 30px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: #3498db;
    color: white;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.btn-back:hover {
    background-color: #2980b9;
}

.no-data {
    text-align: center;
    padding: 50px 20px;
    color: #666;
}

.no-data i {
    font-size: 3em;
    color: #f39c12;
    margin-bottom: 20px;
}

.no-data p {
    font-size: 1.1em;
    margin-bottom: 20px;
}
</style>

</body>
</html>
